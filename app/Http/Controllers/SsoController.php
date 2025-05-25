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
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store client info in session and redirect to login
            session(['sso_client_id' => $request->client_id]);
            session(['sso_state' => $request->state]);
            
            return redirect()->route('login');
        }
        
        // Get client ID from session or direct request
        $clientId = session('sso_client_id') ?? $request->client_id;
        $state = session('sso_state') ?? $request->state;
        
        if (!$clientId) {
            return redirect()->route('home')
            ->with('error', 'Client ID tidak ditemukan');
        }
        
        // Check if client app exists
        $clientApp = ClientApp::where('client_id', $clientId)
        ->where('is_active', true)
        ->first();
        
        if (!$clientApp) {
            return redirect()->route('home')
            ->with('error', 'Aplikasi tidak terdaftar atau tidak aktif');
        }
        // Generate authorization code
        $authCode = AuthCode::generate($clientId, Auth::id(), $state);
        
        // Clear SSO session data
        session()->forget(['sso_client_id', 'sso_state']);
        
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
        // Check for correct request method
        // if ($request->method() != 'POST') {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Method tidak diizinkan, gunakan POST request',
        //         'error_code' => 'METHOD_NOT_ALLOWED'
        //     ], 405);
        // }
        
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
        // dd([!$authCode || !$authCode->isValid(),$authCode,$authCode->isValid()]);
        
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
    }

    public function oauthCheck(Request $request)
    {
        $authCode = AuthCode::where('code', $request->code)->first();
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
    }
}
