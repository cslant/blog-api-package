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

    protected function getModelMetaBox(
        string $modelClass,
        int $modelId,
        string $lang = AppConstant::DEFAULT_LOCALE
    ): ?Model {
        /** @var class-string<Model> $modelClass */
        $model = $modelClass::query()->find($modelId);

        return $model ? $this->getSEOMetaBoxByModel($model, $lang) : null;
    }

    public function getMetaBoxByModel(string $model, int $modelId, string $lang = AppConstant::DEFAULT_LOCALE): ?Model
    {
        $modelMap = [
            'post' => Post::class,
            'page' => Page::class,
            'category' => Category::class,
            'tag' => Tag::class,
        ];

        $modelClass = $modelMap[$model] ?? null;

        return $modelClass ? $this->getModelMetaBox($modelClass, $modelId, $lang) : null;
    }
}
