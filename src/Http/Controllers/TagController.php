<?php

namespace CSlant\Blog\Api\Http\Controllers;

use Botble\Blog\Models\Tag;
use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Http\Resources\TagResource;
use CSlant\Blog\Core\Facades\Base\SlugHelper;
use CSlant\Blog\Core\Http\Controllers\Base\BaseTagController;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Slug;

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
     */
    public function findBySlug(string $slug)
    {
        /** @var Slug $slug */
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Tag::class));
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
