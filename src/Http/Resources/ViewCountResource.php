<?php

namespace CSlant\Blog\Api\Http\Resources;

use CSlant\Blog\Core\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
class ViewCountResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var Post $this */
        return [
            'id' => $this->id,
            'views' => number_format((int) $this->views),
        ];
    }
}
