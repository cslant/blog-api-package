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

    public function trackView(int $postId, string $ipAddress): bool
    {
        $post = Post::find($postId);
        if (!$post) {
            return false;
        }

        $postView = PostView::where('post_id', $postId)
            ->where('ip_address', $ipAddress)
            ->first();

        $shouldIncrementView = false;

        if (!$postView) {
            // Access this post for the first time from this IP
            PostView::create([
                'post_id' => $postId,
                'ip_address' => $ipAddress,
                'time_check' => Carbon::now()->addHours(),
            ]);
            $shouldIncrementView = true;
        } else {
            // Check if field time_check is passed
            if (Carbon::now()->isAfter($postView->time_check)) {
                // Update field time_check
                $postView->update([
                    'time_check' => Carbon::now()->addHours(),
                ]);
                $shouldIncrementView = true;
            }
        }

        if ($shouldIncrementView) {
            $post->increment('views');
        }

        return $shouldIncrementView;
    }
}
