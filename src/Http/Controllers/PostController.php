<?php

namespace CSlant\Blog\Api\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Http\Resources\ListPostResource;
use CSlant\Blog\Core\Http\Controllers\Base\BasePostController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
 * @method BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse toApiResponse()
 */
class PostController extends BasePostController
{
    /**
     * @group Blog API
     *
     * @param  Request  $request
     *
     * @return BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
     */
    public function index(Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $data = $this->postRepository
            ->advancedGet([
                'with' => ['tags', 'categories', 'author', 'slugable'],
                'condition' => ['status' => StatusEnum::PUBLISHED],
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
}
