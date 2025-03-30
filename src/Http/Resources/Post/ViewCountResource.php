<?php

namespace CSlant\Blog\Api\Http\Resources\Post;

use CSlant\Blog\Core\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
class ViewCountResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Post $this */
        return [
            'id' => $this->id,
            'views' => number_format($this->views),
        ];
    }
}
