<?php

namespace CSlant\Blog\Api\Http\Resources\Category;

use CSlant\Blog\Core\Facades\Base\BaseHelper;
use CSlant\Blog\Core\Http\Resources\Base\BaseListCategoryResource;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\Request;

/**
 * @method static mixed make(...$params)
 *
 * @mixin Category
 */
class ListCategoryResource extends BaseListCategoryResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
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
        ];
    }
}
