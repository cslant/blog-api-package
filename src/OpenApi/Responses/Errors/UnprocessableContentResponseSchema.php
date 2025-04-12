<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 422,
        description: "Unprocessable Content",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Unprocessable Content',
                    type: 'string',
                    example: 'Unprocessable Content'
                ),
            ]
        )
    )
]
class UnprocessableContentResponseSchema
{
}
