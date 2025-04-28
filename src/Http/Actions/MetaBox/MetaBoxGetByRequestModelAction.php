<?php

namespace CSlant\Blog\Api\Http\Actions\MetaBox;

use CSlant\Blog\Api\Http\Requests\MetaBox\MetaBoxGetRequestModelRequest;
use CSlant\Blog\Api\Http\Resources\MetaBox\MetaBoxCustomResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\MetaBox\MetaBoxModelResourceSchema;
use CSlant\Blog\Api\Services\MetaBoxService;
use CSlant\Blog\Api\Services\SlugService;
use CSlant\Blog\Core\Constants\AppConstant;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
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
 * Class MetaBoxGetBySlugAction
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class MetaBoxGetByRequestModelAction extends Action
{
    protected MetaBoxService $metaBoxService;

    protected SlugService $slugService;

    public function __construct(MetaBoxService $metaBoxService, SlugService $slugService)
    {
        $this->metaBoxService = $metaBoxService;
        $this->slugService = $slugService;
    }

    #[
        Get(
            path: "/meta-boxes",
            operationId: "metaBoxGetByRequestModel",
            description: "Get the meta data by slug and model.
            
    This API will get the meta SEO data by slug and model.
    The model can be one of the following: post, page, category, tag, etc.
            ",
            summary: "Get meta data by slug and model",
            tags: ["MetaBox"],
            parameters: [
                new Parameter(
                    name: 'model',
                    description: 'The model name. Can be one of the following: post, page, category, tag, etc.',
                    in: 'query',
                    required: true,
                    schema: new Schema(
                        type: 'string',
                        enum: ['post', 'page', 'category', 'tag'],
                        example: 'category'
                    )
                ),
                new Parameter(
                    name: 'slug',
                    description: 'The slug of the model. Can be one of the following: post, page, category, tag, etc.
                    
    Example: post-slug, page-slug, category-slug, tag-slug, etc.',
                    in: 'query',
                    required: true,
                    schema: new Schema(
                        type: 'string',
                        example: 'php',
                    )
                ),
                new Parameter(
                    name: 'lang',
                    description: 'The language code. Default is en.',
                    in: 'query',
                    required: false,
                    schema: new Schema(
                        type: 'string',
                        default: AppConstant::DEFAULT_LOCALE
                    )
                ),
            ],
            responses: [
                new Response(
                    response: 200,
                    description: "Get meta data by slug and model",
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
                                ref: MetaBoxModelResourceSchema::class,
                                description: "Data of the meta box",
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
    public function __invoke(MetaBoxGetRequestModelRequest $request): JsonResponse|BaseHttpResponse|JsonResource|RedirectResponse
    {
        $model = (string) $request->validated('model');
        $slug = (string) $request->validated('slug');
        $lang = (string) $request->validated('lang', AppConstant::DEFAULT_LOCALE);

        $slugModel = $this->slugService->getSlugModel($slug, $model);

        if (!$slugModel) {
            return $this
                ->httpResponse()
                ->setError()
                ->setStatusCode(404)
                ->setMessage(__('Slug not found!'))
                ->toApiResponse();
        }

        $metaBox = $this->metaBoxService->getMetaBoxByModel($model, $slugModel->reference_id, $lang);

        if (!$metaBox) {
            return $this
                ->httpResponse()
                ->setError()
                ->setStatusCode(404)
                ->setMessage(__('MetaBox not found!'))
                ->toApiResponse();
        }

        return $this
            ->httpResponse()
            ->setData(MetaBoxCustomResource::make($metaBox))
            ->setMessage(__('MetaBox retrieved successfully!'))
            ->toApiResponse();
    }
}
