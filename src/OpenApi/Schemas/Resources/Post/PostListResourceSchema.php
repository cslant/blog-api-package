<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Post;

use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\AuthorModelResourceSchema;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Category\CategoryModelResourceSchema;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Tag\TagModelResourceSchema;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "PostListResource",
    required: ["id", "name", "slug", "description", "categories", "tags", "author"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Post name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Post slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", description: "Post description", type: "string"),
        new Property(property: "image", description: "Post image", type: "string", nullable: true),
        new Property(
            property: "categories",
            type: "array",
            items: new Items(ref: CategoryModelResourceSchema::class)
        ),
        new Property(
            property: "tags",
            type: "array",
            items: new Items(ref: TagModelResourceSchema::class)
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
class PostListResourceSchema
{
}
