<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->productService->getProducts());
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return response()->json($product, 201);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        return response()->json(
            $this->productService->updateProduct($product, $request->except('_method'))
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);
        return response()->json(null, 204);
    }
}
