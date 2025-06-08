<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        env('BLOG_API_ROUTE_PREFIX', 'cs-api') . '/*',
        env('BLOG_API_ROUTE_PREFIX', 'cs-api') . '/sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
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
    ],

    'allowed_origins_patterns' => [
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
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Origin',
        'Cache-Control',
        'Pragma',
        'X-Forwarded-For',
        'X-Forwarded-Proto',
        'X-Forwarded-Host',
    ],

    'exposed_headers' => [
        'Cache-Control',
        'Content-Language',
        'Content-Type',
        'Expires',
        'Last-Modified',
        'Pragma',
    ],

    'max_age' => 86400, // 24 hours

    'supports_credentials' => true,
];
