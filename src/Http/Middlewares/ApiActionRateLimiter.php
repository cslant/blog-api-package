<?php

namespace CSlant\Blog\Api\Http\Middlewares;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;

class ApiActionRateLimiter
{
    /**
     * Handle an incoming request with configurable rate limiting.
     */
    public function handle(Request $request, Closure $next, string $name): Response|JsonResponse
    {
        $key = $this->resolveKey($request, $name);
        $maxAttempts = $this->resolveMaxAttempts($name);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'error' => true,
                'message' => 'Too many attempts. Please try again later.',
                'maxAttempts' => $maxAttempts,
            ], 429);
        }

        RateLimiter::hit($key);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);

        return $response;
    }

    private function resolveKey(Request $request, string $prefix): string
    {
        $identifier = $request->user()->id ?? $request->ip();

        return "{$prefix}:{$identifier}";
    }

    private function resolveMaxAttempts(string $name): int
    {
        return (int) config("blog-core.rate_limits.{$name}", config('blog-core.blog_api_default_rate_limit', 50));
    }
}
