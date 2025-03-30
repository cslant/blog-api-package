<?php

namespace CSlant\Blog\Api\Providers;

use Illuminate\Support\ServiceProvider;

class BlogApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $routePath = __DIR__.'/../../routes/blog-api.php';
        if (file_exists($routePath)) {
            $this->loadRoutesFrom($routePath);
        }

        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'blog-api');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->registerCommands();

        $this->registerAssetPublishing();

        $this->resourceOverride();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = __DIR__.'/../../config/blog-api.php';
        $this->mergeConfigFrom($configPath, 'blog-api');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return null|array<string>
     */
    public function provides(): ?array
    {
        return ['blog-api'];
    }

    /**
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            //
        ]);
    }

    /**
     * @return void
     */
    protected function registerAssetPublishing(): void
    {
        $configPath = __DIR__.'/../../config/blog-api.php';
        $this->publishes([
            $configPath => config_path('blog-api.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../lang' => resource_path('lang/packages/blog-api'),
        ], 'lang');
    }

    /**
     * Override resource of the package.
     */
    public function resourceOverride(): void
    {
        if (!class_exists(\Botble\Blog\Http\Resources\TagResource::class, false)) {
            class_alias(
                \CSlant\Blog\Api\Http\Resources\Tag\TagResource::class,
                \Botble\Blog\Http\Resources\TagResource::class
            );
        }
    }
}
