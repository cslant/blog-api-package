<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "MetaBoxModelResource",
    required: ["meta_key", "meta_value", "reference_id", "reference_type"],
    properties: [
        new Property(property: "meta_key", description: "Meta key", type: "string", maxLength: 120),
        new Property(property: "meta_value", description: "Meta value", type: "string", maxLength: 400, nullable: true),
        new Property(property: "reference_id", description: "Reference Id", type: "integer"),
        new Property(property: "reference_type", description: "Reference Type", type: "string", maxLength: 255),
    ],
    type: "object"
)]
class MetaBoxModelResourceSchema
{
}
