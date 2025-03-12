<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Author",
    required: ["id", "email"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "email", description: "Author email", type: "string", maxLength: 191),
        new Property(property: "first_name", description: "Author first_name", type: "string", maxLength: 120, nullable: true),
        new Property(property: "last_name", description: "Author last_name", type: "string", maxLength: 120, nullable: true),
        new Property(property: "username", description: "Author username", type: "string", maxLength: 60, uniqueItems: true),
    ],
    type: "object"
)]
class AuthorSchema
{
}
