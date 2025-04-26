<?php

namespace CSlant\Blog\Api\Supports\Queries;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Language\Facades\Language;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class QueryPost
{
    /**
     * @param  Builder|BaseQueryBuilder  $query
     * @param  array<string, mixed>  $filters
     *
     * @return Builder|BaseQueryBuilder|Post
     */
    public static function setBaseCustomFilterQuery(
        Builder|BaseQueryBuilder $query,
        array $filters
    ): Builder|BaseQueryBuilder|Post {
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
            $query = self::search($query, $keyword);
        }

        return $query;
    }

    /**
     * @param  BaseQueryBuilder|Builder<Model>  $model
     * @param  null|string  $keyword
     *
     * @return BaseQueryBuilder|Builder<Model>
     */
    public static function search(Builder|BaseQueryBuilder $model, ?string $keyword): Builder|BaseQueryBuilder
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
