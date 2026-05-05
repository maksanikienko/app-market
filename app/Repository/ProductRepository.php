<?php

namespace App\Repository;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')->get();
    }

    public function getPaginated(
        int $perPage = 12,
        ?string $search = null,
        array $categories = [],
        ?float $priceMin = null,
        ?float $priceMax = null
    ): LengthAwarePaginator {
        $query = Product::with('category');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($categories)) {
            $query->whereIn('category_id', $categories);
        }

        if ($priceMin !== null) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax !== null) {
            $query->where('price', '<=', $priceMax);
        }

        return $query->paginate($perPage);
    }

    public function getFeatured(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')->latest()->limit($limit)->get();
    }

    public function findById(int $id): ?Product
    {
        return Product::with('category')->find($id);
    }
}