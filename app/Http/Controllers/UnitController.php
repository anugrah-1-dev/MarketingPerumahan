<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $stats   = Unit::stats();
        $summary = Unit::managementSummary();

        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $panel = 'admin';
        } elseif ($user->isAdmin()) {
            $panel = 'manager';
        } else {
            $panel = 'affiliate';
        }

        return view("{$panel}.units", compact('stats', 'summary', 'panel'));
    }

    public function store(Request $request)
    {
        Unit::saveSummary($this->validateSummary($request));

        return back()->with('success', 'Ringkasan unit berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        Unit::saveSummary($this->validateSummary($request));

        return back()->with('success', 'Ringkasan unit berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Unit::clearSummary();

        return back()->with('success', 'Ringkasan unit berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        abort(404);
    }

    protected function validateSummary(Request $request): array
    {
        $validator = Validator::make(
            $request->all(),
            [
                'total'   => 'required|integer|min:0',
                'terjual' => 'required|integer|min:0',
                'booking' => 'required|integer|min:0',
            ],
            [
                'total.required'   => 'Total unit wajib diisi.',
                'total.integer'    => 'Total unit harus berupa angka.',
                'total.min'        => 'Total unit tidak boleh kurang dari 0.',
                'terjual.required' => 'Unit terjual wajib diisi.',
                'terjual.integer'  => 'Unit terjual harus berupa angka.',
                'terjual.min'      => 'Unit terjual tidak boleh kurang dari 0.',
                'booking.required' => 'Unit booking wajib diisi.',
                'booking.integer'  => 'Unit booking harus berupa angka.',
                'booking.min'      => 'Unit booking tidak boleh kurang dari 0.',
            ]
        );

        $validator->after(function ($validator) use ($request) {
            $total = (int) $request->input('total', 0);
            $terjual = (int) $request->input('terjual', 0);
            $booking = (int) $request->input('booking', 0);

            if (($terjual + $booking) > $total) {
                $validator->errors()->add(
                    'booking',
                    'Jumlah unit terjual dan booking tidak boleh melebihi total unit.'
                );
            }
        });

        return $validator->validate();
    }
}
