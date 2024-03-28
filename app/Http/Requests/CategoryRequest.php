<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
    public function rules(): array
    {
        $rules = [
            'code' => 'required|min:3|max:20|unique:categories,code',
            'name' => 'required|min:3|max:20',
            'description' => 'required|min:5',
        ];
        //If update category fields, except 'code' field, don't rewrite 'code'
        if ($this->route()->named('categories.update')) {
            $rules['code'] .= ',' . $this->route()->parameter('category')->id;
        }

        return $rules;
    }
    public function messages()
    {

        return [
            'required' => 'Please enter a value for :attribute.',
            'unique' => 'A unique value is required for the category code.',
            'min' => 'Please enter at least :min characters.',
            
        ];

    }
}
