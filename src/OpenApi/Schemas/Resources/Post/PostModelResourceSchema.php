<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Post;

use CSlant\Blog\Api\OpenApi\Schemas\Models\CategorySchema;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Category\AuthorModelResourceSchema;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "PostModelResource",
    required: ["id", "name", "slug", "description", "categories", "tags", "author", "created_at", "updated_at"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Post name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Post slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", description: "Post description", type: "string", nullable: true),
        new Property(property: "image", description: "Post image", type: "string", nullable: true),
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
        new Property(property: "created_at", description: "Post created at", type: "datetime", nullable: true),
        new Property(property: "updated_at", description: "Post updated at", type: "datetime", nullable: true),
    ],
    type: "object"
)]
class PostModelResourceSchema
{
}
