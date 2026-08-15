<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->has('draw')) {
            return response()->json($this->productService->datatable($request));
        }

        return view('admin.products.index', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create(
            data: $request->safe()->except(['category_ids', 'attributes', 'images', 'main_new_image']),
            categoryIds: $request->input('category_ids', []),
            images: array_values($request->file('images') ?? []),
            attributes: $request->input('attributes', []),
            mainImageIndex: $request->integer('main_new_image'),
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Товар успешно создан.');
    }

    public function edit(Product $product): View
    {
        $product->load(['categories', 'images', 'attributes']);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update(
            product: $product,
            data: $request->safe()->except([
                'category_ids', 'attributes', 'images', 'delete_image_ids',
                'image_order', 'main_image_id', 'main_new_image',
            ]),
            categoryIds: $request->input('category_ids', []),
            newImages: array_values($request->file('images') ?? []),
            attributes: $request->input('attributes', []),
            deleteImageIds: $request->input('delete_image_ids', []),
            imageOrder: array_values($request->input('image_order', [])),
            mainImageId: $request->input('main_image_id') ? (int) $request->input('main_image_id') : null,
            mainNewImageIndex: $request->has('main_new_image') ? $request->integer('main_new_image') : null,
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Товар успешно обновлён.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $result = $this->productService->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with($result['deactivated'] ? 'error' : 'success', $result['message']);
    }
}
