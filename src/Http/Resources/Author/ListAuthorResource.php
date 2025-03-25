<?php

namespace CSlant\Blog\Api\Http\Resources\Author;

use CSlant\Blog\Core\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class ListAuthorResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'image' => $this->avatar_url,
            'posts_count' => $this->posts_count,
            'created_at' => $this->created_at,
        ];
    }
}
