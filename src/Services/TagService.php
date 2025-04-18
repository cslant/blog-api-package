<?php

namespace CSlant\Blog\Api\Services;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Language\Facades\Language;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class TagService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class TagService
{
    /**
     * Get tags by filters.
     *
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<int, Tag>
     */
    public function getFilters(array $filters): LengthAwarePaginator
    {
        $data = Tag::query()
            ->withCount('posts');

        if ($filters['search'] !== null) {
            $keyword = isset($filters['search']) ? (string) $filters['search'] : null;
            $data = $this->search($data, $keyword);
        }

        $data = $data
            ->wherePublished()
            ->orderBy(
                Arr::get($filters, 'order_by', 'posts_count'),
                Arr::get($filters, 'order', 'desc')
            );

        return $data->paginate((int) $filters['per_page']);
    }

    /**
     * @param  BaseQueryBuilder|Builder<Model>  $model
     * @param  null|string  $keyword
     *
     * @return BaseQueryBuilder|Builder<Model>
     */
    protected function search(Builder|BaseQueryBuilder $model, ?string $keyword): Builder|BaseQueryBuilder
    {
        if (!$model instanceof BaseQueryBuilder || !$keyword) {
            return $model;
        }

        if (is_plugin_active('language')
            && is_plugin_active('language-advanced')
            && Language::getCurrentLocale() != Language::getDefaultLocale()
        ) {
            return $model
                ->whereHas('translations', function (BaseQueryBuilder $query) use ($keyword): void {
                    $query->addSearch('name', $keyword, false, false);
                });
        }

        return $model
            ->where(function (BaseQueryBuilder $query) use ($keyword): void {
                $query->addSearch('name', $keyword, false, false);
            });
    }
}
