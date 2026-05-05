<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productRepository->getProducts();
    }

    public function getPaginatedProducts(
        int $perPage = 12,
        ?string $search = null,
        array $categories = [],
        ?float $priceMin = null,
        ?float $priceMax = null
    ): LengthAwarePaginator {
        return $this->productRepository->getPaginated($perPage, $search, $categories, $priceMin, $priceMax);
    }

    public function getFeaturedProducts(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return $this->productRepository->getFeatured($limit);
    }

    public function getProductById(int $id): ?\App\Models\Product
    {
        return $this->productRepository->findById($id);
    }
}