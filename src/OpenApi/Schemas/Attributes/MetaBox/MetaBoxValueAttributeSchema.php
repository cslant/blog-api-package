<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Attributes\MetaBox;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "MetaBoxValueAttribute",
    required: ["id", "name", "slug", "description"],
    properties: [
        new Property(property: "seo_title", description: "SEO title", type: "string", maxLength: 255),
        new Property(property: "seo_description", description: "SEO description", type: "string", maxLength: 255),
        new Property(property: "index", description: "Index", type: "string", maxLength: 255),
    ],
    type: "object"
)]
class MetaBoxValueAttributeSchema
{
}
