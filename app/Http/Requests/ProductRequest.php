<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'code' => 'required|min:3|max:20|unique:products,code',
            'name' => 'required|min:3|max:20',
            'description' => 'required|min:5',
            'price' => 'required|numeric|min:1'
        ];

        //If update product fields, except 'code' field, don't rewrite 'code'
        if ($this->route()->named('products.update')) {
            $rules['code'] .= ',' . $this->route()->parameter('product')->id;
        }

        return $rules;
    }
}
