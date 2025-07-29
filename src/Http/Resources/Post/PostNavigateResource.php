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
        // Ensure the resource is an array with the expected structure
        if (!is_array($this->resource)) {
            return [
                'previous' => null,
                'next' => null,
            ];
        }

        /** @var array{previous?: mixed, next?: mixed} $resource */
        $resource = $this->resource;

        return [
            'previous' => isset($resource['previous']) && $resource['previous'] ? new PostNavigationResource($resource['previous']) : null,
            'next' => isset($resource['next']) && $resource['next'] ? new PostNavigationResource($resource['next']) : null,
        ];
    }
}
