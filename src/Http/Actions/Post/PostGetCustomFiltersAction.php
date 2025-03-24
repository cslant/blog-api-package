<?php

namespace CSlant\Blog\Api\Http\Actions\Post;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\Post\ListPostResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\PostListResourceSchema;
use CSlant\Blog\Api\Services\PostService;
use CSlant\Blog\Core\Http\Actions\Action;
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
 * Class PostGetCustomFiltersAction
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class PostGetCustomFiltersAction extends Action
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param  Request  $request
     *
     * @group Blog
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/custom-filters",
            operationId: "postGetWithCustomFilters",
            description: "Get all posts with pagination (10 items per page by default, page 1 by default)
            
    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
    
    Additional query parameters:
    - `custom-filters`: Apply custom filters such as tags, categories, etc and custom order by multiple.  
    - `tags`: Filter posts that are associated with specific tag IDs. Supports passing multiple tag IDs.
            ",
            summary: "Get posts by filter with pagination",
            tags: ["Post"],
            parameters: [
                new Parameter(
                    name: 'categories',
                    description: 'Filter posts by categories IDs',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'array',
                        items: new Items(description: 'Input the category ID', type: 'integer'),
                        default: null,
                    )
                ),
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
                    description: "Get list posts successfully",
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
                                ref: PostListResourceSchema::class,
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

        $data = $this->postService->getCustomFilters((array) $filters);

        return $this
            ->httpResponse()
            ->setData(ListPostResource::collection($data))
            ->toApiResponse();
    }
}
