<?php

namespace CSlant\Blog\Api\Http\Actions\Post;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\Post\ViewCountResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Post\ViewCountResourceSchema;
use CSlant\Blog\Api\Services\VisitorLogsService;
use CSlant\Blog\Core\Http\Actions\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class PostStoreViewCountAction extends Action
{
    protected VisitorLogsService $visitorLogsService;

    public function __construct(VisitorLogsService $visitorLogsService)
    {
        $this->visitorLogsService = $visitorLogsService;
    }

    #[
        Post(
            path: "/posts/{id}/increment-views",
            operationId: "incrementViewCountPostById",
            description: "Increment views count of the post by ID. Only adds 1 view per IP in 1 hour.",
            summary: "Increment views count of the post by ID",
            tags: ["Post"],
            parameters: [
                new Parameter(
                    name: 'id',
                    description: 'Post Id',
                    in: 'path',
                    required: true,
                    schema: new Schema(type: 'integer', example: 1)
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: "Success",
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
                                description: "Updated view count data",
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
    public function __invoke(Request $request, int $id): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $ipAddress = $request->header('X-Forwarded-For') ?? $request->ip();
        $userAgent = $request->userAgent();

        DB::beginTransaction();

        try {
            $post = $this->visitorLogsService->trackPostView($id, $ipAddress, $userAgent);

            DB::commit();

            return $this
                ->httpResponse()
                ->setData(new ViewCountResource($post))
                ->toApiResponse();
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this
                ->httpResponse()
                ->setError()
                ->setStatusCode(500)
                ->setMessage($exception->getMessage());
        }
    }
}
