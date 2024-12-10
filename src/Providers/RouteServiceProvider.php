<?php

namespace CSlant\Blog\ApiPackage\Providers;

use CSlant\Blog\Core\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Move base routes to a service provider to make sure all filters & actions can hook to base routes
     */
    public function boot(): void
    {
        // add rate limit for api
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for((string) config('blog-api.defaults.route_prefix'), function (Request $request) {
            /** @var User|null $user */
            $user = $request->user();
            $identifier = $user ? $user->id : $request->ip();

            return Limit::perMinute(40)->by($identifier);
        });
    }
}
