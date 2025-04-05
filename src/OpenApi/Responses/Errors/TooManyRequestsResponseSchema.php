<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 429,
        description: "Too Many Requests",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Too Many Requests',
                    type: 'string',
                    example: 'Too Many Requests'
                ),
            ]
        )
    )
]
class TooManyRequestsResponseSchema
{
}
