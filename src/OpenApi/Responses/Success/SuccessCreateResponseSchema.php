<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 201,
        description: "Created",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Created',
                    type: 'string',
                    example: 'Created'
                ),
            ]
        )
    )
]
class SuccessCreateResponseSchema
{
}
