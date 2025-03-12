<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Category;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "CategoryModelResource",
    required: ["id", "slug"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Category name", type: "string", maxLength: 255, nullable: true),
        new Property(property: "slug", description: "Category slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", type: "string", nullable: true),
        new Property(property: "icon", description: "Category icon", type: "string", nullable: true),
    ],
    type: "object"
)]
class CategoryModelResourceSchema
{
}
