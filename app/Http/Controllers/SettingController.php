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
            'instagram_url' => ['nullable', 'url', 'max:255', 'starts_with:http://,https://'],
            'tiktok_url'    => ['nullable', 'url', 'max:255', 'starts_with:http://,https://'],
            'facebook_url'  => ['nullable', 'url', 'max:255', 'starts_with:http://,https://'],
        ], [
            'wa_admin.required' => 'Nomor WA tidak boleh kosong.',
            'wa_admin.regex'    => 'Format nomor harus diawali 62 dan hanya berisi angka (contoh: 6281234567890).',
            'wa_admin.max'      => 'Nomor WA maksimal 20 karakter.',
            'instagram_url.starts_with' => 'URL Instagram harus diawali http:// atau https://.',
            'tiktok_url.starts_with'    => 'URL TikTok harus diawali http:// atau https://.',
            'facebook_url.starts_with'  => 'URL Facebook harus diawali http:// atau https://.',
        ]);

        Setting::set('wa_admin', trim((string) $request->wa_admin));

        foreach (['instagram_url', 'tiktok_url', 'facebook_url'] as $key) {
            if ($request->exists($key)) {
                Setting::set($key, trim((string) $request->input($key, '')));
            }
        }

        $route = request()->routeIs('manager.*') ? 'manager.settings' : 'admin.settings';
        return redirect()->route($route)
                         ->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * GET /admin/denah
     * Tampilkan halaman kelola denah perumahan.
     */
    public function denah()
    {
        $denahImage = Setting::get('denah_image', '');
        $isManager  = request()->routeIs('manager.*');
        $view       = $isManager ? 'manager.denah' : 'admin.denah';
        return view($view, compact('denahImage'));
    }

    /**
     * POST /admin/denah
     * Upload dan simpan gambar denah perumahan.
     */
    public function updateDenah(Request $request)
    {
        $request->validate([
            'denah_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'denah_image.required' => 'Gambar denah wajib diunggah.',
            'denah_image.image'    => 'File harus berupa gambar.',
            'denah_image.mimes'    => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'denah_image.max'      => 'Ukuran gambar maksimal 5 MB.',
        ]);

        // Hapus file lama jika ada
        $oldPath = Setting::get('denah_image', '');
        if ($oldPath && file_exists(public_path($oldPath))) {
            @unlink(public_path($oldPath));
        }

        // Simpan file baru
        $file     = $request->file('denah_image');
        $filename = 'denah_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/denah'), $filename);
        $relativePath = 'uploads/denah/' . $filename;

        Setting::set('denah_image', $relativePath);

        $route = request()->routeIs('manager.*') ? 'manager.denah' : 'admin.denah';
        return redirect()->route($route)
                         ->with('success', 'Gambar denah berhasil diperbarui!');
    }
}
