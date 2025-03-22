<?php

namespace CSlant\Blog\Api\Http\Actions\Author;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\Author\ListAuthorResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\ListAuthorResourceSchema;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

/**
 * Class AuthorGetListAction
 *
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class AuthorGetListAction extends Action
{
    /**
     * @param  int  $authorId
     * @param  Request  $request
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     * @group Blog
     */
    #[
        Get(
            path: "/authors",
            operationId: "postGetAllAuthor",
            description: "Get all authors with pagination (10 items per page by default, page 1 by default)

    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
            ",
            summary: "Get all authors with pagination",
            tags: ["Author"],
            parameters: [
                new Parameter(
                    name: 'per_page',
                    description: 'Number of items per page',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'integer', default: 10)
                ),
                new Parameter(
                    name: 'page',
                    description: 'Page number',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'integer', default: 1)
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: "Get list authors successfully",
                    content: new JsonContent(
                        properties: [
                            new Property(
                                property: 'error',
                                description: 'Error status',
                                type: 'boolean',
                                default: false
                            ),
                            new Property(
                                property: "data",
                                ref: ListAuthorResourceSchema::class,
                                description: "Data of model",
                                type: "object",
                            ),
                        ]
                    )
                ),
                new Response(
                    ref: \CSlant\Blog\Api\OpenApi\Responses\Errors\BadRequestResponseSchema::class,
                    response: 400,
                ),
                new Response(
                    ref: \CSlant\Blog\Api\OpenApi\Responses\Errors\ErrorNotFoundResponseSchema::class,
                    response: 404,
                ),
                new Response(
                    ref: \CSlant\Blog\Api\OpenApi\Responses\Errors\InternalServerResponseSchema::class,
                    response: 500,
                ),
            ]
        )
    ]
    public function __invoke(Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $users = User::query()
            ->withCount('posts')
            ->orderBy('posts_count', 'DESC')
            ->paginate($request->integer('per_page', 10));

        return $this
            ->httpResponse()
            ->setData(ListAuthorResource::collection($users))
            ->toApiResponse();
    }
}
