<?php

namespace CSlant\Blog\Api\Http\Actions\Post;

use Botble\Blog\Repositories\Interfaces\PostInterface;
use CSlant\Blog\Api\Http\Resources\Post\PostNavigateResource;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\RedirectResponse;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

/**
 * Class PostGetNavigateAction
 *
 * @package CSlant\Blog\Api\Http\Actions\Post
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class PostGetNavigateAction
{
    public function __construct(
        protected PostInterface $postRepository
    ) {
    }

    /**
     * @group Blog API
     *
     * @param  string  $slug
     *
     * @return BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse
     */
    #[
        Get(
            path: "/posts/{slug}/navigate",
            operationId: "postGetNavigate",
            description: "Get the previous and next posts by slug
This API will return both previous and next posts for navigation purposes.
            ",
            summary: "Get previous and next posts for navigation",
            tags: ["Post"],
            parameters: [
                new Parameter(
                    name: 'slug',
                    description: 'Post slug',
                    in: 'path',
                    required: true,
                    schema: new Schema(type: 'string')
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: 'Navigation posts retrieved successfully',
                ),
                new Response(
                    response: 404,
                    description: 'Post not found',
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
                ->setMessage('Post not found');
        }

        $navigationPosts = $this->postRepository->getNavigatePosts($slug->reference_id);

        return $this
            ->httpResponse()
            ->setData(new PostNavigateResource($navigationPosts))
            ->toApiResponse();
    }

    /**
     * @return BaseHttpResponse
     */
    protected function httpResponse(): BaseHttpResponse
    {
        return new BaseHttpResponse();
    }
}
