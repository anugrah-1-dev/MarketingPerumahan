<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            $users = User::orderBy('created_at', 'desc')->get(['id', 'name', 'email', 'role', 'created_at']);
            return response()->json($users);
        }

        return view('admin.users');
    }

    /**
     * POST /admin/users
     * Membuat user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => ['required', Rule::in(['super_admin', 'affiliate'])],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
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

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'role'     => ['required', Rule::in(['super_admin', 'affiliate'])],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

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
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
