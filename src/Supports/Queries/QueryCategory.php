<?php

namespace CSlant\Blog\Api\Supports\Queries;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Language\Facades\Language;
use CSlant\Blog\Core\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class QueryCategory
{
    /**
     * @param  BaseQueryBuilder|Builder|Category  $query
     * @param  array<string, mixed>  $filters
     *
     * @return BaseQueryBuilder|Builder|Category
     */
    public static function setBaseCustomFilterQuery(
        Builder|BaseQueryBuilder|Category $query,
        array $filters
    ): Builder|BaseQueryBuilder|Category {
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
    protected static function search(Builder|BaseQueryBuilder $model, ?string $keyword): Builder|BaseQueryBuilder
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
