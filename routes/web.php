<?php

use App\Http\Controllers\ClientAppController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth routes
Auth::routes();

// Custom Logout Page
Route::get('/logout-success', function () {
    return view('auth.logout');
})->name('logout.success');

// Custom Logout Logic
Route::post('/logout-custom', function () {
    Auth::logout();
    return redirect()->route('logout.success');
})->name('logout.custom');

// Home route
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// API Documentation route
Route::get('/docs', function () {
    return view('docs.index');
})->name('api.docs');

// SSO routes 
Route::prefix('v1')->group(function () {
    // Authorize endpoint - accepts any method to handle error properly
    Route::any('/authorize', [SsoController::class, 'oauthAuthorize'])->name('sso.authorize');
    Route::any('/token', [SsoController::class, 'oauthToken'])->name('sso.token');
});

// Redirect from client apps
Route::get('/login/sso', [App\Http\Controllers\Auth\LoginController::class, 'ssoLogin'])->name('login.sso');

// User routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    // Users management (admin and umum only)
    Route::middleware(['can.manage.users'])->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Client Apps (admin only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('client-apps', ClientAppController::class);
        Route::patch('/client-apps/{clientApp}/toggle-status', [ClientAppController::class, 'toggleStatus'])->name('client-apps.toggle-status');
        Route::post('/client-apps/{clientApp}/regenerate-secret', [ClientAppController::class, 'regenerateSecret'])->name('client-apps.regenerate-secret');
    });
});
