<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Category",
    required: ["id", "name", "slug", "description"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Category name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Category slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "url", description: "Category url", type: "string", maxLength: 255, nullable: true),
        new Property(property: "icon", description: "Category icon", type: "string", nullable: true),
        new Property(property: "description", type: "string", nullable: true),
    ],
    type: "object"
)]
class CategorySchema
{
}
