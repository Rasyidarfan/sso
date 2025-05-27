<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Hanya admin dan umum yang bisa create dan store user baru
        $this->middleware('can.manage.users')->only(['create', 'store']);
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        $canManageUsers = Auth::user()->canManageUsers();
        
        return view('users.index', compact('users', 'canManageUsers'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip9' => 'required|string|max:9|unique:users',
            'nip16' => 'required|string|max:18|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip9' => $request->nip9,
            'nip16' => $request->nip16,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Cek authorization: admin/umum bisa edit semua user, role lain hanya bisa edit diri sendiri
        if (!$this->canEditUser($user)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit user ini. Anda hanya dapat mengedit profil diri sendiri.');
        }

        $roles = Role::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        
        // Jika bukan admin/umum, hanya tampilkan form edit profil tanpa role
        $canManageRoles = Auth::user()->canManageUsers();
        
        return view('users.edit', compact('user', 'roles', 'userRoleIds', 'canManageRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Cek authorization: admin/umum bisa edit semua user, role lain hanya bisa edit diri sendiri
        if (!$this->canEditUser($user)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit user ini. Anda hanya dapat mengedit profil diri sendiri.');
        }

        $currentUser = Auth::user();
        $canManageRoles = $currentUser->canManageUsers();
        
        // Validation rules berbeda tergantung level akses
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip9' => ['required', 'string', 'max:9', Rule::unique('users')->ignore($user->id)],
            'nip16' => ['required', 'string', 'max:18', Rule::unique('users')->ignore($user->id)],
        ];
        
        // Hanya admin dan umum yang bisa mengubah roles
        if ($canManageRoles) {
            $validationRules['roles'] = 'required|array|min:1';
            $validationRules['roles.*'] = 'exists:roles,id';
        }

        $request->validate($validationRules);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip9' => $request->nip9,
            'nip16' => $request->nip16,
        ]);

        // Hanya admin dan umum yang bisa mengubah roles
        if ($canManageRoles && $request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        $redirectRoute = $canManageRoles ? 'users.index' : 'profile';
        return redirect()->route($redirectRoute)
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleStatus(User $user)
    {
        // Hanya admin dan umum yang bisa mengubah status user
        if (!Auth::user()->canManageUsers()) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah status user. Hanya admin dan umum yang dapat melakukan aksi ini.');
        }

        // Prevent deactivating own account
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'aktif' : 'nonaktif';
        return redirect()->route('users.index')
            ->with('success', "User berhasil diubah menjadi {$status}.");
    }

    /**
     * Remove the specified user from storage permanently.
     */
    public function destroy(User $user)
    {
        // Hanya admin dan umum yang bisa menghapus user
        if (!Auth::user()->canManageUsers()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus user. Hanya admin dan umum yang dapat melakukan aksi ini.');
        }

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting admin if current user is not admin
        if ($user->isAdmin() && !Auth::user()->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus user admin.');
        }

        $userName = $user->name;
        
        // Delete user relationships first
        $user->roles()->detach();
        
        // Delete the user
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$userName} berhasil dihapus permanen.");
    }

    /**
     * Show user's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Check if current user can edit the specified user.
     * Admin and umum can edit all users.
     * Other roles can only edit themselves.
     */
    private function canEditUser(User $user)
    {
        $currentUser = Auth::user();
        
        // Admin dan umum bisa edit semua user
        if ($currentUser->canManageUsers()) {
            return true;
        }
        
        // Role lain hanya bisa edit diri sendiri
        return $currentUser->id === $user->id;
    }
}
