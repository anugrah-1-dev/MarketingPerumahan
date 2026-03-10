@extends('layouts.app')

@section('title', 'Daftar Tipe Rumah – Bukit Shangrilla Asri')

@section('content')
<section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] py-16">

    <div class="mb-10 text-center">
        <h1 class="text-[36px] font-bold text-[#393939] mb-2">Daftar Tipe Rumah</h1>
        <p class="text-[#676767] text-[16px]">Pilih tipe rumah yang sesuai dengan kebutuhan dan budget Anda</p>
    </div>

    @if($tipeRumah->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <p class="text-lg font-medium">Belum ada tipe rumah tersedia.</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tipeRumah as $t)
        <div class="bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="relative h-[200px] overflow-hidden">
                <img src="{{ $t->gambar_url }}" alt="{{ $t->nama_tipe }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @if($t->is_diskon)
                    <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                        🔥 DISKON
                    </span>
                @endif
                @if($t->stok_tersedia > 0)
                    <span class="absolute top-3 right-3 status-tersedia text-xs font-semibold px-3 py-1 rounded-full capitalize">
                        Tersedia
                    </span>
                @else
                    <span class="absolute top-3 right-3 status-terjual text-xs font-semibold px-3 py-1 rounded-full capitalize">
                        Habis
                    </span>
                @endif
            </div>
            <div class="p-5">
                <h3 class="text-lg font-bold text-[#393939] mb-1">{{ $t->nama_tipe }}</h3>
                <p class="text-xs text-[#676767] mb-3">LB: {{ $t->luas_bangunan }}m² &nbsp;|&nbsp; LT: {{ $t->luas_tanah }}m² &nbsp;|&nbsp; Stok: {{ $t->stok_tersedia }}</p>
                @if($t->is_diskon && $t->harga_diskon)
                    <p class="text-sm text-[#999] line-through">{{ $t->harga_format }}</p>
                    <p class="text-xl font-bold text-red-500 mb-1">{{ $t->harga_diskon_format }}</p>
                @else
                    <p class="text-xl font-bold text-[#393939] mb-1">{{ $t->harga_format }}</p>
                @endif
                @if($t->deskripsi)
                    <p class="text-sm text-[#676767] mt-2 line-clamp-2">{{ $t->deskripsi }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

</section>
@endsection
