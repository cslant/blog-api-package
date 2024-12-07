<?php

namespace CSlant\Blog\Api\Http\Resources;

use Botble\Blog\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Category $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'url' => $this->url,
            'description' => $this->description,
        ];
    }
}
