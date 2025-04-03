<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class AuthorService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class AuthorService
{
    /**
     * Get all author.
     *
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<Model>
     */
    public function getAllAuthor(array $filters): LengthAwarePaginator
    {
        $data = User::query()
            ->withCount('posts');

        $data = $data->when(isset($filters['is_super']), function ($query) use ($filters) {
            return $query->where('super_user', (int) $filters['is_super']);
        });

        $orderBy = (string) Arr::get($filters, 'order_by', 'posts_count');
        $order = (string) Arr::get($filters, 'order', 'desc');

        $data = $data->orderBy($orderBy, $order);

        return $data->paginate((int) $filters['per_page']);
    }
}
