<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Core\Facades\Base\MetaBox;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Page;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class MetaBoxService
{
    protected function getSEOMetaBoxByModel(Model $model, string $lang = 'en'): ?Model
    {
        $metaKey = $lang === 'vi' ? 'seo_meta_vi' : 'seo_meta';

        return MetaBox::getMeta($model, $metaKey);
    }

    protected function getModelMetaBox(string $modelClass, $modelId, mixed $lang): ?Model
    {
        /** @var Model $modelClass */
        $model = $modelClass::query()->find($modelId);

        if (!$model) {
            return null;
        }

        return $this->getSEOMetaBoxByModel($model, $lang);
    }

    public function getPostMetaBox($modelId, mixed $lang): ?Model
    {
        return $this->getModelMetaBox(Post::getBaseModel(), $modelId, $lang);
    }

    public function getPageMetaBox($modelId, mixed $lang): ?Model
    {
        return $this->getModelMetaBox(Page::getBaseModel(), $modelId, $lang);
    }

    public function getCategoryMetaBox($modelId, mixed $lang): ?Model
    {
        return $this->getModelMetaBox(Category::getBaseModel(), $modelId, $lang);
    }

    public function getTagMetaBox($modelId, mixed $lang): ?Model
    {
        return $this->getModelMetaBox(Tag::getBaseModel(), $modelId, $lang);
    }
}
