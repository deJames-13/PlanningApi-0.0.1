<?php

ini_set('max_execution_time', 300);
$REQUIRE_AUTH = config('app.auth_disabled') ? '' : 'jwt.auth';
\Log::info("REQUIRE_AUTH: $REQUIRE_AUTH");

/*
|--------------------------------------------------------------------------
| AUTHENTICATION (Laravel Sanctum) 
|--------------------------------------------------------------------------
*/

Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'authenticate']);
Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
Route::post('reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware($REQUIRE_AUTH);
Route::get('me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware($REQUIRE_AUTH);

Route::get('refresh', [App\Http\Controllers\Api\AuthController::class, 'refresh']);
Route::get('csrf', [App\Http\Controllers\Api\AuthController::class, 'csrf']);



/*
|--------------------------------------------------------------------------
| Resources 
|--------------------------------------------------------------------------
|
| CRUD resources to be registered. Requires the following structure:
| name => controller, middleware
|
|
*/

$resources = [
    'users' => [
        'controller'=>App\Http\Controllers\Api\UserController::class,
    ],
    /* TBC
    'roles' => [
        'controller'=>App\Http\Controllers\Api\UserController::class,
        'middleware' => ['role:super-admin']
    ],
    'permissions' => [
        'controller'=>App\Http\Controllers\Api\UserController::class,
        'middleware' => ['role:super-admin']
    ], 
    */
    'departments' => [
        'controller'=>App\Http\Controllers\Api\DepartmentController::class,
    ],
    'sectors' => [
        'controller'=>App\Http\Controllers\Api\SectorController::class,
    ],
    'objectives' => [
        'controller'=>App\Http\Controllers\Api\ObjectiveController::class,
    ],
    'budgets' => [
        'controller'=>App\Http\Controllers\Api\BudgetController::class,
    ],
    'budget-annual' => [
        'controller'=>App\Http\Controllers\Api\BudgetAnnualController::class,
    ],
    'bar-data' => [
        'controller'=>App\Http\Controllers\Api\BarDataController::class,
    ],
    'particular' => [
        'controller'=>App\Http\Controllers\Api\ParticularController::class,
    ],
    'particular-value' => [
        'controller'=>App\Http\Controllers\Api\ParticularValueController::class,
    ],
];

Route::middleware($REQUIRE_AUTH)->group(function () use($resources, $REQUIRE_AUTH) {
    foreach ($resources as $resource => $endpoint) {
        try {
            registerResourceRoutes($resource, ...$endpoint);
            \Log::info("Resource $resource registered successfully");
        } catch (\Throwable $th) {
            \Log::error("Error registering resource $resource: " . $th->getMessage());
        }
    }
});

/*
|--------------------------------------------------------------------------
| CHARTS (Public/Protected) 
|--------------------------------------------------------------------------
*/
Route::prefix('/charts')->group(function () use($REQUIRE_AUTH) {
    Route::get('bar1', [App\Http\Controllers\Api\ChartController::class, 'bar1']);
    Route::get('budgets', [App\Http\Controllers\Api\ChartController::class, 'budgets']);
    Route::get('objectives', [App\Http\Controllers\Api\ChartController::class, 'objectives']);
});



/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::prefix('public')->group(function(){
    Route::get('departments', [App\Http\Controllers\Api\DepartmentController::class, 'departmentNavList']);
    Route::get('sectors', [App\Http\Controllers\Api\SectorController::class, 'sectorList']);
});

