<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 400,
        description: "Bad Request",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Bad request',
                    type: 'string',
                    example: 'Bad request'
                ),
            ]
        )
    )
]
class BadRequestResponseSchema
{
}
