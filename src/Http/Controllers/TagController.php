<?php

namespace CSlant\Blog\Api\Http\Controllers;

use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Http\Resources\TagResource;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Controllers\Base\BaseTagController;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Slug;
use CSlant\Blog\Core\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TagController
 *
 * @package CSlant\Blog\Api\Http\Controllers
 *
 * @group Blog
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 */
class TagController extends BaseTagController
{
    /**
     * Get tag by slug
     *
     * @group Blog
     * @queryParam slug Find by slug of tag.
     *
     * @param  string  $slug
     *
     * @return JsonResponse|RedirectResponse|JsonResource|BaseHttpResponse
     */
    public function findBySlug(string $slug): JsonResponse|RedirectResponse|JsonResource|BaseHttpResponse
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Tag::getBaseModel()));

        if (!$slug) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        $tag = Tag::query()
            ->with('slugable')
            ->where([
                'id' => $slug->reference_id,
                'status' => StatusEnum::PUBLISHED,
            ])
            ->first();

        if (!$tag) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(new TagResource($tag))
            ->toApiResponse();
    }
}
