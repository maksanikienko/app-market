<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductClassifier;
use Illuminate\Http\JsonResponse;

class ClassifierController
{
    public function index(): JsonResponse
    {
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
    }
}