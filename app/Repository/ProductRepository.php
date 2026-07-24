<?php

declare(strict_types=1);

namespace App\Repository;

use App\DataTransferObjects\AdminProductFilters;
use App\DataTransferObjects\ProductFilters;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    private const RELATIONS = ['category', 'brand', 'media', 'outerMaterial', 'liningMaterial', 'filling', 'variants.location'];

    private function withRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return Product::with(self::RELATIONS);
    }

    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->withRelations()->get();
    }

    public function getAdminFiltered(AdminProductFilters $filters): LengthAwarePaginator
    {
        $query = $filters->trashed
            ? $this->withRelations()->onlyTrashed()
            : $this->withRelations();

        if ($filters->code) {
            $query->where('code', 'like', "%{$filters->code}%");
        }
        if ($filters->name) {
            $like = "%{$filters->name}%";
            $query->where(function ($q) use ($like) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ro')) LIKE ?", [$like])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ru')) LIKE ?", [$like]);
            });
        }
        if ($filters->categoryId !== null) {
            $query->where('category_id', $filters->categoryId);
        }
        if ($filters->isNew !== null) {
            $query->where('is_new', $filters->isNew);
        }
        if ($filters->isHit !== null) {
            $query->where('is_hit', $filters->isHit);
        }
        if ($filters->isSale !== null) {
            $query->where('is_sale', $filters->isSale);
        }

        return $query->orderByDesc('is_active')->orderByDesc('id')->paginate($filters->perPage);
    }

    public function getPaginated(ProductFilters $filters): LengthAwarePaginator
    {
        $query = $this->withRelations()->where('is_active', true);

        if ($filters->search) {
            $like = "%{$filters->search}%";
            $query->where(function ($q) use ($like) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ro')) LIKE ?", [$like])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.ru')) LIKE ?", [$like]);
            });
        }
        if (!empty($filters->categories))      $query->whereIn('category_id', $filters->categories);
        if ($filters->priceMin !== null)       $query->where('price', '>=', $filters->priceMin);
        if ($filters->priceMax !== null)       $query->where('price', '<=', $filters->priceMax);
        if (!empty($filters->outerMaterials))  $query->whereIn('outer_material_id', $filters->outerMaterials);
        if (!empty($filters->liningMaterials)) $query->whereIn('lining_material_id', $filters->liningMaterials);
        if (!empty($filters->fillings))        $query->whereIn('filling_id', $filters->fillings);
        if (!empty($filters->seasons))         $query->whereIn('season', $filters->seasons);
        if (!empty($filters->lengths))         $query->whereIn('length', $filters->lengths);
        if ($filters->hood !== null)           $query->where('hood', $filters->hood);
        if ($filters->waterproof !== null)     $query->where('waterproof', $filters->waterproof);
        if (!empty($filters->colors))          $query->whereHas('variants', fn($q) => $q->whereIn('color', $filters->colors));
        if (!empty($filters->sizes))           $query->whereHas('variants', fn($q) => $q->whereIn('size', $filters->sizes));

        return $query->paginate($filters->perPage);
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
        return Product::create($data)->load(self::RELATIONS);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh(self::RELATIONS);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function restore(int $id): void
    {
        Product::withTrashed()->findOrFail($id)->restore();
    }

    public function forceDelete(int $id): void
    {
        Product::withTrashed()->findOrFail($id)->forceDelete();
    }
}