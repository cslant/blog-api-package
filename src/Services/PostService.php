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
     * Get navigation posts based on content relevance
     * Optimized to get individual posts without fetching collections
     *
     * @param Post $currentPost
     * @return array{previous: null|Post, next: null|Post}
     */
    public function getNavigationPosts(Post $currentPost): array
    {
        // Load relationships if not already loaded
        if (!$currentPost->relationLoaded('categories')) {
            $currentPost->load('categories');
        }
        if (!$currentPost->relationLoaded('tags')) {
            $currentPost->load('tags');
        }

        $categoryIds = collect($currentPost->categories)->pluck('id');
        $tagIds = collect($currentPost->tags)->pluck('id');

        // Get previous post
        $previous = $this->getSingleNavigationPost($currentPost->id, $categoryIds, $tagIds);

        // Get next post (exclude the previous post if found)
        $excludeIds = [$currentPost->id];
        if ($previous) {
            $excludeIds[] = $previous->id;
        }
        $next = $this->getSingleNavigationPost($currentPost->id, $categoryIds, $tagIds, $excludeIds);

        return [
            'previous' => $previous,
            'next' => $next,
        ];
    }

    /**
     * Get a single navigation post
     *
     * @param int $currentPostId
     * @param \Illuminate\Support\Collection $categoryIds
     * @param \Illuminate\Support\Collection $tagIds
     * @param array $excludeIds
     * @return null|Post
     */
    private function getSingleNavigationPost(int $currentPostId, $categoryIds, $tagIds, array $excludeIds = []): ?Post
    {
        if (empty($excludeIds)) {
            $excludeIds = [$currentPostId];
        }

        // Try category match first
        if ($categoryIds->isNotEmpty()) {
            $post = Post::query()
                ->wherePublished()
                ->whereNotIn('id', $excludeIds)
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->with(['slugable', 'categories', 'tags', 'author'])
                ->inRandomOrder()
                ->first();

            if ($post) {
                return $post;
            }
        }

        // Try tag match if no category match
        if ($tagIds->isNotEmpty()) {
            $post = Post::query()
                ->wherePublished()
                ->whereNotIn('id', $excludeIds)
                ->whereHas('tags', function ($query) use ($tagIds) {
                    $query->whereIn('tags.id', $tagIds);
                })
                ->with(['slugable', 'categories', 'tags', 'author'])
                ->inRandomOrder()
                ->first();

            if ($post) {
                return $post;
            }
        }

        // Fallback to any random post
        return Post::query()
            ->wherePublished()
            ->whereNotIn('id', $excludeIds)
            ->with(['slugable', 'categories', 'tags', 'author'])
            ->inRandomOrder()
            ->first();
    }
}
