<?php

namespace App\Http\Resources\Api\V1;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (null !== $this->city) {
            $data['city'] = [
                'id' => $this->city->id,
                'name' => $this->city->name,
            ];
        } else {
            $data['city'] = null;
        }

        return $data;
    }
}
