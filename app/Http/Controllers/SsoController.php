<?php

namespace App\Http\Controllers;

use App\Models\AuthCode;
use App\Models\ClientApp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SsoController extends Controller
{
    public function oauthAuthorize(Request $request)
    {
        // Check for correct request method
        if ($request->method() != 'GET') {
            return response()->json([
                'status' => 'error',
                'message' => 'Method tidak diizinkan, gunakan GET request',
                'error_code' => 'METHOD_NOT_ALLOWED'
            ], 405);
        }
        
        // Validate required parameters
        if (!$request->client_id) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter client_id diperlukan',
                    'error_code' => 'MISSING_CLIENT_ID'
                ], 400);
            }
            
            return redirect()->route('login')
                ->with('error', 'Parameter client_id tidak ditemukan');
        }
        
        // Check if client app exists and is active
        $clientApp = ClientApp::where('client_id', $request->client_id)
            ->where('is_active', true)
            ->first();
        
        if (!$clientApp) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aplikasi tidak terdaftar atau tidak aktif',
                    'error_code' => 'INVALID_CLIENT'
                ], 400);
            }
            
            return redirect()->route('login')
                ->with('error', 'Aplikasi tidak terdaftar atau tidak aktif');
        }
        
        // SELALU redirect ke login dengan parameters - tidak peduli status authentication
        // Jika ada user yang sudah login, logout dulu untuk memastikan fresh login
        if (Auth::check()) {
            Auth::logout();
            // Regenerate session untuk security
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        
        $loginUrl = route('login') . '?' . http_build_query([
            'client_id' => $request->client_id,
            'state' => $request->state,
            'redirect_after_login' => 'sso_authorize',
            'force_login' => 'true',
            'client_name' => $clientApp->name
        ]);
        
        return redirect($loginUrl);
    }

    /**
     * Handle actual OAuth authorization after successful login
     */
    public function processAuthorization(Request $request)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Validate required parameters
        if (!$request->client_id) {
            return redirect()->route('login')
                ->with('error', 'Parameter client_id tidak ditemukan');
        }
        
        // Check if authenticated user is active
        $user = Auth::user();
        
        // Validasi status user
        if (!$user || !isset($user->is_active) || !$user->is_active) {
            // Log inactive user attempt
            Log::warning('Inactive or invalid user attempted SSO authorization', [
                'user_id' => $user->id ?? 'unknown',
                'user_email' => $user->email ?? 'unknown',
                'user_status' => $user->is_active ?? 'unknown',
                'client_id' => $request->client_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);
            
            // Logout the user jika ada session aktif
            if (Auth::check()) {
                Auth::logout();
            }
            
            // Redirect to login with comprehensive error message
            return redirect()->route('login')
                ->withErrors([
                    'account_status' => 'Akun Anda tidak aktif atau telah dinonaktifkan. Silakan hubungi administrator untuk mengaktifkan kembali akun Anda.'
                ])
                ->with('error', 'Akun tidak aktif')
                ->with('error_type', 'account_inactive');
        }
        
        // Get client ID and state directly from request
        $clientId = $request->client_id;
        $state = $request->state;
        
        // Check if client app exists and is active
        $clientApp = ClientApp::where('client_id', $clientId)
            ->where('is_active', true)
            ->first();
        
        if (!$clientApp) {
            return redirect()->route('login')
                ->with('error', 'Aplikasi tidak terdaftar atau tidak aktif');
        }
        
        // Generate authorization code
        $authCode = AuthCode::generate($clientId, Auth::id(), $state);
        
        // Log successful authorization
        Log::info('SSO authorization successful', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'client_id' => $clientId,
            'client_app' => $clientApp->name,
            'auth_code' => $authCode->code,
            'ip_address' => $request->ip()
        ]);
        
        // Use environment-appropriate redirect URI
        $redirectUri = $clientApp->getRedirectUri();
        $redirectParams = [
            'code' => $authCode->code
        ];
        
        if ($state) {
            $redirectParams['state'] = $state;
        }
        
        $redirectUrl = $redirectUri . (parse_url($redirectUri, PHP_URL_QUERY) ? '&' : '?') . http_build_query($redirectParams);
        
        return redirect($redirectUrl);
    }

    public function oauthToken(Request $request)
    {
        try {
            // Log request untuk debugging
            Log::info('SSO Token Request', [
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'body' => $request->all()
            ]);

            // Check for correct request method
            if ($request->method() != 'POST') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Method tidak diizinkan, gunakan POST request',
                    'error_code' => 'METHOD_NOT_ALLOWED'
                ], 405);
            }
            
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'client_id' => 'required|string',
                'client_secret' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter tidak lengkap atau tidak valid',
                    'errors' => $validator->errors()->toArray(),
                    'error_code' => 'INVALID_REQUEST'
                ], 400);
            }

            // Verify client credentials
            $clientApp = ClientApp::where('client_id', $request->client_id)
                ->where('is_active', true)
                ->first();

            if (!$clientApp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Client ID tidak valid atau aplikasi tidak aktif',
                    'error_code' => 'INVALID_CLIENT'
                ], 401);
            }
                
            if ($clientApp->client_secret !== $request->client_secret) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Client Secret tidak valid',
                    'error_code' => 'INVALID_CLIENT_SECRET'
                ], 401);
            }
            
            // Verify authorization code
            $authCode = AuthCode::where('code', $request->code)
                ->where('client_id', $request->client_id)
                ->first();
            
            if (!$authCode || !$authCode->isValid()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authorization code tidak valid atau expired',
                    'error_code' => 'INVALID_GRANT'
                ], 400);
            }

            // Mark code as used
            $authCode->markAsUsed();

            // Get user data
            $user = $authCode->user;
            
            // Get user roles
            $roles = $user->roles->pluck('name')->toArray();

            // Return user data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_id' => $user->nip9,
                    'name' => $user->name,
                    'nip_9' => $user->nip9,
                    'nip_18' => $user->nip16,
                    'email' => $user->email,
                    'roles' => $roles,
                ],
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('SSO Token Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal server',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    public function oauthCheck(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter tidak lengkap atau tidak valid',
                    'errors' => $validator->errors()->toArray(),
                    'error_code' => 'INVALID_REQUEST'
                ], 400);
            }

            $authCode = AuthCode::where('code', $request->code)->first();
            
            if (!$authCode || !$authCode->isValid()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authorization code tidak valid atau expired',
                    'error_code' => 'INVALID_GRANT'
                ], 400);
            }

            $authCode->markAsUsed();
            $user = $authCode->user;
            $roles = $user->roles->pluck('name')->toArray();

            // Return user data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_id' => $user->nip9,
                    'name' => $user->name,
                    'nip_9' => $user->nip9,
                    'nip_18' => $user->nip16,
                    'email' => $user->email,
                    'roles' => $roles,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('SSO Check Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal server',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }
}
