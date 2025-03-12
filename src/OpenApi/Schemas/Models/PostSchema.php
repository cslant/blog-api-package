<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use CSlant\Blog\Api\OpenApi\Schemas\Resources\Category\AuthorModelResourceSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Post",
    required: ["id", "name", "slug", "description", "content", "created_at", "updated_at"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Post name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Post slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", type: "string", nullable: true),
        new Property(property: "content", type: "string", nullable: true),
        new Property(property: "image", type: "string", nullable: true),
        new Property(
            property: "categories",
            type: "array",
            items: new Items(ref: CategorySchema::class)
        ),
        new Property(
            property: "tags",
            type: "array",
            items: new Items(ref: CategorySchema::class)
        ),
        new Property(
            property: "author",
            ref: AuthorModelResourceSchema::class,
            type: "object",
        ),
        new Property(property: "created_at", type: "datetime", nullable: true),
        new Property(property: "updated_at", type: "datetime", nullable: true),
    ],
    type: "object"
)]
class PostSchema
{
}
