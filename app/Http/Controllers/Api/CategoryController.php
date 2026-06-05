<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getCategories()->map(fn($c) => [
                'id'          => $c->id,
                'slug'        => $c->slug,
                'name'        => $c->getTranslations('name'),
                'description' => $c->getTranslations('description'),
                'is_active'   => $c->is_active,
                'sort_order'  => $c->sort_order,
            ]);

            return response()->json($categories);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
