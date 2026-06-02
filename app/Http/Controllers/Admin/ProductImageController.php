<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'images'   => 'required|array|max:10',
            'images.*' => 'image|max:8192',
        ]);

        foreach ($request->file('images') as $file) {
            $product->addMedia($file)->toMediaCollection('product_images');
        }

        return response()->json($this->items($product));
    }

    public function reorder(Request $request, Product $product): JsonResponse
    {
        $request->validate(['ids' => 'required|array']);
        Media::setNewOrder($request->input('ids'));
        return response()->json($this->items($product));
    }

    public function destroy(Product $product, Media $media): JsonResponse
    {
        $media->delete();
        return response()->json(null, 204);
    }

    private function items(Product $product): array
    {
        return $product->fresh()->getMedia('product_images')
            ->map(fn($m) => [
                'id'           => $m->id,
                'thumb_url'    => $m->getUrl('thumb'),
                'original_url' => $m->getUrl(),
                'order'        => $m->order_column,
            ])->values()->toArray();
    }
}
