<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasketAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity'   => 'sometimes|integer|min:1|max:99',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'color'      => 'nullable|string|max:100',
            'color_hex'  => 'nullable|string|max:7',
            'size'       => 'nullable|string|max:20',
        ];
    }

    public function quantity(): int
    {
        return $this->integer('quantity', 1);
    }

    /** @return array<string, int|string> */
    public function variantData(): array
    {
        return array_filter(
            $this->safe()->only(['variant_id', 'color', 'color_hex', 'size']),
            static fn ($value) => $value !== null && $value !== ''
        );
    }
}
