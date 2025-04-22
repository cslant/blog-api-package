<?php

namespace CSlant\Blog\Api\Http\Resources\Comment;

use CSlant\Blog\Api\Http\Resources\Author\AuthorResource;
use FriendsOfBotble\Comment\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Comment
 */
class ListCommentResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var Comment $this */
        return [
            'id' => $this->id,
            'website' => $this->website,
            'content' => $this->content,
            'status' => $this->status,
            'author' => AuthorResource::make($this->author),
        ];
    }
}
