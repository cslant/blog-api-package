<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Tag;

use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\AuthorModelResourceSchema;

#[Schema(
    schema: "TagModelResource",
    required: ["id", "name", "status"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Tag name", type: "string", maxLength: 120),
        new Property(property: "description", description: "Tag description", type: "string", maxLength: 400, nullable: true),
        new Property(property: "status", description: "Tag status", type: "string", nullable: true),
        new Property(
            property: "author",
            ref: AuthorModelResourceSchema::class,
            type: "object"
        ),
    ],
    type: "object"
)]
class TagModelResourceSchema
{
}
