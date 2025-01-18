<?php

namespace CSlant\Blog\Api\Http\Resources;

use CSlant\Blog\Core\Facades\Base\Media\RvMedia;
use CSlant\Blog\Core\Http\Resources\Base\BaseListPostResource;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Http\Request;

/**
 * @mixin Post
 */
class ListPostResource extends BaseListPostResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
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
