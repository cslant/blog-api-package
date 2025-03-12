<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Tag",
    required: ["id", "name", "slug", "description", "status", "author_id", "author_type"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Tag name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Tag slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", description: "Tag description", type: "string", nullable: true),
        new Property(property: "status", description: "Tag status", type: "string", nullable: true),
        new Property("author_id", "Author Id", "integer"),
        new Property("author_type", "Author Type", "string"),
    ],
    type: "object"
)]
class TagSchema
{
}
