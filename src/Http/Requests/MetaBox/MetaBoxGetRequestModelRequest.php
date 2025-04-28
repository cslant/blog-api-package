<?php

namespace CSlant\Blog\Api\Http\Requests\MetaBox;

use CSlant\Blog\Api\Http\Requests\JsonFormRequest;

class MetaBoxGetRequestModelRequest extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'model' => 'required|string|in:post,page,category,tag',
            'slug' => 'required|string',
            'lang' => 'nullable|string|in:en,vi',
        ];
    }
}
