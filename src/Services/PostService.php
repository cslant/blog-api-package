<?php

namespace CSlant\Blog\Api\Services;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Language\Facades\Language;
use CSlant\Blog\Core\Http\Responses\Base\BaseHttpResponse;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class PostService
 *
 * @package CSlant\Blog\Api\Services
 *
 * @method BaseHttpResponse httpResponse()
 */
class PostService
{
    /**
     * @param  array<string, mixed>  $filters
     *
     * @return Builder|BaseQueryBuilder|Post
     */
    public function setBaseCustomFilterQuery(array $filters): Builder|BaseQueryBuilder|Post
    {
        $query = Post::query()->withCount(['comments', 'likes'])->with(['comments', 'likes']);

        if ($filters['tags'] !== null) {
            $tags = array_filter((array) $filters['tags']);

            $query = $query->whereHas('tags', function (Builder $query) use ($tags): void {
                $query->whereIn('tags.id', $tags);
            });
        }

        if ($filters['categories'] !== null) {
            $categories = array_filter((array) $filters['categories']);

            $query = $query->whereHas('categories', function (Builder $query) use ($categories): void {
                $query->whereIn('categories.id', $categories);
            });
        }

        if ($filters['categories_exclude'] !== null) {
            $query = $query
                ->whereHas('categories', function (Builder $query) use ($filters): void {
                    $query->whereNotIn('categories.id', array_filter((array) $filters['categories_exclude']));
                });
        }

        if ($filters['exclude'] !== null) {
            $query = $query->whereNotIn('id', array_filter((array) $filters['exclude']));
        }

        if ($filters['include'] !== null) {
            $query = $query->whereNotIn('id', array_filter((array) $filters['include']));
        }

        if ($filters['author'] !== null) {
            $query = $query->whereIn('author_id', array_filter((array) $filters['author']));
        }

        if ($filters['author_exclude'] !== null) {
            $query = $query->whereNotIn('author_id', array_filter((array) $filters['author_exclude']));
        }

        if ($filters['featured'] !== null) {
            $query = $query->where('is_featured', $filters['featured']);
        }

        if ($filters['search'] !== null) {
            $keyword = isset($filters['search']) ? (string) $filters['search'] : null;
            $query = $this->search($query, $keyword);
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     *
     * @return LengthAwarePaginator<int, Post>
     */
    public function getCustomFilters(array $filters): LengthAwarePaginator
    {
        $query = $this->setBaseCustomFilterQuery($filters);

        $data = $query
            ->wherePublished()
            ->orderBy(
                Arr::get($filters, 'order_by', 'updated_at'),
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
                    $query->addSearch('name', $keyword, false, false)
                        ->addSearch('description', $keyword, false);
                });
        }

        return $model
            ->where(function (BaseQueryBuilder $query) use ($keyword): void {
                $query->addSearch('name', $keyword, false, false)
                    ->addSearch('description', $keyword, false);
            });
    }
}
