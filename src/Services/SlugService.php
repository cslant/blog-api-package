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
    /**
     * @param  string  $slug
     * @param  string  $model
     *
     * @return Slug|null
     */
    public function getSlugModel(string $slug, string $model): ?Slug
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix($model));
        return $slug instanceof Slug ? $slug : null;
    }
}
