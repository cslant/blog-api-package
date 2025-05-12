<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Menu",
    required: ["id"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Name", type: "string", maxLength: 120, example: "Name"),
        new Property(property: "slug", description: "Slug", type: "string", maxLength: 120, example: "Slug"),
        new Property(property: "status", type: "string", enum: ["publish", "draft", "pending"]),
        new Property(
            property: "menu_nodes",
            type: "array",
            items: new Items(
                type: "object",
                properties: [
                    new Property(property: "id", type: "integer", uniqueItems: true),
                    new Property(property: "menu_id", type: "integer", nullable: true),
                    new Property(property: "parent_id", type: "integer", nullable: true),
                    new Property(property: "reference_id", type: "integer", nullable: true),
                    new Property(property: "reference_type", type: "string", nullable: true),
                    new Property(property: "url", type: "string", nullable: true),
                    new Property(property: "position", type: "integer", nullable: true),
                    new Property(property: "title", type: "string", nullable: true),
                    new Property(property: "css_class", type: "string", nullable: true),
                    new Property(property: "target", type: "string", nullable: true),
                    new Property(property: "has_child", type: "integer", nullable: true),
                ]
            )
        ),
        new Property(
            property: "locations",
            type: "array",
            items: new Items(
                type: "object",
                properties: [
                    new Property(property: "id", type: "integer", uniqueItems: true),
                    new Property(property: "location", type: "string"),
                ]
            )
        ),
    ],
    type: "object"
)]
class MenuSchema
{
}
