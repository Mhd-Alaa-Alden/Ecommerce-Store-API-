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
    public function rules(): array
    {
        return [
            'name' => 'string|required|max:25',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'Categorie_id' => 'required|integer|exists:categories,id',
            'image_url' => 'nullable|image|mimes:png,jpg|max:2048',
            'stock_quantity' => 'integer|required'
            
        ];
    }
}
