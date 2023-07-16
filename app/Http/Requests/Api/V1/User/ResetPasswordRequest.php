<?php

namespace App\Http\Requests\Api\V1\User;


use App\Http\Requests\Api\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|different:old_password',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
