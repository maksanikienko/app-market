<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\RouteDiscovery\Attributes\Route;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService) {}

    #[Route(method: ['GET'], name: 'api.products.categories')]
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        try {
            return CategoryResource::collection($this->categoryService->getCategories());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
