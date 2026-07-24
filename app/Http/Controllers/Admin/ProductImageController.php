<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: ['auth', 'role:admin'])]
class ProductImageController extends Controller
{
    #[Route(method: 'POST', fullUri: 'admin/products/{product}/images', name: 'api.admin.products.images.store')]
    public function store(Request $request, Product $product): JsonResponse
    {
        try {
            $request->validate([
                'images'   => 'required|array|max:10',
                'images.*' => 'image|max:8192',
            ]);

            foreach ($request->file('images') as $file) {
                $product->addMedia($file)->toMediaCollection('product_images');
            }

            return response()->json($product->fresh()->media_items);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'PUT', fullUri: 'admin/products/{product}/images/reorder', name: 'api.admin.products.images.reorder')]
    public function reorder(Request $request, Product $product): JsonResponse
    {
        try {
            $request->validate(['ids' => 'required|array']);
            Media::setNewOrder($request->input('ids'));
            return response()->json($product->fresh()->media_items);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'DELETE', fullUri: 'admin/products/{product}/images/{media}', name: 'api.admin.products.images.destroy')]
    public function destroy(Product $product, Media $media): JsonResponse
    {
        try {
            $media->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
