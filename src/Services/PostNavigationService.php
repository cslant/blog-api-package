<?php

namespace CSlant\Blog\Api\Services;

use Botble\Blog\Repositories\Interfaces\PostInterface;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;

/**
 * Class PostNavigationService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class PostNavigationService
{
    public function __construct(
        protected PostInterface $postRepository
    ) {
    }

    /**
     * Get previous post based on content relevance
     *
     * @param int|string $postId
     * @return object|null
     */
    public function getPreviousPost(int|string $postId): ?object
    {
        return $this->getRelatedPostByRelevance($postId, 'previous');
    }

    /**
     * Get next post based on content relevance
     *
     * @param int|string $postId
     * @return object|null
     */
    public function getNextPost(int|string $postId): ?object
    {
        return $this->getRelatedPostByRelevance($postId, 'next');
    }

    /**
     * Get both previous and next posts
     *
     * @param int|string $postId
     * @return array
     */
    public function getNavigatePosts(int|string $postId): array
    {
        return [
            'previous' => $this->getPreviousPost($postId),
            'next' => $this->getNextPost($postId),
        ];
    }

    /**
     * Get posts with relevance score based on shared categories and tags
     * Navigation is purely content-based, not time-based
     *
     * @param int|string $postId
     * @param string $direction 'previous' or 'next'
     * @return object|null
     */
    protected function getRelatedPostByRelevance(int|string $postId, string $direction = 'previous'): ?object
    {
        $currentPost = $this->postRepository->findById($postId);
        
        if (!$currentPost) {
            return null;
        }

        // Load current post's categories and tags
        $currentPost->load(['categories', 'tags']);
        $categoryIds = $currentPost->categories->pluck('id')->toArray();
        $tagIds = $currentPost->tags->pluck('id')->toArray();

        if (empty($categoryIds) && empty($tagIds)) {
            // No categories or tags, return null (no navigation)
            return null;
        }

        // Get all published posts except current one
        $posts = $this->postRepository->getModel()
            ->wherePublished()
            ->where('id', '!=', $postId)
            ->with(['slugable', 'categories', 'tags', 'author'])
            ->get();

        if ($posts->isEmpty()) {
            return null;
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
            ->values(); // Reset array keys

        if ($relevantPosts->isEmpty()) {
            return null;
        }

        // Group posts by relevance score
        $groupedByScore = $relevantPosts->groupBy('relevance_score');
        $scores = $groupedByScore->keys()->sortDesc();
        
        if ($direction === 'previous') {
            // Get highest scoring post(s), pick first one
            $highestScorePosts = $groupedByScore->get($scores->first());
            return $highestScorePosts->first();
        } else {
            // For 'next', try to get a different post
            if ($scores->count() > 1) {
                // If we have multiple score levels, get from second highest
                $secondHighestPosts = $groupedByScore->get($scores->get(1));
                return $secondHighestPosts->first();
            } else {
                // If all posts have same score, get second post if available
                $highestScorePosts = $groupedByScore->get($scores->first());
                return $highestScorePosts->count() > 1 ? $highestScorePosts->get(1) : $highestScorePosts->first();
            }
        }
    }
}
