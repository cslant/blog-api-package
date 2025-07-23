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
        /** @var array{previous: null|object, next: null|object} $resource */
        $resource = $this->resource;

        return [
            'previous' => $resource['previous'] ? new PostNavigationResource($resource['previous']) : null,
            'next' => $resource['next'] ? new PostNavigationResource($resource['next']) : null,
        ];
    }
}
