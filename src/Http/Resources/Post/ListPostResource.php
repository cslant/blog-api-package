<?php

namespace CSlant\Blog\Api\Http\Resources\Post;

use CSlant\Blog\Api\Http\Resources\Author\AuthorResource;
use CSlant\Blog\Api\Http\Resources\Category\CategoryResource;
use CSlant\Blog\Api\Http\Resources\Tag\TagResource;
use CSlant\Blog\Core\Facades\Base\Media\RvMedia;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Post
 */
class ListPostResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $userId = 0;
        if (Auth::user()) {
            $userId = Auth::user()->id;
        }

        /** @var Post $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug instanceof Slug ? $this->slug->key : $this->slug,
            'description' => $this->description,
            'image' => $this->image ? RvMedia::url($this->image) : null,
            'categories' => CategoryResource::collection($this->categories),
            'tags' => TagResource::collection($this->tags),
            'author' => AuthorResource::make($this->author),
            'likes_count' => $this->relationLoaded('likes') ? $this->likesCountDigital() : 0,
            'is_liked' => $this->relationLoaded('likes') && $this->isLikedBy($userId),
            'is_commented' => $this->relationLoaded('comments') && $this->isCommentBy($userId),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
