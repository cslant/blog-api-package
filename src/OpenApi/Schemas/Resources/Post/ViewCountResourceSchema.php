<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\Post;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "ViewCountResource",
    required: ["id", "views"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "views", description: "Post views", type: "integer"),
    ],
    type: "object"
)]
class ViewCountResourceSchema
{
}
