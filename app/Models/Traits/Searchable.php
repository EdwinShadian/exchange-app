<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Collection;

trait Searchable
{
    public static function search(
        string $field,
        ?string $search,
        int $limit
    ): Collection
    {
        $query = self::query();

        if (!empty($search)) {
            $query->where($field, 'like', '%' . $search . '%');
        }

        $query->limit($limit);

        return $query->get();
    }
}
