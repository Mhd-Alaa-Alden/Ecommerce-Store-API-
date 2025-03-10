<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class authRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required | email | max:255 | string|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|integer',
        ];
    }
}
