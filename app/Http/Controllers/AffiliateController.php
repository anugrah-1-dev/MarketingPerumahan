<?php

namespace App\Http\Controllers;

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
            'closing'   => $clicks->where('status', 'interested')->count(),
        ];

        return view('affiliate.leads', compact('clicks', 'stats'));
    }
}
