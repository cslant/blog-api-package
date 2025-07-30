<?php

namespace CSlant\Blog\Api\Http\Controllers;

use CSlant\Blog\Api\Http\Resources\Tag\TagResource;
use CSlant\Blog\Api\OpenApi\Responses\Errors\BadRequestResponseSchema;
use CSlant\Blog\Api\OpenApi\Responses\Errors\ErrorNotFoundResponseSchema;
use CSlant\Blog\Api\OpenApi\Responses\Errors\InternalServerResponseSchema;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Tag\TagModelResourceSchema;
use CSlant\Blog\Core\Enums\StatusEnum;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Controllers\Base\BaseTagController;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Slug;
use CSlant\Blog\Core\Models\Tag;
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
 * Class TagController
 *
 * @package CSlant\Blog\Api\Http\Controllers
 *
 * @group Blog
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 */
class TagController extends BaseTagController
{
    #[
        Get(
            path: "/tags",
            operationId: "tagGetAllWithFilter",
            description: "Get all tags with pagination (10 items per page by default, page 1 by default, page 1 by default)

    This API will get records from the database and return them as a paginated list. 
    The default number of items per page is 10 and the default page number is 1. You can change these values by passing the `per_page` and `page` query parameters.",
            summary: "Get all tags with pagination",
            tags: ["Tag"],
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
                    description: "Get tags successfully",
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
                                items: new Items(ref: TagModelResourceSchema::class)
                            ),
                        ]
                    )
                ),
                new Response(
                    ref: BadRequestResponseSchema::class,
                    response: 400,
                ),
                new Response(
                    ref: ErrorNotFoundResponseSchema::class,
                    response: 404,
                ),
                new Response(
                    ref: InternalServerResponseSchema::class,
                    response: 500,
                ),
            ]
        )
    ]
    public function index(Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $data = Tag::query()
            ->wherePublished()
            ->with('slugable')
            ->paginate($request->integer('per_page', 10) ?: 10);

        return $this
            ->httpResponse()
            ->setData(TagResource::collection($data))
            ->toApiResponse();
    }

    /**
     * Get tag by slug
     *
     * @group Blog
     * @queryParam slug Find by slug of tag.
     *
     * @param  string  $slug
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/tags/{slug}",
            operationId: "tagFilterBySlug",
            description: "Get the tag by slug
            
    This API will get records from the database and return the tag by slug.
            ",
            summary: "Get tag by slug",
            tags: ["Tag"],
            parameters: [
                new Parameter(
                    name: 'slug',
                    description: 'Tag slug',
                    in: 'path',
                    required: true,
                    schema: new Schema(type: 'string', example: 'php')
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: "Get tag successfully",
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
                    ref: BadRequestResponseSchema::class,
                    response: 400,
                ),
                new Response(
                    ref: ErrorNotFoundResponseSchema::class,
                    response: 404,
                ),
                new Response(
                    ref: InternalServerResponseSchema::class,
                    response: 500,
                ),
            ]
        )
    ]
    public function findBySlug(string $slug): JsonResponse|RedirectResponse|JsonResource|BaseHttpResponse
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Tag::getBaseModel()));

        if (!$slug) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $tag = Tag::query()
            ->with('slugable')
            ->where([
                'id' => $slug->reference_id,
                'status' => StatusEnum::PUBLISHED,
            ])
            ->first();

        if (!$tag) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(new TagResource($tag))
            ->toApiResponse();
    }
}
