<?php

namespace CSlant\Blog\Api\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Post Navigate Resource for combined previous/next response
 */
class PostNavigateResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'previous' => $this->resource['previous'] ? new PostNavigationResource($this->resource['previous']) : null,
            'next' => $this->resource['next'] ? new PostNavigationResource($this->resource['next']) : null,
        ];
    }
}
