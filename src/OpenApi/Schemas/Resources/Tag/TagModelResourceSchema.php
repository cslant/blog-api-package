<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Tag;


use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\AuthorModelResourceSchema;

#[Schema(
    schema: "TagModelResource",
    required: ["id", "name", "slug", "description"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "name", description: "Tag name", type: "string", maxLength: 120),
        new Property(property: "slug", description: "Category slug", type: "string", maxLength: 255, uniqueItems: true),
        new Property(property: "description", description: "Tag description", type: "string", maxLength: 400, nullable: true),
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
