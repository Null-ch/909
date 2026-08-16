<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private readonly ImageService $imageService,
        private readonly HomeService $homeService,
        private readonly CatalogService $catalogService,
    ) {}

    /**
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<int, array<string, mixed>>}
     */
    public function datatable(Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim((string) $request->input('search.value', ''));
        $categoryId = $request->input('category_id');
        $status = $request->input('status');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');

        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')])
            ->orderByDesc('id');

        if ($categoryId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId));
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if (is_numeric($priceMin)) {
            $query->where('price', '>=', (float) $priceMin);
        }

        if (is_numeric($priceMax)) {
            $query->where('price', '<=', (float) $priceMax);
        }

        $recordsTotal = Product::query()->count();

        if ($search !== '') {
            $escaped = Product::escapeLike($search);
            $query->where(function ($q) use ($escaped) {
                $q->where('name', 'like', "%{$escaped}%")
                    ->orWhere('sku', 'like', "%{$escaped}%")
                    ->orWhere('slug', 'like', "%{$escaped}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumn = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $sortColumn = match ($orderColumn) {
            0 => 'id',
            3 => 'price',
            4 => 'quantity',
            5 => 'is_active',
            default => 'name',
        };

        $products = $query
            ->orderBy($sortColumn, $orderDir)
            ->skip($start)
            ->take($length > 0 ? $length : 15)
            ->get();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $products->map(fn (Product $product) => $this->formatDatatableRow($product))->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, int|string>  $categoryIds
     * @param  array<int, UploadedFile>  $images
     * @param  array<int, array{name: string, value: string}>  $attributes
     */
    public function create(array $data, array $categoryIds, array $images, array $attributes, ?int $mainImageIndex = 0): Product
    {
        return DB::transaction(function () use ($data, $categoryIds, $images, $attributes, $mainImageIndex) {
            $data['slug'] = $this->resolveSlug($data['name'], $data['slug'] ?? null);
            $data['description'] = $this->sanitizeDescription($data['description'] ?? null);

            $product = Product::query()->create($data);
            $product->categories()->sync($categoryIds);
            $this->storeImages($product, $images, $mainImageIndex);
            $this->syncAttributes($product, $attributes);

            logActivity('created', 'Product', $product->id, "Создан товар «{$product->name}»");

            $this->homeService->clearCache();
            $this->catalogService->clearCache();

            return $product->load(['categories', 'images', 'attributes']);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, int|string>  $categoryIds
     * @param  array<int, UploadedFile>  $newImages
     * @param  array<int, array{name: string, value: string}>  $attributes
     * @param  array<int, int|string>  $deleteImageIds
     * @param  array<int, int|string>  $imageOrder
     */
    public function update(
        Product $product,
        array $data,
        array $categoryIds,
        array $newImages,
        array $attributes,
        array $deleteImageIds,
        array $imageOrder,
        ?int $mainImageId = null,
        ?int $mainNewImageIndex = null,
    ): Product {
        return DB::transaction(function () use ($product, $data, $categoryIds, $newImages, $attributes, $deleteImageIds, $imageOrder, $mainImageId, $mainNewImageIndex) {
            $data['slug'] = $this->resolveSlug($data['name'], $data['slug'] ?? null, $product->id);
            $data['description'] = $this->sanitizeDescription($data['description'] ?? null);

            $product->update($data);
            $product->categories()->sync($categoryIds);

            $this->deleteImages($product, $deleteImageIds);
            $this->updateImageOrder($product, $imageOrder);
            $this->storeImages($product, $newImages, $mainNewImageIndex, false);
            $this->setMainImage($product, $mainImageId);
            $this->syncAttributes($product, $attributes);

            logActivity('updated', 'Product', $product->id, "Обновлён товар «{$product->name}»");

            $this->homeService->clearCache();
            $this->catalogService->clearCache();

            return $product->refresh()->load(['categories', 'images', 'attributes']);
        });
    }

    /**
     * @return array{deleted: bool, deactivated: bool, message: string}
     */
    public function delete(Product $product): array
    {
        if ($this->hasActiveOrders($product)) {
            $product->update(['is_active' => false]);

            logActivity('updated', 'Product', $product->id, "Товар «{$product->name}» деактивирован (есть активные заказы)");

            $this->homeService->clearCache();
            $this->catalogService->clearCache();

            return [
                'deleted' => false,
                'deactivated' => true,
                'message' => 'Товар участвует в активных заказах и не может быть удалён. Он помечен как неактивный.',
            ];
        }

        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                $this->imageService->delete($image->image_path);
                $image->delete();
            }

            $product->attributes()->delete();
            $product->categories()->detach();
            $product->delete();
        });

        logActivity('deleted', 'Product', $product->id, "Удалён товар «{$product->name}»");

        $this->homeService->clearCache();
        $this->catalogService->clearCache();

        return [
            'deleted' => true,
            'deactivated' => false,
            'message' => 'Товар удалён.',
        ];
    }

    public function hasActiveOrders(Product $product): bool
    {
        if (! Schema::hasTable('order_items')) {
            return false;
        }

        return DB::table('order_items')
            ->where('product_id', $product->id)
            ->exists();
    }

    private function formatDatatableRow(Product $product): array
    {
        $mainImage = $product->mainImage();
        $thumbHtml = $mainImage
            ? '<img src="'.e(asset('storage/'.$mainImage->image_path)).'" alt="" width="50" height="50" style="object-fit:cover;border-radius:6px">'
            : '<span style="display:inline-flex;width:50px;height:50px;align-items:center;justify-content:center;background:var(--bg-surface-secondary);border-radius:6px;color:var(--text-muted);font-size:11px">Нет</span>';

        $priceHtml = number_format((float) $product->price, 2, '.', ' ').' ₽';
        if ($product->old_price) {
            $priceHtml .= '<div style="font-size:11px;color:var(--text-muted);text-decoration:line-through">'
                .number_format((float) $product->old_price, 2, '.', ' ').' ₽</div>';
        }

        $statusHtml = $product->is_active
            ? '<span class="badge badge-teal">Активен</span>'
            : '<span class="badge badge-red">Неактивен</span>';

        if ($product->is_featured) {
            $statusHtml .= ' <span class="badge badge-yellow">Хит</span>';
        }

        $editUrl = route('admin.products.edit', $product);
        $deleteUrl = route('admin.products.destroy', $product);
        $confirmMessage = e('Удалить товар «'.$product->name.'»?');

        $actionsHtml = <<<HTML
            <div style="display:inline-flex;gap:8px;justify-content:flex-end;width:100%">
                <a href="{$editUrl}" class="btn btn-outline btn-sm">Изменить</a>
                <form method="POST" action="{$deleteUrl}" data-confirm="{$confirmMessage}">
                    <input type="hidden" name="_token" value="{$this->csrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger)">Удалить</button>
                </form>
            </div>
        HTML;

        return [
            'id' => $product->id,
            'thumbnail' => $thumbHtml,
            'name' => e($product->name).'<div style="font-size:11px;color:var(--text-muted)">'.e($product->sku).'</div>',
            'price' => $priceHtml,
            'quantity' => $product->quantity,
            'is_active' => $statusHtml,
            'actions' => $actionsHtml,
        ];
    }

    /**
     * @param  array<int, UploadedFile>  $images
     */
    private function storeImages(Product $product, array $images, ?int $mainImageIndex, bool $setMainIfFirst = true): void
    {
        $maxSort = (int) $product->images()->max('sort_order');
        $createdIds = [];

        foreach (array_values($images) as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $image = $product->images()->create([
                'image_path' => $this->imageService->storeProductImage($file),
                'is_main' => false,
                'sort_order' => $maxSort + $index + 1,
            ]);

            $createdIds[] = $image->id;
        }

        if ($setMainIfFirst && $createdIds !== [] && ! $product->images()->where('is_main', true)->exists()) {
            $mainIndex = $mainImageIndex ?? 0;
            $mainId = $createdIds[$mainIndex] ?? $createdIds[0];
            $this->setMainImage($product, $mainId);
        } elseif ($mainImageIndex !== null && isset($createdIds[$mainImageIndex])) {
            $this->setMainImage($product, $createdIds[$mainImageIndex]);
        }
    }

    /**
     * @param  array<int, int|string>  $deleteImageIds
     */
    private function deleteImages(Product $product, array $deleteImageIds): void
    {
        if ($deleteImageIds === []) {
            return;
        }

        $product->images()
            ->whereIn('id', $deleteImageIds)
            ->get()
            ->each(function (ProductImage $image) {
                $this->imageService->delete($image->image_path);
                $image->delete();
            });
    }

    /**
     * @param  array<int, int|string>  $imageOrder
     */
    private function updateImageOrder(Product $product, array $imageOrder): void
    {
        foreach ($imageOrder as $sort => $imageId) {
            $product->images()->where('id', $imageId)->update(['sort_order' => $sort + 1]);
        }
    }

    private function setMainImage(Product $product, ?int $mainImageId): void
    {
        if (! $mainImageId) {
            return;
        }

        $product->images()->update(['is_main' => false]);
        $product->images()->where('id', $mainImageId)->update(['is_main' => true]);
    }

    /**
     * @param  array<int, array{name: string, value: string}>  $attributes
     */
    private function syncAttributes(Product $product, array $attributes): void
    {
        $product->attributes()->delete();

        foreach ($attributes as $attribute) {
            $name = trim($attribute['name'] ?? '');
            $value = trim($attribute['value'] ?? '');

            if ($name === '' || $value === '') {
                continue;
            }

            $product->attributes()->create([
                'attribute_name' => $name,
                'attribute_value' => $value,
            ]);
        }
    }

    private function resolveSlug(string $name, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $base = $base !== '' ? $base : 'product';
        $candidate = $base;
        $counter = 1;

        while (
            Product::withTrashed()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    private function sanitizeDescription(?string $html): ?string
    {
        if (blank($html)) {
            return null;
        }

        $html = trim($html);

        if (str_contains($html, 'rt-editor') && preg_match('/<div[^>]*class="rt-editor"[^>]*>(.*?)<\/div>/is', $html, $matches)) {
            $html = trim($matches[1]);
        }

        if (str_contains($html, 'rt-toolbar')) {
            return null;
        }

        return $html === '' ? null : $html;
    }

    private function csrfToken(): string
    {
        return csrf_token();
    }
}
