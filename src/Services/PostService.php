<?php

namespace CSlant\Blog\Api\Services;

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
     * Get posts by tags.
     *
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<Post>
     */
    public function getPostByTags(array $filters): LengthAwarePaginator
    {
        $data = Post::query();

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
}
