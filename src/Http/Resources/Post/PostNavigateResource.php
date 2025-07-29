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

        // Cast to proper array type for PHPStan
        /** @var array<string, null|\CSlant\Blog\Core\Models\Post> $navigationData */
        $navigationData = $this->resource;

        $previous = array_key_exists('previous', $navigationData) ? $navigationData['previous'] : null;
        $next = array_key_exists('next', $navigationData) ? $navigationData['next'] : null;

        return [
            'previous' => $previous ? new PostNavigationResource($previous) : null,
            'next' => $next ? new PostNavigationResource($next) : null,
        ];
    }
}
