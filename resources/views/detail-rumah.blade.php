@extends('layouts.app')
@section('title')
    Detail Rumah – Blok {{ $unit['blok'] ?? '' }} – Perumahan Zahra
@endsection

@section('content')
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 pb-20">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-[#676767] mb-8 flex items-center gap-2">
            <a href="{{ route('landing') }}" class="hover:text-[#393939]">Home</a>
            <span>/</span>
            <a href="{{ route('unit-tersedia') }}" class="hover:text-[#393939]">Unit Tersedia</a>
            <span>/</span>
            <span class="text-[#393939] font-medium">Blok {{ $unit['blok'] }}</span>
        </nav>

        {{-- Title --}}
        <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[#676767] text-sm uppercase tracking-widest mb-1">DetaiL Rumah</p>
                <h1 class="text-[#393939] text-[28px] lg:text-[38px] font-bold">{{ $unit['nama'] }}</h1>
                <p class="text-[#676767] mt-2 max-w-[560px]">{{ $unit['deskripsi'] }}</p>
            </div>
            <span
                class="self-start lg:self-auto text-sm font-semibold px-4 py-2 rounded-full
            {{ $unit['status'] === 'Tersedia' ? 'status-tersedia' : ($unit['status'] === 'Booking' ? 'status-booking' : 'status-terjual') }}">
                {{ $unit['status'] }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ====== LEFT: Images + Spesifikasi ====== --}}
            <div class="lg:col-span-2 space-y-7">

                {{-- Photo Gallery --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <p class="text-xs text-[#676767] mb-3">Tampilan rumah dari berbagai sisi untuk membantu Anda melihat
                        detail desain dan kualitas bangunan.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-semibold text-[#393939] mb-2">Tampak Depan</p>
                            <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80"
                                alt="Tampak Depan" class="w-full h-[200px] object-cover rounded-[12px]">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#393939] mb-2">Tampak Dalam</p>
                            <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600&q=80"
                                alt="Tampak Dalam" class="w-full h-[200px] object-cover rounded-[12px]">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#393939] mb-2">Tipe Jenis</p>
                            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80"
                                alt="Tipe Jenis" class="w-full h-[200px] object-cover rounded-[12px]">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#393939] mb-2">Area Sekitar</p>
                            <img src="https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80"
                                alt="Area Sekitar" class="w-full h-[200px] object-cover rounded-[12px]">
                        </div>
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h2 class="text-[#393939] text-lg font-bold mb-5">📋 Spesifikasi</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                        @foreach ([['🛏', 'Kamar Tidur', $unit['kt'] . ' Kamar'], ['🚿', 'Kamar Mandi', $unit['km'] . ' Kamar'], ['🏢', 'Lantai', $unit['lantai'] . ' Lantai'], ['📜', 'Sertifikat', $unit['sertifikat']], ['📐', 'Luas Bangunan', $unit['lb']], ['🌿', 'Luas Tanah', $unit['lt']]] as $spec)
                            <div class="bg-[#F7F7F7] rounded-[14px] p-4 text-center">
                                <p class="text-xl mb-1">{{ $spec[0] }}</p>
                                <p class="text-xs text-[#676767]">{{ $spec[1] }}</p>
                                <p class="text-sm font-bold text-[#393939] mt-1">{{ $spec[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h2 class="text-[#393939] text-lg font-bold mb-4">Fasilitas</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($unit['fasilitas'] as $f)
                            <div class="flex items-center gap-2 text-sm text-[#393939]">
                                <span class="text-green-600 font-bold">✓</span> {{ $f }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ====== RIGHT: Harga & Booking ====== --}}
            <div class="space-y-6">

                {{-- Harga --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <p class="text-[#676767] text-sm mb-1">💰 Harga</p>
                    <p class="text-[#393939] text-3xl font-bold mb-5">{{ $unit['harga'] }}</p>

                    <div class="grid grid-cols-2 gap-3 mb-5">
                        @foreach ([['DP 20%', $unit['dp']], ['Cicilan/Bln (15th)', $unit['cicilan_15']], ['Cicilan/Bln (20th)', $unit['cicilan_20']], ['Bunga/Tahun', '8%']] as $h)
                            <div class="bg-[#F7F7F7] rounded-[12px] p-3">
                                <p class="text-[10px] text-[#676767]">{{ $h[0] }}</p>
                                <p class="text-sm font-bold text-[#393939]">{{ $h[1] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xs text-[#676767] mb-5">Skema KPR: Cicilan jangka panjang dengan bank</p>

                    @if ($unit['status'] === 'Tersedia')
                        <a href="{{ route('form-booking', $unit['blok']) }}"
                            class="btn-primary w-full block text-center py-3">Booking Sekarang</a>
                    @else
                        <button
                            class="w-full bg-gray-200 text-gray-400 rounded-[25px] py-3 font-semibold cursor-not-allowed">
                            {{ $unit['status'] === 'Terjual' ? 'Unit Sudah Terjual' : 'Sedang Dalam Proses Booking' }}
                        </button>
                    @endif
                </div>

                {{-- Status Info --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h3 class="font-bold text-[#393939] mb-3">Informasi Unit</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Status</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['status'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">KT</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['kt'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">KM</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['km'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Lantai</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['lantai'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Sertifikat</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['sertifikat'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">LB</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['lb'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">LT</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['lt'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="bg-[#393939] rounded-[20px] p-6 text-white">
                    <h3 class="font-bold mb-2">Butuh Bantuan?</h3>
                    <p class="text-gray-300 text-sm mb-4">Tim kami siap membantu Anda memilih unit terbaik.</p>
                    <a href="https://wa.me/6212345678909" target="_blank"
                        class="block bg-white text-[#393939] text-center font-semibold rounded-[25px] py-3 hover:bg-gray-100 transition-colors">
                        📞 Hubungi via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
