<?php

namespace CSlant\Blog\Api\Http\Actions\MetaBox;

use CSlant\Blog\Api\Http\Resources\MetaBox\MetaBoxResource;
use CSlant\Blog\Api\Services\MetaBoxService;
use CSlant\Blog\Api\Services\SlugService;
use CSlant\Blog\Core\Constants\AppConstant;
use CSlant\Blog\Core\Http\Actions\Action;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;

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
class MetaBoxGetBySlugAction extends Action
{
    protected MetaBoxService $metaBoxService;

    protected SlugService $slugService;

    public function __construct(MetaBoxService $metaBoxService, SlugService $slugService)
    {
        $this->metaBoxService = $metaBoxService;
        $this->slugService = $slugService;
    }

    public function __invoke(string $model, string $slug, string $lang = AppConstant::DEFAULT_LOCALE)
    {
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
            ->setData(MetaBoxResource::make($metaBox))
            ->setMessage(__('MetaBox retrieved successfully!'))
            ->toApiResponse();
    }
}
