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
        return view('admin.settings', compact('waAdmin'));
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

        return redirect()->route('admin.settings')
                         ->with('success', 'Nomor WhatsApp admin berhasil diperbarui!');
    }
}
