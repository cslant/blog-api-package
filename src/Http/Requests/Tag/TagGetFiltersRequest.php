<?php

namespace CSlant\Blog\Api\Http\Requests\Tag;

use CSlant\Blog\Api\Http\Requests\JsonFormRequest;

class TagGetFiltersRequest extends JsonFormRequest
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
            'search' => 'nullable|string|max:255',
            'order_by' => 'nullable|string',
            'order' => 'nullable|string|in:asc,desc,ASC,DESC',
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric|between:1,100',
        ];
    }
}
