<?php

namespace CSlant\Blog\Api\Http\Controllers;

use CSlant\Blog\Api\Services\MetaBoxService;
use CSlant\Blog\Api\Services\SlugService;
use CSlant\Blog\Core\Constants\AppConstant;
use Illuminate\Database\Eloquent\Model;

class MetaBoxController
{
    protected MetaBoxService $metaBoxService;
    protected SlugService $slugService;

    public function __construct(MetaBoxService $metaBoxService, SlugService $slugService)
    {
        $this->metaBoxService = $metaBoxService;
        $this->slugService = $slugService;
    }

    public function getMetaBoxBySlugModel(string $model, string $slug, string $lang = AppConstant::DEFAULT_LOCALE): ?Model
    {
        $slugModel = $this->slugService->getSlugModel($slug, $model);

        if ($slugModel) {
            return $this->metaBoxService->getMetaBoxByModel($model, $slugModel->reference_id, $lang);
        }

        return null;
    }
}
