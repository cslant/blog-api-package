<?php

namespace CSlant\Blog\Api\Http\Resources;

use CSlant\Blog\Core\Facades\Base\BaseHelper;
use CSlant\Blog\Core\Http\Resources\Base\BaseListCategoryResource;
use CSlant\Blog\Core\Models\Category;
use Illuminate\Http\Request;

/**
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => BaseHelper::renderIcon($this->icon),
            'description' => $this->description,
            'children' => CategoryResource::collection($this->children),
            'parent' => new CategoryResource($this->parent),
        ];
    }
}
