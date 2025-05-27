<?php

use App\Http\Controllers\Api\SsoApiController;
use App\Http\Controllers\SsoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// SSO OAuth endpoints - tanpa CSRF middleware
Route::prefix('v1')->group(function () {
    Route::any('/authorize', [SsoController::class, 'oauthAuthorize'])->name('sso.authorize');
    Route::any('/token', [SsoController::class, 'oauthToken'])->name('sso.token');
    Route::any('/check', [SsoController::class, 'oauthCheck'])->name('sso.check');
});

// SSO Data API endpoints
Route::prefix('v1/data')->group(function () {
    // Endpoints that require client_secret (POST method)
    Route::post('/employees', [SsoApiController::class, 'getAllEmployees'])->name('api.employees.all');
    Route::post('/employees/by-role', [SsoApiController::class, 'getEmployeesByRole'])->name('api.employees.by-role');
    
    // Public endpoints (GET method, no client_secret required)
    Route::get('/roles', [SsoApiController::class, 'getAllRoles'])->name('api.roles.all');
    Route::get('/role-names', [SsoApiController::class, 'getRoleNames'])->name('api.roles.names');
});
