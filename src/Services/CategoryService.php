<?php

namespace CSlant\Blog\Api\Services;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Language\Facades\Language;
use CSlant\Blog\Api\Supports\Queries\QueryCategory;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class CategoryService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class CategoryService
{
    /**
     * Get categories by filters.
     *
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<int, Category>
     */
    public function getCustomFilters(array $filters): LengthAwarePaginator
    {
        $query = Category::query()
            ->withCount('posts');

        $query = QueryCategory::setBaseCustomFilterQuery($query, $filters);

        $query = $query
            ->wherePublished()
            ->orderBy(
                Arr::get($filters, 'order_by', 'posts_count'),
                Arr::get($filters, 'order', 'desc')
            );

        return $query->paginate($filters['per_page']);
    }
}
