<?php

namespace CSlant\Blog\Api\Supports;

class FilterTag
{
    /**
     * @param  array<string, mixed>  $request
     *
     * @return array<string, mixed>
     */
    public static function setFilters(array $request): array
    {
        return [
            'page' => $request['page'] ?? 1,
            'per_page' => $request['per_page'] ?? 10,
            'search' => $request['search'] ?? null,
            'slug' => $request['slug'] ?? null,
        ];
    }
}
