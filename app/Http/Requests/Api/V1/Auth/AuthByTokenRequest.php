<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthByTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'auth_token' => 'string|required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
