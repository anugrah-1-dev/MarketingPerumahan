<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipeRumah;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    /**
     * Handle link referral affiliate.
     * URL: /ref/{code}
     *
     * - Simpan referral_code ke session DAN cookie (30 hari)
     *   sehingga sistem tahu user datang dari affiliate mana,
     *   meskipun user tidak login.
     */
    public function handle(string $code, Request $request)
    {
        $code = strtoupper($code);

        // Cari affiliate berdasarkan referral_code
        $affiliate = User::where('referral_code', $code)
                         ->where('role', 'affiliate')
                         ->first();

        // Jika tidak ditemukan, redirect ke landing utama
        if (! $affiliate) {
            return redirect()->route('landing');
        }

        // ── Simpan ke SESSION ────────────────────────────────────────
        // Session: aktif selama browser terbuka (atau sampai expire)
        $request->session()->put('affiliate_ref_code', $code);
        $request->session()->put('affiliate_user_id',  $affiliate->id);
        $request->session()->put('affiliate_name',      $affiliate->name);

        // ── Simpan ke COOKIE ─────────────────────────────────────────
        // Cookie: bertahan 30 hari, bahkan setelah browser ditutup
        $response = redirect()->route('landing');
        $response->withCookie(cookie('affiliate_ref_code', $code,        60 * 24 * 30)); // 30 hari
        $response->withCookie(cookie('affiliate_user_id',  $affiliate->id, 60 * 24 * 30));

        // Bangun data agent dari user affiliate
        $agent = [
            'nama'    => $affiliate->name,
            'jabatan' => 'Marketing Affiliate',
            'wa'      => Setting::get('wa_admin', '6283876766055'),
            'slug'    => null,
        ];

        $tipeRumahDiskon = TipeRumah::diskon()->latest()->take(6)->get();
        $semuaTipeRumah  = TipeRumah::latest()->get();

        // Return view langsung (tidak redirect) agar referral_code langsung tersedia
        // di halaman pertama tanpa perlu redirect tambahan
        return response()
            ->view('landing', compact('agent', 'tipeRumahDiskon', 'semuaTipeRumah'))
            ->withCookie(cookie('affiliate_ref_code', $code,           60 * 24 * 30))
            ->withCookie(cookie('affiliate_user_id',  $affiliate->id,  60 * 24 * 30));
    }
}
