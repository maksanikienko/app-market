<?php

namespace App\Http\Controllers\Api;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController
{
    public function __construct(public ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->productService->getPaginatedProducts(
            perPage:        (int) $request->integer('per_page', 12),
            search:         $request->string('search')->trim()->value() ?: null,
            categories:     array_filter(array_map('intval', (array) $request->input('categories', []))),
            priceMin:       $request->filled('price_min') ? (float) $request->input('price_min') : null,
            priceMax:       $request->filled('price_max') ? (float) $request->input('price_max') : null,
            outerMaterials:  array_filter(array_map('intval', (array) $request->input('outer_materials', []))),
            liningMaterials: array_filter(array_map('intval', (array) $request->input('lining_materials', []))),
            fillings:        array_filter(array_map('intval', (array) $request->input('fillings', []))),
            seasons:         array_filter((array) $request->input('seasons', [])),
            lengths:         array_filter((array) $request->input('lengths', [])),
            hood:            $request->filled('hood') ? (bool) $request->input('hood') : null,
            waterproof:      $request->filled('waterproof') ? (bool) $request->input('waterproof') : null,
            colors:          array_filter((array) $request->input('colors', [])),
            sizes:           array_filter((array) $request->input('sizes', [])),
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

    public function featured(): JsonResponse
    {
        return response()->json($this->productService->getFeaturedProducts(8));
    }

    public function variantOptions(): JsonResponse
    {
        return response()->json($this->productService->getVariantOptions());
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}