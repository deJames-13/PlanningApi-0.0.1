<?php

use Illuminate\Support\Facades\Log;

$resources = [
    'users' => App\Http\Controllers\Api\UserController::class, 
    'departments' => App\Http\Controllers\Api\DepartmentController::class,
    'sectors' => App\Http\Controllers\Api\SectorController::class,
    'objectives' => App\Http\Controllers\Api\ObjectiveController::class,
    'budgets' => App\Http\Controllers\Api\BudgetController::class,
    'budget-annual' => App\Http\Controllers\Api\BudgetAnnualController::class,
    'bar-data' => App\Http\Controllers\Api\BarDataController::class,
    'particular' => App\Http\Controllers\Api\ParticularController::class,
    'particular-value' => App\Http\Controllers\Api\ParticularValueController::class,
];

foreach ($resources as $resource => $controller) {
    try {
        registerResourceRoutes($resource, $controller);
        Log::info("Resource $resource registered successfully");
    } catch (\Throwable $th) {
        Log::error("Error registering resource $resource: " . $th->getMessage());
    }
}

$charts = [
    [
        'routeName' => 'bar1',
        'controller' => App\Http\Controllers\Api\ChartController::class,
        'uri' => 'bar1',
    ],
    [
        'routeName' => 'budgets',
        'controller' => App\Http\Controllers\Api\ChartController::class,
        'uri' => 'budgets',
    ],
    [
        'routeName' => 'objectives',
        'controller' => App\Http\Controllers\Api\ChartController::class,
        'uri' => 'objectives',
    ]
];
try {
    registerRoutes($charts, 'charts');
    \Log::info("Routes for charts registered successfully");
} catch (\Throwable $th) {
    \Log::error("Error registering routes for charts: " . $th->getMessage());
}


