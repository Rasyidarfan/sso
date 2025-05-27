<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ClientApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SsoApiController extends Controller
{
    /**
     * Validate client secret
     */
    private function validateClientSecret(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'response' => response()->json([
                    'status' => 'error',
                    'message' => 'Client secret diperlukan',
                    'errors' => $validator->errors()->toArray(),
                    'error_code' => 'MISSING_CLIENT_SECRET'
                ], 400)
            ];
        }

        // Check if client secret exists in registered apps
        $clientApp = ClientApp::where('client_secret', $request->client_secret)
            ->where('is_active', true)
            ->first();

        if (!$clientApp) {
            return [
                'valid' => false,
                'response' => response()->json([
                    'status' => 'error',
                    'message' => 'Client secret tidak valid atau aplikasi tidak aktif',
                    'error_code' => 'INVALID_CLIENT_SECRET'
                ], 401)
            ];
        }

        return [
            'valid' => true,
            'client_app' => $clientApp
        ];
    }

    /**
     * Get all active employees data
     * Requires client_secret in POST body
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllEmployees(Request $request)
    {
        try {
            // Validate client secret
            $validation = $this->validateClientSecret($request);
            if (!$validation['valid']) {
                return $validation['response'];
            }

            $users = User::with('roles')
                ->where('is_active', true)
                ->get()
                ->map(function ($user) {
                    return [
                        'nip_9' => $user->nip9,
                        'nip_18' => $user->nip16,
                        'name' => $user->name,
                        'email' => $user->email,
                        'roles' => $user->roles->pluck('name')->toArray(),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil diambil',
                'data' => $users,
                'total' => $users->count(),
                'requested_by' => $validation['client_app']->name
            ]);

        } catch (\Exception $e) {
            Log::error('SSO API Get All Employees Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data pegawai',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get all roles
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRoles()
    {
        try {
            $roles = Role::orderBy('name')
                ->get()
                ->map(function ($role) {
                    return [
                        'name' => $role->name,
                        'description' => $role->description,
                        'user_count' => $role->users()->where('is_active', true)->count()
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Data role berhasil diambil',
                'data' => $roles,
                'total' => $roles->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data role',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get employees by specific role
     * Requires client_secret in POST body
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeesByRole(Request $request)
    {
        try {
            // Validate client secret first
            $validation = $this->validateClientSecret($request);
            if (!$validation['valid']) {
                return $validation['response'];
            }

            // Validate role parameter
            $validator = Validator::make($request->all(), [
                'role' => 'required|string',
                'client_secret' => 'required|string', // Already validated above but include for completeness
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter tidak valid',
                    'errors' => $validator->errors()->toArray(),
                    'error_code' => 'INVALID_REQUEST'
                ], 400);
            }

            $roleName = strtolower($request->role);

            // Check if role exists
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Role tidak ditemukan',
                    'error_code' => 'ROLE_NOT_FOUND'
                ], 404);
            }

            $users = User::with('roles')
                ->where('is_active', true)
                ->whereHas('roles', function($query) use ($roleName) {
                    $query->where('name', $roleName);
                })
                ->get()
                ->map(function ($user) {
                    return [
                        'nip_9' => $user->nip9,
                        'nip_18' => $user->nip16,
                        'name' => $user->name,
                        'email' => $user->email,
                        'roles' => $user->roles->pluck('name')->toArray(),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => "Data pegawai dengan role '{$role->name}' berhasil diambil",
                'data' => $users,
                'role_info' => [
                    'name' => $role->name,
                    'description' => $role->description
                ],
                'total' => $users->count(),
                'requested_by' => $validation['client_app']->name
            ]);

        } catch (\Exception $e) {
            Log::error('SSO API Get Employees by Role Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data pegawai',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get role names only (simple list)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleNames()
    {
        try {
            $roleNames = Role::orderBy('name')->pluck('name')->toArray();

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar nama role berhasil diambil',
                'data' => $roleNames,
                'total' => count($roleNames)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil daftar role',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }
}
