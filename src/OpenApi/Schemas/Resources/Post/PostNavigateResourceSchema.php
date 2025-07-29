<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Post;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "PostNavigateResource",
    required: ["previous", "next"],
    properties: [
        new Property(
            property: "previous",
            description: "Previous post for navigation",
            oneOf: [
                new Schema(ref: PostNavigationResourceSchema::class),
                new Schema(type: "null")
            ]
        ),
        new Property(
            property: "next", 
            description: "Next post for navigation",
            oneOf: [
                new Schema(ref: PostNavigationResourceSchema::class),
                new Schema(type: "null")
            ]
        ),
    ],
    type: "object"
)]
class PostNavigateResourceSchema
{
}
