<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CanManageUsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging
        Log::info('CanManageUsersMiddleware triggered', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'route' => $request->route()?->getName(),
            'url' => $request->url(),
            'method' => $request->method(),
        ]);

        if (!Auth::check()) {
            Log::warning('User not authenticated in CanManageUsersMiddleware');
            abort(403, 'Anda harus login untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        $canManage = $user->canManageUsers();
        
        // Debug user roles
        Log::info('User role check in CanManageUsersMiddleware', [
            'user_id' => $user->id,
            'user_roles' => $user->roles->pluck('name')->toArray(),
            'can_manage_users' => $canManage,
            'is_admin' => $user->isAdmin(),
            'has_umum' => $user->hasRole('umum'),
        ]);

        if (!$canManage) {
            Log::warning('User access denied in CanManageUsersMiddleware', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_roles' => $user->roles->pluck('name')->toArray(),
                'route' => $request->route()?->getName(),
            ]);
            
            // Jika request adalah AJAX atau expects JSON, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk mengakses fitur manajemen user.',
                    'error_code' => 'INSUFFICIENT_PERMISSIONS'
                ], 403);
            }
            
            // Redirect ke home dengan error message
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses fitur manajemen user. Hanya admin dan umum yang dapat mengakses halaman tersebut.')
                ->with('error_type', 'insufficient_permissions');
        }

        Log::info('User access granted in CanManageUsersMiddleware', [
            'user_id' => $user->id,
            'user_email' => $user->email,
        ]);

        return $next($request);
    }
}
