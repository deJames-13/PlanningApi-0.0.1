<?php

use Laravel\Sanctum\Sanctum;

return [

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1,https://planning-dev.vercel.app',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : '',
        env('FRONTEND_URL') ? ','.parse_url(env('FRONTEND_URL'), PHP_URL_HOST) : ''
    ))),

    'guard' => ['web'],

    'expiration' => null,

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        // 'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        // 'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
        // 'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ],

    'prefix' => 'sanctum',

    'domain' => env('SESSION_DOMAIN', 'localhost'),

    'path' => '/',

    'secure' => env('SESSION_SECURE_COOKIE', true),

    'same_site' => 'none',
    
    'partitioned' => true,

];