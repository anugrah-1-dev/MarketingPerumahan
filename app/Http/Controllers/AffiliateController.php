<?php

namespace App\Http\Controllers;

use App\Models\Closing;
use App\Models\WaClick;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    /**
     * Halaman Dashboard Utama Affiliate — Tampilkan ringkasan ringkas, link cepat, dan aktivitas terbaru.
     */
    public function dashboard()
    {
        $user    = Auth::user();
        $refCode = $user->referral_code;

        $stats = [
            'total_klik'   => WaClick::where('referral_code', $refCode)->count(),
            'klik_hari_ini'=> WaClick::where('referral_code', $refCode)->whereDate('created_at', today())->count(),
            'klik_bulan'   => WaClick::where('referral_code', $refCode)->where('created_at', '>=', now()->startOfMonth())->count(),
            'total_leads'  => WaClick::where('referral_code', $refCode)->whereIn('status', ['new', 'follow-up', 'interested'])->count(),
            'total_closing'=> WaClick::where('referral_code', $refCode)->where('status', 'closed')->count(),
            'pendapatan'   => 0, // Dikosongkan sementara atau bisa fetch dari komisi di-develop selanjutnya
        ];

        // Hitung conversion rate (Leads / Klik * 100)
        $stats['conversion_rate'] = $stats['total_klik'] > 0 
            ? number_format(($stats['total_leads'] / $stats['total_klik']) * 100, 1) 
            : 0;

        // Aktivitas terbaru (5 klik WA terbaru dari link affiliate ini)
        $activities = WaClick::where('referral_code', $refCode)
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
        $user    = Auth::user();
        $refCode = $user->referral_code;

        $totalKlik    = WaClick::where('referral_code', $refCode)->count();
        $klikBulanIni = WaClick::where('referral_code', $refCode)
                               ->where('created_at', '>=', now()->startOfMonth())
                               ->count();
        $klikHariIni  = WaClick::where('referral_code', $refCode)
                               ->whereDate('created_at', today())
                               ->count();
        $klikInterest = WaClick::where('referral_code', $refCode)
                               ->where('status', 'interested')
                               ->count();

        return view('affiliate.link', compact(
            'totalKlik', 'klikBulanIni', 'klikHariIni', 'klikInterest'
        ));
    }

    /**
     * Halaman "Leads Saya" — daftar klik WA yang berasal dari referral affiliate ini.
     */
    public function leadsPage()
    {
        $user    = Auth::user();
        $refCode = $user->referral_code;

        // Ambil klik WA dari referral code ini, terbaru dulu
        $clicks = WaClick::where('referral_code', $refCode)
                         ->latest()
                         ->get();

        // Stats ringkas
        $stats = [
            'total'     => $clicks->count(),
            'baru'      => $clicks->where('status', 'new')->count(),
            'follow_up' => $clicks->where('status', 'follow-up')->count(),
            'closing'   => $clicks->where('status', 'closed')->count(),
        ];

        return view('affiliate.leads', compact('clicks', 'stats'));
    }

    /**
     * Halaman "Closing Saya" — daftar leads yang sudah berstatus closed.
     */
    public function closingPage()
    {
        $user    = Auth::user();
        $refCode = $user->referral_code;

        $closings = WaClick::where('referral_code', $refCode)
                           ->where('status', 'closed')
                           ->latest()
                           ->get();

        $stats = [
            'total_closing'     => $closings->count(),
            'closing_bulan_ini' => WaClick::where('referral_code', $refCode)
                                          ->where('status', 'closed')
                                          ->where('created_at', '>=', now()->startOfMonth())
                                          ->count(),
        ];

        $commissionRate = $user->agent?->commission ?? 0;

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
        $komisiTerbayar = $closings->where('payment_status', 'paid-off')->sum('komisi_nominal');
        $komisiPending  = $totalKomisi - $komisiTerbayar;

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
            'commissionRate' => $agent->commission ?? 0,
        ]);
    }
}
