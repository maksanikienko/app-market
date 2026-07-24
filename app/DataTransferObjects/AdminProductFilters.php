<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

final readonly class AdminProductFilters
{
    public function __construct(
        public ?string $code = null,
        public ?string $name = null,
        public ?int    $categoryId = null,
        public ?bool   $isNew = null,
        public ?bool   $isHit = null,
        public ?bool   $isSale = null,
        public bool    $trashed = false,
        public int     $perPage = 20,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            code:       $request->string('code')->trim()->value() ?: null,
            name:       $request->string('name')->trim()->value() ?: null,
            categoryId: $request->filled('category_id') ? (int) $request->input('category_id') : null,
            isNew:      $request->has('is_new')  ? (bool) (int) $request->input('is_new')  : null,
            isHit:      $request->has('is_hit')  ? (bool) (int) $request->input('is_hit')  : null,
            isSale:     $request->has('is_sale') ? (bool) (int) $request->input('is_sale') : null,
            trashed:    $request->boolean('trashed', false),
            perPage:    $request->integer('per_page', 20),
        );
    }
}
