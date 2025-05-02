<?php

namespace CSlant\Blog\Api\Http\Resources\Category;

use CSlant\Blog\Core\Facades\Base\BaseHelper;
use CSlant\Blog\Core\Facades\Base\Media\RvMedia;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method static mixed make(...$params)
 *
 * @mixin Category
 */
class ListCategoryResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Category $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug instanceof Slug ? $this->slug->key : $this->slug,
            'icon' => $this->icon ? BaseHelper::renderIcon($this->icon) : null,
            'description' => $this->description,
            'children' => CategoryResource::collection($this->children),
            'parent' => new CategoryResource($this->parent),
            'posts_count' => $this->posts_count,
            'image' => $this->image ? RvMedia::url($this->image) : null,
        ];
    }
}
