<?php

namespace CSlant\Blog\Api\Http\Controllers;

use CSlant\Blog\Api\Services\MetaBoxService;
use CSlant\Blog\Api\Services\SlugService;
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

    public function getMetaBoxBySlugModel(string $model, string $slug, string $lang = 'en'): ?Model
    {
        $slugModel = $this->slugService->getSlugModel($slug, $model);

        if ($slugModel) {
            return $this->getMetaBoxByModel($model, $slugModel->reference_id, $lang);
        }

        return null;
    }
    /**
     * @param  string  $model
     * @param  int  $modelId
     * @param  string  $lang
     *
     * @return Model|null
     */
    public function getMetaBoxByModel(string $model, int $modelId, string $lang = 'en'): ?Model
    {
        if ($model === 'post') {
            return $this->metaBoxService->getPostMetaBox($modelId, $lang);
        } elseif ($model === 'page') {
            return $this->metaBoxService->getPageMetaBox($modelId, $lang);
        } elseif ($model === 'category') {
            return $this->metaBoxService->getCategoryMetaBox($modelId, $lang);
        } elseif ($model === 'tag') {
            return $this->metaBoxService->getTagMetaBox($modelId, $lang);
        } else {
            return null;
        }
    }
}
