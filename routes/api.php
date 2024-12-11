<?php


$REQUIRE_AUTH = config('app.debug') ? 'auth:sanctum' : 'auth:sanctum';
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
        'middleware' => ['role:super-admin']
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
        'middleware' => ['role:admin|super-admin']
    ],
    'sectors' => [
        'controller'=>App\Http\Controllers\Api\SectorController::class,
        'middleware' => ['role:admin|super-admin']
    ],
    'objectives' => [
        'controller'=>App\Http\Controllers\Api\ObjectiveController::class,
        'middleware' => ['role:user|admin|super-admin']
    ],
    'budgets' => [
        'controller'=>App\Http\Controllers\Api\BudgetController::class,
        'middleware' => ['role:user|admin|super-admin']
    ],
    'budget-annual' => [
        'controller'=>App\Http\Controllers\Api\BudgetAnnualController::class,
        'middleware' => ['role:user|admin|super-admin']
    ],
    'bar-data' => [
        'controller'=>App\Http\Controllers\Api\BarDataController::class,
        'middleware' => ['role:user|admin|super-admin']
    ],
    'particular' => [
        'controller'=>App\Http\Controllers\Api\ParticularController::class,
        'middleware' => ['role:user|admin|super-admin']
    ],
    'particular-value' => [
        'controller'=>App\Http\Controllers\Api\ParticularValueController::class,
        'middleware' => ['role:user|admin|super-admin']
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
})->middleware($REQUIRE_AUTH);




