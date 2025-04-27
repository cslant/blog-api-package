<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Api\Supports\Queries\QueryPost;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Class PostService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class PostService
{
    /**
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<int, Post>
     */
    public function getCustomFilters(array $filters): LengthAwarePaginator
    {
        $query = Post::query()->withCount(['comments', 'likes'])->with(['comments', 'likes']);

        $query = QueryPost::setBaseCustomFilterQuery($query, $filters);

        $query = $query
            ->wherePublished()
            ->orderBy(
                Arr::get($filters, 'order_by', 'updated_at'),
                Arr::get($filters, 'order', 'desc')
            );

        return $query->paginate((int) $filters['per_page']);
    }
}
