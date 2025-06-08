<?php

return [
    'defaults' => [
        /* Set route prefix for the blog API */
        'route_prefix' => env('BLOG_API_ROUTE_PREFIX', 'api'),
    ],

    'cors_custom_domain_pattern' => env('CORS_CUSTOM_DOMAIN_PATTERN', '/^$/'),
];
