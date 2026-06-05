<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'code'        => $request->string('code')->trim()->value() ?: null,
                'name'        => $request->string('name')->trim()->value() ?: null,
                'category_id' => $request->filled('category_id') ? (int) $request->input('category_id') : null,
                'is_new'      => $request->has('is_new')  ? (bool) (int) $request->input('is_new')  : null,
                'is_hit'      => $request->has('is_hit')  ? (bool) (int) $request->input('is_hit')  : null,
                'is_sale'     => $request->has('is_sale') ? (bool) (int) $request->input('is_sale') : null,
                'trashed'     => $request->boolean('trashed', false),
                'per_page'    => $request->integer('per_page', 20),
            ];

            $paginator = $this->productService->getAdminProducts($filters);

            return response()->json([
                'data' => $paginator->items(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function store(ProductRequest $request): JsonResponse
    {
        try {
            return response()->json($this->productService->createProduct($request->validated()), 201);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            return response()->json($this->productService->updateProduct($product, $request->validated()));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->productService->deleteProduct($product);
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $this->productService->restoreProduct($id);
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function forceDelete(int $id): JsonResponse
    {
        try {
            $this->productService->forceDeleteProduct($id);
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}