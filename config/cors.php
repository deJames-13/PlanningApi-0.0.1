<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'api/login', 'api/logout', 'api/refresh'],
    'allowed_methods' => ['*'],
    // 'allowed_origins' => [env('FRONTEND_URL', '*'), 'http://localhost:3000'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];