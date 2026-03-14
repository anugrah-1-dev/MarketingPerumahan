<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bukit Shangrilla Asri') – Pilihan Rumah Terbaik</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind v4 via CDN (matches package.json) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --brand-green: #5f6f3e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #EEEEEE;
            color: #393939;
        }

        .btn-primary {
            background: #393939;
            color: #fff;
            border-radius: 25px;
            padding: 12px 32px;
            font-weight: 600;
            transition: background .2s;
            display: inline-block;
            text-align: center;
        }

        .btn-primary:hover {
            background: #111;
        }

        .btn-outline {
            background: transparent;
            color: #393939;
            border: 2px solid #393939;
            border-radius: 25px;
            padding: 10px 32px;
            font-weight: 600;
            transition: all .2s;
            display: inline-block;
            text-align: center;
        }

        .btn-outline:hover {
            background: #393939;
            color: #fff;
        }

        .input-field {
            width: 100%;
            border: 1.5px solid #C8C8C8;
            border-radius: 20px;
            padding: 14px 20px;
            font-size: 15px;
            background: #fff;
            outline: none;
            transition: border .2s;
        }

        .input-field:focus {
            border-color: #393939;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
        }

        .status-tersedia {
            background: #D1FAE5;
            color: #065F46;
        }


        .status-terjual {
            background: #FEE2E2;
            color: #991B1B;
        }

        .brand-mark {
            display: inline-flex;
            align-items: baseline;
            gap: 8px;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: 0.02em;
        }

        .brand-mark .brand-prefix,
        .brand-mark .brand-suffix {
            font-size: 16px;
            font-weight: 700;
            color: var(--brand-green);
        }

        .brand-mark .brand-main {
            font-size: 36px;
            font-weight: 800;
            color: var(--brand-green);
        }

        @media (max-width: 640px) {
            .brand-mark {
                gap: 6px;
            }

            .brand-mark .brand-prefix,
            .brand-mark .brand-suffix {
                font-size: 11px;
            }

            .brand-mark .brand-main {
                font-size: 20px;
            }
        }
    </style>
    @yield('head')
</head>

<body class="min-h-screen">

    @php
        $logoCandidates = [
            'assets/landing/logo-bsa.png',
            'assets/landing/logo-bsa.webp',
            'assets/landing/logo-bsa.jpg',
            'assets/landing/logo.png',
            'assets/landing/logo.webp',
            'assets/landing/logo.jpg',
            'assets/landing/landing1.png',
            'assets/landing/logo-bsa.svg',
        ];

        $brandLogo = null;
        foreach ($logoCandidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                $brandLogo = asset($candidate);
                break;
            }
        }
    @endphp

    <style>
        /* ── Navbar ───────────────────────────────────────── */
        #mainNav {
            position: sticky;
            top: 0;
            z-index: 999;
            transition: background .3s, box-shadow .3s, padding .3s;
        }
        #mainNav.scrolled {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }
        .nav-pill {
            position: relative;
            font-size: 15px;
            font-weight: 500;
            color: #393939;
            text-decoration: none;
            padding: 6px 0;
            transition: color .2s;
            white-space: nowrap;
        }
        .nav-pill::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 100%;
            height: 2px;
            background: #0B5E41;
            border-radius: 9px;
            transition: transform .25s cubic-bezier(.4,0,.2,1);
        }
        .nav-pill:hover { color: #0B5E41; }
        .nav-pill:hover::after,
        .nav-pill.active::after { transform: translateX(-50%) scaleX(1); }
        .nav-pill.active { color: #0B5E41; font-weight: 700; }

        /* CTA button */
        .nav-cta {
            background: #0B5E41;
            color: #fff !important;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 22px;
            border-radius: 100px;
            transition: background .2s, transform .15s;
            white-space: nowrap;
        }
        .nav-cta:hover { background: #094d36; transform: translateY(-1px); }
        .nav-cta::after { display: none !important; }

        /* Mobile drawer */
        #mobileDrawer {
            position: fixed;
            inset: 0;
            z-index: 9998;
            pointer-events: none;
        }
        #mobileDrawer.open { pointer-events: all; }
        #drawerOverlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0);
            transition: background .3s;
        }
        #drawerOverlay.open { background: rgba(0,0,0,0.35); }
        #drawerPanel {
            position: absolute;
            top: 0; right: 0;
            width: min(320px, 88vw);
            height: 100%;
            background: #fff;
            transform: translateX(100%);
            transition: transform .35s cubic-bezier(.4,0,.2,1);
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 32px rgba(0,0,0,0.12);
        }
        #drawerPanel.open { transform: translateX(0); }
        .drawer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 500;
            color: #393939;
            text-decoration: none;
            border-radius: 10px;
            margin: 2px 12px;
            transition: background .15s, color .15s;
        }
        .drawer-link:hover, .drawer-link.active {
            background: #f0fdf4;
            color: #0B5E41;
        }
        .drawer-link .drawer-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background .15s;
        }
        .drawer-link:hover .drawer-icon,
        .drawer-link.active .drawer-icon {
            background: #dcfce7;
        }
    </style>

    <!-- ====== NAVBAR ====== -->
    <nav id="mainNav">
        <div class="max-w-[1440px] mx-auto px-4 lg:pl-8 lg:pr-[80px] flex items-center justify-between py-4">

            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-[#393939] tracking-wide flex-shrink-0">
                <img src="{{ $brandLogo ?? asset('assets/landing/logo-bsa.svg') }}"
                    alt="Logo Bukit Shangrilla Asri"
                    class="w-14 h-14 rounded-full object-cover border border-[#D8D8D8] bg-white p-[3px]">
                <span class="brand-mark" aria-label="Bukit Shangrilla Asri">
                    <span class="brand-prefix">Bukit</span>
                    <span class="brand-main">Shangrilla</span>
                    <span class="brand-suffix">Asri</span>
                </span>
            </a>

            {{-- Desktop Nav Links (flex-1 + justify-center agar benar-benar di tengah) --}}
            @php
                $isLanding  = request()->routeIs('landing');
                $isUnitPage = request()->routeIs('unit-tersedia');
                $waUrl      = 'https://wa.me/' . preg_replace('/\D/', '', $navWa ?? '6283876766055') . '?text=' . urlencode('Halo, saya tertarik dengan properti Bukit Shangrilla Asri. Boleh saya bertanya?');
            @endphp
            <div class="hidden lg:flex flex-1 justify-center items-center gap-7">
                <a href="{{ route('landing') }}"
                   class="nav-pill {{ $isLanding ? 'active' : '' }}">Home</a>
                <a href="{{ route('unit-tersedia') }}"
                   class="nav-pill {{ $isUnitPage ? 'active' : '' }}">Unit Tersedia</a>
                <a href="{{ $isLanding ? '#fasilitas' : route('landing').'#fasilitas' }}"
                   class="nav-pill">Fasilitas</a>
                <a href="{{ $isLanding ? '#sosial-media' : route('landing').'#sosial-media' }}"
                   class="nav-pill">Media Sosial</a>
                <a href="{{ $isLanding ? '#lokasi' : route('landing').'#lokasi' }}"
                   class="nav-pill">Lokasi</a>
                <a href="{{ $isLanding ? '#tentang' : route('landing').'#tentang' }}"
                   class="nav-pill">Tentang Kami</a>
            </div>

            {{-- Desktop CTA — buka WhatsApp Admin --}}
            <div class="hidden lg:flex items-center flex-shrink-0">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                   class="nav-pill nav-cta inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Hubungi Kami
                </a>
            </div>

            {{-- Mobile Hamburger --}}
            <button id="drawerToggle" class="lg:hidden flex flex-col justify-center gap-[5px] w-10 h-10 rounded-xl hover:bg-gray-100 transition items-center" aria-label="Menu">
                <span id="hb1" class="block w-5 h-0.5 bg-[#393939] transition-all duration-300 origin-center"></span>
                <span id="hb2" class="block w-5 h-0.5 bg-[#393939] transition-all duration-300"></span>
                <span id="hb3" class="block w-5 h-0.5 bg-[#393939] transition-all duration-300 origin-center"></span>
            </button>
        </div>
    </nav>

    {{-- Mobile Drawer --}}
    <div id="mobileDrawer">
        <div id="drawerOverlay"></div>
        <div id="drawerPanel">
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <span class="font-bold text-[#0B5E41] text-lg">Menu</span>
                <button id="drawerClose" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            {{-- Drawer Links --}}
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('landing') }}" class="drawer-link {{ request()->routeIs('landing') ? 'active' : '' }}">
                    <span class="drawer-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </span>
                    Home
                </a>
                <a href="{{ route('unit-tersedia') }}" class="drawer-link {{ request()->routeIs('unit-tersedia') ? 'active' : '' }}">
                    <span class="drawer-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    Unit Tersedia
                </a>
                <a href="{{ route('landing') }}#fasilitas" class="drawer-link">
                    <span class="drawer-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </span>
                    Fasilitas
                </a>
                <a href="{{ route('landing') }}#sosial-media" class="drawer-link">
                    <span class="drawer-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                    </span>
                    MediaSosial
                </a>
                <a href="{{ route('landing') }}#lokasi" class="drawer-link">
                    <span class="drawer-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    Lokasi
                </a>
                <a href="{{ route('landing') }}#tentang" class="drawer-link">
                    <span class="drawer-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Tentang Kami
                </a>
            </nav>
            {{-- Drawer Footer CTA --}}
            <div class="px-6 py-5 border-t border-gray-100">
                <a href="{{ $waUrl ?? '#' }}" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-2 bg-[#0B5E41] text-white font-semibold text-sm py-3 rounded-xl hover:bg-[#094d36] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Hubungi Kami via WhatsApp
                </a>
            </div>
        </div>
    </div>

    <script>
    (function () {
        // ── Scroll-aware navbar ──────────────────────────────
        var nav = document.getElementById('mainNav');
        function updateNav() {
            if (window.scrollY > 30) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        }
        window.addEventListener('scroll', updateNav, { passive: true });
        updateNav();

        // ── Mobile Drawer ────────────────────────────────────
        var drawer       = document.getElementById('mobileDrawer');
        var overlay      = document.getElementById('drawerOverlay');
        var panel        = document.getElementById('drawerPanel');
        var toggleBtn    = document.getElementById('drawerToggle');
        var closeBtn     = document.getElementById('drawerClose');
        var hb1          = document.getElementById('hb1');
        var hb2          = document.getElementById('hb2');
        var hb3          = document.getElementById('hb3');

        function openDrawer() {
            drawer.classList.add('open');
            overlay.classList.add('open');
            panel.classList.add('open');
            document.body.style.overflow = 'hidden';
            // Animate to X
            hb1.style.transform = 'translateY(7px) rotate(45deg)';
            hb2.style.opacity   = '0';
            hb3.style.transform = 'translateY(-7px) rotate(-45deg)';
        }
        function closeDrawer() {
            drawer.classList.remove('open');
            overlay.classList.remove('open');
            panel.classList.remove('open');
            document.body.style.overflow = '';
            hb1.style.transform = '';
            hb2.style.opacity   = '1';
            hb3.style.transform = '';
        }

        toggleBtn.addEventListener('click', function () {
            drawer.classList.contains('open') ? closeDrawer() : openDrawer();
        });
        closeBtn.addEventListener('click', closeDrawer);
        overlay.addEventListener('click', closeDrawer);

        // Close drawer when any link inside is clicked
        panel.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeDrawer);
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDrawer();
        });
    })();
    </script>

    <!-- ====== CONTENT ====== -->
    @if (session('success'))
        <div class="max-w-[1440px] mx-auto px-6 py-3">
            <div class="bg-green-100 text-green-800 rounded-xl px-5 py-3 text-sm font-medium">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @yield('content')

    <!-- ====== FOOTER ====== -->
    <footer id="tentang" class="bg-[#393939] text-white mt-20">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] py-14 grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Brand -->
            <div>
                <h3 class="text-2xl font-bold mb-3">Bukit Shangrilla Asri</h3>
                <p class="text-gray-300 text-sm leading-7">Hunian modern minimalis dengan desain nyaman untuk keluarga
                    muda.</p>
            </div>
            <!-- Tentang Kami -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Tentang Kami</h4>
                <p class="text-gray-300 text-sm leading-7">Bukit Shangrilla Asri hadir dengan berbagai tipe rumah berkualitas
                    dan harga terjangkau di lokasi strategis Lawang, Malang.</p>
                @php
                    $smIg = \App\Models\Setting::get('instagram_url', '');
                    $smTt = \App\Models\Setting::get('tiktok_url', '');
                    $smFb = \App\Models\Setting::get('facebook_url', '');
                @endphp
                @if($smIg || $smTt || $smFb)
                <div class="flex gap-3 mt-4">
                    @if($smIg)
                    <a href="{{ $smIg }}" target="_blank" rel="noopener noreferrer"
                       title="Instagram"
                       class="w-9 h-9 rounded-full flex items-center justify-center transition-opacity hover:opacity-80"
                       style="background:#E1306C;">
                        <i class="fab fa-instagram text-white text-sm"></i>
                    </a>
                    @endif
                    @if($smTt)
                    <a href="{{ $smTt }}" target="_blank" rel="noopener noreferrer"
                       title="TikTok"
                       class="w-9 h-9 rounded-full flex items-center justify-center transition-opacity hover:opacity-80"
                       style="background:#010101;">
                        <i class="fab fa-tiktok text-white text-sm"></i>
                    </a>
                    @endif
                    @if($smFb)
                    <a href="{{ $smFb }}" target="_blank" rel="noopener noreferrer"
                       title="Facebook"
                       class="w-9 h-9 rounded-full flex items-center justify-center transition-opacity hover:opacity-80"
                       style="background:#1877F2;">
                        <i class="fab fa-facebook-f text-white text-sm"></i>
                    </a>
                    @endif
                </div>
                @endif
            </div>
            <!-- Lokasi -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Lokasi</h4>
                <p class="text-gray-300 text-sm">📍 Jl. Indrokilo No.135, Krajan, Kalirejo, Kec. Lawang, Kabupaten Malang, Jawa Timur 65216</p>
            </div>
            <!-- Footer House Photo -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Foto Kawasan</h4>
                <img src="{{ asset('assets/landing/landing2.jpg') }}"
                    onerror="this.src='https://images.unsplash.com/photo-1625602812206-5ec545ca1231?w=800&q=80';this.onerror=null;"
                    alt="Foto kawasan Bukit Shangrilla Asri"
                    class="w-full h-[170px] object-cover rounded-2xl border border-white/10 shadow-lg">
            </div>
        </div>
        <div class="border-t border-gray-600 text-center py-5 text-gray-400 text-xs">
            © {{ date('Y') }} Bukit Shangrilla Asri. All rights reserved.
        </div>
    </footer>

    @yield('scripts')
</body>

</html>
