<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: ['auth', 'role:admin'])]
class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService) {}

    #[Route(method: 'GET', fullUri: 'admin/categories', name: 'api.admin.categories.index')]
    public function index(): JsonResponse
    {
        try {
            return response()->json($this->categoryService->getCategories());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'GET', fullUri: 'admin/categories/{category}', name: 'api.admin.categories.show')]
    public function show(Category $category): JsonResponse
    {
        try {
            return response()->json($category);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'POST', fullUri: 'admin/categories', name: 'api.admin.categories.store')]
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            return response()->json($this->categoryService->createCategory($request->validated()), 201);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: ['PUT', 'PATCH'], fullUri: 'admin/categories/{category}', name: 'api.admin.categories.update')]
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        try {
            return response()->json($this->categoryService->updateCategory($category, $request->validated()));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'DELETE', fullUri: 'admin/categories/{category}', name: 'api.admin.categories.destroy')]
    public function destroy(Category $category): JsonResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
