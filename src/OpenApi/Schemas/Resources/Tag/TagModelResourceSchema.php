<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Tag;


use CSlant\Blog\Api\OpenApi\Schemas\Models\TagSchema;

#[Schema(
    schema: "TagModelResource",
    required: ["id", "name", "slug", "description", "status", "author_id", "author_type"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Tag name", type: "string", maxLength: 255),
        new Property(property: "slug", description: "Tag slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", description: "Tag description", type: "string", nullable: true),
        new Property(property: "status", description: "Tag status", type: "string", nullable: true),
        new Property("author_id", "Author Id", "integer"),
        new Property("author_type", "Author Type", "string"),
        new Property(
            property: "children",
            type: "array",
            items: new Items(ref: TagSchema::class)
        ),
        new Property(
            property: "parent",
            ref: TagSchema::class,
            type: "object",
        ),
    ],
    type: "object"
)]
class TagModelResourceSchema {}
