<?php

namespace CSlant\Blog\Api\Http\Actions\Post;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\Post\PostNavigateResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\PostNavigateResourceSchema;
use CSlant\Blog\Api\Services\PostService;
use CSlant\Blog\Core\Enums\StatusEnum;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Actions\Action;
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
 * Class PostGetNavigateAction
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class PostGetNavigateAction extends Action
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param  string  $slug
     *
     * @group Blog
     * @queryParam Find by slug of post.
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/{slug}/navigate",
            operationId: "postGetNavigate",
            description: "Get the previous and next posts by slug. This API will return both previous and next posts for navigation purposes.",
            summary: "Get previous and next posts for navigation",
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
                    description: "Get previous and next posts by slug successfully",
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
                                ref: PostNavigateResourceSchema::class,
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
        /** @var Slug $slugModel */
        $slugModel = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Post::getBaseModel()));

        if (!$slugModel) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $currentPost = Post::query()
            ->where('id', $slugModel->reference_id)
            ->where('status', StatusEnum::PUBLISHED)
            ->with(['categories', 'tags'])
            ->first();

        if (!$currentPost) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        // Using service method for complex business logic
        $navigationPosts = $this->postService->getNavigationPosts($currentPost);

        return $this
            ->httpResponse()
            ->setData(new PostNavigateResource($navigationPosts))
            ->toApiResponse();
    }
}
