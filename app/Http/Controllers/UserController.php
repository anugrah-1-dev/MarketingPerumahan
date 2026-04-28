<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * GET /admin/users
     * – Browser → tampilkan view
     * – AJAX (Accept: application/json) → return JSON list
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $users = $this->manageableUsersQuery()
                ->orderBy('created_at', 'desc')
                ->get(['id', 'name', 'email', 'role', 'created_at']);

            return response()->json($users);
        }

        $view = request()->routeIs('manager.*') ? 'manager.users' : 'admin.users';
        return view($view);
    }

    /**
     * POST /admin/users
     * Membuat user baru.
     */
    public function store(Request $request)
    {
        $allowedRoles = $this->allowedRoles();

        $request->validate([
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|max:150|unique:users,email',
            'password'          => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
            'role'              => ['required', Rule::in($allowedRoles)],
        ]);

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'role'               => $request->role,
        ]);

        return response()->json($user, 201);
    }

    /**
     * PUT /admin/users/{id}
     * Memperbarui data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorizeManageUser($user);
        $allowedRoles = $this->allowedRoles();

        $request->validate([
            'name'              => 'required|string|max:100',
            'email'             => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($id)],
            'password'          => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()],
            'role'              => ['required', Rule::in($allowedRoles)],
        ]);

        $user->name               = $request->name;
        $user->email              = $request->email;
        $user->role               = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * DELETE /admin/users/{id}
     * Menghapus user. Tidak bisa hapus diri sendiri.
     */
    public function destroy(Request $request, $id)
    {
        if ((string) auth()->id() === (string) $id) {
            return response()->json(['message' => 'Tidak bisa menghapus akun sendiri.'], 403);
        }

        $user = User::findOrFail($id);
        $this->authorizeManageUser($user);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }

    protected function manageableUsersQuery()
    {
        $authUser = auth()->user();

        if ($authUser->isSuperAdmin()) {
            return User::query();
        }

        return User::where('role', 'affiliate');
    }

    protected function allowedRoles(): array
    {
        $authUser = auth()->user();

        if ($authUser->isSuperAdmin()) {
            return ['super_admin', 'admin', 'affiliate'];
        }

        return ['affiliate'];
    }

    protected function authorizeManageUser(User $user): void
    {
        $authUser = auth()->user();

        if ($authUser->isSuperAdmin()) {
            return;
        }

        abort_unless($user->role === 'affiliate', 403, 'Anda tidak punya izin untuk mengelola user ini.');
    }
}
