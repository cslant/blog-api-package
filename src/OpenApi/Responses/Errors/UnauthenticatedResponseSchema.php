<?php

namespace CSlant\Blog\Api\OpenApi\Responses\Errors;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[
    Response(
        response: 401,
        description: "Unauthenticated",
        content: new JsonContent(
            properties: [
                new Property(
                    property: 'message',
                    description: 'Unauthenticated',
                    type: 'string',
                ),
            ]
        )
    )
]
class UnauthenticatedResponseSchema
{
}
