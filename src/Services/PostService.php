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

    /**
     * Get navigation posts based on ID sequence
     * Previous = ID smaller than current, Next = ID larger than current
     *
     * @param  Post  $currentPost
     *
     * @return array{previous: null|Post, next: null|Post}
     */
    public function getNavigationPosts(?Post $currentPost): array
    {
        if (!$currentPost instanceof Post) {
            throw new \InvalidArgumentException("Expected a Post instance.");
        }

        $currentId = $currentPost->id;

        // Get previous post (ID smaller than current, order by ID desc to get the closest one)
        $previous = Post::query()
            ->wherePublished()
            ->where('id', '<', $currentId)
            ->with(['slugable', 'author'])
            ->orderBy('id', 'desc')
            ->first();

        // Get next post (ID larger than current, order by ID asc to get the closest one)
        $next = Post::query()
            ->wherePublished()
            ->where('id', '>', $currentId)
            ->with(['slugable', 'author'])
            ->orderBy('id', 'asc')
            ->first();

        return [
            'previous' => $previous,
            'next' => $next,
        ];
    }
}
