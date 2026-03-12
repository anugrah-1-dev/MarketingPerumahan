@extends('layouts.app')

@section('title', 'Bukit Shangrilla Asri – Pilihan Rumah Terbaik')

@php
    // Bangun URL & pesan WhatsApp berdasarkan agent yang aktif
    $waPeran  = $agent['jabatan'] ?? 'Marketing';
    $waNama   = $agent['nama'] ?? 'Tim Kami';
    $waRaw    = $agent['wa'] ?? '6283876766055';

    // Normalisasi nomor ke format internasional
    $waNomor = preg_replace('/\D/', '', $waRaw);
    $waNomor = preg_replace('/^0/', '62', $waNomor);
    if (!str_starts_with($waNomor, '62')) {
        $waNomor = '62' . $waNomor;
    }

    $waPesan  = urlencode("Halo, saya tertarik dengan Bukit Shangrilla Asri. Saya dari website - PIC {$waNama}.");
    $waUrl    = "https://wa.me/{$waNomor}?text={$waPesan}";

    // Baca referral code dari session dan cookie server-side
    $refCode = session('affiliate_ref_code')
            ?? request()->cookie('affiliate_ref_code')
            ?? null;
@endphp


@section('content')

    {{-- ================================================================
     HERO SECTION
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 lg:pt-16 pb-20 relative overflow-hidden">

        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-10">

            {{-- Left: Text --}}
            <div class="w-full lg:w-1/2 text-center lg:text-left z-10">
                <p class="text-[#676767] text-[15px] font-medium uppercase tracking-widest mb-4">Bukit Shangrilla Asri</p>
                <h1 class="text-[#393939] text-[40px] lg:text-[56px] font-bold leading-tight mb-5">
                    Pilihan Rumah Terbaik<br class="hidden lg:block"> untuk Keluarga Anda
                </h1>
                <p
                    class="text-[#676767] text-[17px] lg:text-[19px] font-medium leading-8 max-w-[500px] mx-auto lg:mx-0 mb-8">
                    Berbagai tipe rumah dengan desain modern dan harga terjangkau.
                    Lihat ketersediaan unit, cek lokasi, dan pesan sekarang juga.
                </p>

            </div>

            {{-- Right: Image --}}
            <div class="w-full lg:w-1/2 relative flex justify-center">
                <div class="absolute right-0 top-[-20px] w-[90%] h-[105%] bg-[#D9D9D9] rounded-[20px] z-0 hidden lg:block">
                </div>
                <img src="{{ asset('assets/images/hero.png') }}"
                    onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80';this.onerror=null;"
                    alt="Rumah Impian"
                    class="relative z-10 w-[85%] lg:w-[95%] rounded-[15px] object-cover shadow-2xl hover:scale-[1.01] transition-transform duration-700">
            </div>

        </div>

        {{-- Stats bar --}}
        <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([['86', 'Total Unit'], ['66', 'Unit Tersedia'], ['20', 'Unit Terjual']] as $stat)
                <div class="card text-center shadow-sm">
                    <p class="text-3xl font-bold text-[#393939]">{{ $stat[0] }}</p>
                    <p class="text-[#676767] text-sm mt-1">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================
     UNIT PERUMAHAN – Tipe dengan Diskon (dinamis dari DB)
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-20">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold mb-2">Unit Perumahan</h2>
                <p class="text-[#676767] text-[15px]">Tipe rumah pilihan dengan penawaran terbaik</p>
            </div>
            <a href="{{ route('tipe-rumah.publik') }}"
                class="text-sm font-semibold text-blue-600 hover:underline flex items-center gap-1">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($tipeRumahDiskon->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tipeRumahDiskon as $t)
                <a href="{{ route('tipe-rumah.detail', $t->id) }}"
                    class="block bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="relative h-[200px] overflow-hidden">
                        <img src="{{ $t->gambar_url }}" alt="{{ $t->nama_tipe }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            🔥 DISKON
                        </span>
                        <span class="absolute top-3 right-3 status-tersedia text-xs font-semibold px-3 py-1 rounded-full capitalize">
                            Stok {{ $t->stok_tersedia }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-[#676767] mb-1">LB: {{ $t->luas_bangunan }}m² &nbsp;·&nbsp; LT: {{ $t->luas_tanah }}m²</p>
                        <h3 class="text-lg font-bold text-[#393939] mb-2">{{ $t->nama_tipe }}</h3>
                        @if($t->harga_diskon)
                            <p class="text-sm text-[#999] line-through">{{ $t->harga_format }}</p>
                            <p class="text-lg font-bold text-red-500 mb-3">{{ $t->harga_diskon_format }}</p>
                        @else
                            <p class="text-lg font-bold text-[#393939] mb-3">{{ $t->harga_format }}</p>
                        @endif
                        <span class="btn-primary text-sm w-full block text-center">Lihat Detail</span>
                    </div>
                </a>
            @endforeach
        </div>
        @elseif($semuaTipeRumah->isNotEmpty())
        {{-- Jika tidak ada diskon, tampilkan semua tipe rumah (max 3) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($semuaTipeRumah->take(3) as $t)
                <a href="{{ route('tipe-rumah.detail', $t->id) }}"
                    class="block bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="relative h-[200px] overflow-hidden">
                        <img src="{{ $t->gambar_url }}" alt="{{ $t->nama_tipe }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 status-tersedia text-xs font-semibold px-3 py-1 rounded-full capitalize">
                            Stok {{ $t->stok_tersedia }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-[#676767] mb-1">LB: {{ $t->luas_bangunan }}m² &nbsp;·&nbsp; LT: {{ $t->luas_tanah }}m²</p>
                        <h3 class="text-lg font-bold text-[#393939] mb-2">{{ $t->nama_tipe }}</h3>
                        <p class="text-lg font-bold text-[#393939] mb-3">{{ $t->harga_format }}</p>
                        <span class="btn-primary text-sm w-full block text-center">Lihat Detail</span>
                    </div>
                </a>
            @endforeach
        </div>
        @else
        {{-- Fallback jika database kosong --}}
        <div class="text-center py-16 text-gray-400">
            <p class="text-lg">Belum ada tipe rumah tersedia.</p>
            <a href="{{ route('tipe-rumah.publik') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">Lihat halaman tipe rumah →</a>
        </div>
        @endif
    </section>

    {{-- ================================================================
     SOCIAL MEDIA SHOWCASE — infinite auto-scroll carousel
     ================================================================ --}}
    @if($socialMedias->isNotEmpty())
    <section class="pb-20 overflow-hidden" id="showcase-section">

        {{-- Section header --}}
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-10">
            <p class="text-[#676767] text-[13px] font-semibold uppercase tracking-widest mb-2">Social Media</p>
            <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold leading-tight">
                Social Media Updates
            </h2>
            <p class="text-[#676767] text-[15px] mt-2">Follow our latest property promotions and house tours.</p>
        </div>

        {{-- Carousel wrapper — full width, no padding so cards bleed to edges --}}
        <div class="relative" id="showcase-outer">

            {{-- Left edge fade --}}
            <div class="pointer-events-none absolute left-0 top-0 h-full w-20 lg:w-32 z-10"
                 style="background:linear-gradient(to right,#EEEEEE 30%,transparent);"></div>
            {{-- Right edge fade --}}
            <div class="pointer-events-none absolute right-0 top-0 h-full w-20 lg:w-32 z-10"
                 style="background:linear-gradient(to left,#EEEEEE 30%,transparent);"></div>

            {{-- The scrolling track --}}
            <div id="showcase-track" class="flex gap-5 select-none"
                 style="width:max-content;will-change:transform;cursor:grab;">

                {{-- Cards × 2 for seamless infinite loop --}}
                @for ($pass = 0; $pass < 2; $pass++)
                @foreach($socialMedias as $sm)
                @php $cfg = $sm->config; @endphp
                <a href="{{ $sm->content_url }}" target="_blank" rel="noopener noreferrer"
                   class="showcase-card block flex-shrink-0 rounded-[16px] overflow-hidden bg-white group"
                   style="width:280px;text-decoration:none;transition:transform .25s ease,box-shadow .25s ease;"
                   aria-label="{{ e($sm->title) }} — {{ $cfg['name'] }}">

                    {{-- Thumbnail --}}
                    <div class="relative overflow-hidden" style="height:175px;background:#f1f5f9;">
                        @if($sm->thumbnail_src)
                            <img src="{{ $sm->thumbnail_src }}" alt="{{ e($sm->title) }}"
                                 loading="lazy"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform .35s ease;display:block;">
                        @else
                            {{-- Placeholder with platform icon --}}
                            <div class="w-full h-full flex flex-col items-center justify-center gap-3"
                                 style="background:#f8fafc;">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                     style="background:{{ $cfg['color'] }};">
                                    <svg style="width:22px;height:22px;fill:white;" viewBox="0 0 24 24">
                                        <path d="{{ $cfg['svg'] }}"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        {{-- Platform badge overlay --}}
                        <div class="absolute top-3 left-3 flex items-center gap-1.5 px-2.5 py-1 rounded-full text-white text-[11px] font-bold"
                             style="background:{{ $cfg['color'] }};backdrop-filter:blur(4px);">
                            <svg style="width:10px;height:10px;fill:white;flex-shrink:0;" viewBox="0 0 24 24">
                                <path d="{{ $cfg['svg'] }}"/>
                            </svg>
                            {{ $cfg['name'] }}
                        </div>
                    </div>

                    {{-- Card body --}}
                    <div style="padding:14px 16px 16px;">
                        <p style="font-weight:700;font-size:.875rem;color:#1e293b;margin:0 0 4px;line-height:1.4;
                                  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $sm->title }}
                        </p>
                        @if($sm->description)
                        <p style="font-size:.78rem;color:#64748b;margin:0;line-height:1.5;
                                  display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $sm->description }}
                        </p>
                        @endif
                    </div>
                </a>
                @endforeach
                @endfor

            </div>
        </div>

    </section>

    <style>
        #showcase-track {
            animation: showcaseScroll {{ max(20, $socialMedias->count() * 5) }}s linear infinite;
        }
        #showcase-section:hover #showcase-track,
        #showcase-track.dragging {
            animation-play-state: paused;
        }
        @@keyframes showcaseScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .showcase-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,.10);
        }
        .showcase-card:hover img {
            transform: scale(1.04);
        }
    </style>
    <script>
    (function () {
        var track  = document.getElementById('showcase-track');
        var outer  = document.getElementById('showcase-outer');
        if (!track || !outer) return;

        // ── Drag / Swipe ────────────────────────────────────────────────
        var isDragging = false, startX = 0, scrollStart = 0, dragDist = 0;
        var currentOffset = 0, animPaused = false;

        // We manually shift translateX during drag on top of the CSS animation
        // Simple approach: pause animation, handle pointer events for drag
        function getTranslateX(el) {
            var style = window.getComputedStyle(el);
            var mat   = new DOMMatrixReadOnly(style.transform);
            return mat.m41;
        }

        outer.addEventListener('mousedown', startDrag);
        outer.addEventListener('touchstart', startDrag, { passive: true });

        function startDrag(e) {
            isDragging  = true;
            dragDist    = 0;
            startX      = e.touches ? e.touches[0].clientX : e.clientX;
            // Capture current visual offset so we can continue from there
            currentOffset = getTranslateX(track);
            track.classList.add('dragging');
            track.style.animationPlayState = 'paused';
            track.style.transform = 'translateX(' + currentOffset + 'px)';
        }

        window.addEventListener('mousemove', onDrag);
        window.addEventListener('touchmove', onDrag, { passive: false });

        function onDrag(e) {
            if (!isDragging) return;
            if (e.cancelable) e.preventDefault();
            var x    = e.touches ? e.touches[0].clientX : e.clientX;
            var diff = x - startX;
            dragDist = Math.abs(diff);
            track.style.transform = 'translateX(' + (currentOffset + diff) + 'px)';
        }

        window.addEventListener('mouseup', endDrag);
        window.addEventListener('touchend', endDrag);

        function endDrag() {
            if (!isDragging) return;
            isDragging = false;
            track.classList.remove('dragging');
            // If barely moved it's a click — let the <a> open
            // Re-enable animation from the current position isn't easy with pure CSS,
            // so we just resume (it snaps back to the animation's own timing)
            track.style.transform = '';
            track.style.animationPlayState = '';
        }

        // Prevent accidental link navigation when user drags
        track.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (dragDist > 8) e.preventDefault();
            });
        });
    })();
    </script>
    @endif

    {{-- ================================================================
     LOKASI PERUMAHAN (Google Maps)
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
        <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold mb-2">Lokasi Kami</h2>
        <p class="text-[#676767] text-[15px] mb-8">Kunjungi kantor pemasaran dan lokasi proyek kami</p>

        <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm">
            <div class="w-full h-[300px] lg:h-[450px] rounded-[15px] overflow-hidden bg-gray-100">
                {{-- Gunakan iframe embed Google Maps di sini. 
                     Cara mendapatkan iframe:
                     1. Buka Google Maps
                     2. Cari lokasi perumahan
                     3. Klik "Bagikan" -> "Sematkan peta" -> Salin HTML
                     Ganti URL src di bawah ini dengan URL lokasi Anda. --}}
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.339678564254!2d106.9427017!3d-6.7283287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e684d0032bda4eb%3A0xe542fff8e0861194!2sKantor%20Pemasaran%20Perumahan%20Bukit%20Shangrilla%20Asri%202!5e0!3m2!1sid!2sid!4v1709999999999!5m2!1sid!2sid" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            
            <div class="mt-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-50 text-blue-600 p-3 rounded-full mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg">Kantor Pemasaran Bukit Shangrilla Asri 2</h4>
                        <p class="text-gray-500 text-sm mt-1 max-w-lg">Sukaraya, Kec. Karangbahagia, Kabupaten Bekasi, Jawa Barat 17530</p>
                    </div>
                </div>
                
                <a href="https://maps.app.goo.gl/8ih1wSvpCPuMRRfa8" target="_blank" rel="noopener noreferrer" 
                    class="bg-white border rounded-lg border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Buka di Google Maps
                </a>
            </div>
        </div>
    </section>






    {{-- ================================================================
     FLOATING WHATSAPP BUTTON – dinamis berdasarkan agent di URL
     ================================================================ --}}
    <a  href="{{ $waUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        title="Chat dengan {{ $waNama }}"
        id="wa-float-btn"
        onclick="recordWaClick(event, '{{ $waUrl }}', '{{ $agent['slug'] ?? '' }}')"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-[#25D366] text-white
               px-5 py-3 rounded-full shadow-2xl
               hover:bg-[#1ebe5d] hover:scale-105 active:scale-95
               transition-all duration-200 group">

        {{-- Ikon WA --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor"
             class="w-6 h-6 shrink-0">
            <path d="M16.003 2C8.28 2 2 8.28 2 16.003c0 2.46.666 4.843 1.93 6.93L2 30l7.27-1.904A13.938 13.938 0 0016.003 30C23.72 30 30 23.72 30 16.003 30 8.28 23.72 2 16.003 2zm0 25.447a11.93 11.93 0 01-6.09-1.666l-.437-.26-4.316 1.13 1.153-4.204-.284-.45A11.938 11.938 0 014.063 16.003c0-6.582 5.356-11.94 11.94-11.94 6.583 0 11.94 5.358 11.94 11.94 0 6.583-5.357 11.944-11.94 11.944zm6.54-8.942c-.357-.18-2.114-1.043-2.443-1.163-.328-.12-.566-.18-.804.18-.238.358-.924 1.163-1.133 1.402-.208.24-.417.27-.775.09-.357-.18-1.504-.554-2.865-1.77-1.058-.946-1.773-2.116-1.98-2.473-.208-.358-.022-.55.156-.729.16-.16.358-.417.536-.625.18-.208.24-.358.358-.596.12-.24.06-.447-.03-.626-.09-.18-.803-1.938-1.1-2.653-.29-.697-.584-.6-.804-.61-.207-.01-.447-.012-.685-.012-.238 0-.625.09-.953.447-.328.358-1.25 1.22-1.25 2.978 0 1.757 1.28 3.455 1.46 3.694.178.238 2.52 3.847 6.103 5.394.854.37 1.52.59 2.04.756.857.272 1.638.234 2.254.142.688-.102 2.114-.864 2.413-1.7.298-.835.298-1.549.208-1.7-.09-.149-.328-.238-.685-.417z"/>
        </svg>

        {{-- Label --}}
        <span class="font-semibold text-sm leading-tight">
            Chat {{ $waNama }}
        </span>
    </a>

    <script>
    // Referral code dari server (PHP session/cookie) — embed langsung sebagai JS variable
    const AFFILIATE_REF_CODE = @json($refCode ?? null);

    // Fallback: baca dari cookie browser (untuk kasus session expired tapi cookie masih ada)
    function getCookieVal(name) {
        const val = document.cookie.split('; ').find(r => r.startsWith(name + '='));
        return val ? decodeURIComponent(val.split('=')[1]) : null;
    }

    function recordWaClick(e, waUrl, slug) {
        // Jangan block navigasi WA bawaan dari <a href="...">
        // Biarkan request API berjalan di background menggunakan keepalive.

        // Prioritas: PHP session → cookie browser
        const refCode = AFFILIATE_REF_CODE || getCookieVal('affiliate_ref_code') || null;

        console.log('[WA Track] refCode:', refCode, 'slug:', slug);

        fetch('{{ route("wa-click.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({
                slug:          slug  || null,
                referral_code: refCode || null,
                page_url:      window.location.href
            }),
            keepalive: true // Penting agar request tetap terkirim meski halaman berpindah/ditutup
        }).catch(err => console.error("Gagal mencatat klik WA:", err));
    }
    </script>

@endsection

@section('scripts')

@endsection
