<?php

$routePrefix = env('BLOG_API_ROUTE_PREFIX', 'api');

return [
    'defaults' => [
        /* Set route prefix for the blog API */
        'route_prefix' => $routePrefix,
    ],
];
