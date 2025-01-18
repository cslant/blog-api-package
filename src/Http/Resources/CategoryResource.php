<?php

namespace CSlant\Blog\Api\Http\Resources;

use CSlant\Blog\Core\Models\Category;
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
            'slug' => $this->slug->key,
            'url' => $this->url,
            'icon' => $this->icon,
            'description' => $this->description,
        ];
    }
}
