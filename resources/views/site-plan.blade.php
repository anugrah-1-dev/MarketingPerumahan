@extends('layouts.app')
@section('title', 'Site Plan – Perumahan Zahra')

@section('head')
<style>
    .site-block { transition: transform .15s, box-shadow .15s; cursor: pointer; }
    .site-block:hover { transform: scale(1.04); box-shadow: 0 6px 20px rgba(0,0,0,.15); z-index:10; }
    .site-block.tersedia { background: #D1FAE5; border-color: #059669; }
    .site-block.booking  { background: #FEF3C7; border-color: #D97706; }
    .site-block.terjual  { background: #FEE2E2; border-color: #DC2626; cursor:default; }
    .site-block.terjual:hover { transform: none; box-shadow: none; }

    /* Tooltip */
    .site-block:not(.terjual):hover .tooltip { display: block; }
    .tooltip { display:none; position:absolute; bottom:calc(100% + 6px); left:50%; transform:translateX(-50%);
               background:#393939; color:#fff; font-size:11px; white-space:nowrap; padding:4px 10px;
               border-radius:8px; z-index:20; pointer-events:none; }
    .tooltip::after { content:''; position:absolute; top:100%; left:50%; transform:translateX(-50%);
                      border:5px solid transparent; border-top-color:#393939; }
</style>
@endsection

@section('content')

{{-- Header --}}
<section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 pb-8">
    <h1 class="text-[#393939] text-[32px] lg:text-[44px] font-bold mb-2">Temukan Letak Unit Pilihan Anda</h1>
    <p class="text-[#676767] text-[15px]">Klik pada blok untuk melihat detail unit.</p>
</section>

{{-- Summary --}}
<section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-8">
    <div class="flex flex-wrap gap-4">
        @foreach([
            ['Total Unit', '86', 'bg-[#393939] text-white'],
            ['Tersedia',   '40', 'bg-[#D1FAE5] text-[#065F46]'],
            ['Terjual',    '20', 'bg-[#FEE2E2] text-[#991B1B]'],
            ['Booking',    '26', 'bg-[#FEF3C7] text-[#92400E]'],
        ] as $s)
        <div class="rounded-[16px] {{ $s[2] }} px-6 py-3 flex items-center gap-3 shadow-sm">
            <span class="text-2xl font-bold">{{ $s[1] }}</span>
            <span class="text-sm font-medium">{{ $s[0] }}</span>
        </div>
        @endforeach
    </div>
</section>

{{-- Legend --}}
<section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-6">
    <div class="flex flex-wrap gap-6 items-center">
        <span class="font-semibold text-sm text-[#393939]">Keterangan:</span>
        @foreach([
            ['Tersedia', 'bg-[#D1FAE5]', 'border-[#059669]'],
            ['Booking',  'bg-[#FEF3C7]', 'border-[#D97706]'],
            ['Terjual',  'bg-[#FEE2E2]', 'border-[#DC2626]'],
        ] as $l)
        <div class="flex items-center gap-2">
            <span class="w-5 h-5 rounded border-2 {{ $l[1] }} {{ $l[2] }} inline-block"></span>
            <span class="text-xs text-[#676767]">{{ $l[0] }}</span>
        </div>
        @endforeach
    </div>
</section>

{{-- Site Plan Map --}}
<section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-20">
    <div class="bg-white rounded-[24px] p-6 lg:p-10 shadow-sm overflow-x-auto">

        {{-- Road label --}}
        <div class="text-center mb-6">
            <div class="inline-block bg-[#C8C8C8] text-[#393939] text-sm font-semibold px-10 py-2 rounded-full">
                ← JALAN UTAMA →
            </div>
        </div>

        {{-- Grid blocks per blok --}}
        <div class="space-y-8 min-w-[640px]">
            @php
                $bloks = ['A', 'B', 'C', 'D'];
                foreach ($bloks as $blokName) {
                    $blokUnits = array_filter($units, fn($u) => str_starts_with($u['blok'], $blokName));
            @endphp

            <div class="flex items-center gap-4">
                <span class="w-8 text-center text-sm font-bold text-[#393939] shrink-0">{{ $blokName }}</span>
                <div class="flex flex-wrap gap-3">
                    @foreach($blokUnits as $u)
                    <div class="relative site-block border-2 rounded-[12px] w-[90px] h-[80px] flex flex-col items-center justify-center {{ $u['status'] }}"
                         @if($u['status'] !== 'terjual')
                         onclick="window.location='{{ route('detail-rumah', $u['blok']) }}'"
                         @endif>
                        <div class="tooltip">{{ $u['tipe'] }} · {{ $u['harga'] }}</div>
                        <p class="font-bold text-sm text-[#393939]">{{ $u['blok'] }}</p>
                        <p class="text-[10px] text-[#676767]">{{ $u['tipe'] }}</p>
                        <span class="text-[10px] font-semibold capitalize mt-1">{{ ucfirst($u['status']) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            @php } @endphp
        </div>

        {{-- Road at the bottom --}}
        <div class="text-center mt-8">
            <div class="inline-block bg-[#C8C8C8] text-[#393939] text-sm font-semibold px-10 py-2 rounded-full">
                ← JALAN UTAMA →
            </div>
        </div>

        {{-- Additional Info --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-[#676767]">
            <div class="flex items-center gap-2">📍 <span>Jl. Semangi, Semarang, Jawa Tengah</span></div>
            <div class="flex items-center gap-2">📐 <span>Luas Kawasan ± 2 Hektar</span></div>
            <div class="flex items-center gap-2">🏠 <span>86 Unit Total (4 Blok)</span></div>
        </div>
    </div>
</section>

@endsection
