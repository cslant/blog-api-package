<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Category;

use CSlant\Blog\Api\OpenApi\Schemas\Models\CategorySchema;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "CategoryModelResource",
    required: ["id", "name", "slug", "description"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Category name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Category slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", type: "string", nullable: true),
        new Property(property: "icon", description: "Category icon", type: "string", nullable: true),
        new Property(
            property: "children",
            type: "array",
            items: new Items(ref: CategorySchema::class)
        ),
        new Property(
            property: "parent",
            ref: CategorySchema::class,
            type: "object",
        ),
    ],
    type: "object"
)]
class CategoryModelResourceSchema
{
}
