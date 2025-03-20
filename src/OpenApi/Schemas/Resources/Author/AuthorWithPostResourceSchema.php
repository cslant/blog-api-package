<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Author;

use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\PostModelResourceSchema;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "AuthorWithPostResource",
    required: ["id", "email"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "email", description: "Author email", type: "string", maxLength: 191, uniqueItems: true),
        new Property(property: "first_name", description: "Author first_name", type: "string", maxLength: 120, nullable: true),
        new Property(property: "last_name", description: "Author last_name", type: "string", maxLength: 120, nullable: true),
        new Property(property: "image", description: "Author image", type: "string", maxLength: 255, nullable: true),
        new Property(
            property: "posts",
            type: "array",
            items: new Items(ref: PostModelResourceSchema::class)
        ),
    ],
    type: "object"
)]
class AuthorWithPostResourceSchema
{
}
