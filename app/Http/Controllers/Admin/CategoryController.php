<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->categoryService->getCategories());
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        return response()->json($this->categoryService->createCategory($request->validated()), 201);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        return response()->json($this->categoryService->updateCategory($category, $request->validated()));
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);
        return response()->json(null, 204);
    }
}