<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->has('draw')) {
            return response()->json($this->categoryService->datatable($request));
        }

        return view('admin.categories.index');
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'parentOptions' => $this->categoryService->parentOptions(),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create(
            $request->safe()->except('image'),
            $request->file('image'),
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Категория успешно создана.');
    }

    public function edit(Category $category): View
    {
        $category->loadCount('products');

        return view('admin.categories.edit', [
            'category' => $category,
            'parentOptions' => $this->categoryService->parentOptions($category),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->update(
            $category,
            $request->safe()->except('image'),
            $request->file('image'),
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $detached = $this->categoryService->delete($category);

        $message = 'Категория удалена.';
        if ($detached > 0) {
            $message .= " От категории отвязано товаров: {$detached}.";
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', $message);
    }
}
