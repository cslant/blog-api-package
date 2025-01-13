<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Core\Constants\AppConstant;
use CSlant\Blog\Core\Facades\Base\MetaBox;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Page;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class MetaBoxService
{
    protected function getSEOMetaBoxByModel(Model $model, string $lang = AppConstant::DEFAULT_LOCALE): ?Model
    {
        $metaKey = $lang === 'vi' ? 'seo_meta_vi' : 'seo_meta';

        return MetaBox::getMeta($model, $metaKey);
    }

    protected function getModelMetaBox(string $modelClass, int $modelId, string $lang = AppConstant::DEFAULT_LOCALE): ?Model
    {
        /** @var Model $modelClass */
        $model = $modelClass::query()->find($modelId);

        if (!$model) {
            return null;
        }

        return $this->getSEOMetaBoxByModel($model, $lang);
    }

    public function getPostMetaBox(int $modelId, string $lang): ?Model
    {
        return $this->getModelMetaBox(Post::getBaseModel(), $modelId, $lang);
    }

    public function getPageMetaBox(int $modelId, string $lang): ?Model
    {
        return $this->getModelMetaBox(Page::getBaseModel(), $modelId, $lang);
    }

    public function getCategoryMetaBox(int $modelId, string $lang): ?Model
    {
        return $this->getModelMetaBox(Category::getBaseModel(), $modelId, $lang);
    }

    public function getTagMetaBox(int $modelId, string $lang): ?Model
    {
        return $this->getModelMetaBox(Tag::getBaseModel(), $modelId, $lang);
    }

    /**
     * @param  string  $model
     * @param  int  $modelId
     * @param  string  $lang
     *
     * @return Model|null
     */
    public function getMetaBoxByModel(string $model, int $modelId, string $lang = AppConstant::DEFAULT_LOCALE): ?Model
    {
        if ($model === 'post') {
            return $this->getPostMetaBox($modelId, $lang);
        } elseif ($model === 'page') {
            return $this->getPageMetaBox($modelId, $lang);
        } elseif ($model === 'category') {
            return $this->getCategoryMetaBox($modelId, $lang);
        } elseif ($model === 'tag') {
            return $this->getTagMetaBox($modelId, $lang);
        } else {
            return null;
        }
    }
}
