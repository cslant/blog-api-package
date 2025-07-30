<?php

namespace CSlant\Blog\Api\Http\Actions\Post;

use CSlant\Blog\Api\Http\Resources\Post\ViewCountResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\ViewCountResourceSchema;
use CSlant\Blog\Core\Enums\StatusEnum;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Slug;
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
 * Class ViewCountAction
 *
 * @package CSlant\Blog\Api\Http\Controllers\Actions\Post
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class PostGetViewCountAction extends Action
{
    /**
     * @param  string  $slug
     *
     * @group Blog
     * @queryParam Find by slug of post.
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/{slug}/view-count",
            operationId: "viewCountPostBySlug",
            description: "Get views count of the post by slug
            
    This API will get record from the database and return views count of the post by slug.
            ",
            summary: "Get views count of the post by slug",
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
                    description: "Get views count successfully",
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
                                ref: ViewCountResourceSchema::class,
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
    public function __invoke(string $slug): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
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
            ->select(['id', 'views'])
            ->whereId($slug->reference_id)
            ->where('status', StatusEnum::PUBLISHED)
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
            ->setData(new ViewCountResource($post))
            ->toApiResponse();
    }
}
