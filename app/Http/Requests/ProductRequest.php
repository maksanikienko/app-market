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
            'code' => 'required|min:3|max:50|unique:products,code',
            'name' => 'required|min:3|max:50',
            'description' => 'required|min:5',
            'price' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:png|dimensions:ratio=1'
        ];

        //If update product fields, except 'code' field, don't rewrite 'code'
        if ($this->route()->named('products.update')) {
            $rules['code'] .= ',' . $this->route()->parameter('product')->id;
        }

        return $rules;
    }
    public function messages()
    {

        return [
            'required' => 'Please enter a value for :attribute.',
            'image.required' => 'Please upload an :attribute.',
            'unique' => 'A unique value is required for the product code.',
            'min' => 'Please enter at least :min characters.',
            'price.numeric' => 'Price must be a numeric value.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image file must be in .png format.',
            'image.dimensions' => 'Image must have a 1:1 aspect ratio.'
        ];

    }
}
