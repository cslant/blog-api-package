<?php

namespace CSlant\Blog\Api\Http\Resources\Author;

use CSlant\Blog\Api\Http\Resources\Post\ListPostResource;
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
        /** @var User $this */
        $posts = $this->posts()
            ->orderBy($request->get('order_by', 'created_at'), $request->get('order', 'DESC'))
            ->paginate($request->get('per_page', 10));

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
