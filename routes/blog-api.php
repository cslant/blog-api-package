<?php

use CSlant\Blog\Api\Http\Actions\Post\GetByTagAction;
use CSlant\Blog\Api\Http\Actions\Author\AuthorAction;
use CSlant\Blog\Api\Http\Actions\Post\ViewCountAction;
use CSlant\Blog\Api\Http\Controllers\CategoryController;
use CSlant\Blog\Api\Http\Controllers\MetaBoxController;
use CSlant\Blog\Api\Http\Controllers\PostController;
use CSlant\Blog\Api\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register bot routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Enjoy building your API!
|
*/

$routePrefix = config('blog-api.defaults.route_prefix');

Route::prefix($routePrefix)->name("$routePrefix.")->middleware('api')->group(function () {
    Route::get('/', fn () => response()->json(['message' => 'Welcome to CSlant Blog API']));

    Route::get('search', [PostController::class, 'getSearch']);

    Route::group(['prefix' => 'authors'], function () {
        Route::get('/{authorId}', AuthorAction::class);
    });

    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('filters', [PostController::class, 'getFilters']);
        Route::get('get-by-tags', GetByTagAction::class);
        Route::get('{slug}', [PostController::class, 'findBySlug']);
        Route::get('{slug}/view-count', ViewCountAction::class);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('filters', [CategoryController::class, 'getFilters']);
        Route::get('{slug}', [CategoryController::class, 'findBySlug']);
    });

    Route::group(['prefix' => 'tags'], function () {
        Route::get('/', [TagController::class, 'index']);
        Route::get('{slug}', [TagController::class, 'findBySlug']);
    });

    Route::group(['prefix' => 'meta-box'], function () {
        Route::get('{model}/{modelSlug}/{lang?}', [MetaBoxController::class, 'getMetaBoxBySlugModel']);
    });
});
