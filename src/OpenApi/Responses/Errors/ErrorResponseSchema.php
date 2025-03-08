<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 500,
        description: "Internal server error",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Internal server error',
                    type: 'string',
                    example: 'Internal server error'
                ),
            ]
        )
    )
]
class ErrorResponseSchema
{
}
