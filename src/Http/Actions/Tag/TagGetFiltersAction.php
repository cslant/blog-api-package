<?php

namespace CSlant\Blog\Api\Http\Actions\Tag;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Requests\Tag\TagGetFiltersRequest;
use CSlant\Blog\Api\Http\Resources\Tag\TagResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Tag\TagModelResourceSchema;
use CSlant\Blog\Api\Services\TagService;
use CSlant\Blog\Api\Supports\Filters\FilterTag;
use CSlant\Blog\Core\Http\Actions\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

/**
 * Class TagGetFiltersAction
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class TagGetFiltersAction extends Action
{
    protected TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @param  TagGetFiltersRequest  $request
     *
     * @group Blog
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/tags/filters",
            operationId: "TagGetFiltersAction",
            description: "Get all tags with pagination (10 items per page by default, page 1 by default)
            
    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
            ",
            summary: "Get tags by filters with pagination",
            tags: ["Tag"],
            parameters: [
                new Parameter(
                    name: 'search',
                    description: 'Search for tags where the given keyword appears in either the name fields.',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'string', default: null)
                ),
                new Parameter(
                    name: 'order_by',
                    description: 'Can order by field: id, name, created_at, posts_count, ...',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'string', default: 'posts_count')
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
                    description: "Get list tags successfully",
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
                                ref: TagModelResourceSchema::class,
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
    public function __invoke(TagGetFiltersRequest $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $filters = FilterTag::setFilters($request->validated());

        $data = $this->tagService->getFilters($filters);

        return $this
            ->httpResponse()
            ->setData(TagResource::collection($data))
            ->toApiResponse();
    }
}
