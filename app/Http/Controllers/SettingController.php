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
        $waAdmin = Setting::get('wa_admin', '6283876766055');
        $isManager = request()->routeIs('manager.*');
        $view = $isManager ? 'manager.settings' : 'admin.settings';
        return view($view, compact('waAdmin'));
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
                // Harus diawali 62 dan berisi angka saja
                'regex:/^62[0-9]{8,14}$/',
            ],
        ], [
            'wa_admin.required' => 'Nomor WA tidak boleh kosong.',
            'wa_admin.regex'    => 'Format nomor harus diawali 62 dan hanya berisi angka (contoh: 6281234567890).',
            'wa_admin.max'      => 'Nomor WA maksimal 20 karakter.',
        ]);

        Setting::set('wa_admin', $request->wa_admin);

        $route = request()->routeIs('manager.*') ? 'manager.settings' : 'admin.settings';
        return redirect()->route($route)
                         ->with('success', 'Nomor WhatsApp admin berhasil diperbarui!');
    }
}
