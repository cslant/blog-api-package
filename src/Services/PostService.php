<?php

namespace CSlant\Blog\Api\Services;

use Carbon\Carbon;
use CSlant\Blog\Api\Models\PostView;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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
     * Get posts by filters.
     *
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<Post>
     */
    public function getCustomFilters(array $filters): LengthAwarePaginator
    {
        $data = Post::query();

        if ($filters['categories'] !== null) {
            $categories = array_filter((array) $filters['categories']);

            $data = $data->whereHas('categories', function (Builder $query) use ($categories): void {
                $query->whereIn('categories.id', $categories);
            });
        }

        if ($filters['tags'] !== null) {
            $tags = array_filter((array) $filters['tags']);

            $data = $data->whereHas('tags', function (Builder $query) use ($tags): void {
                $query->whereIn('tags.id', $tags);
            });
        }

        $orderBy = Arr::get($filters, 'order_by', 'updated_at');
        $order = Arr::get($filters, 'order', 'desc');

        $data = $data
            ->wherePublished()
            ->orderBy($orderBy, $order);

        return $data->paginate((int) $filters['per_page']);
    }

    /**
     * Track post view.
     *
     * @param int $postId
     * @param string $ipAddress
     * @return bool
     */
    public function trackView(int $postId, string $ipAddress): bool
    {
        /** @var Post|null $post */
        $post = Post::find($postId);
        if (!$post) return false;

        /** @var \Illuminate\Database\Eloquent\Builder<PostView> $query */
        $query = PostView::query();

        /** @var PostView|null $postView */
        $postView = $query
            ->where('post_id', '=', $postId)
            ->where('ip_address', '=', $ipAddress)
            ->first();

        $shouldIncrementView = false;

        if (!$postView) {
            // Access this post for the first time from this IP
            /** @var array<string, mixed> $attributes */
            $attributes = [
                'post_id' => $postId,
                'ip_address' => $ipAddress,
                'time_check' => Carbon::now()->addHour(),
            ];
            PostView::create($attributes);
            $shouldIncrementView = true;
        } else {
            // Check if field time_check is passed
            if (Carbon::now()->isAfter($postView->time_check)) {
                // Update field time_check
                /** @var array<string, mixed> $updateData */
                $updateData = [
                    'time_check' => Carbon::now()->addHour(),
                ];
                $postView->update($updateData);
                $shouldIncrementView = true;
            }
        }

        if ($shouldIncrementView) {
            $post->increment('views');
        }

        return $shouldIncrementView;
    }
}
