<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
