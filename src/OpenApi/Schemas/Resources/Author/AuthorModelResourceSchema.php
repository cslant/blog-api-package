<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Author;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "AuthorModelResource",
    required: ["id", "first_name", "last_name", "image"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "first_name", description: "Author first_name", type: "string", maxLength: 120, nullable: true),
        new Property(property: "last_name", description: "Author last_name", type: "string", maxLength: 120, nullable: true),
        new Property(property: "image", description: "Author image", type: "string", maxLength: 255, nullable: true),
        new Property(property: "role", description: "Author role", type: "string", maxLength: 120),
    ],
    type: "object"
)]
class AuthorModelResourceSchema
{
}
