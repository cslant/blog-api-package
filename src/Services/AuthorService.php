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
     * @param  Request  $request
     *
     * @return LengthAwarePaginator<Model>
     */
    public function getAllAuthor(Request $request): LengthAwarePaginator
    {
        $data = User::query()
            ->withCount('posts');  //Eloquent method

        $orderBy = (string) Arr::get($request->toArray(), 'order_by', 'posts_count');
        $order = (string) Arr::get($request->toArray(), 'order', 'desc');

        $data = $data->orderBy($orderBy, $order);

        return $data->paginate($request->integer('per_page', 10));
    }
}
