<?php

namespace App\Http\Requests\Api\V1\City;

use App\Models\City;
use App\Http\Requests\Api\FormRequest;

class CitySearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => 'string|min:3|max:255',
            'limit' => 'integer|min:1|max:100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getLimit(): int
    {
        return $this->query('limit', City::DEFAULT_LIMIT);
    }
}
