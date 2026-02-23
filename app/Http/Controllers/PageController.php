<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function unitTersedia()
    {
        $units = $this->getUnits();
        return view('unit-tersedia', compact('units'));
    }

    public function sitePlan()
    {
        $units = $this->getUnits();
        return view('site-plan', compact('units'));
    }

    public function detailRumah($blok = 'A3')
    {
        $unit = $this->getUnitDetail($blok);
        return view('detail-rumah', compact('unit'));
    }

    public function formBooking($blok = 'C3')
    {
        return view('form-booking', ['blok' => $blok]);
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'required|email',
            'no_ktp'        => 'required|string|max:20',
            'no_whatsapp'   => 'required|string|max:20',
            'alamat'        => 'required|string',
            'status_pekerjaan' => 'required|string',
            'penghasilan'   => 'required|string',
        ]);

        // TODO: simpan ke database
        return redirect()->route('landing')->with('success', 'Booking berhasil! Tim kami akan segera menghubungi Anda.');
    }

    // -------------------------------------------------------
    // Data dummy – nanti bisa diganti dengan query Eloquent
    // -------------------------------------------------------

    /**
     * Master unit data – deterministik agar detail-rumah konsisten
     * dengan tampilan di unit-tersedia & site-plan.
     */
    private function masterUnits(): array
    {
        // [blok, tipe, status, harga_raw]
        return [
            // Blok A
            ['blok'=>'A1','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'A2','tipe'=>'Tipe 45/90', 'status'=>'terjual', 'harga_raw'=>390_000_000],
            ['blok'=>'A3','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'A4','tipe'=>'Tipe 54/108','status'=>'booking', 'harga_raw'=>450_000_000],
            ['blok'=>'A5','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'A6','tipe'=>'Tipe 36/72', 'status'=>'terjual', 'harga_raw'=>310_000_000],
            // Blok B
            ['blok'=>'B1','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
            ['blok'=>'B2','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'B3','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'B4','tipe'=>'Tipe 36/72', 'status'=>'booking', 'harga_raw'=>310_000_000],
            ['blok'=>'B5','tipe'=>'Tipe 54/108','status'=>'terjual', 'harga_raw'=>450_000_000],
            ['blok'=>'B6','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            // Blok C
            ['blok'=>'C1','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'C2','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'C3','tipe'=>'Tipe 54/108','status'=>'booking', 'harga_raw'=>450_000_000],
            ['blok'=>'C4','tipe'=>'Tipe 36/72', 'status'=>'terjual', 'harga_raw'=>310_000_000],
            ['blok'=>'C5','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'C6','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
            // Blok D
            ['blok'=>'D1','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'D2','tipe'=>'Tipe 36/72', 'status'=>'booking', 'harga_raw'=>310_000_000],
            ['blok'=>'D3','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
            ['blok'=>'D4','tipe'=>'Tipe 45/90', 'status'=>'terjual', 'harga_raw'=>390_000_000],
            ['blok'=>'D5','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'D6','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
        ];
    }

    private function getUnits(): array
    {
        return array_map(function ($u) {
            return [
                'blok'   => $u['blok'],
                'tipe'   => $u['tipe'],
                'status' => $u['status'],
                'harga'  => 'Rp ' . number_format($u['harga_raw'], 0, ',', '.'),
            ];
        }, $this->masterUnits());
    }

    private function getUnitDetail(string $blok): array
    {
        // Cari dari master data
        $master = collect($this->masterUnits())->firstWhere('blok', strtoupper($blok));

        // Default jika blok tidak ditemukan
        if (!$master) {
            $master = ['blok'=>$blok,'tipe'=>'Tipe 36/72','status'=>'tersedia','harga_raw'=>350_000_000];
        }

        $harga_raw  = $master['harga_raw'];
        $tipe_str   = str_replace('Tipe ', '', $master['tipe']); // e.g. "54/108"
        $tipe_parts = explode('/', $tipe_str);
        $lb         = ($tipe_parts[0] ?? '54') . ' m²';
        $lt         = ($tipe_parts[1] ?? '108') . ' m²';

        // Hitung KPR
        $dp_raw     = $harga_raw * 0.20;
        $pokok      = $harga_raw - $dp_raw;
        $r          = 0.08 / 12;

        $n15        = 15 * 12;
        $cicilan_15 = ($pokok * $r * pow(1 + $r, $n15)) / (pow(1 + $r, $n15) - 1);

        $n20        = 20 * 12;
        $cicilan_20 = ($pokok * $r * pow(1 + $r, $n20)) / (pow(1 + $r, $n20) - 1);

        // KT/KM berdasarkan tipe
        $kt = (int)($tipe_parts[0] ?? 36) >= 54 ? 3 : ((int)($tipe_parts[0] ?? 36) >= 45 ? 2 : 2);
        $km = (int)($tipe_parts[0] ?? 36) >= 54 ? 2 : 1;

        return [
            'blok'       => $master['blok'],
            'tipe'       => $tipe_str,
            'nama'       => "Detail Unit Blok {$master['blok']} – {$master['tipe']}",
            'deskripsi'  => 'Hunian modern minimalis dengan desain nyaman untuk keluarga muda.',
            'status'     => ucfirst($master['status']),
            'kt'         => $kt,
            'km'         => $km,
            'lantai'     => 1,
            'sertifikat' => 'SHM',
            'lb'         => $lb,
            'lt'         => $lt,
            'harga'      => 'Rp ' . number_format($harga_raw, 0, ',', '.'),
            'dp'         => 'Rp ' . number_format($dp_raw, 0, ',', '.'),
            'cicilan_15' => 'Rp ' . number_format($cicilan_15, 0, ',', '.'),
            'cicilan_20' => 'Rp ' . number_format($cicilan_20, 0, ',', '.'),
            'fasilitas'  => [
                'Listrik 2200W', 'Air PDAM', 'Kitchen Set',
                'Carport 1 Mobil', 'Meja Dapur + Sink', 'Pagar Minimalis',
            ],
        ];
    }
}
