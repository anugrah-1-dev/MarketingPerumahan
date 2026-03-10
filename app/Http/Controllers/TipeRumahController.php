<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipeRumah;
use Illuminate\Support\Facades\Storage;

class TipeRumahController extends Controller
{
    public function index()
    {
        $tipeRumah = TipeRumah::latest()->get();
        $panel = auth()->user()->isSuperAdmin() ? 'admin' : 'affiliate';
        return view("{$panel}.tipe-rumah", compact('tipeRumah'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_tipe'      => 'required|string|max:100',
            'luas_bangunan'  => 'required|integer|min:1',
            'luas_tanah'     => 'required|integer|min:1',
            'harga'          => 'required|integer|min:0',
            'harga_diskon'   => 'nullable|integer|min:0',
            'is_diskon'      => 'nullable|boolean',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stok_tersedia'  => 'required|integer|min:0',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('tipe-rumah', 'public');
        }

        $data['is_diskon'] = $request->boolean('is_diskon');

        TipeRumah::create($data);

        return back()->with('success', 'Tipe rumah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tipe = TipeRumah::findOrFail($id);

        $data = $request->validate([
            'nama_tipe'      => 'required|string|max:100',
            'luas_bangunan'  => 'required|integer|min:1',
            'luas_tanah'     => 'required|integer|min:1',
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
                Storage::disk('public')->delete($tipe->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('tipe-rumah', 'public');
        } else {
            unset($data['gambar']); // jangan timpa kalau tidak ada upload baru
        }

        $data['is_diskon'] = $request->boolean('is_diskon');

        $tipe->update($data);

        return back()->with('success', 'Tipe rumah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tipe = TipeRumah::findOrFail($id);

        if ($tipe->gambar) {
            Storage::disk('public')->delete($tipe->gambar);
        }

        $tipe->delete();

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
}
