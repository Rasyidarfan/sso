<?php

namespace App\Http\Controllers;

use App\Models\AuthCode;
use App\Models\ClientApp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SsoController extends Controller
{
    /**
     * Authorize endpoint
     */
    public function authorize(Request $request)
    {
        // Check for correct request method
        if ($request->method() != 'GET') {
            return response()->json([
                'status' => 'error',
                'message' => 'Method tidak diizinkan, gunakan GET request',
                'error_code' => 'METHOD_NOT_ALLOWED'
            ], 405);
        }
        
        // Validate required parameters for direct API access
        $validator = Validator::make($request->all(), [
            'client_id' => 'required_without:sso_client_id|string',
        ]);

        if ($validator->fails() && !session('sso_client_id')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter client_id diperlukan',
                'errors' => $validator->errors(),
                'error_code' => 'MISSING_PARAMETERS'
            ], 400);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            // If this is an AJAX request or expects JSON, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Otentikasi diperlukan',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // Otherwise redirect to login
            return redirect()->route('login');
        }

        // Get client ID from session or direct request
        $clientId = session('sso_client_id') ?? $request->client_id;
        $state = session('sso_state') ?? $request->state;

        if (!$clientId) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Client ID tidak ditemukan',
                    'error_code' => 'CLIENT_ID_NOT_FOUND'
                ], 400);
            }
            
            return redirect()->route('home')
                ->with('error', 'Client ID tidak ditemukan');
        }

        // Check if client app exists
        $clientApp = ClientApp::where('client_id', $clientId)
            ->where('is_active', true)
            ->first();

        if (!$clientApp) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aplikasi tidak terdaftar atau tidak aktif',
                    'error_code' => 'INVALID_CLIENT'
                ], 401);
            }
            
            return redirect()->route('home')
                ->with('error', 'Aplikasi tidak terdaftar atau tidak aktif');
        }

        // Generate authorization code
        $authCode = AuthCode::generate($clientId, Auth::id(), $state);

        // Clear SSO session data
        session()->forget(['sso_client_id', 'sso_state']);

        // For API requests, return JSON instead of redirecting
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'code' => $authCode->code,
                'state' => $state,
                'redirect_uri' => $clientApp->redirect_uri,
            ]);
        }

        // Redirect to client app with code
        $redirectUri = $clientApp->redirect_uri;
        $redirectParams = [
            'code' => $authCode->code
        ];

        // Add state if it was provided
        if ($state) {
            $redirectParams['state'] = $state;
        }

        $redirectUrl = $redirectUri . (parse_url($redirectUri, PHP_URL_QUERY) ? '&' : '?') . http_build_query($redirectParams);

        return redirect($redirectUrl);
    }

    /**
     * Token endpoint
     */
    public function token(Request $request)
    {
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

        if (!$authCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authorization code tidak ditemukan',
                'error_code' => 'INVALID_GRANT'
            ], 400);
        }
        
        if (!$authCode->isValid()) {
            $invalidReason = $authCode->used_at ? 'sudah digunakan' : 'expired';
            return response()->json([
                'status' => 'error',
                'message' => 'Authorization code ' . $invalidReason,
                'error_code' => 'INVALID_GRANT',
                'expired_at' => $authCode->expires_at,
                'used_at' => $authCode->used_at
            ], 400);
        }

        // Mark code as used
        $authCode->markAsUsed();

        // Get user data
        $user = $authCode->user;
        
        // Verify user is active
        if (!$user || !$user->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak aktif',
                'error_code' => 'INACTIVE_USER'
            ], 403);
        }
        
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
    }
}
