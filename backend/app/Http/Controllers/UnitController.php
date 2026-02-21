<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Return all units as JSON.
     */
    public function index(): JsonResponse
    {
        $units = Unit::all();

        return response()->json([
            'success' => true,
            'data' => $units,
        ]);
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipe' => 'required|string',
            'nama' => 'required|string',
            'kamar_tidur' => 'required|integer',
            'kamar_mandi' => 'required|integer',
            'luas_tanah' => 'required|integer',
            'harga' => 'required|numeric',
            'status' => 'required|in:tersedia,booking,terjual',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $data['gambar'] = 'images/' . $filename;
        }

        $unit = Unit::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil ditambahkan',
            'data' => $unit
        ], 201);
    }

    /**
     * Display a specific unit.
     */
    public function show(Unit $unit): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $unit
        ]);
    }

    /**
     * Update a specific unit.
     */
    public function update(Request $request, Unit $unit): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipe' => 'sometimes|string',
            'nama' => 'sometimes|string',
            'kamar_tidur' => 'sometimes|integer',
            'kamar_mandi' => 'sometimes|integer',
            'luas_tanah' => 'sometimes|integer',
            'harga' => 'sometimes|numeric',
            'status' => 'sometimes|in:tersedia,booking,terjual',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($unit->gambar && file_exists(public_path($unit->gambar))) {
                unlink(public_path($unit->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $data['gambar'] = 'images/' . $filename;
        }

        $unit->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil diperbarui',
            'data' => $unit
        ]);
    }

    /**
     * Remove a specific unit.
     */
    public function destroy(Unit $unit): JsonResponse
    {
        // Hapus file gambar dari storage
        if ($unit->gambar && file_exists(public_path($unit->gambar))) {
            unlink(public_path($unit->gambar));
        }

        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil dihapus'
        ]);
    }
}
