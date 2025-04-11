<?php

namespace CSlant\Blog\Api\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Supports\FilterCategory;
use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Http\Resources\Category\ListCategoryResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Category\CategoryListResourceSchema;
use CSlant\Blog\Api\Services\CategoryService;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Slug;
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
 * Class CategoryController
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
class CategoryController extends Action
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[
        Get(
            path: "/categories",
            operationId: "categoryGetAllWithFilter",
            description: "Get all categories with pagination (10 items per page by default, page 1 by default)
            
    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
            ",
            summary: "Get all categories with pagination",
            tags: ["Category"],
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
                    description: "Get categories successfully",
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
                                items: new Items(ref: CategoryListResourceSchema::class)
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
        $data = Category::query()
            ->wherePublished()
            ->orderByDesc('created_at')
            ->with(['slugable'])
            ->paginate($request->integer('per_page', 10) ?: 10);

        return $this
            ->httpResponse()
            ->setData(ListCategoryResource::collection($data))
            ->toApiResponse();
    }

    #[
        Get(
            path: "/categories/filters",
            operationId: "categoryGetWithFilter",
            description: "Get all categories with filters and pagination (10 items per page by default, page 1 by default)
            
    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.
            ",
            summary: "Get categories by filters with pagination",
            tags: ['Category'],
            parameters: [
                new Parameter(
                    name: 'search',
                    description: 'Search for categories where the given keyword appears in either the name field.',
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
                    description: "Get filters successfully",
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
                                description: "Filter data",
                                type: "array",
                                items: new Items(ref: CategoryListResourceSchema::class)
                            ),
                        ]
                    )
                ),
            ],
        )
    ]
    public function getFilters(
        Request $request,
        CategoryInterface $categoryRepository
    ): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse {
        $filters = FilterCategory::setFilters($request->input());
        $data = $this->categoryService->getCustomFilters($filters);

        return $this
            ->httpResponse()
            ->setData(ListCategoryResource::collection($data))
            ->toApiResponse();
    }

    /**
     *  Get category by slug
     *
     * @group Blog
     * @queryParam slug Find by slug of category.
     *
     * @param  string  $slug
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/categories/{slug}",
            operationId: "categoryFilterBySlug",
            description: "Get the category by slug
            
    This API will get records from the database and return the category by slug.
            ",
            summary: "Get category by slug",
            tags: ["Category"],
            parameters: [
                new Parameter(
                    name: 'slug',
                    description: 'Category slug',
                    in: 'path',
                    required: true,
                    schema: new Schema(type: 'string', example: 'php')
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: "Get category successfully",
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
                                ref: CategoryListResourceSchema::class,
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
    public function findBySlug(string $slug): JsonResponse|RedirectResponse|JsonResource|BaseHttpResponse
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Category::getBaseModel()));

        if (!$slug) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $category = Category::query()
            ->with(['slugable'])
            ->where([
                'id' => $slug->reference_id,
                'status' => StatusEnum::PUBLISHED,
            ])
            ->first();

        if (!$category) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(ListCategoryResource::make($category))
            ->toApiResponse();
    }
}
