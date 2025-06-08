<?php

namespace CSlant\Blog\Api\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
                ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Cache-Control, Pragma')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        // Add CORS headers to response
        $response->headers->set('Access-Control-Allow-Origin', $this->getAllowedOrigin($request));
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH');
        $response->headers->set('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN, X-XSRF-TOKEN, Origin, Cache-Control, Pragma');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Expose-Headers', 'Cache-Control, Content-Language, Content-Type, Expires, Last-Modified, Pragma');

        return $response;
    }

    /**
     * Get allowed origin for the request.
     */
    private function getAllowedOrigin(Request $request): string
    {
        $origin = $request->headers->get('Origin');

        // Get environment-specific configurations
        $allowedOrigins = $this->getAllowedOrigins();

        // Check if origin is in allowed list
        if (in_array($origin, $allowedOrigins)) {
            return $origin;
        }

        // Check against patterns
        $allowedPatterns = $this->getAllowedPatterns();

        foreach ($allowedPatterns as $pattern) {
            if (preg_match($pattern, $origin)) {
                return $origin;
            }
        }

        // Default to first allowed origin or the origin itself if it matches basic security rules
        if ($this->isSecureOrigin($origin)) {
            return $origin;
        }

        return $allowedOrigins[0] ?? '*';
    }

    /**
     * Get all allowed origins based on environment.
     */
    private function getAllowedOrigins(): array
    {
        $baseOrigins = [
            // Environment-specific URLs from .env
            env('BLOG_FE_URL'),
            env('BLOG_ADMIN_URL'),
            env('BLOG_API_URL'),
            env('APP_URL'),

            // Development origins
            'http://localhost',
            'http://localhost:3000',
            'http://localhost:5173', // Vite default
            'http://127.0.0.1',
            'http://127.0.0.1:3000',

            // Development with .local domains
            'http://cslant.com.local',
            'http://cslant.com.local:81',

            // Production domains (without .local)
            'https://cslant.com',
            'https://api-docs.cslant.com',

            // Staging domains
            'https://staging.cslant.com',
        ];

        // Filter out null values and return unique origins
        return array_unique(array_filter($baseOrigins));
    }

    /**
     * Get allowed patterns for dynamic origin matching.
     */
    private function getAllowedPatterns(): array
    {
        return [
            // Development patterns (.local domains)
            '/^https?:\/\/[a-zA-Z0-9\-]+\.cslant\.com\.local(:\d+)?$/',
            '/^https?:\/\/[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-]+\.cslant\.com\.local(:\d+)?$/',

            // Production patterns (without .local)
            '/^https:\/\/[a-zA-Z0-9\-]+\.cslant\.com$/',
            '/^https:\/\/[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-]+\.cslant\.com$/',
            '/^http:\/\/[a-zA-Z0-9\-]+\.cslant\.com$/',
            '/^http:\/\/[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-]+\.cslant\.com$/',

            // Staging patterns
            '/^https?:\/\/[a-zA-Z0-9\-]+\.staging\.cslant\.com$/',
            '/^https?:\/\/[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-]+\.staging\.cslant\.com$/',

            // Localhost patterns with any port
            '/^https?:\/\/localhost(:\d+)?$/',
            '/^https?:\/\/127\.0\.0\.1(:\d+)?$/',
            '/^https?:\/\/0\.0\.0\.0(:\d+)?$/',

            // Custom domain patterns from environment
            $this->getCustomDomainPattern(),
        ];
    }

    /**
     * Get custom domain pattern from environment.
     */
    private function getCustomDomainPattern(): string
    {
        $customDomain = env('CORS_CUSTOM_DOMAIN_PATTERN');
        return $customDomain ?: '/^$/'; // Empty pattern if not set
    }

    /**
     * Check if origin is secure (basic security validation).
     */
    private function isSecureOrigin(?string $origin): bool
    {
        // Allow null origin (for mobile apps, Postman, etc.)
        if (empty($origin)) {
            return true;
        }

        // Must be a valid URL
        if (!filter_var($origin, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parsed = parse_url($origin);

        // Must have valid scheme
        if (!in_array($parsed['scheme'] ?? '', ['http', 'https'])) {
            return false;
        }

        // Block dangerous hosts
        $dangerousHosts = ['0.0.0.0', '255.255.255.255'];
        if (in_array($parsed['host'] ?? '', $dangerousHosts)) {
            return false;
        }

        return true;
    }
}
