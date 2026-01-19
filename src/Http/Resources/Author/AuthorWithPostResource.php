<?php

namespace CSlant\Blog\Api\Http\Resources\Author;

use CSlant\Blog\Api\Http\Resources\Post\ListPostResourceCollection;
use CSlant\Blog\Core\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class AuthorWithPostResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $this */
        $posts = $this->posts()
            ->orderBy((string) $request->query->get('order_by', 'created_at'), (string) $request->query->get('order', 'DESC'))
            ->paginate($request->integer('per_page', 10));

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
