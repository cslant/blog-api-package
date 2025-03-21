<?php

namespace CSlant\Blog\Api\Http\Actions\Post;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Http\Resources\PostResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\PostModelResourceSchema;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Http\Repositories\PostRepository;
use CSlant\Blog\Core\Supports\Base\FilterPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

/**
 * Class GetByTagAction
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
class PostGetByTagAction extends Action
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param  Request  $request
     *
     * @group Blog
     *
     * @queryParam  Find by tagId of post.
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/get-by-tags",
            operationId: "postGetByTag",
            description: "Get list post of the tag by tag id
            
    This API will get record from the database and return list post of the tag by tag id.
            ",
            summary: "Get list post of the tag by tag id",
            tags: ["Post"],
            parameters: [
                new Parameter(
                    name: 'tags',
                    description: 'Filter posts by tag specific tag IDs.',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'array',
                        items: new Items(description: 'Input the exclude tag ID', type: 'integer'),
                        default: null
                    )
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
                    description: "Get list posts by tag successfully",
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
                                ref: PostModelResourceSchema::class,
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
        $filters = FilterPost::setFilters($request->input());

        $data = $this->postRepository->getFilters((array) $filters);

        return $this
            ->httpResponse()
            ->setData(PostResource::collection($data))
            ->toApiResponse();
    }
}
