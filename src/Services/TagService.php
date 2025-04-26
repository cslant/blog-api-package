<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Api\Supports\Queries\QueryTag;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Class TagService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class TagService
{
    /**
     * Get tags by filters.
     *
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<int, Tag>
     */
    public function getFilters(array $filters): LengthAwarePaginator
    {
        $query = Tag::query()->withCount('posts')->with([]);

        $query = QueryTag::setBaseCustomFilterQuery($query, $filters);

        $data = $query
            ->wherePublished()
            ->orderBy(
                Arr::get($filters, 'order_by', 'posts_count'),
                Arr::get($filters, 'order', 'desc')
            );

        return $data->paginate((int) $filters['per_page']);
    }
}
