@extends('layouts.app')

@section('title', 'Daftar Tipe Rumah – Bukit Shangrilla Asri')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/admin/css/tiperumah.css') }}">
@endsection

@section('content')
<section class="tr-section">

    <div class="tr-header">
        <h1>Daftar Tipe Rumah</h1>
        <p>Pilih tipe rumah yang sesuai dengan kebutuhan dan budget Anda</p>
    </div>

    @if($tipeRumah->isEmpty())
        <div class="tr-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <p>Belum ada tipe rumah tersedia.</p>
        </div>
    @else
    <div class="tr-grid">
        @foreach($tipeRumah as $t)
        <a href="{{ route('tipe-rumah.detail', $t->id) }}" class="tr-card block" style="text-decoration: none; color: inherit; display: block;">
            <div class="tr-card-img">
                <div style="display:flex; overflow-x:auto; scroll-snap-type:x mandatory; scroll-behavior:smooth; height:100%;">
                    @foreach($t->gallery_urls as $img)
                        <img src="{{ $img }}" alt="{{ $t->nama_tipe }}" style="width:100%; flex:0 0 100%; object-fit:cover; scroll-snap-align:center;">
                    @endforeach
                </div>

                @if(count($t->gallery_urls) > 1)
                    <span class="tr-badge tr-badge-status" style="right:14px; bottom:14px; top:auto; background:rgba(0,0,0,.55); color:#fff;">
                        {{ count($t->gallery_urls) }} foto • geser
                    </span>
                @endif

                @if($t->is_diskon)
                    <span class="tr-badge tr-badge-diskon">🔥 DISKON</span>
                @endif

                @if($t->stok_tersedia > 0)
                    <span class="tr-badge tr-badge-status tr-badge-tersedia">Tersedia</span>
                @else
                    <span class="tr-badge tr-badge-status tr-badge-habis">Habis</span>
                @endif
            </div>

            <div class="tr-card-body">
                <h3 class="tr-card-title">{{ $t->nama_tipe }}</h3>
                <p class="tr-card-meta">
                    LB: {{ $t->luas_bangunan }}m²
                    <span>|</span>
                    LT: {{ $t->luas_tanah }}m²
                    <span>|</span>
                    Stok: {{ $t->stok_tersedia }}
                </p>

                @if($t->is_diskon && $t->harga_diskon)
                    <p class="tr-price-original">{{ $t->harga_format }}</p>
                    <p class="tr-price-main is-diskon">{{ $t->harga_diskon_format }}</p>
                @else
                    <p class="tr-price-main">{{ $t->harga_format }}</p>
                @endif

                @if($t->deskripsi)
                    <p class="tr-card-desc">{{ $t->deskripsi }}</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    @endif

</section>
@endsection
