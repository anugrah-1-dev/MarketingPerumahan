<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Agent;

class ProfileController extends Controller
{
    /**
     * Cari agent yang terhubung ke user login.
     * Prioritas: via user_id (jika migration sudah jalan),
     * fallback: via email (jika migration belum jalan).
     */
    private function resolveAgent($user): ?Agent
    {
        // Coba via relasi user_id (jika kolom sudah ada)
        if (Schema::hasColumn('agents', 'user_id')) {
            $agent = $user->agent;
            if ($agent) return $agent;
        }

        // Fallback: cari agent yang email-nya sama dengan email login
        return Agent::where('email', $user->email)->first();
    }

    /**
     * Tampilkan halaman profile affiliate.
     */
    public function show()
    {
        $user  = Auth::user();
        $agent = $this->resolveAgent($user);

        return view('affiliate.profile', compact('user', 'agent'));
    }

    /**
     * Simpan perubahan biodata (nama, email, nomor telepon).
     */
    public function update(Request $request)
    {
        $user  = Auth::user();
        $agent = $this->resolveAgent($user);

        $request->validate([
            'nama'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
        ], [
            'nama.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if ($agent) {
            // Update data di tabel agents
            $agent->update([
                'nama'  => $request->nama,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        // Sinkronkan nama & email ke tabel users juga
        $user->update([
            'name'  => $request->nama,
            'email' => $request->email,
        ]);

        return back()->with('success_biodata', 'Biodata berhasil diperbarui.');
    }

    /**
     * Ganti password affiliate.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'              => ['required'],
            'password_baru'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_baru_confirmation' => ['required'],
        ], [
            'password_lama.required'  => 'Password lama wajib diisi.',
            'password_baru.required'  => 'Password baru wajib diisi.',
            'password_baru.min'       => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                ->with('tab_password', true);
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success_password', 'Password berhasil diubah.');
    }
}
