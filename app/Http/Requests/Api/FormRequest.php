<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as OldFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends OldFormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(
            [
                'status' => 400,
                'msg' => 'Validation error',
                'reason' => $validator->errors(),
            ],
            400
        ));
    }
}
