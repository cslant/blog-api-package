<?php

namespace CSlant\Blog\Api\Http\Actions\Author;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\Author\AuthorWithPostResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\AuthorModelResourceSchema;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
class AuthorGetProfileAction extends Action
{
    /**
     * @param  int|string  $author
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
            path: "/authors/{author}",
            operationId: "profileAuthorByAuthorIdOrUsername",
            description: "Get profile and list post of the author by author id or username
            
    This API will get record from the database and return profile and list post of the author by id or username of author.
            ",
            summary: "Get profile and list post of the author by id or username of author",
            tags: ["Author"],
            parameters: [
                new Parameter(
                    name: 'author',
                    description: 'Id or username of author',
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
    public function __invoke(
        string|int $author,
        Request $request
    ): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse {
        /** @var User $userQuery */
        $userQuery = User::query()->with('posts');

        $user = match (true) {
            is_numeric($author) => (clone $userQuery)->whereId((int) $author)->first(),
            default => (clone $userQuery)->where('username', (string) $author)->first()
        };

        if (!$user instanceof User) {
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
