@extends('layouts.app')

@section('title', 'Bukit Shangrilla Asri – Hunian Modern Strategis')

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

    $heroSlideFiles = glob(public_path('assets/landing/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE) ?: [];
    $heroSlideFiles = array_values(array_filter($heroSlideFiles, function ($file) {
        $filename = strtolower(pathinfo($file, PATHINFO_FILENAME));
        return !str_contains($filename, 'logo');
    }));
    $heroSlides = array_map(fn ($file) => asset('assets/landing/' . basename($file)), $heroSlideFiles);

    $heroFallbackSlide = 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&q=80';
    if (empty($heroSlides)) {
        $heroSlides = [$heroFallbackSlide];
    }
@endphp


@section('content')

    {{-- ================================================================
     HERO SECTION
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 lg:pt-16 pb-20 relative overflow-hidden">

        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-10">

            {{-- Left: Text --}}
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

            {{-- Right: Hero Slider --}}
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

        {{-- Stats bar --}}
        @php
            $stats = [
                ['value' => '86', 'label' => 'Total Unit', 'icon' => 'home', 'iconClass' => 'bg-[#EEF5FF] text-[#1D4ED8]'],
                ['value' => '40', 'label' => 'Unit Tersedia', 'icon' => 'check', 'iconClass' => 'bg-[#ECFDF3] text-[#047857]'],
                ['value' => '20', 'label' => 'Unit Terjual', 'icon' => 'chart', 'iconClass' => 'bg-[#FFF1F2] text-[#BE123C]'],
                ['value' => '26', 'label' => 'Unit Booking', 'icon' => 'calendar', 'iconClass' => 'bg-[#FFFBEB] text-[#B45309]'],
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
        @php
            $tipeUnggulan = [
                [
                    'nama' => 'Harmony',
                    'gambar' => asset('assets/landing/harmony.jpg'),
                    'tagline' => 'Tipe compact dengan desain nyaman untuk keluarga kecil.',
                    'fitur' => ['1 Lantai', '2 Kamar Tidur', '1 Kamar Mandi', '1 Garasi', 'Ukuran 40/72'],
                ],
                [
                    'nama' => 'Blissfull',
                    'gambar' => asset('assets/landing/blissfull.jpg'),
                    'tagline' => 'Ruang lebih lega untuk kebutuhan keluarga aktif.',
                    'fitur' => ['2 Lantai', '3 Kamar Tidur', '2 Kamar Mandi', 'Carport 1 Mobil', 'Ukuran 50/84'],
                ],
                [
                    'nama' => 'Serenity',
                    'gambar' => asset('assets/landing/serenity.jpg'),
                    'tagline' => 'Nuansa tenang dengan tata ruang modern dan asri.',
                    'fitur' => ['1 Lantai', '3 Kamar Tidur', '2 Kamar Mandi', 'Carport 1 Mobil', 'Ukuran 60/98'],
                ],
            ];
        @endphp
        <div class="bg-white rounded-[24px] border border-[#E9E9E9] p-6 lg:p-8 shadow-[0_18px_36px_rgba(0,0,0,0.06)]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-7">
                <div>
                    <h3 class="text-[#393939] text-[22px] lg:text-[28px] font-bold">Tipe Pilihan Bukit Shangrilla Asri</h3>
                    <p class="text-[#676767] text-sm">Detail fasilitas di bawah ini bisa kamu edit langsung dari satu blok data.</p>
                </div>
                <a href="{{ route('unit-tersedia') }}"
                    class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:underline w-fit">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($tipeUnggulan as $tipe)
                    <div class="group rounded-[20px] overflow-hidden border border-[#E7E7E7] bg-[#FCFCFC] hover:shadow-[0_18px_32px_rgba(0,0,0,0.10)] transition-all duration-300">
                        <div class="relative h-[210px] overflow-hidden">
                            <img src="{{ $tipe['gambar'] }}"
                                onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1000&q=80';this.onerror=null;"
                                alt="Tipe {{ $tipe['nama'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/65 to-transparent">
                                <p class="text-white text-xs tracking-wide uppercase">Tipe Rumah</p>
                                <h4 class="text-white text-2xl font-bold leading-tight">{{ $tipe['nama'] }}</h4>
                            </div>
                        </div>

                        <div class="p-5">
                            <p class="text-[#676767] text-sm mb-4">{{ $tipe['tagline'] }}</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($tipe['fitur'] as $fitur)
                                    <span class="inline-flex items-center justify-center text-center rounded-xl border border-[#D9E7DB] bg-[#F4FAF5] text-[#355B3E] text-xs font-semibold px-2 py-2">{{ $fitur }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================================
     FASILITAS PERUMAHAN
     ================================================================ --}}
    @php
        $fasilitas = [
            [
                'nama' => 'Mosque',
                'deskripsi' => 'Masjid nyaman di dalam kawasan perumahan, memudahkan penghuni beribadah dengan tenang dan khusyuk.',
                'icon' => 'mosque',
            ],
            [
                'nama' => 'Waterpark',
                'deskripsi' => 'Fasilitas waterpark kawasan hunian yang menghadirkan hiburan air menyenangkan untuk quality time keluarga.',
                'icon' => 'waterpark',
            ],
            [
                'nama' => 'Foodcourt',
                'deskripsi' => 'Area foodcourt dengan beragam pilihan kuliner, nyaman untuk bersantai dan berkumpul bersama keluarga.',
                'icon' => 'foodcourt',
            ],
            [
                'nama' => 'Playground',
                'deskripsi' => 'Playground aman dan seru untuk anak setiap hari, menghadirkan keceriaan di lingkungan perumahan.',
                'icon' => 'playground',
            ],
            [
                'nama' => 'Sport Center',
                'deskripsi' => 'Fasilitas sport center modern untuk olahraga dan kebugaran penghuni setiap hari.',
                'icon' => 'sport',
            ],
            [
                'nama' => 'One Gate System',
                'deskripsi' => 'Sistem satu pintu keluar masuk area cluster untuk menjaga keamanan lingkungan lebih optimal.',
                'icon' => 'gate',
            ],
            [
                'nama' => '24 Hour CCTV',
                'deskripsi' => 'Pemantauan CCTV 24 jam membantu lingkungan perumahan menjadi lebih aman dan terpantau.',
                'icon' => 'cctv',
            ],
            [
                'nama' => '24 Hour Security',
                'deskripsi' => 'Keamanan 24 jam untuk memastikan kenyamanan dan ketenangan penghuni sepanjang waktu.',
                'icon' => 'security',
            ],
            [
                'nama' => 'Mountain View',
                'deskripsi' => 'Pemandangan pegunungan dari ketinggian yang menenangkan, memberi suasana asri setiap hari.',
                'icon' => 'mountain',
            ],
            [
                'nama' => 'Lokasi Strategis',
                'deskripsi' => 'Hunian dengan akses terbaik, hanya berjarak 3 menit dari Stasiun Lawang, memudahkan mobilitas harian Anda dengan lebih efisien.',
                'icon' => 'location',
            ],
        ];
    @endphp
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
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
                                @if($item['icon'] === 'mosque')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 20h18M6 20V9l6-4 6 4v11M12 5V3m-2 9h4m-4 3h4"/></svg>
                                @elseif($item['icon'] === 'waterpark')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 14c1 .9 2 .9 3 0s2-.9 3 0 2 .9 3 0 2-.9 3 0 2 .9 3 0M7 10l3-5h4l3 5"/></svg>
                                @elseif($item['icon'] === 'foodcourt')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4v8m3-8v8M5 8h4M13 4h2a2 2 0 012 2v14m0-9h2"/></svg>
                                @elseif($item['icon'] === 'playground')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 20h16M7 20v-8l5-6 5 6v8M12 6V4"/></svg>
                                @elseif($item['icon'] === 'sport')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 17h16M8 7v10m8-10v10"/></svg>
                                @elseif($item['icon'] === 'gate')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 20h16M6 20V8h12v12M9 8V5h6v3"/></svg>
                                @elseif($item['icon'] === 'cctv')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 9h11l2 2h3M7 14l-2 4m10-4l2 4M6 9l2-3h5"/></svg>
                                @elseif($item['icon'] === 'security')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.2 7.7-7 9-3.8-1.3-7-4-7-9V7l7-4zM9 12l2 2 4-4"/></svg>
                                @elseif($item['icon'] === 'mountain')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 20h18L14 9l-3 4-2-3-6 10z"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.4 7-11a7 7 0 10-14 0c0 5.6 7 11 7 11z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                @endif
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
