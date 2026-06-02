<?php

namespace App\Repository;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    private function withRelations()
    {
        return Product::with(['category', 'brand', 'media', 'outerMaterial', 'liningMaterial', 'filling', 'variants']);
    }

    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->withRelations()->get();
    }

    public function getPaginated(
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
        $query = $this->withRelations()->where('is_active', true);

        if ($search) {
            $like = "%{$search}%";
            $query->where(function ($q) use ($like) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ro')) LIKE ?", [$like])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ru')) LIKE ?", [$like]);
            });
        }
        if (!empty($categories))    $query->whereIn('category_id', $categories);
        if ($priceMin !== null)     $query->where('price', '>=', $priceMin);
        if ($priceMax !== null)     $query->where('price', '<=', $priceMax);
        if (!empty($outerMaterials))  $query->whereIn('outer_material_id', $outerMaterials);
        if (!empty($liningMaterials)) $query->whereIn('lining_material_id', $liningMaterials);
        if (!empty($fillings))        $query->whereIn('filling_id', $fillings);
        if (!empty($seasons))         $query->whereIn('season', $seasons);
        if (!empty($lengths))         $query->whereIn('length', $lengths);
        if ($hood !== null)           $query->where('hood', $hood);
        if ($waterproof !== null)     $query->where('waterproof', $waterproof);
        if (!empty($colors))          $query->whereHas('variants', fn($q) => $q->whereIn('color', $colors));
        if (!empty($sizes))           $query->whereHas('variants', fn($q) => $q->whereIn('size', $sizes));

        return $query->paginate($perPage);
    }

    public function getVariantOptions(): array
    {
        $colors = ProductVariant::select('color', DB::raw('MAX(color_hex) as color_hex'))
            ->whereNotNull('color')->where('color', '!=', '')
            ->groupBy('color')->orderBy('color')
            ->get()
            ->map(fn($v) => ['name' => $v->color, 'hex' => $v->color_hex ?? '#888888'])
            ->values()->toArray();

        $sizes = ProductVariant::whereNotNull('size')->where('size', '!=', '')
            ->distinct()->orderBy('size')->pluck('size')->values()->toArray();

        return compact('colors', 'sizes');
    }

    public function getFeatured(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return $this->withRelations()
            ->where('is_active', true)
            ->where(fn($q) => $q->where('is_hit', true)->orWhere('is_new', true))
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findById(int $id): ?Product
    {
        return $this->withRelations()->find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data)->load(['category', 'brand', 'media', 'outerMaterial', 'liningMaterial', 'filling']);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh(['category', 'brand', 'media', 'outerMaterial', 'liningMaterial', 'filling']);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}