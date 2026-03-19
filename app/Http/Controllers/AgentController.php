<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    /**
     * GET /admin/agents
     * – Browser → tampilkan view
     * – Fetch/AJAX (Accept: application/json) → return JSON list
     */
    public function index()
    {
        $panel = auth()->user()->isAdmin() ? 'manager' : 'admin';
        return view("{$panel}.agents");
    }

    public function data()
    {
        $users = User::where('role', 'affiliate')->with('agent')->orderBy('created_at', 'desc')->get();

        $agents = $users->map(function ($user) {
            $agent = $user->agent;

            if (! $agent) {
                try {
                    $slug = Str::slug($user->name);
                    $base = $slug;
                    $i = 1;
                    while (Agent::where('slug', $slug)->exists()) {
                        $slug = $base . '-' . $i++;
                    }
                    $agent = Agent::create([
                        'user_id' => $user->id,
                        'nama' => $user->name,
                        'jabatan' => 'Affiliate',
                        'slug' => $slug,
                        'aktif' => true,
                        'email' => $user->email,
                        'phone' => null,
                        'commission' => 0,
                    ]);
                } catch (\Throwable $e) {
                    return null;
                }
            }

            if (! $agent || ! $agent->id) {
                return null;
            }

            return [
                'id' => $agent->id,
                'nama' => $user->name,
                'jabatan' => $agent->jabatan,
                'email' => $user->email,
                'phone' => $agent->phone,
                'commission' => $agent->commission,
                'slug' => $agent->slug,
                'aktif' => $agent->aktif,
                'user' => [
                    'referral_code' => $user->referral_code,
                ],
                'user_id' => $user->id,
            ];
        });

        return response()->json($agents->filter()->values());
    }

    /**
     * POST /admin/agents
     * Menyimpan agent baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'email'      => 'required|email|max:150|unique:users,email',
            'password'   => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
            'jabatan'    => 'nullable|string|max:100', // hidden, default Affiliate
            'phone'      => 'nullable|string|max:20',
            'commission' => 'nullable|numeric|min:0|max:100',
        ]);

        // Auto-generate referral code using Model's method
        $referralCode = User::generateReferralCode();

        // Create the User account
        $user = User::create([
            'name'          => $request->nama,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'affiliate',
            'referral_code' => $referralCode,
        ]);

        // Auto-generate slug unik dari nama
        $baseSlug = Str::slug($request->nama);
        $slug     = $baseSlug;
        $counter  = 1;
        while (Agent::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $agent = Agent::create([
            'user_id'    => $user->id,
            'nama'       => $request->nama,
            'jabatan'    => $request->jabatan ?? 'Affiliate',
            'slug'       => $slug,
            'aktif'      => true,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'commission' => $request->commission ?? 0,
        ]);

        return response()->json($agent, 201);
    }

    /**
     * PUT /admin/agents/{id}
     * Memperbarui data agent.
     */
    public function update(Request $request, $id)
    {
        $agent = null;
        $user  = null;

        if (Str::startsWith($id, 'u-')) {
            $userId = str_replace('u-', '', $id);
            $user   = User::findOrFail($userId);
            $agent  = $user->agent; // might be null
        } else {
            $agent = Agent::findOrFail($id);
            if ($agent->user_id) {
                $user = User::find($agent->user_id);
            }
        }

        $request->validate([
            'nama'       => 'required|string|max:100',
            'email'      => 'required|email|max:150|unique:users,email,' . ($agent->user_id ?? 'NULL'),
            'password'   => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()],
            'jabatan'    => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'commission' => 'nullable|numeric|min:0|max:100',
        ]);

        // 1. Update User first (if exists)
        if ($user) {
            $user->name  = $request->nama;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        // 2. Update or Create Agent
        $baseSlug = Str::slug($request->nama);
        $slug     = $baseSlug;
        $counter  = 1;

        if ($agent) {
            // Re-generate slug jika nama berubah
            if ($agent->nama !== $request->nama) {
                while (Agent::where('slug', $slug)->where('id', '!=', $agent->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $agent->slug = $slug;
            }

            $agent->nama       = $request->nama;
            $agent->jabatan    = $request->jabatan ?? $agent->jabatan;
            $agent->email      = $request->email;
            $agent->phone      = $request->phone;
            $agent->commission = $request->commission ?? $agent->commission;
            $agent->save();
        } else {
            // Create target agent for this user since it doesn't exist yet
            while (Agent::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $agent = Agent::create([
                'user_id'    => $user->id,
                'nama'       => $request->nama,
                'jabatan'    => $request->jabatan ?? 'Affiliate',
                'slug'       => $slug,
                'aktif'      => true,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'commission' => $request->commission ?? 0,
            ]);
        }

        return response()->json($agent);
    }

    /**
     * DELETE /admin/agents/{id}
     * Menghapus agent dan akun user yang terhubung.
     */
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return response()->json(['message' => 'Agent dan akun berhasil dihapus.']);
    }

    /**
     * PATCH /admin/agents/{id}/status
     * Toggle kolom `aktif` (aktif ↔ nonaktif).
     */
    public function toggleStatus($id)
    {
        if (Str::startsWith($id, 'u-')) {
            $userId = str_replace('u-', '', $id);
            $user   = User::findOrFail($userId);
            
            // If agent doesn't exist, create it but set as nonaktif
            if (!$user->agent) {
                $baseSlug = Str::slug($user->name);
                $slug     = $baseSlug;
                $counter  = 1;
                while (Agent::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $agent = Agent::create([
                    'user_id'    => $user->id,
                    'nama'       => $user->name,
                    'jabatan'    => 'Affiliate',
                    'slug'       => $slug,
                    'aktif'      => false,
                    'email'      => $user->email,
                    'phone'      => null,
                    'commission' => 0,
                ]);
            } else {
                $user->agent->aktif = !$user->agent->aktif;
                $user->agent->save();
                $agent = $user->agent;
            }
        } else {
            $agent        = Agent::findOrFail($id);
            $agent->aktif = !$agent->aktif;
            $agent->save();
        }

        return response()->json($agent ?? []);
    }
}
