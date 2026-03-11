<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipeRumah;
use App\Models\Setting;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    /**
     * Handle link referral affiliate: /ref/{code}
     *
     * PENTING: Gunakan redirect() agar session & cookie
     * ter-commit ke browser sebelum landing page dibuka.
     * Jika langsung return view(), cookie belum terkirim
     * sehingga sesi referral tidak terbaca.
     */
    public function handle(string $code, Request $request)
    {
        $code = strtoupper(trim($code));

        // Cari affiliate berdasarkan referral_code
        $affiliate = User::where('referral_code', $code)
                         ->where('role', 'affiliate')
                         ->first();

        // Jika tidak ditemukan, redirect ke landing utama
        if (! $affiliate) {
            return redirect()->route('landing');
        }

        // Simpan ke SESSION Laravel (akan dibaca sisi-server saat landing page dirender)
        $request->session()->put('affiliate_ref_code', $code);
        $request->session()->put('affiliate_user_id',  $affiliate->id);
        $request->session()->put('affiliate_name',      $affiliate->name);

        // Redirect ke landing page dengan cookie (30 hari)
        // Redirect penting agar cookie dikirim ke browser SEBELUM halaman dirender
        return redirect()->route('landing')
            ->withCookie(cookie('affiliate_ref_code', $code,            60 * 24 * 30))
            ->withCookie(cookie('affiliate_user_id',  (string)$affiliate->id, 60 * 24 * 30));
    }
}
