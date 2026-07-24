<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\Route;

class BrandController extends Controller
{
    #[Route(method: ['GET'], name: 'api.products.brands.index')]
    public function index(): JsonResponse
    {
        try {
            return response()->json(Brand::where('is_active', true)->orderBy('name')->get());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
