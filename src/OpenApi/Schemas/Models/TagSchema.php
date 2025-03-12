<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Tag",
    required: ["id", "name", "status", "author_type"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Tag name", type: "string", maxLength: 120),
        new Property(property: "description", description: "Tag description", type: "string", maxLength: 400, nullable: true),
        new Property(property: "status", description: "Tag status", type: "string", nullable: true),
        new Property(property: "author_id", description: "Author Id", type: "integer", nullable: true),
        new Property(property: "author_type", description: "Author Type", type: "string", maxLength: 255),
    ],
    type: "object"
)]
class TagSchema
{
}
