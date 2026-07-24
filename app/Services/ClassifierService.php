<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductClassifier;
use Illuminate\Support\Collection;

class ClassifierService
{
    public function getGroupedClassifiers(): Collection
    {
        return ProductClassifier::where('is_active', true)
            ->orderBy('key')
            ->get()
            ->map(fn(ProductClassifier $c) => [
                'id'   => $c->id,
                'type' => $c->type,
                'key'  => $c->key,
                'name' => $c->getTranslations('name'),
            ])
            ->groupBy('type');
    }
}
