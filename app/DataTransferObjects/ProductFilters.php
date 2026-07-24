<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

final readonly class ProductFilters
{
    public function __construct(
        public int     $perPage = 12,
        public ?string $search = null,
        public array   $categories = [],
        public ?float  $priceMin = null,
        public ?float  $priceMax = null,
        public array   $outerMaterials = [],
        public array   $liningMaterials = [],
        public array   $fillings = [],
        public array   $seasons = [],
        public array   $lengths = [],
        public ?bool   $hood = null,
        public ?bool   $waterproof = null,
        public array   $colors = [],
        public array   $sizes = [],
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            perPage:         (int) $request->integer('per_page', 12),
            search:          $request->string('search')->trim()->value() ?: null,
            categories:      self::intArray($request, 'categories'),
            priceMin:        $request->filled('price_min') ? (float) $request->input('price_min') : null,
            priceMax:        $request->filled('price_max') ? (float) $request->input('price_max') : null,
            outerMaterials:  self::intArray($request, 'outer_materials'),
            liningMaterials: self::intArray($request, 'lining_materials'),
            fillings:        self::intArray($request, 'fillings'),
            seasons:         self::stringArray($request, 'seasons'),
            lengths:         self::stringArray($request, 'lengths'),
            hood:            $request->filled('hood') ? (bool) $request->input('hood') : null,
            waterproof:      $request->filled('waterproof') ? (bool) $request->input('waterproof') : null,
            colors:          self::stringArray($request, 'colors'),
            sizes:           self::stringArray($request, 'sizes'),
        );
    }

    private static function intArray(Request $request, string $key): array
    {
        return array_filter(array_map('intval', (array) $request->input($key, [])));
    }

    private static function stringArray(Request $request, string $key): array
    {
        return array_filter((array) $request->input($key, []));
    }
}
