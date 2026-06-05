<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            return response()->json(Location::where('is_active', true)->orderBy('type')->orderBy('name')->get());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function stock(): JsonResponse
    {
        try {
            $mapVariant = fn($v) => [
                'id'           => $v->id,
                'product_id'   => $v->product_id,
                'product_name' => $v->product?->getTranslations('name'),
                'product_code' => $v->product?->code,
                'color'        => $v->color,
                'color_hex'    => $v->color_hex,
                'size'         => $v->size,
                'sku'          => $v->sku,
                'stock'        => $v->stock,
            ];

            $locations = Location::with(['variants' => fn($q) => $q->with('product:id,name,code')])
                ->get()
                ->map(fn($l) => [
                    'id'          => $l->id,
                    'name'        => $l->name,
                    'type'        => $l->type,
                    'total_stock' => $l->variants->sum('stock'),
                    'variants'    => $l->variants->map($mapVariant)->sortBy('product_id')->values(),
                ]);

            $unassigned = ProductVariant::whereNull('location_id')->with('product:id,name,code')->get();
            if ($unassigned->isNotEmpty()) {
                $locations->push([
                    'id'          => null,
                    'name'        => 'Без локации',
                    'type'        => null,
                    'total_stock' => $unassigned->sum('stock'),
                    'variants'    => $unassigned->map($mapVariant)->sortBy('product_id')->values(),
                ]);
            }

            return response()->json($locations);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
