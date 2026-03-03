<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    /**
     * GET /admin/agents
     * – Browser → tampilkan view
     * – Fetch/AJAX (Accept: application/json) → return JSON list
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $agents = Agent::orderBy('created_at', 'desc')->get();
            return response()->json($agents);
        }

        return view('admin.agents');
    }

    /**
     * POST /admin/agents
     * Menyimpan agent baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'jabatan'    => 'required|string|max:100',
            'email'      => 'nullable|email|max:150',
            'phone'      => 'nullable|string|max:20',
            'commission' => 'nullable|numeric|min:0|max:100',
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
            'nama'       => $request->nama,
            'jabatan'    => $request->jabatan,
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
        $agent = Agent::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:100',
            'jabatan'    => 'required|string|max:100',
            'email'      => 'nullable|email|max:150',
            'phone'      => 'nullable|string|max:20',
            'commission' => 'nullable|numeric|min:0|max:100',
        ]);

        // Re-generate slug jika nama berubah
        if ($agent->nama !== $request->nama) {
            $baseSlug = Str::slug($request->nama);
            $slug     = $baseSlug;
            $counter  = 1;
            while (Agent::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $agent->slug = $slug;
        }

        $agent->nama       = $request->nama;
        $agent->jabatan    = $request->jabatan;
        $agent->email      = $request->email;
        $agent->phone      = $request->phone;
        $agent->commission = $request->commission ?? $agent->commission;
        $agent->save();

        return response()->json($agent);
    }

    /**
     * DELETE /admin/agents/{id}
     * Menghapus agent dari database.
     */
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return response()->json(['message' => 'Agent berhasil dihapus.']);
    }

    /**
     * PATCH /admin/agents/{id}/status
     * Toggle kolom `aktif` (aktif ↔ nonaktif).
     */
    public function toggleStatus($id)
    {
        $agent        = Agent::findOrFail($id);
        $agent->aktif = !$agent->aktif;
        $agent->save();

        return response()->json($agent);
    }
}
