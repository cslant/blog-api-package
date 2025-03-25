<?php

namespace CSlant\Blog\Api\Http\Actions\Auth;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Member\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use CSlant\Blog\Core\Http\Actions\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:members',
            'password' => 'required|min:6|confirmed'
        ]);

        $token = Str::random(64);

        $member = Member::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $member->sendPasswordResetNotification($token);

        return $this
            ->httpResponse()
            ->setData(['message' => 'Registration successful. Please check your email to confirm.'])
            ->toApiResponse();
    }
}
