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
     * @return LengthAwarePaginator<int, Model>
     */
    public function getAllAuthor(array $filters): LengthAwarePaginator
    {
        /** @var User $data */
        $data = User::query()->withCount('posts');

        $data = $data->where('super_user', $filters['is_super']);

        $data = $data->orderBy(
            Arr::get($filters, 'order_by', 'posts_count'),
            Arr::get($filters, 'order', 'desc')
        );

        return $data->paginate($filters['per_page']);
    }
}
