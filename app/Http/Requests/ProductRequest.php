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
        $name   = $this->input('name');
        $nameRo = is_array($name) ? ($name['ro'] ?? '') : '';

        if ($nameRo && !$this->filled('slug')) {
            $this->merge(['slug' => Str::slug($nameRo)]);
        }
    }

    public function rules(): array
    {
        $isUpdate  = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $productId = $isUpdate ? $this->route('product')?->id : null;
        $uniqueSlug = 'required|unique:products,slug' . ($productId ? ",$productId" : '');
        $uniqueCode = 'required|min:2|max:50|unique:products,code' . ($productId ? ",$productId" : '');

        return [
            'name'                  => 'required|array',
            'name.ro'               => 'required|min:3|max:200',
            'name.ru'               => 'nullable|string|max:200',
            'slug'                  => $uniqueSlug,
            'code'                  => $uniqueCode,
            'price'                 => 'required|numeric|min:0',
            'old_price'             => 'nullable|numeric|min:0',
            'category_id'           => 'required|exists:categories,id',
            'brand_id'              => 'required|exists:brands,id',
            'description'           => 'required|array',
            'description.ro'        => 'required|string',
            'description.ru'        => 'required|string',
            'short_description'     => 'required|array',
            'short_description.ro'  => 'required|string|max:500',
            'short_description.ru'  => 'required|string|max:500',
            'is_active'             => 'boolean',
            'is_new'                => 'boolean',
            'is_hit'                => 'boolean',
            'is_sale'               => 'boolean',
            'outer_material_id'     => 'required|exists:product_classifiers,id',
            'lining_material_id'    => 'required|exists:product_classifiers,id',
            'filling_id'            => 'required|exists:product_classifiers,id',
            'season'                => 'required|string|max:50',
            'length'                => 'required|string|max:50',
            'hood'                  => 'boolean',
            'detachable_hood'       => 'boolean',
            'waterproof'            => 'boolean',
            'variants'              => 'nullable|array',
            'variants.*.color'      => 'required|string|max:100',
            'variants.*.color_hex'  => 'required|string|max:7',
            'variants.*.size'       => 'required|string|max:20',
            'variants.*.sku'        => 'nullable|string|max:100|distinct',
            'variants.*.price'      => 'nullable|numeric|min:0',
            'variants.*.stock'       => 'required|integer|min:0',
            'variants.*.location_id' => 'nullable|exists:locations,id',
        ];
    }

    public function messages(): array
    {
        return [
            // name
            'name.required'                 => 'Название обязательно.',
            'name.ro.required'              => 'Название (RO) обязательно.',
            'name.ro.min'                   => 'Название (RO) — минимум :min символа.',
            'name.ro.max'                   => 'Название (RO) — максимум :max символов.',
            'name.ru.max'                   => 'Название (RU) — максимум :max символов.',
            // slug / code
            'slug.required'                 => 'Slug обязателен.',
            'slug.unique'                   => 'Такой slug уже существует.',
            'code.required'                 => 'Код (SKU) обязателен.',
            'code.min'                      => 'Код (SKU) — минимум :min символа.',
            'code.max'                      => 'Код (SKU) — максимум :max символов.',
            'code.unique'                   => 'Такой код (SKU) уже существует.',
            // price
            'price.required'                => 'Цена обязательна.',
            'price.numeric'                 => 'Цена должна быть числом.',
            'price.min'                     => 'Цена не должна быть меньше :min.',
            'old_price.numeric'             => 'Старая цена должна быть числом.',
            'old_price.min'                 => 'Старая цена не должна быть меньше :min.',
            // relations
            'category_id.required'          => 'Категория обязательна.',
            'category_id.exists'            => 'Выбранная категория не существует.',
            'brand_id.required'             => 'Бренд обязателен.',
            'brand_id.exists'               => 'Выбранный бренд не существует.',
            // description
            'description.required'          => 'Описание обязательно.',
            'description.ro.required'       => 'Описание (RO) обязательно.',
            'description.ru.required'       => 'Описание (RU) обязательно.',
            'short_description.required'    => 'Краткое описание обязательно.',
            'short_description.ro.required' => 'Краткое описание (RO) обязательно.',
            'short_description.ro.max'      => 'Краткое описание (RO) — максимум :max символов.',
            'short_description.ru.required' => 'Краткое описание (RU) обязательно.',
            'short_description.ru.max'      => 'Краткое описание (RU) — максимум :max символов.',
            // classifiers
            'outer_material_id.required'    => 'Внешний материал обязателен.',
            'outer_material_id.exists'      => 'Выбранный внешний материал не существует.',
            'lining_material_id.required'   => 'Подкладка обязательна.',
            'lining_material_id.exists'     => 'Выбранная подкладка не существует.',
            'filling_id.required'           => 'Наполнитель обязателен.',
            'filling_id.exists'             => 'Выбранный наполнитель не существует.',
            'season.required'               => 'Сезон обязателен.',
            'length.required'               => 'Длина обязательна.',
            // variants
            'variants.*.color.required'     => 'Цвет варианта обязателен.',
            'variants.*.color_hex.required' => 'Цвет (hex) варианта обязателен.',
            'variants.*.size.required'      => 'Размер варианта обязателен.',
            'variants.*.stock.required'     => 'Остаток варианта обязателен.',
            'variants.*.sku.distinct'       => 'SKU вариантов должны быть уникальными.',
            'variants.*.price.numeric'      => 'Цена варианта должна быть числом.',
            'variants.*.price.min'          => 'Цена варианта не должна быть меньше :min.',
            'variants.*.stock.integer'      => 'Остаток варианта должен быть целым числом.',
            'variants.*.stock.min'          => 'Остаток варианта не должен быть меньше :min.',
        ];
    }
}