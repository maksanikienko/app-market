<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductClassifier;
use Illuminate\Http\JsonResponse;

class ClassifierController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $grouped = ProductClassifier::where('is_active', true)
                ->orderBy('key')
                ->get()
                ->map(fn($c) => [
                    'id'   => $c->id,
                    'type' => $c->type,
                    'key'  => $c->key,
                    'name' => $c->getTranslations('name'),
                ])
                ->groupBy('type');

            return response()->json($grouped);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
