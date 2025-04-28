<?php

namespace CSlant\Blog\Api\Http\Resources\Comment;

use CSlant\Blog\Api\Http\Resources\Author\AuthorResource;
use CSlant\Blog\Core\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Comment
 */
class CommentResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
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
