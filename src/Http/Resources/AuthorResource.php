<?php

namespace CSlant\Blog\Api\Http\Resources;

use Botble\Blog\Models\Category;
use CSlant\Blog\Core\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class AuthorResource extends JsonResource
{
    /**  */
    public function toArray($request): array
    {
        /** @var User $this */

        $role = $this->roles->first()->name ?? null;

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'image' => $this->avatar_url,
            'role' => $role,
        ];
    }
}
