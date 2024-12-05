<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('registerResourceRoutes')) {
    /**
     * Register dynamic resource routes with optional checks for method existence.
     *
     * @param string $resourceName
     * @param string $controller
     * @return void
     * @throws Exception
     */
    function registerResourceRoutes(string $resourceName, string $controller)
    {
        $routes = [
            'index' => ['method' => 'get', 'uri' => '/'],
            'thrashed' => ['method' => 'get', 'uri' => '/thrashed'],
            'all' => ['method' => 'get', 'uri' => '/all'],
            'show' => ['method' => 'get', 'uri' => '/{id}'],
            'store' => ['method' => 'post', 'uri' => '/'],
            'update' => ['method' => 'put', 'uri' => '/{id}'],
            'destroy' => ['method' => 'delete', 'uri' => '/{id}'],
            'restore' => ['method' => 'patch', 'uri' => '/{id}/restore'],
        ];

        return Route::prefix($resourceName)->group(function () use ($routes, $controller) {
            foreach ($routes as $method => $route) {
                if (!method_exists($controller, $method)) {
                    throw new Exception("Method '{$method}' does not exist in controller '{$controller}'.");
                }

                Route::match([$route['method']], $route['uri'], [$controller, $method]);
            }
        });
    }
}
