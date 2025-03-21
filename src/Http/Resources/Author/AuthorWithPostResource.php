<?php

namespace CSlant\Blog\Api\Http\Resources\Author;

use CSlant\Blog\Api\Http\Resources\Post\ListPostResourceCollection;
use CSlant\Blog\Core\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class AuthorWithPostResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $orderBy = (string) $request->get('order_by', 'created_at');
        $order = strtoupper((string) $request->get('order', 'DESC'));
        $perPage = $request->input('per_page', 10);
        $perPage = is_numeric($perPage) ? (int) $perPage : 10;

        /** @var User $this */
        $posts = $this->posts()
            ->orderBy($orderBy, $order)
            ->paginate($perPage);


        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'image' => $this->avatar_url,
            'posts' => ListPostResourceCollection::make($posts),
        ];
    }
}
