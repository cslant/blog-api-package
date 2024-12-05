<?php

namespace CSlant\Blog\Api\Http\Resources;

use Botble\Blog\Http\Resources\TagResource;
use Botble\Blog\Models\Post;
use Botble\Media\Facades\RvMedia;
use Botble\Blog\Http\Resources\ListPostResource as BaseListPostResource;

/**
 * @mixin Post
 */
class ListPostResource extends BaseListPostResource
{
    public function toArray($request): array
    {
        /** @var Post $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image ? RvMedia::url($this->image) : null,
            'categories' => CategoryResource::collection($this->categories),
            'tags' => TagResource::collection($this->tags),
            'author' => AuthorResource::make($this->author),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}