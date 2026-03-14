<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipeRumah;
use App\Models\TipeRumahFoto;
use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class TipeRumahController extends Controller
{
    public function index()
    {
        $tipeRumah = TipeRumah::latest()->get();
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $panel = 'admin';
        } elseif ($user->isAdmin()) {
            $panel = 'manager';
        } else {
            $panel = 'affiliate';
        }
        return view("{$panel}.tipe-rumah", compact('tipeRumah'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_tipe'      => 'required|string|max:100',
            'luas_bangunan'  => 'required|integer|min:1',
            'luas_tanah'     => 'required|integer|min:1',
            'kamar_tidur'    => 'required|integer|min:1',
            'kamar_mandi'    => 'required|integer|min:1',
            'lantai'         => 'required|integer|min:1',
            'garasi'         => 'nullable|integer|min:0',
            'sertifikat'     => 'nullable|string|max:50',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'string|max:100',
            'harga'          => 'required|integer|min:0',
            'harga_diskon'   => 'nullable|integer|min:0',
            'is_diskon'      => 'nullable|boolean',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stok_tersedia'  => 'required|integer|min:0',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('tipe-rumah', 'uploads');
        }

        $data['is_diskon'] = $request->boolean('is_diskon');
        // Simpan fasilitas sebagai JSON array (model cast akan handle)
        if (!isset($data['fasilitas'])) {
            $data['fasilitas'] = [];
        }

        $tipe = TipeRumah::create($data);

        // Simpan foto-foto tambahan ke tabel tipe_rumah_foto
        if ($request->hasFile('foto_tambahan')) {
            foreach ($request->file('foto_tambahan') as $i => $file) {
                $path = $file->store('tipe-rumah', 'uploads');
                TipeRumahFoto::create([
                    'tipe_rumah_id' => $tipe->id,
                    'path'          => $path,
                    'keterangan'    => $request->input('foto_keterangan.' . $i, ''),
                    'urutan'        => $i + 1,
                ]);
            }
        }

        return back()->with('success', 'Tipe rumah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tipe = TipeRumah::findOrFail($id);

        $data = $request->validate([
            'nama_tipe'      => 'required|string|max:100',
            'luas_bangunan'  => 'required|integer|min:1',
            'luas_tanah'     => 'required|integer|min:1',
            'kamar_tidur'    => 'required|integer|min:1',
            'kamar_mandi'    => 'required|integer|min:1',
            'lantai'         => 'required|integer|min:1',
            'garasi'         => 'nullable|integer|min:0',
            'sertifikat'     => 'nullable|string|max:50',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'string|max:100',
            'harga'          => 'required|integer|min:0',
            'harga_diskon'   => 'nullable|integer|min:0',
            'is_diskon'      => 'nullable|boolean',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stok_tersedia'  => 'required|integer|min:0',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($tipe->gambar) {
                Storage::disk('uploads')->delete($tipe->gambar);
                // Also try deleting from legacy public disk
                Storage::disk('public')->delete($tipe->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('tipe-rumah', 'uploads');
        } else {
            unset($data['gambar']); // jangan timpa kalau tidak ada upload baru
        }

        $data['is_diskon'] = $request->boolean('is_diskon');
        if (!isset($data['fasilitas'])) {
            $data['fasilitas'] = [];
        }

        $tipe->update($data);

        // Simpan foto-foto tambahan baru
        if ($request->hasFile('foto_tambahan')) {
            $lastUrutan = $tipe->fotos()->max('urutan') ?? 0;
            foreach ($request->file('foto_tambahan') as $i => $file) {
                $path = $file->store('tipe-rumah', 'uploads');
                TipeRumahFoto::create([
                    'tipe_rumah_id' => $tipe->id,
                    'path'          => $path,
                    'keterangan'    => $request->input('foto_keterangan.' . $i, ''),
                    'urutan'        => $lastUrutan + $i + 1,
                ]);
            }
        }

        return back()->with('success', 'Tipe rumah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tipe = TipeRumah::findOrFail($id);

        if ($tipe->gambar) {
            Storage::disk('uploads')->delete($tipe->gambar);
            Storage::disk('public')->delete($tipe->gambar);
        }

        // Hapus juga foto-foto tambahan dari disk
        foreach ($tipe->fotos as $foto) {
            Storage::disk('uploads')->delete($foto->path);
            Storage::disk('public')->delete($foto->path);
        }

        $tipe->delete();  // cascade deletes rows in tipe_rumah_foto automatically

        return back()->with('success', 'Tipe rumah berhasil dihapus!');
    }

    /**
     * Halaman publik – daftar semua tipe rumah
     */
    public function publicIndex()
    {
        $tipeRumah = TipeRumah::latest()->get();
        return view('tipe-rumah', compact('tipeRumah'));
    }

    /**
     * Halaman publik – detail satu tipe rumah
     */
    public function publicDetail($id)
    {
        $tipe = TipeRumah::with('fotos')->findOrFail($id);

        $harga_raw = $tipe->is_diskon && $tipe->harga_diskon ? $tipe->harga_diskon : $tipe->harga;

        // Hitung KPR
        $dp_raw     = $harga_raw * 0.20;
        $pokok      = $harga_raw - $dp_raw;
        $r          = 0.08 / 12;

        $n15        = 15 * 12;
        $cicilan_15 = ($pokok * $r * pow(1 + $r, $n15)) / (pow(1 + $r, $n15) - 1);

        $n20        = 20 * 12;
        $cicilan_20 = ($pokok * $r * pow(1 + $r, $n20)) / (pow(1 + $r, $n20) - 1);

        // Estimasi KT KM
        $kt = $tipe->luas_bangunan >= 54 ? 3 : 2;
        $km = $tipe->luas_bangunan >= 54 ? 2 : 1;

        $unit = [
            'blok'       => '-',
            'tipe'       => $tipe->nama_tipe,
            'nama'       => "Detail " . $tipe->nama_tipe,
            'deskripsi'  => $tipe->deskripsi ?? 'Hunian modern minimalis dengan desain nyaman untuk keluarga muda.',
            'status'     => $tipe->stok_tersedia > 0 ? 'Tersedia' : 'Terjual',
            'kt'         => $tipe->kamar_tidur ?? $kt,
            'km'         => $tipe->kamar_mandi ?? $km,
            'lantai'     => $tipe->lantai      ?? 1,
            'sertifikat' => $tipe->sertifikat  ?? 'SHM',
            'lb'         => $tipe->luas_bangunan . ' m²',
            'lt'         => $tipe->luas_tanah . ' m²',
            'harga'      => 'Rp ' . number_format($harga_raw, 0, ',', '.'),
            'dp'         => 'Rp ' . number_format($dp_raw, 0, ',', '.'),
            'cicilan_15' => 'Rp ' . number_format($cicilan_15, 0, ',', '.'),
            'cicilan_20' => 'Rp ' . number_format($cicilan_20, 0, ',', '.'),
            'fasilitas'  => $tipe->fasilitas ?? [
                'Listrik 2200W', 'Air PDAM', 'Kitchen Set',
                'Carport 1 Mobil', 'Meja Dapur + Sink', 'Pagar Minimalis',
            ],
            'gambar'       => $tipe->gambar_url,
            'gambar_list'  => $tipe->all_fotos,
        ];

        // Baca agent dari session (di-set saat kunjungi /{slug} atau /ref/{code})
        $agent = [
            'nama' => session('agent_nama', 'Admin'),
            'wa'   => session('agent_phone', Setting::get('wa_admin', '6283876766055')),
        ];

        return view('detail-rumah', compact('unit', 'agent'));
    }
}
