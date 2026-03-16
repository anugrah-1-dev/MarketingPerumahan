<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * GET /admin/settings
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        $waAdmin      = Setting::get('wa_admin', '6283876766055');
        $instagramUrl = Setting::get('instagram_url', '');
        $tiktokUrl    = Setting::get('tiktok_url', '');
        $facebookUrl  = Setting::get('facebook_url', '');
        $isManager = request()->routeIs('manager.*');
        $view = $isManager ? 'manager.settings' : 'admin.settings';
        return view($view, compact('waAdmin', 'instagramUrl', 'tiktokUrl', 'facebookUrl'));
    }

    /**
     * POST /admin/settings
     * Simpan perubahan nomor WA admin.
     */
    public function update(Request $request)
    {
        $request->validate([
            'wa_admin' => [
                'required',
                'string',
                'max:20',
                'regex:/^62[0-9]{8,14}$/',
            ],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url'    => ['nullable', 'url', 'max:255'],
            'facebook_url'  => ['nullable', 'url', 'max:255'],
        ], [
            'wa_admin.required' => 'Nomor WA tidak boleh kosong.',
            'wa_admin.regex'    => 'Format nomor harus diawali 62 dan hanya berisi angka (contoh: 6281234567890).',
            'wa_admin.max'      => 'Nomor WA maksimal 20 karakter.',
        ]);

        Setting::set('wa_admin', $request->wa_admin);
        Setting::set('instagram_url', $request->instagram_url ?? '');
        Setting::set('tiktok_url',    $request->tiktok_url    ?? '');
        Setting::set('facebook_url',  $request->facebook_url  ?? '');

        $route = request()->routeIs('manager.*') ? 'manager.settings' : 'admin.settings';
        return redirect()->route($route)
                         ->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
