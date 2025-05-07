<?php

namespace CSlant\Blog\Api\Providers;

use CSlant\Blog\Api\Http\Middlewares\ApiActionRateLimiter;
use Illuminate\Routing\Router;
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

        $this->registerMiddlewares();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfigs();
    }

    /**
     * Register configs.
     *
     * @return void
     */
    protected function registerConfigs(): void
    {
        $configDir = __DIR__.'/../../config';
        $files = scandir($configDir);

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $configName = pathinfo($file, PATHINFO_FILENAME);
                $configPath = $configDir.'/'.$file;

                if (file_exists(config_path($configName.'.php'))) {
                    config()->set($configName, array_merge(
                        is_array(config($configName)) ? config($configName) : [],
                        require $configPath
                    ));
                } else {
                    $this->mergeConfigFrom($configPath, $configName);
                }
            }
        }
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

    /**
     * Register middlewares for the package.
     */
    protected function registerMiddlewares(): void
    {
        /** @var Router $router */
        $router = $this->app->make('router');

        // Register route middleware
        $router->aliasMiddleware('api-action-rate-limiter', ApiActionRateLimiter::class);
    }
}
