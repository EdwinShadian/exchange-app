<?php

namespace App\Http\Requests\Api\V1\User;


use App\Http\Requests\Api\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'string|nullable',
            'city_id' => 'int|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
