<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $name = is_array($this->input('name'))
                ? ($this->input('name.en') ?? $this->input('name.ru') ?? '')
                : $this->input('name');
            $this->merge(['slug' => Str::slug($name)]);
        }
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $categoryId = $isUpdate ? $this->route('category')?->id : null;

        return [
            'name'          => 'required|array',
            'name.ru'       => 'required|string|min:2|max:100',
            'name.ro'       => 'required|string|min:2|max:100',
            'name.en'       => 'nullable|string|min:2|max:100',
            'slug'          => 'required|string|unique:categories,slug' . ($categoryId ? ",$categoryId" : ''),
            'description'   => 'nullable|array',
            'description.ru'=> 'nullable|string',
            'description.ro'=> 'nullable|string',
            'description.en'=> 'nullable|string',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer|min:0',
        ];
    }
}