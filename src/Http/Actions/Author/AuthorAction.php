<?php

namespace CSlant\Blog\Api\Http\Actions\Author;

use Botble\ACL\Models\User;
use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Actions\Action;
use CSlant\Blog\Api\Http\Resources\Author\AuthorWithPostResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\AuthorModelResourceSchema;
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
 * Class AuthorAction
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
class AuthorAction extends Action
{
    /**
     * @param  int  $authorId
     * @param  Request  $request
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     * @group Blog
     *
     * @queryParam  Find by authorId of user.
     *
     */
    #[
        Get(
            path: "/authors/{authorId}",
            operationId: "profileAuthorByAuthorId",
            description: "Get profile and list post of the author by author id
            
    This API will get record from the database and return profile and list post of the author by author id.
            ",
            summary: "Get profile and list post of the author by author id",
            tags: ["Author"],
            parameters: [
                new Parameter(
                    name: 'authorId',
                    description: 'Author Id',
                    in: 'path',
                    required: true,
                    schema: new Schema(type: 'string', example: 'php')
                ),
                new Parameter(
                    name: 'order_by',
                    description: 'Can order by field: id, slug, created_at, ...',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'string', default: 'created_at')
                ),
                new Parameter(
                    name: 'order',
                    description: 'Order direction: 
                        ASC for ascending
                        DESC for descending',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'string', default: 'ASC', enum: ['ASC', 'DESC'])
                ),
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
                    description: "Get author and list posts successfully",
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
                                ref: AuthorModelResourceSchema::class,
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
    public function __invoke(int $authorId, Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $user = User::query()
            ->with('posts')
            ->whereId($authorId)
            ->first();

        if (!$user) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(AuthorWithPostResource::make($user))
            ->toApiResponse();
    }
}
