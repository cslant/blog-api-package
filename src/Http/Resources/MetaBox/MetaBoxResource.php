<?php

namespace CSlant\Blog\Api\Http\Resources\MetaBox;

use CSlant\Blog\Core\Models\MetaBox;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MetaBox
 */
class MetaBoxResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var MetaBox $this */
        return [
            'meta_key' => $this->meta_key,
            'meta_value' => $this->meta_value,
            'reference_id' => $this->reference_id,
            'reference_type' => $this->reference_type,
        ];
    }
}
