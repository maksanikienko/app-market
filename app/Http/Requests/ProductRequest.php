<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        $nameRo = is_array($name) ? ($name['ro'] ?? '') : '';
        if ($nameRo && !$this->filled('slug')) {
            $this->merge(['slug' => Str::slug($nameRo)]);
        }
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $productId = $isUpdate ? $this->route('product')?->id : null;

        return [
            'name'                  => 'required|array',
            'name.ro'               => 'required|min:3|max:200',
            'name.ru'               => 'nullable|string|max:200',
            'slug'                  => 'required|unique:products,slug' . ($productId ? ",$productId" : ''),
            'code'                  => 'required|min:2|max:50|unique:products,code' . ($productId ? ",$productId" : ''),
            'price'                 => 'required|numeric|min:0',
            'old_price'             => 'nullable|numeric|min:0',
            'category_id'           => 'nullable|exists:categories,id',
            'brand_id'              => 'nullable|exists:brands,id',
            'description'           => 'nullable|array',
            'description.ro'        => 'nullable|string',
            'description.ru'        => 'nullable|string',
            'short_description'     => 'nullable|array',
            'short_description.ro'  => 'nullable|string|max:500',
            'short_description.ru'  => 'nullable|string|max:500',
            'is_active'         => 'boolean',
            'is_new'            => 'boolean',
            'is_hit'            => 'boolean',
            'is_sale'           => 'boolean',
            'outer_material_id' => 'nullable|exists:product_classifiers,id',
            'lining_material_id'=> 'nullable|exists:product_classifiers,id',
            'filling_id'        => 'nullable|exists:product_classifiers,id',
            'season'            => 'nullable|string|max:50',
            'length'            => 'nullable|string|max:50',
            'hood'              => 'boolean',
            'detachable_hood'   => 'boolean',
            'waterproof'        => 'boolean',
            'variants'              => 'nullable|array',
            'variants.*.color'      => 'nullable|string|max:100',
            'variants.*.color_hex'  => 'nullable|string|max:7',
            'variants.*.size'       => 'nullable|string|max:20',
            'variants.*.sku'        => 'nullable|string|max:100|distinct',
            'variants.*.price'      => 'nullable|numeric|min:0',
            'variants.*.stock'      => 'nullable|integer|min:0',
        ];
    }
}
