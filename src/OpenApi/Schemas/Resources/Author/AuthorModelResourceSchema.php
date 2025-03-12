<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Category;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "AuthorModelResource",
    required: ["id"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "first_name", description: "Author first_name", type: "string", maxLength: 120),
        new Property(property: "last_name", description: "Author last_name", type: "string", maxLength: 120),
        new Property(property: "image", description: "Author image", type: "string", maxLength: 255),
        new Property(property: "role", description: "Author role", type: "string", maxLength: 120),
    ],
    type: "object"
)]
class AuthorModelResourceSchema
{
}
