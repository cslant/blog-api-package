<?php

namespace CSlant\Blog\Api\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Http\Resources\ListPostResource;
use CSlant\Blog\Api\Http\Resources\PostResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\PostListResourceSchema;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\PostModelResourceSchema;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Controllers\Base\BasePostController;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Slug;
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
 * Class PostController
 *
 * @package CSlant\Blog\Api\Http\Controllers
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class PostController extends BasePostController
{
    /**
     * @group Blog API
     *
     * @param  Request  $request
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts",
            operationId: "postGetAllWithFilter",
            description: "Get all posts with pagination (10 items per page by default, page 1 by default)

    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
            ",
            summary: "Get all posts with pagination",
            tags: ["Post"],
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
                    description: "Get posts successfully",
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
                                description: "Data of model",
                                type: "array",
                                items: new Items(ref: PostListResourceSchema::class)
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
    public function index(Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $data = $this
            ->postRepository
            ->advancedGet([
                'with' => ['tags', 'categories', 'author', 'slugable'],
                'condition' => ['status' => StatusEnum::PUBLISHED->value],
                'paginate' => [
                    'per_page' => $request->integer('per_page', 10),
                    'current_paged' => $request->integer('page', 1),
                ],
            ]);

        return $this
            ->httpResponse()
            ->setData(ListPostResource::collection($data))
            ->toApiResponse();
    }

    /**
     * @param  string  $slug
     *
     * @group Blog
     * @queryParam slug Find by slug of post.
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/{slug}",
            operationId: "postFilterBySlug",
            description: "Get the post by slug
            
    This API will get records from the database and return the post by slug.
            ",
            summary: "Get post by slug",
            tags: ["Post"],
            parameters: [
                new Parameter(
                    name: 'slug',
                    description: 'Post slug',
                    in: 'path',
                    required: true,
                    schema: new Schema(type: 'string', example: 'php')
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: "Get post successfully",
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
    public function findBySlug(string $slug): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Post::getBaseModel()));

        if (!$slug) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $post = Post::query()
            ->with(['tags', 'categories', 'author'])
            ->where([
                'id' => $slug->reference_id,
                'status' => StatusEnum::PUBLISHED,
            ])
            ->first();

        if (!$post) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(new PostResource($post))
            ->toApiResponse();
    }

    /**
     * @param  Request  $request
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/filters",
            operationId: "postGetWithFilter",
            description: "Get all posts with pagination (10 items per page by default, page 1 by default)
            
    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
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
                        items: new Items(type: 'integer'),
                        default: null
                    )
                ),
                new Parameter(
                    name: 'categories_exclude',
                    description: 'Filter posts by excluding specific category IDs.',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'array',
                        items: new Items(type: 'integer'),
                        default: null
                    )
                ),
                new Parameter(
                    name: 'exclude',
                    description: 'Filter posts by excluding specific post IDs.',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'array',
                        items: new Items(type: 'integer'),
                        default: null
                    )
                ),
                new Parameter(
                    name: 'author',
                    description: 'Filter posts by author IDs',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'array',
                        items: new Items(type: 'integer'),
                        default: null
                    )
                ),
                new Parameter(
                    name: 'author_exclude',
                    description: 'Filter posts by excluding specific author IDs.',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'array',
                        items: new Items(type: 'integer'),
                        default: null
                    )
                ),
                new Parameter(
                    name: 'featured',
                    description: 'Filter posts by featured status. Accepts values:
                        1 for featured posts
                        0 for non-featured posts.',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'integer',
                        default: null,
                        enum: [0, 1],
                        nullable: true
                    )
                ),
                new Parameter(
                    name: 'search',
                    description: 'Search for posts where the given keyword appears in either the name or description fields.',
                    in: 'query',
                    required: false,
                    schema: new Schema(type: 'string', default: null)
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
                    description: "Get posts successfully",
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
                                description: "Data of model",
                                type: "array",
                                items: new Items(ref: PostListResourceSchema::class)
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
    public function getFilters(Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $filters = FilterPost::setFilters($request->input());

        $data = $this->postRepository->getFilters((array) $filters);

        return $this
            ->httpResponse()
            ->setData(ListPostResource::collection($data))
            ->toApiResponse();
    }
}
