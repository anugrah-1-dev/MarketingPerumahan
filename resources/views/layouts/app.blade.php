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

    <!-- ====== NAVBAR ====== -->
    <nav class="w-full bg-transparent z-50" id="mainNav">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] flex items-center justify-between py-6">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 text-[#393939] tracking-wide">
                <img src="{{ $brandLogo ?? asset('assets/landing/logo-bsa.svg') }}"
                    alt="Logo Bukit Shangrilla Asri"
                    class="w-10 h-10 rounded-full object-cover border border-[#D8D8D8] bg-white p-1">
                <span class="brand-mark" aria-label="Bukit Shangrilla Asri">
                    <span class="brand-prefix">Bukit</span>
                    <span class="brand-main">Shangrilla</span>
                    <span class="brand-suffix">Asri</span>
                </span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex items-center gap-10">
                <a href="{{ route('landing') }}"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors {{ request()->routeIs('landing') ? 'font-bold underline underline-offset-4' : '' }}">Home</a>
                <a href="{{ route('unit-tersedia') }}"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors {{ request()->routeIs('unit-tersedia') ? 'font-bold underline underline-offset-4' : '' }}">Unit Tersedia</a>
                <a href="{{ route('landing') }}#fasilitas"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors">Fasilitas</a>
                <a href="{{ route('landing') }}#sosial-media"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors">Sosial Media</a>
                <a href="{{ route('landing') }}#lokasi"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors">Lokasi</a>
                <a href="{{ route('landing') }}#tentang"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors">Tentang Kami</a>
            </div>

            <!-- Login Button Removed -->
            <div class="hidden lg:flex items-center gap-4">
            </div>

            <!-- Mobile Hamburger -->
            <button id="mobileMenuBtn" class="lg:hidden flex flex-col gap-1.5 p-2" aria-label="Menu">
                <span class="block w-6 h-0.5 bg-[#393939] transition-all"></span>
                <span class="block w-6 h-0.5 bg-[#393939] transition-all"></span>
                <span class="block w-6 h-0.5 bg-[#393939] transition-all"></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white shadow-lg px-6 py-4 flex flex-col gap-4">
            <a href="{{ route('landing') }}" class="text-[#393939] font-medium">Home</a>
            <a href="{{ route('unit-tersedia') }}" class="text-[#393939] font-medium">Unit Tersedia</a>
            <a href="{{ route('landing') }}#fasilitas" class="text-[#393939] font-medium">Fasilitas</a>
            <a href="{{ route('landing') }}#sosial-media" class="text-[#393939] font-medium">Sosial Media</a>
            <a href="{{ route('landing') }}#lokasi" class="text-[#393939] font-medium">Lokasi</a>
            <a href="{{ route('landing') }}#tentang" class="text-[#393939] font-medium">Tentang Kami</a>
        </div>
    </nav>

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
            <!-- About -->
            <div>
                <h4 class="text-lg font-semibold mb-4">About us</h4>
                <p class="text-gray-300 text-sm leading-7">Bukit Shangrilla Asri hadir dengan berbagai tipe rumah berkualitas
                    dan harga terjangkau di lokasi strategis Lawang,Malang.</p>
            </div>
            <!-- Contact & Location -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <p class="text-gray-300 text-sm mb-2">📞 +62 12345678909</p>
                <h4 class="text-lg font-semibold mt-4 mb-2">Location</h4>
                <p class="text-gray-300 text-sm">📍 Jl. Indrokilo No.135, Krajan, Kalirejo, Kec. Lawang, Kabupaten Malang, Jawa Timur 65216</p>
            </div>
            <!-- Footer House Photo -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Foto Kawasan</h4>
                <img src="{{ asset('assets/landing/footer-rumah-2.jpg') }}"
                    onerror="this.src='https://images.unsplash.com/photo-1625602812206-5ec545ca1231?w=800&q=80';this.onerror=null;"
                    alt="Foto kawasan Bukit Shangrilla Asri"
                    class="w-full h-[170px] object-cover rounded-2xl border border-white/10 shadow-lg">
            </div>
        </div>
        <div class="border-t border-gray-600 text-center py-5 text-gray-400 text-xs">
            © {{ date('Y') }} Bukit Shangrilla Asri. All rights reserved.
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
    </script>
    @yield('scripts')
</body>

</html>
