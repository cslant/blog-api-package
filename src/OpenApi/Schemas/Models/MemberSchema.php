<?php

namespace CSlant\Blog\Api\OpenApi\Schemas\Models;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Member",
    required: ["id", "email"],
    properties: [
        new Property(property: "id", type: "integer", uniqueItems: true),
        new Property(property: "first_name", description: "First name", type: "string", maxLength: 120, example: "Francis"),
        new Property(property: "last_name", description: "Last name", type: "string", maxLength: 120, example: "Duong"),
        new Property(property: "email", type: "string", format: "email", maxLength: 120, example: "francisduong@gmail.com"),
        new Property(property: "phone", type: "string", example: "0904064751"),
        new Property(property: "avatar_url", type: "string", format: "uri", example: "https://example.com/avatars/user.jpg"),
        new Property(property: "dob", type: "string", format: "date", example: "2000-09-05"),
        new Property(property: "gender", type: "string", enum: ["male", "female", "other"]),
        new Property(property: "description", type: "string", example: "Software developer passionate about coding"),
    ],
    type: "object"
)]
class MemberSchema
{
}
