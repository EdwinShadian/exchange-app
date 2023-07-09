<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
