<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Closing;
use App\Models\TipeRumah;
use App\Models\WaClick;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    /**
     * Buat closure filter WaClick yang mencakup semua cara klik bisa dikaitkan ke affiliate ini:
     * 1. affiliate_user_id = user->id  (klik via link ref, data baru)
     * 2. referral_code = refCode       (fallback data lama)
     * 3. agent_id = agent->id          (klik via halaman agent tanpa ?ref=)
     */
    private function waClickScope(\App\Models\User $user): \Closure
    {
        $refCode = $user->referral_code;
        $agentId = Agent::where('user_id', $user->id)->where('aktif', true)->value('id');

        return function ($q) use ($user, $refCode, $agentId) {
            $q->where('affiliate_user_id', $user->id);
            if ($refCode) {
                $q->orWhere('referral_code', $refCode);
            }
            if ($agentId) {
                $q->orWhere('agent_id', $agentId);
            }
        };
    }

    public function dashboard()
    {
        $user      = Auth::user();
        $scope     = $this->waClickScope($user);
        $baseQuery = fn() => WaClick::where($scope);

        // Data closing nyata dari tabel closings
        $agent    = Agent::where('user_id', $user->id)->first();
        $closings = $agent ? Closing::where('agent_id', $agent->id)->get() : collect();

        $stats = [
            'total_klik'   => $baseQuery()->count(),
            'klik_hari_ini'=> $baseQuery()->whereDate('created_at', today())->count(),
            'klik_bulan'   => $baseQuery()->where('created_at', '>=', now()->startOfMonth())->count(),
            'total_leads'  => $baseQuery()->whereIn('status', ['new', 'follow-up', 'interested'])->count(),
            'total_closing'=> $closings->count(),
            'pendapatan'   => $closings->sum('komisi_nominal'),
        ];

        // Hitung conversion rate (Leads / Klik * 100)
        $stats['conversion_rate'] = $stats['total_klik'] > 0 
            ? number_format(($stats['total_leads'] / $stats['total_klik']) * 100, 1) 
            : 0;

        // Aktivitas terbaru (5 klik WA terbaru dari link affiliate ini)
        $activities = $baseQuery()
            ->latest()
            ->take(5)
            ->get();

        return view('affiliate.dashboard', compact('stats', 'activities'));
    }

    /**
     * Halaman "Link Saya" — tampilkan referral link, QR code, dan stats klik WA.
     */
    public function linkPage()
    {
        $user      = Auth::user();
        $scope     = $this->waClickScope($user);
        $baseQuery = fn() => WaClick::where($scope);

        $totalKlik    = $baseQuery()->count();
        $klikBulanIni = $baseQuery()->where('created_at', '>=', now()->startOfMonth())->count();
        $klikHariIni  = $baseQuery()->whereDate('created_at', today())->count();
        $klikInterest = $baseQuery()->where('status', 'interested')->count();

        return view('affiliate.link', compact(
            'totalKlik', 'klikBulanIni', 'klikHariIni', 'klikInterest'
        ));
    }

    /**
     * Halaman "Leads Saya" — daftar klik WA yang berasal dari referral affiliate ini.
     */
    public function leadsPage()
    {
        $user   = Auth::user();
        $clicks = WaClick::where($this->waClickScope($user))
                         ->latest()
                         ->get();

        // Stats ringkas
        $stats = [
            'total'     => $clicks->count(),
            'baru'      => $clicks->where('status', 'new')->count(),
            'follow_up' => $clicks->where('status', 'follow-up')->count(),
            'closing'   => $clicks->where('status', 'closed')->count(),
        ];

        $tipeRumah = $this->tipeRumahList();

        return view('affiliate.leads', compact('clicks', 'stats', 'tipeRumah'));
    }

    private function tipeRumahList()
    {
        return TipeRumah::select('id', 'nama_tipe', 'harga')->orderBy('harga')->get();
    }

    /**
     * Halaman "Closing Saya" — daftar leads yang sudah berstatus closed.
     */
    public function closingPage()
    {
        $user  = Auth::user();
        $agent = $user->agent;

        if (!$agent) {
            return view('affiliate.closing', [
                'closings'       => collect(),
                'stats'          => ['total_closing' => 0, 'closing_bulan_ini' => 0],
                'commissionRate' => 1
            ]);
        }

        $closings = Closing::with('tipeRumah')
            ->where('agent_id', $agent->id)
            ->latest('tanggal_closing')
            ->get();

        $stats = [
            'total_closing'     => $closings->count(),
            'closing_bulan_ini' => $closings->filter(function($c) {
                return $c->tanggal_closing && $c->tanggal_closing->isCurrentMonth();
            })->count(),
        ];

        $commissionRate = $agent->commission ?? 1;

        return view('affiliate.closing', compact('closings', 'stats', 'commissionRate'));
    }

    /**
     * Halaman "Komisi" — ringkasan komisi affiliate berdasarkan data closing nyata.
     */
    public function komisiPage()
    {
        $user  = Auth::user();
        $agent = $user->agent; // HasOne: users → agents via user_id

        if (!$agent) {
            return view('affiliate.komisi', [
                'agent'          => null,
                'closings'       => collect(),
                'totalClosing'   => 0,
                'closingBulanIni'=> 0,
                'totalKomisi'    => 0,
                'komisiTerbayar' => 0,
                'komisiPending'  => 0,
                'commissionRate' => 0,
            ]);
        }

        $closings = Closing::with('tipeRumah')
            ->where('agent_id', $agent->id)
            ->latest('tanggal_closing')
            ->get();

        $totalKomisi    = $closings->sum('komisi_nominal');
        $komisiTerbayar = $closings->where('komisi_status', 'terbayar')->sum('komisi_nominal');
        $komisiPending  = $closings->where('komisi_status', 'pending')->sum('komisi_nominal');

        $closingBulanIni = $closings->filter(function ($c) {
            return $c->tanggal_closing && $c->tanggal_closing->isCurrentMonth();
        })->count();

        return view('affiliate.komisi', [
            'agent'          => $agent,
            'closings'       => $closings,
            'totalClosing'   => $closings->count(),
            'closingBulanIni'=> $closingBulanIni,
            'totalKomisi'    => $totalKomisi,
            'komisiTerbayar' => $komisiTerbayar,
            'komisiPending'  => $komisiPending,
            'commissionRate' => $agent->commission ?? 1,
        ]);
    }
}
