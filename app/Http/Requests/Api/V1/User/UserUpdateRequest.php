<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\User;

use App\Http\Requests\Api\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|nullable|max:255',
            'phone' => 'string|nullable|max:255',
            'city_id' => 'int|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
