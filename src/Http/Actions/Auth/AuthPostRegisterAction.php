<?php

namespace CSlant\Blog\Api\Http\Actions\Auth;

use Botble\Base\Http\Responses\BaseHttpResponse;
use CSlant\Blog\Api\Http\Resources\Author\ListAuthorResource;
use CSlant\Blog\Api\OpenApi\Schemas\Resources\Author\ListAuthorResourceSchema;
use CSlant\Blog\Api\Services\AuthorService;
use CSlant\Blog\Core\Http\Actions\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

/**
 * Class AuthPostRegisterAction
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
class AuthPostRegisterAction extends Action
{
    public function __invoke(Request $request): BaseHttpResponse|JsonResponse|JsonResource|RedirectResponse
    {
        return $this
            ->httpResponse()
            ->setData()
            ->toApiResponse();
    }
}
