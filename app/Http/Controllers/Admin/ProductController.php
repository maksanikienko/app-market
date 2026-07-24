<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\AdminProductFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: ['auth', 'role:admin'])]
class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    #[Route(method: 'GET', fullUri: 'admin/products', name: 'api.admin.products.index')]
    public function index(Request $request): JsonResponse
    {
        try {
            $paginator = $this->productService->getAdminProducts(AdminProductFilters::fromRequest($request));

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

    #[Route(method: 'POST', fullUri: 'admin/products', name: 'api.admin.products.store')]
    public function store(ProductRequest $request): JsonResponse
    {
        try {
            return response()->json($this->productService->createProduct($request->validated()), 201);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: ['PUT', 'PATCH'], fullUri: 'admin/products/{product}', name: 'api.admin.products.update')]
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            return response()->json($this->productService->updateProduct($product, $request->validated()));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'DELETE', fullUri: 'admin/products/{product}', name: 'api.admin.products.destroy')]
    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->productService->deleteProduct($product);
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'POST', fullUri: 'admin/products/{id}/restore', name: 'api.admin.products.restore')]
    public function restore(int $id): JsonResponse
    {
        try {
            $this->productService->restoreProduct($id);
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'DELETE', fullUri: 'admin/products/{id}/force-delete', name: 'api.admin.products.force-delete')]
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