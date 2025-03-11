<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Author",
    required: ["id", "name", "email", "first_name", "last_name", "username", "avatar_url"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Author name", type: "string", maxLength: 255),
        new Property(property: "email", description: "Author email", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "first_name", description: "Author first_name", type: "string", maxLength: 255, nullable: true),
        new Property(property: "last_name", description: "Author last_name", type: "string", maxLength: 255, nullable: true),
        new Property(property: "username", description: "Author username", type: "string", maxLength: 255, nullable: true),
    ],
    type: "object"
)]
class AuthorSchema
{
}
