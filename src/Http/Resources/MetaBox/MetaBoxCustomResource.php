<?php

namespace CSlant\Blog\Api\Http\Resources\MetaBox;

use CSlant\Blog\Core\Facades\Base\Media\RvMedia;
use CSlant\Blog\Core\Models\MetaBox;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MetaBox
 */
class MetaBoxCustomResource extends JsonResource
{
    /**
     * @param  Request  $request
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var MetaBox $this */

        $metaValueCustom = is_array($this->meta_value) && count($this->meta_value) == 1
            ? $this->meta_value[0]
            : $this->meta_value;
        $reference = $this->reference;

        if (is_array($metaValueCustom)) {
            $metaValueCustom['seo_title'] = $metaValueCustom['seo_title']
                ?? (property_exists($reference, 'name') ? substr($reference->name, 0, 65) : null);
            $metaValueCustom['seo_description'] = $metaValueCustom['seo_description']
                ?? (property_exists($reference, 'description') ? substr($reference->description, 0, 300) : null);
            $metaValueCustom['seo_image'] = isset($metaValueCustom['seo_image']) && !empty($metaValueCustom['seo_image'])
                ? ( RvMedia::getImageUrl($metaValueCustom['seo_image'], '', false, RvMedia::getDefaultImage()) ?? '') : null;
        }

        return [
            'meta_key' => $this->meta_key,
            'meta_value' => $this->meta_value,
            'meta_value_custom' => $metaValueCustom,
            //'reference_id' => $this->reference_id,
            //'reference_type' => $this->reference_type,
        ];
    }
}
