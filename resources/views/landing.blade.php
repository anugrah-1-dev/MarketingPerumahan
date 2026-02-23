@extends('layouts.app')

@section('title', 'Perumahan Zahra – Pilihan Rumah Terbaik')

@section('content')

    {{-- ================================================================
     HERO SECTION
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 lg:pt-16 pb-20 relative overflow-hidden">

        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-10">

            {{-- Left: Text --}}
            <div class="w-full lg:w-1/2 text-center lg:text-left z-10">
                <p class="text-[#676767] text-[15px] font-medium uppercase tracking-widest mb-4">Perumahan Zahra</p>
                <h1 class="text-[#393939] text-[40px] lg:text-[56px] font-bold leading-tight mb-5">
                    Pilihan Rumah Terbaik<br class="hidden lg:block"> untuk Keluarga Anda
                </h1>
                <p
                    class="text-[#676767] text-[17px] lg:text-[19px] font-medium leading-8 max-w-[500px] mx-auto lg:mx-0 mb-8">
                    Berbagai tipe rumah dengan desain modern dan harga terjangkau.
                    Lihat ketersediaan unit, cek lokasi, dan pesan sekarang juga.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="{{ route('unit-tersedia') }}" class="btn-primary">Lihat Unit Tersedia</a>
                    <a href="{{ route('site-plan') }}" class="btn-outline">Lihat Site Plan</a>
                </div>
            </div>

            {{-- Right: Image --}}
            <div class="w-full lg:w-1/2 relative flex justify-center">
                <div class="absolute right-0 top-[-20px] w-[90%] h-[105%] bg-[#D9D9D9] rounded-[20px] z-0 hidden lg:block">
                </div>
                <img src="{{ asset('images/hero.png') }}"
                    onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80';this.onerror=null;"
                    alt="Rumah Impian"
                    class="relative z-10 w-[85%] lg:w-[95%] rounded-[15px] object-cover shadow-2xl hover:scale-[1.01] transition-transform duration-700">
            </div>

        </div>

        {{-- Stats bar --}}
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([['86', 'Total Unit'], ['40', 'Unit Tersedia'], ['20', 'Unit Terjual'], ['26', 'Unit Booking']] as $stat)
                <div class="card text-center shadow-sm">
                    <p class="text-3xl font-bold text-[#393939]">{{ $stat[0] }}</p>
                    <p class="text-[#676767] text-sm mt-1">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================
     DILIHAT BARU-BARU INI
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-20">
        <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold mb-2">Dilihat Baru-Baru Ini</h2>
        <p class="text-[#676767] text-[15px] mb-8">Unit pilihan yang banyak dilihat calon pembeli</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([['blok' => 'A1', 'tipe' => 'Tipe 36/72', 'harga' => 'Rp 310.000.000', 'status' => 'tersedia', 'img' => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80'], ['blok' => 'B3', 'tipe' => 'Tipe 45/90', 'harga' => 'Rp 390.000.000', 'status' => 'tersedia', 'img' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80'], ['blok' => 'C3', 'tipe' => 'Tipe 54/108', 'harga' => 'Rp 350.000.000', 'status' => 'booking', 'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80']] as $u)
                <div class="bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative h-[200px] overflow-hidden">
                        <img src="{{ $u['img'] }}" alt="Unit {{ $u['blok'] }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        <span
                            class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-full capitalize
                    {{ $u['status'] === 'tersedia' ? 'status-tersedia' : ($u['status'] === 'booking' ? 'status-booking' : 'status-terjual') }}">
                            {{ ucfirst($u['status']) }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-[#676767] mb-1">Blok {{ $u['blok'] }} · {{ $u['tipe'] }}</p>
                        <p class="text-lg font-bold text-[#393939] mb-3">{{ $u['harga'] }}</p>
                        <a href="{{ route('detail-rumah', $u['blok']) }}"
                            class="btn-primary text-sm w-full block text-center">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================
     UNIT TERSEDIA HARI INI (CTA Section)
     ================================================================ --}}
    <section class="bg-[#393939] py-20">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="text-white text-center lg:text-left">
                <h2 class="text-[30px] lg:text-[40px] font-bold leading-tight mb-3">
                    Temukan Unit Rumah yang<br class="hidden lg:block"> Tersedia Hari Ini
                </h2>
                <p class="text-gray-300 text-[16px] leading-7 max-w-[520px] mx-auto lg:mx-0">
                    Cek langsung ketersediaan unit per blok, lihat site plan kami,
                    dan segera booking sebelum kehabisan!
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                <a href="{{ route('unit-tersedia') }}"
                    class="bg-white text-[#393939] font-semibold px-8 py-3 rounded-[25px] hover:bg-gray-100 transition-colors text-center">Cek
                    Unit Tersedia</a>
                <a href="{{ route('site-plan') }}"
                    class="border-2 border-white text-white font-semibold px-8 py-3 rounded-[25px] hover:bg-white hover:text-[#393939] transition-colors text-center">Lihat
                    Site Plan</a>
            </div>
        </div>
    </section>

    {{-- ================================================================
     SIMULASI KPR
     ================================================================ --}}
    <section id="simulasi" class="max-w-[1440px] mx-auto px-6 lg:px-[80px] py-20">
        <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold mb-2">Simulasi KPR</h2>
        <p class="text-[#676767] text-[15px] mb-10">Hitung estimasi cicilan rumah impian Anda</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            {{-- Form simulasi --}}
            <div class="card shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Harga Rumah</label>
                        <input id="sim_harga" type="number" class="input-field" placeholder="350000000" value="350000000">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Uang Muka (%)</label>
                        <input id="sim_dp" type="number" class="input-field" placeholder="20" value="20">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Tenor (Tahun)</label>
                        <select id="sim_tenor" class="input-field">
                            <option value="10">10 Tahun</option>
                            <option value="15" selected>15 Tahun</option>
                            <option value="20">20 Tahun</option>
                            <option value="25">25 Tahun</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Bunga / Tahun (%)</label>
                        <input id="sim_bunga" type="number" class="input-field" placeholder="8" value="8"
                            step="0.1">
                    </div>
                </div>
                <button onclick="hitungKPR()" class="btn-primary mt-6 w-full">Hitung Cicilan</button>
            </div>

            {{-- Result --}}
            <div id="sim_result" class="card shadow-sm hidden">
                <h3 class="text-lg font-bold text-[#393939] mb-5">Estimasi Cicilan</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-[#676767] text-sm">Harga Rumah</span>
                        <span id="res_harga" class="font-semibold text-[#393939]">-</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-[#676767] text-sm">Uang Muka (20%)</span>
                        <span id="res_dp" class="font-semibold text-[#393939]">-</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-[#676767] text-sm">Pokok Pinjaman</span>
                        <span id="res_pokok" class="font-semibold text-[#393939]">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[#676767] text-sm font-semibold">Cicilan / Bulan</span>
                        <span id="res_cicilan" class="text-xl font-bold text-[#393939]">-</span>
                    </div>
                </div>
                <a href="{{ route('unit-tersedia') }}" class="btn-primary mt-6 w-full block text-center">Booking
                    Sekarang</a>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function hitungKPR() {
            const harga = parseFloat(document.getElementById('sim_harga').value) || 0;
            const dpPct = parseFloat(document.getElementById('sim_dp').value) || 20;
            const tenor = parseFloat(document.getElementById('sim_tenor').value) || 15;
            const bunga = parseFloat(document.getElementById('sim_bunga').value) || 8;

            const dp = harga * dpPct / 100;
            const pokok = harga - dp;
            const r = (bunga / 100) / 12;
            const n = tenor * 12;
            const cicilan = r === 0 ? pokok / n : (pokok * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);

            document.getElementById('res_harga').textContent = formatRupiah(harga);
            document.getElementById('res_dp').textContent = formatRupiah(dp);
            document.getElementById('res_pokok').textContent = formatRupiah(pokok);
            document.getElementById('res_cicilan').textContent = formatRupiah(cicilan);
            document.getElementById('sim_result').classList.remove('hidden');
        }
    </script>
@endsection
