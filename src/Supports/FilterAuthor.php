<?php

namespace CSlant\Blog\Api\Supports;

class FilterAuthor
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
            'is_super' => $request['is_super'] ?? 0,
            'order' => isset($request['order']) && in_array($request['order'], ['asc', 'desc']) ? $request['order'] : 'desc',
            'order_by' => $request['order_by'] ?? 'posts_count',
        ];
    }
}
