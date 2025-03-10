<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class wishlistRequest extends FormRequest
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
            'user_id' => 'integer|required',
            'product_id' => 'integer|required|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'The selected product is not available.',
        ];
    }
}
