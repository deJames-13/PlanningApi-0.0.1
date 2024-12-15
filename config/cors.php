<?php

return [

    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL', 'https://planning-dashboard.vercel.app'), 'http://localhost:3000'],
    // 'allowed_origins' => ['https://planning-dashboard.vercel.app'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];