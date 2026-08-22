<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryService
{
    public const MAX_DEPTH = 3;

    private const NAVIGATION_CACHE_KEY = 'categories.navigation';

    public function __construct(
        private readonly ImageService $imageService,
        private readonly HomeService $homeService,
    ) {}

    /**
     * @return Collection<int, Category>
     */
    public function getNavigationTree(): Collection
    {
        return Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function clearNavigationCache(): void
    {
        Cache::forget(self::NAVIGATION_CACHE_KEY);
        $this->homeService->clearCache();
    }

    /**
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<int, array<string, mixed>>}
     */
    public function datatable(Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim((string) $request->input('search.value', ''));

        $orderColumn = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';

        $filterId = trim((string) $request->input('id', ''));
        $filterName = trim((string) $request->input('name', ''));
        $filterStatus = $request->input('status');

        $flat = $this->buildFlatTree(
            Category::query()
                ->withCount('products')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
        );

        $recordsTotal = $flat->count();

        if ($filterId !== '') {
            $flat = $flat->filter(fn (array $row) => str_contains((string) $row['id'], $filterId))->values();
        }

        if ($filterName !== '') {
            $flat = $flat->filter(fn (array $row) => str_contains(mb_strtolower($row['name_plain']), mb_strtolower($filterName)))->values();
        }

        if ($filterStatus === 'active') {
            $flat = $flat->filter(fn (array $row) => $row['is_active_raw'])->values();
        } elseif ($filterStatus === 'inactive') {
            $flat = $flat->filter(fn (array $row) => ! $row['is_active_raw'])->values();
        }

        if ($search !== '') {
            $flat = $flat->filter(function (array $row) use ($search) {
                return str_contains(mb_strtolower($row['name_plain']), mb_strtolower($search))
                    || str_contains((string) $row['id'], $search);
            })->values();
        }

        $recordsFiltered = $flat->count();

        $sortKey = match ($orderColumn) {
            0 => 'id',
            2 => 'is_active',
            default => 'name_plain',
        };

        $flat = $orderDir === 'desc'
            ? $flat->sortByDesc($sortKey)->values()
            : $flat->sortBy($sortKey)->values();

        $page = $flat->slice($start, $length > 0 ? $length : null)->values();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $page->map(fn (array $row) => [
                'id' => $row['id'],
                'name' => $row['name'],
                'is_active' => $row['is_active'],
                'actions' => $row['actions'],
            ])->all(),
        ];
    }

    /**
     * @return array<int, string>
     */
    public function parentOptions(?Category $exclude = null): array
    {
        $options = ['' => '— Корневая категория —'];
        $excludeIds = $exclude ? $this->getDescendantIds($exclude)->push($exclude->id)->all() : [];

        foreach ($this->buildFlatTree(Category::query()->orderBy('sort_order')->orderBy('name')->get()) as $row) {
            if (in_array($row['id'], $excludeIds, true)) {
                continue;
            }

            if ($row['depth'] >= self::MAX_DEPTH - 1) {
                continue;
            }

            $options[$row['id']] = str_repeat('— ', $row['depth']).$row['name_plain'];
        }

        return $options;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $image = null): Category
    {
        $data['slug'] = $this->resolveSlug($data['name'], $data['slug'] ?? null);
        $data['description'] = $this->sanitizeDescription($data['description'] ?? null);
        $data['parent_id'] = null;

        if ($image) {
            $data['image'] = $this->imageService->storeCategoryImage($image);
        }

        $category = Category::query()->create($data);

        $this->clearNavigationCache();

        logActivity('created', 'Category', $category->id, "Создана категория «{$category->name}»");

        return $category;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Category $category, array $data, ?UploadedFile $image = null): Category
    {
        $data['slug'] = $this->resolveSlug(
            $data['name'],
            $data['slug'] ?? null,
            $category->id
        );
        $data['description'] = $this->sanitizeDescription($data['description'] ?? null);
        $data['parent_id'] = null;

        if ($image) {
            $this->imageService->delete($category->image);
            $data['image'] = $this->imageService->storeCategoryImage($image);
        }

        $category->update($data);

        $this->clearNavigationCache();

        logActivity('updated', 'Category', $category->id, "Обновлена категория «{$category->name}»");

        return $category->refresh();
    }

    public function delete(Category $category): int
    {
        $detachedProducts = $category->products()->count();

        $category->products()->detach();

        $name = $category->name;
        $categoryId = $category->id;

        $this->softDeleteDescendants($category);

        $category->delete();

        $this->clearNavigationCache();

        logActivity('deleted', 'Category', $categoryId, "Удалена категория «{$name}»");

        return $detachedProducts;
    }

    public function maxDepthForParent(?int $parentId): int
    {
        if ($parentId === null) {
            return 0;
        }

        $parent = Category::query()->find($parentId);

        return $parent ? $parent->depth() + 1 : 0;
    }

    private function resolveSlug(string $name, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $name);
        $base = $base !== '' ? $base : 'category';
        $candidate = $base;
        $counter = 1;

        while (
            Category::withTrashed()
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

    /**
     * @param  Collection<int, Category>  $categories
     * @return Collection<int, array<string, mixed>>
     */
    private function buildFlatTree(Collection $categories, ?int $parentId = null, int $depth = 0): Collection
    {
        $result = collect();

        foreach ($categories->where('parent_id', $parentId) as $category) {
            $indent = str_repeat('— ', $depth);
            $nameHtml = e($indent.$category->name);

            $statusHtml = $category->is_active
                ? '<span class="badge badge-teal">Активна</span>'
                : '<span class="badge badge-red">Неактивна</span>';

            $editUrl = route('admin.categories.edit', $category);
            $deleteUrl = route('admin.categories.destroy', $category);
            $productsCount = $category->products_count ?? 0;
            $warning = $productsCount > 0
                ? "От категории будут отвязаны {$productsCount} товар(ов). "
                : '';
            $confirmMessage = e($warning.'Удалить категорию «'.$category->name.'»?');

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

            $result->push([
                'id' => $category->id,
                'depth' => $depth,
                'name_plain' => $category->name,
                'name' => $nameHtml,
                'is_active' => $statusHtml,
                'is_active_raw' => $category->is_active,
                'actions' => $actionsHtml,
            ]);

            $result = $result->merge(
                $this->buildFlatTree($categories, $category->id, $depth + 1)
            );
        }

        return $result;
    }

    private function csrfToken(): string
    {
        return csrf_token();
    }

    private function softDeleteDescendants(Category $category): void
    {
        $category->children()->each(function (Category $child) {
            $this->softDeleteDescendants($child);
            $child->products()->detach();
            $child->delete();
        });
    }

    /**
     * @return Collection<int, int>
     */
    public function getDescendantIds(Category $category): Collection
    {
        $ids = collect();
        $parentIds = [$category->id];

        while ($parentIds !== []) {
            $children = Category::query()
                ->whereIn('parent_id', $parentIds)
                ->pluck('id');

            $ids = $ids->merge($children);
            $parentIds = $children->all();
        }

        return $ids;
    }
}
