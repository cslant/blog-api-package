<?php

namespace CSlant\Blog\Api\Http\Requests\Post;

use CSlant\Blog\Api\Http\Requests\JsonFormRequest;

class PostGetFiltersRequest extends JsonFormRequest
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
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|integer|exists:tags,id',

            'categories' => 'nullable|array',
            'categories.*' => 'nullable|integer|exists:categories,id',
            'categories_exclude' => 'nullable|array',
            'categories_exclude.*' => 'nullable|integer|exists:categories,id',

            'exclude' => 'nullable|array',
            'exclude.*' => 'nullable|integer|exists:posts,id',
            'include' => 'nullable|array',
            'include.*' => 'nullable|integer|exists:posts,id',

            'author' => 'nullable|array',
            'author.*' => 'nullable|integer|exists:users,id',
            'author_exclude' => 'nullable|array',
            'author_exclude.*' => 'nullable|integer|exists:users,id',

            'featured' => 'nullable|numeric|in:0,1',
            'search' => 'nullable|string|max:255',
            'order_by' => 'nullable|string',
            'order' => 'nullable|string|in:asc,desc,ASC,DESC',
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric|between:1,100',
        ];
    }
}
