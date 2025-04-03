<?php

namespace CSlant\Blog\Api\Http\Requests\Author;

use CSlant\Blog\Api\Http\Requests\JsonFormRequest;

class AuthorGetListRequest extends JsonFormRequest
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
            'is_super' => 'nullable|digits_between:0,1',
            'page' => 'nullable|numeric|between:1,100',
            'order_by' => 'nullable|string',
            'order' => 'nullable|string|in:asc,desc,ASC,DESC',
            'per_page' => 'nullable|numeric',
        ];
    }
}
