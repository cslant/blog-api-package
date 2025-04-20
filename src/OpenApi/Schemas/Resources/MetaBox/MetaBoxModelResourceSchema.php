<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Resources\MetaBox;

use CSlant\Blog\Api\OpenApi\Schemas\Attributes\MetaBox\MetaBoxValueAttributeSchema;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "MetaBoxModelResource",
    required: ["meta_key", "meta_value", "reference_id", "reference_type"],
    properties: [
        new Property(property: "meta_key", description: "Meta key", type: "string", maxLength: 120),
        new Property(
            property: "meta_value",
            ref: MetaBoxValueAttributeSchema::class,
            description: "Meta value",
            type: "object",
        ),
        new Property(property: "reference_id", description: "Reference Id", type: "integer"),
        new Property(property: "reference_type", description: "Reference Type", type: "string", maxLength: 255),
    ],
    type: "object"
)]
class MetaBoxModelResourceSchema
{
}
