@extends('layouts.app')
@section('title', 'Unit Tersedia – Bukit Shangrilla Asri')

@section('head')
<style>
    .filter-btn {
        border-radius: 25px;
        padding: 8px 22px;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid #D9D9D9;
        transition: all .2s;
        cursor: pointer;
        background: #fff;
        color: #393939;
    }
    .filter-btn.active,
    .filter-btn:hover {
        background: #393939;
        color: #fff;
        border-color: #393939;
    }
    .unit-card {
        transition: transform .2s, box-shadow .2s;
    }
    .unit-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,.12);
    }
    .unit-card.terjual {
        opacity: .65;
        pointer-events: none;
    }
</style>
@endsection

@section('content')

    {{-- Page Header --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 pb-6">
        <p class="text-[#676767] text-[14px] font-medium uppercase tracking-widest mb-2">Bukit Shangrilla Asri</p>
        <h1 class="text-[#393939] text-[32px] lg:text-[44px] font-bold mb-2">Unit Perumahan Tersedia</h1>
        <p class="text-[#676767] text-[16px]">Pilih unit favoritmu dan segera hubungi kami sebelum kehabisan.</p>
    </section>

    {{-- Summary Stats: copy dari landing page, hanya tampilan --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="rounded-[20px] bg-white p-7 text-center shadow-sm flex flex-col items-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 mb-2">
                    <i class="fas fa-building text-blue-400 text-2xl"></i>
                </span>
                <p class="text-3xl font-bold text-[#393939]">{{ $totalUnit }}</p>
                <p class="text-base mt-1 font-medium text-[#676767]">Jumlah Unit</p>
            </div>
            <div class="rounded-[20px] bg-white p-7 text-center shadow-sm flex flex-col items-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mb-2">
                    <i class="fas fa-door-open text-green-600 text-2xl"></i>
                </span>
                <p class="text-3xl font-bold text-[#393939]">{{ $totalTersedia }}</p>
                <p class="text-base mt-1 font-medium text-[#676767]">Unit Tersedia</p>
            </div>
            <div class="rounded-[20px] bg-white p-7 text-center shadow-sm flex flex-col items-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-50 mb-2">
                    <i class="fas fa-house-circle-xmark text-red-500 text-2xl"></i>
                </span>
                <p class="text-3xl font-bold text-[#393939]">{{ $totalTerjual }}</p>
                <p class="text-base mt-1 font-medium text-[#676767]">Unit Terjual</p>
            </div>
            <div class="rounded-[20px] bg-white p-7 text-center shadow-sm flex flex-col items-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-50 mb-2">
                    <i class="fas fa-bookmark text-yellow-600 text-2xl"></i>
                </span>
                <p class="text-3xl font-bold text-[#393939]">{{ $totalBooking }}</p>
                <p class="text-base mt-1 font-medium text-[#676767]">Unit Dipesan</p>
            </div>
        </div>
    </section>

    {{-- Filter Buttons Dihapus --}}

    {{-- Unit Cards Grid --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
        <div id="unitGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @forelse ($units as $u)
                @php
                    $status = $u->stok_tersedia > 0 ? 'tersedia' : 'terjual';
                @endphp

                @if ($status !== 'terjual')
                    <a href="{{ route('tipe-rumah.detail', $u->id) }}"
                       data-status="{{ $status }}"
                       class="unit-card block bg-white rounded-[20px] overflow-hidden shadow-sm group">
                @else
                    <div data-status="{{ $status }}"
                         class="unit-card terjual bg-white rounded-[20px] overflow-hidden shadow-sm">
                @endif

                    {{-- Photo --}}
                    <div class="relative h-[190px] overflow-hidden">
                        <img src="{{ $u->gambar_url }}"
                             onerror="this.src='https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80';this.onerror=null;"
                             alt="Tipe {{ $u->nama_tipe }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-full capitalize
                            {{ $status === 'tersedia' ? 'status-tersedia' : 'status-terjual' }}">
                            {{ $status === 'tersedia' ? 'Tersedia' : 'Terjual' }}
                        </span>
                        @if($u->is_diskon && $u->harga_diskon)
                            <div class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">DISKON</div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-5">
                        <p class="text-xs text-[#676767] mb-1">{{ $u->nama_tipe }}</p>
                        @if($u->is_diskon && $u->harga_diskon)
                            <p class="text-lg font-bold text-[#393939] mb-2">
                                <span class="line-through text-gray-400 font-normal mr-1 text-sm">{{ $u->harga_format }}</span>
                                {{ $u->harga_diskon_format }}
                            </p>
                        @else
                            <p class="text-lg font-bold text-[#393939] mb-2">{{ $u->harga_format }}</p>
                        @endif
                        <div class="flex items-center gap-3 text-xs text-[#676767] mb-4">
                            <span><i class="fas fa-bed"></i> {{ $u->kamar_tidur }} KT</span>
                            <span><i class="fas fa-bath"></i> {{ $u->kamar_mandi }} KM</span>
                        </div>
                        @if ($status !== 'terjual')
                            <span class="btn-primary text-sm w-full block text-center">Lihat Detail</span>
                        @else
                            <span class="w-full block text-center text-sm font-semibold px-4 py-2 rounded-[25px] bg-gray-100 text-gray-400 cursor-not-allowed">Stok Habis</span>
                        @endif
                    </div>

                @if ($status !== 'terjual')
                    </a>
                @else
                    </div>
                @endif

            @empty
                <div class="col-span-full text-center py-16">
                    <p class="text-[#676767] text-lg">Belum ada tipe rumah tersedia.</p>
                </div>
            @endforelse

        </div>

        <p id="emptyState" class="hidden text-center text-[#676767] py-16 text-lg">Tidak ada unit yang sesuai filter.</p>
    </section>

@endsection

{{-- Filter script dihapus --}}
