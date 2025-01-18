<?php

namespace CSlant\Blog\Api\Http\Resources;

use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug instanceof Slug ? $this->slug->key : $this->slug,
            'url' => $this->url,
            'icon' => $this->icon,
            'description' => $this->description,
        ];
    }
}
