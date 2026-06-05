<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(public ProductRepository $productRepository) {}

    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productRepository->getProducts();
    }

    public function getAdminProducts(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->getAdminFiltered($filters);
    }

    public function getPaginatedProducts(
        int     $perPage        = 12,
        ?string $search         = null,
        array   $categories     = [],
        ?float  $priceMin       = null,
        ?float  $priceMax       = null,
        array   $outerMaterials  = [],
        array   $liningMaterials = [],
        array   $fillings        = [],
        array   $seasons         = [],
        array   $lengths         = [],
        ?bool   $hood            = null,
        ?bool   $waterproof      = null,
        array   $colors          = [],
        array   $sizes           = [],
    ): LengthAwarePaginator {
        return $this->productRepository->getPaginated(
            $perPage, $search, $categories, $priceMin, $priceMax,
            $outerMaterials, $liningMaterials, $fillings,
            $seasons, $lengths, $hood, $waterproof, $colors, $sizes,
        );
    }

    public function getVariantOptions(): array
    {
        return $this->productRepository->getVariantOptions();
    }

    public function getFeaturedProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productRepository->getFeatured($limit);
    }

    public function getProductById(int $id): ?\App\Models\Product
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data): \App\Models\Product
    {
        $variants = $data['variants'] ?? [];
        unset($data['variants']);
        $product = $this->productRepository->create($data);
        if (!empty($variants)) {
            $product->variants()->createMany($variants);
        }
        return $product->load(['category', 'brand', 'media', 'outerMaterial', 'liningMaterial', 'filling', 'variants.location']);
    }

    public function updateProduct(\App\Models\Product $product, array $data): \App\Models\Product
    {
        $variants = array_key_exists('variants', $data) ? $data['variants'] : null;
        unset($data['variants'], $data['_method']);
        $product = $this->productRepository->update($product, $data);
        if ($variants !== null) {
            $product->variants()->delete();
            if (!empty($variants)) {
                $product->variants()->createMany($variants);
            }
        }
        return $product->fresh(['category', 'brand', 'media', 'outerMaterial', 'liningMaterial', 'filling', 'variants.location']);
    }

    public function deleteProduct(\App\Models\Product $product): void
    {
        $this->productRepository->delete($product);
    }

    public function restoreProduct(int $id): void
    {
        $this->productRepository->restore($id);
    }

    public function forceDeleteProduct(int $id): void
    {
        $this->productRepository->forceDelete($id);
    }
}