<?php

namespace App\Http\Controllers\Api;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController
{
    public ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Paginated product list with optional filters.
     *
     * Query params:
     *   page         (int, default 1)
     *   per_page     (int, default 12)
     *   search       (string)
     *   categories[] (array of ints)
     *   price_min    (float)
     *   price_max    (float)
     */
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->productService->getPaginatedProducts(
            perPage: (int) $request->integer('per_page', 12),
            search: $request->string('search')->trim()->value() ?: null,
            categories: array_filter(array_map('intval', (array) $request->input('categories', []))),
            priceMin: $request->filled('price_min') ? (float) $request->input('price_min') : null,
            priceMax: $request->filled('price_max') ? (float) $request->input('price_max') : null,
        );

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Latest products for the home page featured section.
     */
    public function featured(): JsonResponse
    {
        return response()->json(
            $this->productService->getFeaturedProducts(8)
        );
    }

    /**
     * Single product detail.
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}