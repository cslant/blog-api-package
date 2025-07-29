<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Post;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "PostNavigationResource",
    required: ["id", "name", "slug", "url"],
    properties: [
        new Property(property: "id", description: "Post ID", type: "integer", example: 1),
        new Property(property: "name", description: "Post title", type: "string", example: "Sample Post Title"),
        new Property(property: "description", description: "Post description", type: "string", nullable: true),
        new Property(property: "slug", description: "Post slug", type: "string", example: "sample-post-title"),
        new Property(property: "url", description: "Post URL", type: "string", example: "/posts/sample-post-title"),
        new Property(property: "image", description: "Post featured image URL", type: "string", nullable: true),
        new Property(property: "created_at", description: "Creation timestamp", type: "string", format: "date-time"),
        new Property(property: "updated_at", description: "Last update timestamp", type: "string", format: "date-time"),
    ],
    type: "object"
)]
class PostNavigationResourceSchema
{
}
