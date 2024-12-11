<?php

use Illuminate\Support\Facades\Route;

// Resourceful routes
if (!function_exists('registerResourceRoutes')) {
    /**
     * Register dynamic resource routes with optional checks for method existence.
     *
     * @param string $resourceName
     * @param string $controller
     * @param array $middleware
     * @return void
     * @throws Exception
     */
    function registerResourceRoutes(string $resourceName, string $controller, $middleware = [])
    {
        if (!class_exists($controller)) {
            throw new Exception("Controller '{$controller}' does not exist.");
        }

        if (!is_array($middleware)) {
            $middleware = [$middleware];
        }

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

        return Route::middleware($middleware)->group(function () use ($routes, $controller, $resourceName) {
                Route::prefix($resourceName)->group(function () use ($routes, $controller, $resourceName) {
                    foreach ($routes as $method => $route) {
                        if (!method_exists($controller, $method)) {
                            throw new Exception("Method '{$method}' does not exist in controller '{$controller}'.");
                        }
                        Route::match([$route['method']], $route['uri'], [$controller, $method])->middleware([
                            "role_or_permission:super-admin|{$resourceName} {$method}|manage {$resourceName}"
                        ]);
                        \Log::info("Route: {$route['method']} \n{$route['uri']} -> {$controller}@{$method} \nMiddleware: permission:{$resourceName} {$method}|manage {$resourceName}");
                    }
                });
        });
    }
}

// Non-resourceful routes
if (!function_exists('registerResourceRoute')){
    /**
     * Register dynamic resource routes with optional checks for method existence.
     *
     * @param string $routeName
     * @param string $controller          
     * @param string $method       default: "get"
     * @param string $uri          default: ""
     * @param array $middleware    default: []
     * @return Route
     * @throws Exception
     */
    function registerRoute(
        string $routeName, 
        string $controller, 
        string $method = 'get', 
        string $uri = '',
        $middleware = []
    )
    {
        if (!class_exists($controller)) {
            throw new Exception("Controller '{$controller}' does not exist.");
        }
        if (!method_exists($controller, $routeName)) {
            throw new Exception("Method '{$routeName}' does not exist in controller '{$controller}'.");
        }
        if (!is_array($middleware)) {
            $middleware = [$middleware];
        }
        // API Endpoint Equivalent: /api/{routeName}
        return Route::match([$method], $uri, [$controller, $routeName])->middleware($middleware);
    }
}

if (!function_exists('registerRoutes')) {
    /**
     * Register dynamic resource routes with optional checks for method existence.
     *
     * @param array $routes
     * @param string $prefix
     *      @param string $routeName
     *      @param string $controller          
     *      @param string $method       default: "get"
     *      @param string $uri          default: ""
     *      @param array $middleware    default: []
     * @return Route
     * @throws Exception
     */
    function registerRoutes(
        array $routes, 
        string $prefix = '',

    )
    {
        if (!is_array($routes)) {
            $routes = [$routes];
        }
        return Route::prefix($prefix)->group(function () use ($routes) {
            foreach ($routes as $route) {
                if (!is_array($route)) {
                    $route = [$route];
                }
                if (!isset($route['routeName']) || !isset($route['controller'])) {
                    \Log::warning('Route name or controller is not set.');
                    continue;
                }

                registerRoute(
                    $route['routeName'], 
                    $route['controller'], 
                    $route['method'] ?? 'get', 
                    $route['uri'] ?? '',
                    $route['middleware'] ?? []
                );
            }
        });
    }
}