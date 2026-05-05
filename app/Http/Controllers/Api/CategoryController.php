<?php

namespace App\Http\Controllers\Api;

use App\Services\CategoryService;

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->categoryService->getCategories());
    }

}