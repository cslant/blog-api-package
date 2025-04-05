<?php

namespace CSlant\Blog\Api\Http\Middlewares;

use Closure;
use CSlant\Blog\Core\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;

class ApiActionRateLimiter
{
    /**
     * Handle an incoming request with configurable rate limiting.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $name Action identifier for rate limiting
     * @return Response|JsonResponse
     */
    public function handle(Request $request, Closure $next, string $name): Response|JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();
        $identifier = $user ? $user->id : $request->ip();
        $key = $name . ':' . $identifier;

        // Get max attempts from env variable
        $maxAttempts = (int) config('blog-core.blog_api_default_rate_limit', 50);

        if(RateLimiter::tooManyAttempts($key, $maxAttempts))
        {
            return $this->buildTooManyAttemptsResponse($maxAttempts);
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

    /**
     * Build a response for too many attempts.
     *
     * @param  int  $maxAttempts
     * @return JsonResponse
     */
    private function buildTooManyAttemptsResponse(int $maxAttempts): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => 'Too many attempts. Please try again later.',
            'maxAttempts' => $maxAttempts,
        ])->setStatusCode(429);
    }
}
