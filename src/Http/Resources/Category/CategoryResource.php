<?php

namespace CSlant\Blog\Api\Http\Resources\Category;

use CSlant\Blog\Core\Facades\Base\Media\RvMedia;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
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
            // 'url' => $this->url,
            'icon' => $this->icon,
            'description' => $this->description,
            'image' => $this->image ? RvMedia::url($this->image) : null,
        ];
    }
}
