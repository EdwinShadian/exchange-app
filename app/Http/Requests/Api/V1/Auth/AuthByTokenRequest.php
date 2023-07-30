<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\FormRequest;

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
