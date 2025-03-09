<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 404,
        description: "Not found",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Not found',
                    type: 'string',
                    example: 'Not found'
                ),
            ]
        )
    )
]
class ErrorNotFoundResponseSchema
{
}
