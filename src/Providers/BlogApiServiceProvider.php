<?php

namespace CSlant\BlogApi\Providers;

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

        $this->registerCommands();

        $this->registerAssetPublishing();
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
     * @return array<string>|null
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
}
