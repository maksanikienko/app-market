<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            return response()->json(Brand::where('is_active', true)->orderBy('name')->get());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
