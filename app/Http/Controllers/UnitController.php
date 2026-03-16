<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\TipeRumah;

class UnitController extends Controller
{
    public function index()
    {
        $units     = Unit::with('tipeRumah')->latest()->get();
        $stats     = Unit::stats();
        $tipeRumah = TipeRumah::orderBy('nama_tipe')->get();

        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $panel = 'admin';
        } elseif ($user->isAdmin()) {
            $panel = 'manager';
        } else {
            $panel = 'affiliate';
        }

        return view("{$panel}.units", compact('units', 'stats', 'tipeRumah', 'panel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipe_rumah_id' => 'required|exists:tipe_rumah,id',
            'nomor_unit'    => 'required|string|max:30',
            'blok'          => 'nullable|string|max:30',
            'status'        => 'required|in:tersedia,booking,terjual',
            'harga_jual'    => 'nullable|integer|min:0',
            'catatan'       => 'nullable|string|max:500',
        ]);

        Unit::create($data);

        return back()->with('success', 'Unit berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $data = $request->validate([
            'tipe_rumah_id' => 'required|exists:tipe_rumah,id',
            'nomor_unit'    => 'required|string|max:30',
            'blok'          => 'nullable|string|max:30',
            'status'        => 'required|in:tersedia,booking,terjual',
            'harga_jual'    => 'nullable|integer|min:0',
            'catatan'       => 'nullable|string|max:500',
        ]);

        $unit->update($data);

        return back()->with('success', 'Unit berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Unit::findOrFail($id)->delete();

        return back()->with('success', 'Unit berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:tersedia,booking,terjual']);

        Unit::findOrFail($id)->update(['status' => $request->status]);

        return back()->with('success', 'Status unit berhasil diubah!');
    }
}
