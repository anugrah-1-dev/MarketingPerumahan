<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Closing;
use App\Models\TipeRumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClosingController extends Controller
{
    /**
     * Resolve file URL: cek public/uploads/ dulu, fallback ke /storage/ route.
     */
    private function fileUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (file_exists(public_path('uploads/' . $path))) {
            return asset('uploads/' . $path);
        }
        return asset('storage/' . $path);
    }

    /**
     * GET /admin/closing  atau  /manager/closing
     * Browser  → tampilkan view dengan data properti
     * AJAX JSON → return daftar closings
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $closings = Closing::with(['agent', 'tipeRumah'])
                ->latest('tanggal_closing')
                ->get();

            return response()->json($closings->map(fn($c) => [
                'id'              => $c->id,
                'tanggal_closing' => $c->tanggal_closing?->format('Y-m-d'),
                'agent_id'        => $c->agent_id,
                'agent_name'      => $c->agent?->nama ?? '(tidak ada)',
                'komisi_persen'   => $c->komisi_persen,
                'tipe_rumah_id'   => $c->tipe_rumah_id,
                'tipe_rumah_nama' => $c->tipeRumah?->nama_tipe ?? '-',
                'customer_name'   => $c->customer_name,
                'customer_phone'  => $c->customer_phone,
                'harga_jual'      => $c->harga_jual,
                'komisi_nominal'  => $c->komisi_nominal,
                'payment_status'  => $c->payment_status,
                'komisi_status'   => $c->komisi_status ?? 'pending',
                'bukti_transfer'  => $this->fileUrl($c->bukti_transfer),
                'catatan'         => $c->catatan,
                'created_at'      => $c->created_at?->toIso8601String(),
            ]));
        }

        $panel     = auth()->user()->isAdmin() ? 'manager' : 'admin';
        $tipeRumah = TipeRumah::select('id', 'nama_tipe', 'harga')->orderBy('harga')->get();

        return view("{$panel}.closing", compact('tipeRumah'));
    }

    /**
     * POST /admin/closing
     * Input closing baru secara manual oleh admin / manager.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_closing' => 'required|date',
            'agent_id'        => 'nullable|exists:agents,id',
            'tipe_rumah_id'   => 'required|exists:tipe_rumah,id',
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|max:20',
            'harga_jual'      => 'required|integer|min:0',
            'payment_status'  => 'required|in:dp,installment,paid-off',
            'catatan'         => 'nullable|string',
        ]);

        $agent         = $request->filled('agent_id') ? Agent::find($request->agent_id) : null;
        $komisiPersen  = $agent?->commission ?? 0;
        $komisiNominal = (int) round($request->harga_jual * $komisiPersen / 100);

        $closing = Closing::create([
            'tanggal_closing' => $request->tanggal_closing,
            'agent_id'        => $request->agent_id,
            'tipe_rumah_id'   => $request->tipe_rumah_id,
            'customer_name'   => $request->customer_name,
            'customer_phone'  => $request->customer_phone,
            'harga_jual'      => $request->harga_jual,
            'komisi_persen'   => $komisiPersen,
            'komisi_nominal'  => $komisiNominal,
            'payment_status'  => $request->payment_status,
            'catatan'         => $request->catatan,
            'komisi_status'   => 'pending',
            'created_by'      => Auth::id(),
        ]);

        $closing->load('tipeRumah');

        return response()->json([
            'id'              => $closing->id,
            'tanggal_closing' => $closing->tanggal_closing->format('Y-m-d'),
            'agent_id'        => $closing->agent_id,
            'agent_name'      => $agent?->nama ?? '(tidak ada)',
            'komisi_persen'   => $komisiPersen,
            'tipe_rumah_id'   => $closing->tipe_rumah_id,
            'tipe_rumah_nama' => $closing->tipeRumah?->nama_tipe ?? '-',
            'customer_name'   => $closing->customer_name,
            'customer_phone'  => $closing->customer_phone,
            'harga_jual'      => $closing->harga_jual,
            'komisi_nominal'  => $closing->komisi_nominal,
            'payment_status'  => $closing->payment_status,
            'komisi_status'   => $closing->komisi_status ?? 'pending',
            'bukti_transfer'  => $this->fileUrl($closing->bukti_transfer),
            'catatan'         => $closing->catatan,
            'created_at'      => $closing->created_at->format('Y-m-d H:i:s'),
        ], 201);
    }

    /**
     * PUT /admin/closing/{id}
     */
    public function update(Request $request, $id)
    {
        $closing = Closing::findOrFail($id);

        $request->validate([
            'tanggal_closing' => 'required|date',
            'agent_id'        => 'nullable|exists:agents,id',
            'tipe_rumah_id'   => 'required|exists:tipe_rumah,id',
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|max:20',
            'harga_jual'      => 'required|integer|min:0',
            'payment_status'  => 'required|in:dp,installment,paid-off',
            'catatan'         => 'nullable|string',
        ]);

        $agent         = $request->filled('agent_id') ? Agent::find($request->agent_id) : null;
        $komisiPersen  = $agent?->commission ?? 0;
        $komisiNominal = (int) round($request->harga_jual * $komisiPersen / 100);

        $closing->update([
            'tanggal_closing' => $request->tanggal_closing,
            'agent_id'        => $request->agent_id,
            'tipe_rumah_id'   => $request->tipe_rumah_id,
            'customer_name'   => $request->customer_name,
            'customer_phone'  => $request->customer_phone,
            'harga_jual'      => $request->harga_jual,
            'komisi_persen'   => $komisiPersen,
            'komisi_nominal'  => $komisiNominal,
            'payment_status'  => $request->payment_status,
            'catatan'         => $request->catatan,
        ]);

        $closing->load('tipeRumah');

        return response()->json([
            'id'              => $closing->id,
            'tanggal_closing' => $closing->tanggal_closing->format('Y-m-d'),
            'agent_id'        => $closing->agent_id,
            'agent_name'      => $agent?->nama ?? '(tidak ada)',
            'komisi_persen'   => $komisiPersen,
            'tipe_rumah_id'   => $closing->tipe_rumah_id,
            'tipe_rumah_nama' => $closing->tipeRumah?->nama_tipe ?? '-',
            'customer_name'   => $closing->customer_name,
            'customer_phone'  => $closing->customer_phone,
            'harga_jual'      => $closing->harga_jual,
            'komisi_nominal'  => $closing->komisi_nominal,
            'payment_status'  => $closing->payment_status,
            'komisi_status'   => $closing->komisi_status ?? 'pending',
            'bukti_transfer'  => $this->fileUrl($closing->bukti_transfer),
            'catatan'         => $closing->catatan,
            'created_at'      => $closing->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * PATCH /admin/closing/{id}/komisi-status
     * Perbarui status komisi + upload bukti transfer.
     */
    public function updateKomisiStatus(Request $request, $id)
    {
        $closing = Closing::findOrFail($id);

        $request->validate([
            'komisi_status'  => 'required|in:pending,terbayar',
            'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = ['komisi_status' => $request->komisi_status];

        if ($request->hasFile('bukti_transfer')) {
            if ($closing->bukti_transfer) {
                Storage::disk('uploads')->delete($closing->bukti_transfer);
                Storage::disk('public')->delete($closing->bukti_transfer);
            }
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'uploads');
            $data['bukti_transfer'] = $path;
        }

        $closing->update($data);

        return response()->json([
            'message'        => 'Status komisi berhasil diperbarui.',
            'komisi_status'  => $closing->komisi_status,
            'bukti_transfer' => $this->fileUrl($closing->bukti_transfer),
        ]);
    }

    /**
     * DELETE /admin/closing/{id}
     */
    public function destroy($id)
    {
        $closing = Closing::findOrFail($id);
        $closing->delete();

        return response()->json(['message' => 'Closing berhasil dihapus.']);
    }
}
