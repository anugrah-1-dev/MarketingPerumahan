@extends('layouts.app')

@section('title', 'Bukit Shangrilla Asri - Hunian Modern Strategis')

@section('head')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        .swiper-hero {
            width: 85%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        @media (min-width: 1024px) {
            .swiper-hero {
                width: 95%;
            }
        }
        .swiper-pagination-bullet {
            background: #fff;
            opacity: 0.7;
        }
        .swiper-pagination-bullet-active {
            background: #fff;
            opacity: 1;
        }
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
            background: rgba(0,0,0,0.3);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: rgba(0,0,0,0.6);
        }
        .swiper-button-next::after, .swiper-button-prev::after {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@endsection

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

    $heroFallbackSlide = 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&q=80';
    if (empty($heroSlides ?? [])) {
        $heroSlides = [$heroFallbackSlide];
    }
@endphp


@section('content')

    {{-- ================================================================
     BAGIAN HERO
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 lg:pt-16 pb-20 relative overflow-hidden">

        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-10">

            {{-- Kiri: Teks --}}
            <div class="w-full lg:w-1/2 text-center lg:text-left z-10">
                <h1 class="text-[#393939] text-[36px] lg:text-[52px] font-bold leading-tight mb-5">
                    Hunian Modern Strategis: Investasi Cerdas untuk Keluarga
                </h1>
                <p
                    class="text-[#676767] text-[17px] lg:text-[19px] font-medium leading-8 max-w-[620px] mx-auto lg:mx-0 mb-8">
                    Dapatkan rumah mewah harga terjangkau dengan nilai investasi yang terus bertumbuh.
                    Terletak di lokasi emas dengan fasilitas lengkap dan desain kekinian.
                    Unit terbatas! Cek lokasi sekarang sebelum kehabisan dan dapatkan promo menarik bulan ini.
                </p>

            </div>

            {{-- Kanan: Slider Beranda --}}
            <div class="w-full lg:w-1/2 relative flex justify-center">
                <div class="absolute right-0 top-[-20px] w-[90%] h-[105%] bg-[#D9D9D9] rounded-[20px] z-0 hidden lg:block">
                </div>

                <div id="hero-slider" class="relative z-10 w-[85%] lg:w-[95%] rounded-[15px] overflow-hidden shadow-2xl" data-total="{{ count($heroSlides) }}">
                    <div id="hero-track" class="flex transition-transform duration-700 ease-out">
                        @foreach ($heroSlides as $index => $slide)
                            <div class="w-full shrink-0">
                                  <img src="{{ $slide }}"
                                      onerror="this.src='{{ $heroFallbackSlide }}';this.onerror=null;"
                                     alt="Foto perumahan {{ $index + 1 }}"
                                     class="w-full h-[260px] sm:h-[340px] lg:h-[430px] object-cover">
                            </div>
                        @endforeach
                    </div>

                    <button id="hero-prev" type="button" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 text-[#393939] shadow flex items-center justify-center hover:bg-white transition" aria-label="Slide sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>

                    <button id="hero-next" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 text-[#393939] shadow flex items-center justify-center hover:bg-white transition" aria-label="Slide berikutnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>

                    <div id="hero-dots" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                        @foreach ($heroSlides as $index => $slide)
                            <button type="button"
                                    class="hero-dot w-2.5 h-2.5 rounded-full bg-white/60 transition"
                                    data-slide="{{ $index }}"
                                    aria-label="Pilih slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Bar statistik --}}
        @php
            $stats = [
                ['value' => $unitStats['total'] ?? 0, 'label' => 'Jumlah Unit', 'icon' => 'home', 'iconClass' => 'bg-[#EEF5FF] text-[#1D4ED8]'],
                ['value' => $unitStats['tersedia'] ?? 0, 'label' => 'Unit Tersedia', 'icon' => 'check', 'iconClass' => 'bg-[#ECFDF3] text-[#047857]'],
                ['value' => $unitStats['terjual'] ?? 0, 'label' => 'Unit Terjual', 'icon' => 'chart', 'iconClass' => 'bg-[#FFF1F2] text-[#BE123C]'],
                ['value' => $unitStats['booking'] ?? 0, 'label' => 'Unit Dipesan', 'icon' => 'calendar', 'iconClass' => 'bg-[#FFFBEB] text-[#B45309]'],
            ];
        @endphp
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($stats as $stat)
                <div class="card text-center shadow-sm flex flex-col items-center">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center {{ $stat['iconClass'] }} mb-3">
                        @if($stat['icon'] === 'home')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9.5V20h14V9.5" />
                            </svg>
                        @elseif($stat['icon'] === 'check')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                            </svg>
                        @elseif($stat['icon'] === 'chart')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l4-5 3 3 3-5" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                            </svg>
                        @endif
                    </div>
                    <p class="text-3xl font-bold text-[#393939]">{{ $stat['value'] }}</p>
                    <p class="text-[#676767] text-sm mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================
     UNIT PERUMAHAN
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-20">
        <div class="bg-white rounded-[24px] border border-[#E9E9E9] p-6 lg:p-8 shadow-[0_18px_36px_rgba(0,0,0,0.06)]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-7">
                <div>
                    <h3 class="text-[#393939] text-[22px] lg:text-[28px] font-bold">Tipe Pilihan Bukit Shangrilla Asri</h3>
                    <p class="text-[#676767] text-sm">Temukan berbagai pilihan tipe rumah modern yang dirancang untuk kenyamanan, keindahan, dan kebutuhan keluarga masa kini.</p>
                </div>
                <a href="{{ route('unit-tersedia') }}"
                    class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:underline w-fit">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($semuaTipeRumah->isEmpty())
                <p class="text-center text-[#676767] py-10">Tipe rumah belum tersedia. Silakan tambah melalui panel admin.</p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                @foreach($semuaTipeRumah->take(4) as $tipe)
                    @php
                        $fitur = [];
                        if ($tipe->lantai)       $fitur[] = $tipe->lantai . ' Lantai';
                        if ($tipe->kamar_tidur)  $fitur[] = $tipe->kamar_tidur . ' Kamar Tidur';
                        if ($tipe->kamar_mandi)  $fitur[] = $tipe->kamar_mandi . ' Kamar Mandi';
                        if ($tipe->luas_bangunan && $tipe->luas_tanah) $fitur[] = 'Ukuran ' . $tipe->luas_bangunan . '/' . $tipe->luas_tanah;
                        // fitur tambahan dari JSON fasilitas (max 1 item)
                        if (!empty($tipe->fasilitas) && is_array($tipe->fasilitas)) {
                            $fitur[] = $tipe->fasilitas[0];
                        }
                    @endphp
                    <a href="{{ route('tipe-rumah.detail', $tipe->id) }}" class="group rounded-[20px] overflow-hidden border border-[#E7E7E7] bg-[#FCFCFC] hover:shadow-[0_18px_32px_rgba(0,0,0,0.10)] transition-all duration-300 block">
                        <div class="relative h-[210px] overflow-hidden">
                            <img src="{{ $tipe->gambar_url }}"
                                onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1000&q=80';this.onerror=null;"
                                alt="Tipe {{ $tipe->nama_tipe }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/65 to-transparent">
                                <p class="text-white text-xs tracking-wide uppercase">Tipe Rumah</p>
                                <h4 class="text-white text-2xl font-bold leading-tight">{{ $tipe->nama_tipe }}</h4>
                            </div>
                            @if($tipe->is_diskon && $tipe->harga_diskon)
                                <div class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">DISKON</div>
                            @endif
                        </div>

                        <div class="p-5">
                            <p class="text-[#676767] text-sm mb-4">{{ $tipe->deskripsi ?: 'Hunian nyaman untuk keluarga Anda.' }}</p>
                            @if($tipe->harga)
                                <p class="text-[#0B5E41] font-bold text-sm mb-3">
                                    @if($tipe->is_diskon && $tipe->harga_diskon)
                                        <span class="line-through text-gray-400 font-normal mr-1">{{ $tipe->harga_format }}</span>
                                        {{ $tipe->harga_diskon_format }}
                                    @else
                                        {{ $tipe->harga_format }}
                                    @endif
                                </p>
                            @endif
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($fitur as $f)
                                    <span class="inline-flex items-center justify-center text-center rounded-xl border border-[#D9E7DB] bg-[#F4FAF5] text-[#355B3E] text-xs font-semibold px-2 py-2">{{ $f }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    {{-- ================================================================
     FASILITAS PERUMAHAN
     ================================================================ --}}
    @php
        $fasilitas = [
            [
                'nama' => 'Masjid',
                'deskripsi' => 'Masjid nyaman di dalam kawasan perumahan, memudahkan penghuni beribadah dengan tenang dan khusyuk.',
                'icon' => 'mosque',
            ],
            [
                'nama' => 'Taman Air',
                'deskripsi' => 'Fasilitas taman air kawasan hunian yang menghadirkan hiburan air menyenangkan untuk waktu berkualitas bersama keluarga.',
                'icon' => 'person-swimming',
            ],
            [
                'nama' => 'Area Kuliner',
                'deskripsi' => 'Area kuliner dengan beragam pilihan makanan, nyaman untuk bersantai dan berkumpul bersama keluarga.',
                'icon' => 'utensils',
            ],
            [
                'nama' => 'Taman Bermain',
                'deskripsi' => 'Taman bermain aman dan seru untuk anak setiap hari, menghadirkan keceriaan di lingkungan perumahan.',
                'icon' => 'children',
            ],
            [
                'nama' => 'Pusat Olahraga',
                'deskripsi' => 'Fasilitas pusat olahraga modern untuk kebugaran penghuni setiap hari.',
                'icon' => 'dumbbell',
            ],
            [
                'nama' => 'Sistem Satu Gerbang',
                'deskripsi' => 'Sistem satu pintu keluar masuk area kluster untuk menjaga keamanan lingkungan lebih optimal.',
                'icon' => 'archway',
            ],
            [
                'nama' => 'CCTV 24 Jam',
                'deskripsi' => 'Pemantauan CCTV 24 jam membantu lingkungan perumahan menjadi lebih aman dan terpantau.',
                'icon' => 'camera',
            ],
            [
                'nama' => 'Keamanan 24 Jam',
                'deskripsi' => 'Keamanan 24 jam untuk memastikan kenyamanan dan ketenangan penghuni sepanjang waktu.',
                'icon' => 'shield-halved',
            ],
            [
                'nama' => 'Pemandangan Pegunungan',
                'deskripsi' => 'Pemandangan pegunungan dari ketinggian yang menenangkan, memberi suasana asri setiap hari.',
                'icon' => 'mountain',
            ],
            [
                'nama' => 'Lokasi Strategis',
                'deskripsi' => 'Hunian dengan akses terbaik, hanya berjarak 3 menit dari Stasiun Lawang, memudahkan mobilitas harian Anda dengan lebih efisien.',
                'icon' => 'location-dot',
            ],
        ];
    @endphp
    <section id="fasilitas" class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
        <div class="relative overflow-hidden rounded-[24px] bg-[#0B5E41] text-white shadow-xl">
            <div class="absolute -top-24 -right-20 w-72 h-72 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-16 -left-16 w-60 h-60 rounded-full bg-white/5"></div>

            <div class="relative p-6 lg:p-10">
                <h2 class="text-[28px] lg:text-[36px] font-bold mb-2">Fasilitas</h2>
                <p class="text-white/85 text-sm lg:text-base mb-8">Beragam fasilitas penunjang kenyamanan hidup di Bukit Shangrilla Asri.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    @foreach ($fasilitas as $item)
                        <div class="flex items-start gap-4 border-b border-white/20 pb-4 last:border-b-0">
                            <div class="w-12 h-12 rounded-xl border border-white/30 bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-{{ $item['icon'] }} text-xl"></i>
                            </div>

                            <div>
                                <h3 class="text-[28px] lg:text-[30px] font-semibold leading-none mb-2">{{ $item['nama'] }}</h3>
                                <p class="text-white/90 text-sm leading-6">{{ $item['deskripsi'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
     DENAH PERUMAHAN (Site Plan)
     ================================================================ --}}
    @if(!empty($denahImage))
    <section id="denah" class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
        <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold mb-2">Denah Perumahan</h2>
        <p class="text-[#676767] text-[15px] mb-8">Lihat tata letak kawasan dan posisi unit yang tersedia di Bukit Shangrilla Asri.</p>

        <div class="bg-white p-4 lg:p-6 rounded-[24px] border border-[#E9E9E9] shadow-[0_18px_36px_rgba(0,0,0,0.06)] text-center">
            <img src="{{ asset($denahImage) }}" alt="Denah Perumahan Bukit Shangrilla Asri"
                 class="w-full rounded-[16px] cursor-pointer transition-transform duration-300 hover:scale-[1.01]"
                 style="max-height:700px;object-fit:contain;"
                 onclick="openDenahModal(this.src)"
                 onerror="this.parentElement.style.display='none';">
            <p class="text-[#94a3b8] text-xs mt-3">Klik gambar untuk memperbesar</p>
        </div>
    </section>

    {{-- Denah Zoom Modal --}}
    <div id="denahModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.88);align-items:center;justify-content:center;cursor:zoom-out;" onclick="closeDenahModal()">
        <img id="denahModalImg" src="" alt="Denah Perumahan"
             style="max-width:95vw;max-height:95vh;border-radius:16px;object-fit:contain;box-shadow:0 24px 64px rgba(0,0,0,.6);">
        <button style="position:absolute;top:16px;right:16px;width:40px;height:40px;background:rgba(0,0,0,.55);border:none;border-radius:50%;color:#fff;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(6px);" aria-label="Tutup">&#x2715;</button>
    </div>
    <script>
    function openDenahModal(src){
        document.getElementById('denahModalImg').src=src;
        document.getElementById('denahModal').style.display='flex';
        document.body.style.overflow='hidden';
    }
    function closeDenahModal(){
        document.getElementById('denahModal').style.display='none';
        document.body.style.overflow='';
    }
    document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeDenahModal();}});
    </script>
    @endif

    {{-- ================================================================
     ETALASE MEDIA SOSIAL - carousel geser otomatis tanpa batas
     ================================================================ --}}
    @if($socialMedias->isNotEmpty())
    <section class="pb-20 overflow-hidden" id="sosial-media">

        {{-- Section header --}}
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-10">
            <p class="text-[#676767] text-[13px] font-semibold uppercase tracking-widest mb-2">Media Sosial</p>
            <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold leading-tight">
                Update Media Sosial
            </h2>
            <p class="text-[#676767] text-[15px] mt-2">Ikuti promosi properti dan tur rumah terbaru kami.</p>
        </div>

        {{-- Carousel wrapper - full width, no padding so cards bleed to edges --}}
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px]">
            <div class="relative overflow-hidden" id="showcase-outer">

            {{-- Left edge fade --}}
              <div class="pointer-events-none absolute left-0 top-0 h-full w-20 lg:w-32 z-10"
                 style="background:linear-gradient(to right,#EEEEEE 30%,transparent);"></div>
            {{-- Right edge fade --}}
            <div class="pointer-events-none absolute right-0 top-0 h-full w-20 lg:w-32 z-10"
                 style="background:linear-gradient(to left,#EEEEEE 30%,transparent);"></div>

            {{-- The scrolling track --}}
            <div id="showcase-track" class="flex gap-5 select-none"
                 style="width:max-content;will-change:transform;cursor:grab;">

                {{-- Cards Ã— 2 for seamless infinite loop --}}
                @for ($pass = 0; $pass < 2; $pass++)
                @foreach($socialMedias as $sm)
                @php $cfg = $sm->config; @endphp
                     <a href="{{ $sm->display_href }}" target="_blank" rel="noopener noreferrer"
                   class="showcase-card block flex-shrink-0 rounded-[16px] overflow-hidden bg-white group"
                   style="width:280px;text-decoration:none;transition:transform .25s ease,box-shadow .25s ease;"
                         data-media-type="{{ $sm->media_type ?? '' }}"
                         data-media-src="{{ $sm->media_src ?? '' }}"
                   aria-label="{{ e($sm->title) }} - {{ $cfg['name'] }}">

                    {{-- Thumbnail --}}
                    <div class="relative overflow-hidden" style="height:175px;background:#f1f5f9;">
                        @if($sm->thumbnail_src)
                            <img src="{{ $sm->thumbnail_src }}" alt="{{ e($sm->title) }}"
                                 loading="lazy"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform .35s ease;display:block;">
                        @elseif($sm->media_type === 'image' && $sm->media_src)
                            <img src="{{ $sm->media_src }}" alt="{{ e($sm->title) }}"
                                 loading="lazy"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform .35s ease;display:block;">
                        @elseif($sm->media_type === 'video' && $sm->media_src)
                            <video muted playsinline preload="metadata"
                                   style="width:100%;height:100%;object-fit:cover;display:block;">
                                <source src="{{ $sm->media_src }}">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/15">
                                <div class="w-11 h-11 rounded-full bg-white/90 flex items-center justify-center shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#111827" class="w-5 h-5 ml-0.5">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </div>
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
        </div>

    </section>

    {{-- -- Video modal (YouTube auto-play saat card diklik) -- --}}
    <div id="sm-video-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.88);align-items:center;justify-content:center;">
        <div style="position:relative;width:min(900px,95vw);aspect-ratio:16/9;border-radius:16px;overflow:hidden;background:#000;box-shadow:0 24px 64px rgba(0,0,0,.6);">
            <iframe id="sm-video-frame" src="" frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen
                style="width:100%;height:100%;display:none;"></iframe>
            <video id="sm-video-player" controls playsinline
               style="width:100%;height:100%;display:none;background:#000;"></video>
            <button id="sm-video-close"
                    style="position:absolute;top:12px;right:12px;width:38px;height:38px;background:rgba(0,0,0,.55);border:none;border-radius:50%;color:#fff;font-size:18px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(6px);"
                    aria-label="Tutup video">&#x2715;</button>
        </div>
    </div>

    <style>
        #showcase-track {
            animation: showcaseScroll {{ max(20, $socialMedias->count() * 5) }}s linear infinite;
        }
        #sosial-media:hover #showcase-track,
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

        // -- Drag / Swipe ------------------------------------------------
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
            // If barely moved it's a click - let the <a> open
            // Re-enable animation from the current position isn't easy with pure CSS,
            // so we just resume (it snaps back to the animation's own timing)
            track.style.transform = '';
            track.style.animationPlayState = '';
        }

        // -- Video modal - YouTube auto-play ---------------------------
        var smModal = document.getElementById('sm-video-modal');
        var smFrame = document.getElementById('sm-video-frame');
        var smVideo = document.getElementById('sm-video-player');
        var smClose = document.getElementById('sm-video-close');
        function ytEmbed(url) {
            var m = url.match(/(?:youtube\.com\/(?:watch\?v=|shorts\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            return m ? 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1&rel=0' : null;
        }
        function openSmModal(type, src) {
            if (type === 'video') {
                smFrame.style.display = 'none';
                smFrame.src = '';
                smVideo.style.display = 'block';
                smVideo.src = src;
                smVideo.play();
            } else {
                smVideo.pause();
                smVideo.removeAttribute('src');
                smVideo.load();
                smVideo.style.display = 'none';
                smFrame.style.display = 'block';
                smFrame.src = src;
            }
            smModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeSmModal() {
            smModal.style.display = 'none';
            smFrame.src = '';
            smFrame.style.display = 'none';
            smVideo.pause();
            smVideo.removeAttribute('src');
            smVideo.load();
            smVideo.style.display = 'none';
            document.body.style.overflow = '';
        }
        if (smClose) smClose.addEventListener('click', closeSmModal);
        if (smModal) smModal.addEventListener('click', function(e) { if (e.target === smModal) closeSmModal(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && smModal && smModal.style.display !== 'none') closeSmModal(); });

        // Prevent accidental nav on drag; intercept YouTube links for in-page modal
        track.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (dragDist > 8) { e.preventDefault(); return; }
                var embed = ytEmbed(this.href);
                var mediaType = this.dataset.mediaType;
                var mediaSrc = this.dataset.mediaSrc;
                if (embed) {
                    e.preventDefault();
                    openSmModal('youtube', embed);
                    return;
                }
                if (mediaType === 'video' && mediaSrc) {
                    e.preventDefault();
                    openSmModal('video', mediaSrc);
                }
            });
        });
    })();
    </script>
    @endif

    {{-- ================================================================
     LOKASI PERUMAHAN (Google Maps)
     ================================================================ --}}
    <section id="lokasi" class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
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
                    src="https://maps.google.com/maps?q=Jl+Indrokilo+No+135+Kalirejo+Lawang+Malang&output=embed" 
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
                        <p class="text-gray-500 text-sm mt-1 max-w-lg">Jl. Indrokilo No.135, Krajan, Kalirejo, Kec. Lawang, Kabupaten Malang, Jawa Timur 65216</p>
                    </div>
                </div>
                
                <a href="https://www.google.com/maps/search/?api=1&query=Jl.+Indrokilo+No.135+Kalirejo+Lawang+Kabupaten+Malang+Jawa+Timur+65216" target="_blank" rel="noopener noreferrer" 
                    class="bg-white border rounded-lg border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Buka di Google Maps
                </a>
            </div>

            {{-- Tombol Video Rute --}}
            <div class="mt-4">
                <button type="button" onclick="toggleVideoRute()"
                    id="btn-video-rute"
                    class="w-full flex items-center justify-between gap-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-[12px] px-5 py-3 text-sm font-semibold text-gray-700 transition-all duration-200">
                    <span class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        Tonton Video Rute ke Lokasi
                    </span>
                    <svg id="icon-chevron-rute" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="video-rute-wrap" class="overflow-hidden transition-all duration-500" style="max-height:0;">
                    <div class="pt-3">
                        <div class="w-full rounded-[12px] overflow-hidden bg-black" style="aspect-ratio:16/9;">
                            <video id="video-rute-player"
                                class="w-full h-full object-contain"
                                controls
                                preload="none"
                                playsinline>
                                @php
                                    $ruteVideoSrc = !empty($lokasiVideo) ? asset($lokasiVideo) : asset('assets/images/rute.mp4');
                                    $ruteVideoExt = strtolower(pathinfo($ruteVideoSrc, PATHINFO_EXTENSION));
                                    $ruteVideoType = $ruteVideoExt === 'mov' ? 'video/quicktime' : 'video/mp4';
                                @endphp
                                <source src="{{ $ruteVideoSrc }}" type="{{ $ruteVideoType }}">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function toggleVideoRute() {
        var wrap    = document.getElementById('video-rute-wrap');
        var chevron = document.getElementById('icon-chevron-rute');
        var video   = document.getElementById('video-rute-player');
        var isOpen  = wrap.style.maxHeight !== '0px' && wrap.style.maxHeight !== '';

        if (isOpen) {
            wrap.style.maxHeight = '0';
            chevron.style.transform = 'rotate(0deg)';
            if (video) video.pause();
        } else {
            wrap.style.maxHeight = wrap.scrollHeight + 500 + 'px';
            chevron.style.transform = 'rotate(180deg)';
        }
    }
    </script>






    {{-- ================================================================
     FLOATING WHATSAPP BUTTON - dinamis berdasarkan agent di URL
     ================================================================ --}}
    <button type="button"
        title="Chat dengan {{ $waNama }}"
        id="wa-float-btn"
        onclick="openWaPopup('{{ $waUrl }}', '{{ $agent['slug'] ?? '' }}')"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-[#25D366] text-white
               px-5 py-3 rounded-full shadow-2xl border-0 cursor-pointer
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
    </button>

    {{-- ================================================================
     POPUP FORM - Kumpulkan nama & HP sebelum membuka WhatsApp
     ================================================================ --}}
    <div id="wa-contact-modal"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden"
         style="background:rgba(0,0,0,0.55);"
         onclick="closeWaPopup(event)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative" onclick="event.stopPropagation()">
            {{-- Tombol tutup --}}
            <button type="button" onclick="closeWaPopupDirect()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none border-0 bg-transparent cursor-pointer"
                    aria-label="Tutup">&times;</button>

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-[#25D366] rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="white" class="w-6 h-6">
                        <path d="M16.003 2C8.28 2 2 8.28 2 16.003c0 2.46.666 4.843 1.93 6.93L2 30l7.27-1.904A13.938 13.938 0 0016.003 30C23.72 30 30 23.72 30 16.003 30 8.28 23.72 2 16.003 2zm0 25.447a11.93 11.93 0 01-6.09-1.666l-.437-.26-4.316 1.13 1.153-4.204-.284-.45A11.938 11.938 0 014.063 16.003c0-6.582 5.356-11.94 11.94-11.94 6.583 0 11.94 5.358 11.94 11.94 0 6.583-5.357 11.944-11.94 11.944zm6.54-8.942c-.357-.18-2.114-1.043-2.443-1.163-.328-.12-.566-.18-.804.18-.238.358-.924 1.163-1.133 1.402-.208.24-.417.27-.775.09-.357-.18-1.504-.554-2.865-1.77-1.058-.946-1.773-2.116-1.98-2.473-.208-.358-.022-.55.156-.729.16-.16.358-.417.536-.625.18-.208.24-.358.358-.596.12-.24.06-.447-.03-.626-.09-.18-.803-1.938-1.1-2.653-.29-.697-.584-.6-.804-.61-.207-.01-.447-.012-.685-.012-.238 0-.625.09-.953.447-.328.358-1.25 1.22-1.25 2.978 0 1.757 1.28 3.455 1.46 3.694.178.238 2.52 3.847 6.103 5.394.854.37 1.52.59 2.04.756.857.272 1.638.234 2.254.142.688-.102 2.114-.864 2.413-1.7.298-.835.298-1.549.208-1.7-.09-.149-.328-.238-.685-.417z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base leading-tight">Hubungi {{ $waNama }}</h3>
                    <p class="text-gray-500 text-xs">via WhatsApp</p>
                </div>
            </div>

            <p class="text-gray-600 text-sm mb-4">Silakan isi data Anda agar kami bisa menghubungi Anda kembali.</p>

            {{-- Form --}}
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="wa-popup-name" placeholder="Contoh: Budi Santoso"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="tel" id="wa-popup-phone" placeholder="Contoh: 08123456789"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent">
                </div>
                <p id="wa-popup-error" class="text-red-500 text-xs hidden">Mohon isi nama dan nomor HP terlebih dahulu.</p>
            </div>

            {{-- Submit --}}
            <button type="button" onclick="submitWaPopup()"
                    class="mt-5 w-full bg-[#25D366] hover:bg-[#1ebe5d] text-white font-semibold py-3 rounded-xl
                           transition-colors duration-200 cursor-pointer border-0 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="w-5 h-5">
                    <path d="M16.003 2C8.28 2 2 8.28 2 16.003c0 2.46.666 4.843 1.93 6.93L2 30l7.27-1.904A13.938 13.938 0 0016.003 30C23.72 30 30 23.72 30 16.003 30 8.28 23.72 2 16.003 2zm0 25.447a11.93 11.93 0 01-6.09-1.666l-.437-.26-4.316 1.13 1.153-4.204-.284-.45A11.938 11.938 0 014.063 16.003c0-6.582 5.356-11.94 11.94-11.94 6.583 0 11.94 5.358 11.94 11.94 0 6.583-5.357 11.944-11.94 11.944zm6.54-8.942c-.357-.18-2.114-1.043-2.443-1.163-.328-.12-.566-.18-.804.18-.238.358-.924 1.163-1.133 1.402-.208.24-.417.27-.775.09-.357-.18-1.504-.554-2.865-1.77-1.058-.946-1.773-2.116-1.98-2.473-.208-.358-.022-.55.156-.729.16-.16.358-.417.536-.625.18-.208.24-.358.358-.596.12-.24.06-.447-.03-.626-.09-.18-.803-1.938-1.1-2.653-.29-.697-.584-.6-.804-.61-.207-.01-.447-.012-.685-.012-.238 0-.625.09-.953.447-.328.358-1.25 1.22-1.25 2.978 0 1.757 1.28 3.455 1.46 3.694.178.238 2.52 3.847 6.103 5.394.854.37 1.52.59 2.04.756.857.272 1.638.234 2.254.142.688-.102 2.114-.864 2.413-1.7.298-.835.298-1.549.208-1.7-.09-.149-.328-.238-.685-.417z"/>
                </svg>
                Chat Sekarang
            </button>

            {{-- Lewati --}}
            <button type="button" onclick="skipWaPopup()"
                    class="mt-2 w-full text-gray-400 hover:text-gray-600 text-sm py-2 bg-transparent border-0 cursor-pointer">
                Lewati, langsung chat â†’
            </button>
        </div>
    </div>

    <script>
    function initHeroSlider() {
        const slider = document.getElementById('hero-slider');
        const track = document.getElementById('hero-track');
        const prevBtn = document.getElementById('hero-prev');
        const nextBtn = document.getElementById('hero-next');
        const dots = Array.from(document.querySelectorAll('.hero-dot'));

        if (!slider || !track) {
            return;
        }

        const total = Number(slider.dataset.total || 0);
        if (total === 0) {
            return;
        }

        let currentIndex = 0;
        let autoSlideTimer = null;
        let touchStartX = 0;
        let touchEndX = 0;

        const render = () => {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            dots.forEach((dot, idx) => {
                if (idx === currentIndex) {
                    dot.classList.add('bg-white');
                    dot.classList.remove('bg-white/60');
                } else {
                    dot.classList.add('bg-white/60');
                    dot.classList.remove('bg-white');
                }
            });
        };

        const next = () => {
            currentIndex = (currentIndex + 1) % total;
            render();
        };

        const prev = () => {
            currentIndex = (currentIndex - 1 + total) % total;
            render();
        };

        const startAutoSlide = () => {
            if (total < 2) {
                return;
            }

            autoSlideTimer = setInterval(next, 4000);
        };

        const stopAutoSlide = () => {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        };

        prevBtn?.addEventListener('click', () => {
            prev();
            stopAutoSlide();
            startAutoSlide();
        });

        nextBtn?.addEventListener('click', () => {
            next();
            stopAutoSlide();
            startAutoSlide();
        });

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                currentIndex = Number(dot.dataset.slide || 0);
                render();
                stopAutoSlide();
                startAutoSlide();
            });
        });

        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].clientX;
        }, { passive: true });

        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) < 40) {
                return;
            }

            if (diff > 0) {
                next();
            } else {
                prev();
            }

            stopAutoSlide();
            startAutoSlide();
        }, { passive: true });

        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoSlide();
            } else {
                startAutoSlide();
            }
        });

        render();
        startAutoSlide();
    }

    initHeroSlider();

    // Referral code dari server (PHP session/cookie) - embed langsung sebagai JS variable
    const AFFILIATE_REF_CODE = @json($refCode ?? null);

    // Fallback: baca dari cookie browser (untuk kasus session expired tapi cookie masih ada)
    function getCookieVal(name) {
        const val = document.cookie.split('; ').find(r => r.startsWith(name + '='));
        return val ? decodeURIComponent(val.split('=')[1]) : null;
    }

    // -- Popup WA: buka modal ------------------------------------------------
    let _pendingWaUrl  = null;
    let _pendingWaSlug = null;

    function openWaPopup(waUrl, slug) {
        _pendingWaUrl  = waUrl;
        _pendingWaSlug = slug;
        document.getElementById('wa-popup-name').value  = '';
        document.getElementById('wa-popup-phone').value = '';
        document.getElementById('wa-popup-error').classList.add('hidden');
        document.getElementById('wa-contact-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('wa-popup-name').focus(), 100);
    }

    function closeWaPopupDirect() {
        document.getElementById('wa-contact-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function closeWaPopup(event) {
        // hanya tutup jika klik overlay luar (bukan konten modal)
        if (event.target === document.getElementById('wa-contact-modal')) {
            closeWaPopupDirect();
        }
    }

    function submitWaPopup() {
        const name  = document.getElementById('wa-popup-name').value.trim();
        const phone = document.getElementById('wa-popup-phone').value.trim();
        const errEl = document.getElementById('wa-popup-error');

        if (!name || !phone) {
            errEl.classList.remove('hidden');
            return;
        }
        errEl.classList.add('hidden');

        const refCode = AFFILIATE_REF_CODE || getCookieVal('affiliate_ref_code') || null;

        fetch('{{ route("wa-click.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({
                slug:          _pendingWaSlug || null,
                referral_code: refCode || null,
                page_url:      window.location.href,
                sender_name:   name,
                sender_phone:  phone
            }),
            keepalive: true
        }).catch(err => console.error("Gagal mencatat klik WA:", err));

        closeWaPopupDirect();
        // Bangun ulang URL WA dengan pesan personal visitor (strip ?text= dari URL lama)
        const waBase    = _pendingWaUrl.split('?')[0];
        const waMessage = `Halo, nama saya ${name}. Saya tertarik dengan Bukit Shangrilla Asri 2. No HP saya: ${phone}.`;
        window.open(`${waBase}?text=${encodeURIComponent(waMessage)}`, '_blank');
    }

    function skipWaPopup() {
        const refCode = AFFILIATE_REF_CODE || getCookieVal('affiliate_ref_code') || null;
        fetch('{{ route("wa-click.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({
                slug:          _pendingWaSlug || null,
                referral_code: refCode || null,
                page_url:      window.location.href
            }),
            keepalive: true
        }).catch(err => console.error("Gagal mencatat klik WA:", err));
        closeWaPopupDirect();
        window.open(_pendingWaUrl, '_blank');
    }

    // Tutup modal saat tekan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeWaPopupDirect();
    });
    </script>

@endsection

@section('scripts')
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var swiper = new Swiper('.swiper-hero', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
@endsection



