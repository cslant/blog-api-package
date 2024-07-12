<?php

use Botble\Blog\Http\Controllers\API\PostController;
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
    Route::get('/', fn() => response()->json(['message' => 'Welcome to Blog API']));

    Route::get('search', [PostController::class, 'getSearch']);

    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('filters', [PostController::class, 'getFilters']);
        Route::get('{slug}', [PostController::class, 'findBySlug']);
    });
});
