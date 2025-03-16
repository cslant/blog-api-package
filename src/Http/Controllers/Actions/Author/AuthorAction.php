<?php

namespace CSlant\Blog\Api\Http\Controllers\Actions\Author;

use Botble\ACL\Models\User;
use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\AuthorWithPostResource;
use CSlant\Blog\Core\Http\Controllers\Base\BasePostController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ViewCountAction
 *
 *
 * @group Blog API
 *
 * @authenticated
 *
 * @method BaseHttpResponse httpResponse()
 * @method BaseHttpResponse setData(mixed $data)
 * @method BaseHttpResponse|JsonResource|JsonResponse|RedirectResponse toApiResponse()
 */
class AuthorAction extends BasePostController
{
    /**
     * @param  int  $authorId
     *
     * @group Blog
     *
     * @queryParam  Find by authorId of user.
     *
     * @return BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
     */
    public function __invoke(int $authorId, Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        $user = User::query()
            ->with('posts')
            ->whereId($authorId)
            ->first();

        if (! $user) {
            return $this
                ->httpResponse()
                ->setError()
                ->setCode(404)
                ->setMessage('Not found');
        }

        return $this
            ->httpResponse()
            ->setData(new AuthorWithPostResource($user))
            ->toApiResponse();
    }
}
