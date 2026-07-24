<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\ProductFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteDiscovery\Attributes\Route;
use Spatie\RouteDiscovery\Attributes\Where;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    #[Route(method: ['GET'], name: 'api.products.index')]
    public function index(Request $request): JsonResponse
    {
        try {
            $paginator = $this->productService->getPaginatedProducts(ProductFilters::fromRequest($request));

            return response()->json([
                'data' => ProductResource::collection($paginator->items()),
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

    #[Route(method: ['GET'], name: 'api.products.featured')]
    public function featured(): JsonResponse
    {
        try {
            return response()->json($this->productService->getFeaturedProducts(8));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: ['GET'], name: 'api.products.variant-options')]
    public function variantOptions(): JsonResponse
    {
        try {
            return response()->json($this->productService->getVariantOptions());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: ['GET'], uri: '{id}', name: 'api.products.show')]
    #[Where('id', Where::numeric)]
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            return response()->json($product);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
