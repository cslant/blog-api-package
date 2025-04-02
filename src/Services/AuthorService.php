<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
     * @param  array  $filters
     *
     * @return LengthAwarePaginator<Model>
     */
    public function getAllAuthor(array $filters): LengthAwarePaginator
    {
        $data = User::query()
            ->withCount('posts');

        $data = $data->where('super_user', $filters['is_super']);

        $orderBy = Arr::get($filters, 'order_by', 'updated_at');
        $order = Arr::get($filters, 'order', 'desc');

        $data = $data->orderBy($orderBy, $order);

        return $data->paginate((int) $filters['per_page']);
    }
}
