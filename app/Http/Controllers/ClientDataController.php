<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ClientData;
use App\Models\Closing;
use App\Models\TipeRumah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientDataController extends Controller
{
    private function fileUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (file_exists(public_path('uploads/' . $path))) {
            return asset('uploads/' . $path);
        }
        return asset('storage/' . $path);
    }

    /**
     * GET /admin/client-data
     * HTML → tampilkan view, AJAX JSON → return daftar client data
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $clients = ClientData::with(['tipeRumah', 'creator', 'closing.agent'])
                ->latest()
                ->get();

            return response()->json($clients->map(fn($c) => [
                'id'               => $c->id,
                'nama_lengkap'     => $c->nama_lengkap,
                'email'            => $c->email,
                'nik'              => $c->nik,
                'no_whatsapp'      => $c->no_whatsapp,
                'alamat'           => $c->alamat,
                'tipe_rumah_id'    => $c->tipe_rumah_id,
                'tipe_rumah_nama'  => $c->tipeRumah?->nama_tipe ?? '-',
                'status_pembayaran'=> $c->status_pembayaran ?? 'baru',
                'bukti_pembayaran' => $this->fileUrl($c->bukti_pembayaran),
                'created_by_name'  => $c->creator?->name ?? '-',
                'closing_id'       => $c->closing?->id,
                'agent_name'       => $c->closing?->agent?->nama ?? '-',
                'komisi_nominal'   => $c->closing?->komisi_nominal ?? 0,
                'komisi_status'    => $c->closing?->komisi_status ?? '-',
                'created_at'       => $c->created_at?->toIso8601String(),
            ]));
        }

        $panel  = auth()->user()->isAdmin() ? 'manager' : 'admin';
        $agents = Agent::aktif()->select('id', 'nama', 'commission')->orderBy('nama')->get();

        return view("{$panel}.manajemen-client", compact('agents'));
    }

    /**
     * PATCH /admin/client-data/{id}/status
     * Ubah status_pembayaran; otomatis buat/perbarui closing jika DP/Lunas.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:baru,dp,lunas,cancel',
            'agent_id'          => 'nullable|exists:agents,id',
        ]);

        $clientData = ClientData::findOrFail($id);
        $status     = $request->status_pembayaran;

        $clientData->update(['status_pembayaran' => $status]);

        if (in_array($status, ['dp', 'lunas'])) {
            $this->syncClosing($clientData, $request->input('agent_id'), $status);
        } elseif ($status === 'cancel') {
            Closing::where('client_data_id', $clientData->id)->delete();
        }

        return response()->json([
            'message' => 'Status berhasil diperbarui.',
            'status'  => $status,
        ]);
    }

    // ── Private helper ─────────────────────────────────────────────────────────

    private function syncClosing(ClientData $clientData, ?string $agentIdInput, string $status): void
    {
        $creator = User::find($clientData->created_by);

        if ($agentIdInput) {
            $agent = Agent::find($agentIdInput);
        } elseif ($creator && $creator->isAffiliate() && $creator->agent) {
            $agent = $creator->agent;
        } else {
            $agent = null;
        }

        $tipeRumah     = TipeRumah::find($clientData->tipe_rumah_id);
        $hargaJual     = (int) ($tipeRumah?->harga ?? 0);
        $komisiPersen  = (float) ($agent?->commission ?? 0);
        $komisiNominal = (int) round($hargaJual * $komisiPersen / 100);
        $paymentStatus = $status === 'lunas' ? 'paid-off' : 'dp';

        $closing = Closing::firstOrNew(['client_data_id' => $clientData->id]);
        $isNew   = !$closing->exists;

        $closing->fill([
            'agent_id'        => $agent?->id,
            'tipe_rumah_id'   => $clientData->tipe_rumah_id,
            'customer_name'   => $clientData->nama_lengkap,
            'customer_phone'  => $clientData->no_whatsapp,
            'harga_jual'      => $hargaJual,
            'komisi_persen'   => $komisiPersen,
            'komisi_nominal'  => $komisiNominal,
            'payment_status'  => $paymentStatus,
            'tanggal_closing' => now()->toDateString(),
            'created_by'      => Auth::id(),
        ]);

        // Hanya set komisi_status 'pending' saat closing baru dibuat
        if ($isNew) {
            $closing->komisi_status = 'pending';
        }

        $closing->save();
    }
}
