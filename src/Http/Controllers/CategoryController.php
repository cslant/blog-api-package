<?php

namespace CSlant\Blog\Api\Http\Controllers;

use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Http\Resources\ListCategoryResource;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Controllers\Base\BaseCategoryController;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Category;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryController
 *
 * @package CSlant\Blog\Api\Http\Controllers
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class CategoryController extends BaseCategoryController
{
    /**
     *  Get category by slug
     *
     * @group Blog
     * @queryParam slug Find by slug of category.
     *
     * @param  string  $slug
     *
     * @return BaseHttpResponse|JsonResponse|RedirectResponse|JsonResource
     */
    public function findBySlug(string $slug): JsonResponse|RedirectResponse|JsonResource|BaseHttpResponse
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Category::getBaseModel()));

        if (!$slug) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $category = Category::query()
            ->with('slugable')
            ->where([
                'id' => $slug->reference_id,
                'status' => StatusEnum::PUBLISHED,
            ])
            ->first();

        if (!$category) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(ListCategoryResource::make($category))
            ->toApiResponse();
    }
}
