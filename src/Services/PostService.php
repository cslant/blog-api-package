<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Api\Supports\Queries\QueryPost;
use CSlant\Blog\Core\Enums\StatusEnum;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Slug;
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
     * Find post by slug
     *
     * @param string $slug
     * @return Post|null
     */
    public function findBySlug(string $slug): ?Post
    {
        /** @var Slug $slugModel */
        $slugModel = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Post::getBaseModel()));

        if (!$slugModel) {
            return null;
        }

        return Post::query()
            ->where('id', $slugModel->reference_id)
            ->where('status', StatusEnum::PUBLISHED)
            ->with(['categories', 'tags'])
            ->first();
    }

    /**
     * Get navigation posts based on content relevance
     *
     * @param Post $currentPost
     * @return array{previous: null|Post, next: null|Post}
     */
    public function getNavigationPosts(Post $currentPost): array
    {
        $categoryIds = $currentPost->categories->pluck('id')->toArray();
        $tagIds = $currentPost->tags->pluck('id')->toArray();

        if (empty($categoryIds) && empty($tagIds)) {
            return ['previous' => null, 'next' => null];
        }

        // Get all published posts except current one
        $posts = Post::query()
            ->wherePublished()
            ->where('id', '!=', $currentPost->id)
            ->with(['slugable', 'categories', 'tags', 'author'])
            ->get();

        if ($posts->isEmpty()) {
            return ['previous' => null, 'next' => null];
        }

        // Calculate relevance score for each post
        $scoredPosts = $posts->map(function ($post) use ($categoryIds, $tagIds) {
            $post->load(['categories', 'tags']);

            $postCategoryIds = $post->categories->pluck('id')->toArray();
            $postTagIds = $post->tags->pluck('id')->toArray();

            // Calculate shared categories and tags
            $sharedCategories = count(array_intersect($categoryIds, $postCategoryIds));
            $sharedTags = count(array_intersect($tagIds, $postTagIds));

            // Weight categories higher than tags
            $relevanceScore = ($sharedCategories * 3) + ($sharedTags * 1);

            $post->relevance_score = $relevanceScore;

            return $post;
        });

        // Filter posts with relevance score > 0
        $relevantPosts = $scoredPosts
            ->filter(function ($post) {
                return $post->relevance_score > 0;
            })
            ->sortByDesc('relevance_score')
            ->values();

        if ($relevantPosts->isEmpty()) {
            return ['previous' => null, 'next' => null];
        }

        // Group posts by relevance score
        $groupedByScore = $relevantPosts->groupBy('relevance_score');
        $scores = $groupedByScore->keys()->sortDesc();

        $previous = null;
        $next = null;

        // Get highest scoring post as previous
        $highestScorePosts = $groupedByScore->get($scores->first());
        $previous = $highestScorePosts->first();

        // For next, try to get a different post
        if ($scores->count() > 1) {
            // If we have multiple score levels, get from second highest
            $secondHighestPosts = $groupedByScore->get($scores->get(1));
            $next = $secondHighestPosts->first();
        } else {
            // If all posts have same score, get second post if available
            $next = $highestScorePosts->count() > 1 ? $highestScorePosts->get(1) : null;
        }

        return [
            'previous' => $previous,
            'next' => $next,
        ];
    }
}
