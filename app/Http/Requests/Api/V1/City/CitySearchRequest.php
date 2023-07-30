<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\City;

use App\Http\Requests\Api\FormRequest;
use App\Models\City;

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
        $limit = (int) $this->get('limit');

        return $limit > 0 ? $limit : City::DEFAULT_LIMIT;
    }
}
