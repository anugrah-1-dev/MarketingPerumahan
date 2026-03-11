<?php

namespace App\Http\Controllers;

use App\Models\WaClick;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
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
