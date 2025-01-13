<?php

namespace CSlant\Blog\Api\Services;

use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Slug;

/**
 * Class SlugService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class SlugService
{
    public function getSlugModel(string $slug, string $model)
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(get_class_name_by_slug($model)));

        return $slug ?? null;
    }
}
