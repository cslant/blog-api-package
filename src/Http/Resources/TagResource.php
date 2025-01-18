<?php

namespace CSlant\Blog\Api\Http\Resources;

use CSlant\Blog\Core\Models\Slug;
use CSlant\Blog\Core\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Tag
 */
class TagResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var Tag $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug instanceof Slug ? $this->slug->key : $this->slug,
            'description' => $this->description,
        ];
    }
}
