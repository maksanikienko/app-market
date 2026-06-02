<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController
{
    public function index(): JsonResponse
    {
        return response()->json(Brand::where('is_active', true)->orderBy('name')->get());
    }
}