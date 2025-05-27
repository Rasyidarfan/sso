<?php

use App\Http\Controllers\ClientAppController;
use App\Http\Controllers\RoleController;
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

// Auth routes - disable registration
Auth::routes(['register' => false]);

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
    // Redirect based on authentication status
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('login');
    }
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// API Documentation route
Route::get('/docs', function () {
    return view('docs.index');
})->name('api.docs');

// SSO routes 
Route::prefix('v1')->group(function () {
    Route::any('/authorize', [SsoController::class, 'oauthAuthorize'])->name('sso.authorize');
    Route::any('/process-authorization', [SsoController::class, 'processAuthorization'])->name('sso.process');
    Route::any('/token', [SsoController::class, 'oauthToken'])->name('sso.token');
    Route::any('/check', [SsoController::class, 'oauthCheck'])->name('sso.check');
});

// Redirect from client apps
Route::get('/login/sso', [App\Http\Controllers\Auth\LoginController::class, 'ssoLogin'])->name('login.sso');

// User routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

    // Users index - semua user bisa akses (read-only untuk non-admin/umum)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Users edit - semua user bisa edit diri sendiri, admin/umum bisa edit semua
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Users management (admin and umum only)
    Route::middleware(['can.manage.users'])->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Client Apps (admin only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('client-apps', ClientAppController::class);
        Route::patch('/client-apps/{clientApp}/toggle-status', [ClientAppController::class, 'toggleStatus'])->name('client-apps.toggle-status');
        Route::post('/client-apps/{clientApp}/regenerate-secret', [ClientAppController::class, 'regenerateSecret'])->name('client-apps.regenerate-secret');
        
        // Roles management (admin only)
        Route::resource('roles', RoleController::class);
    });
});
