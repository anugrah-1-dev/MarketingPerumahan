<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perumahan Zahra') – Pilihan Rumah Terbaik</title>

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

        .status-booking {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-terjual {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>
    @yield('head')
</head>

<body class="min-h-screen">

    <!-- ====== NAVBAR ====== -->
    <nav class="w-full bg-transparent z-50" id="mainNav">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] flex items-center justify-between py-6">
            <!-- Logo / Brand -->
            <a href="{{ route('landing') }}" class="text-[#393939] text-2xl font-bold tracking-wide">
                Perumahan <span class="text-[#393939]">Zahra</span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex items-center gap-10">
                <a href="{{ route('landing') }}"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors {{ request()->routeIs('landing') ? 'font-bold underline underline-offset-4' : '' }}">Home</a>
                <a href="{{ route('unit-tersedia') }}"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors {{ request()->routeIs('unit-tersedia') ? 'font-bold underline underline-offset-4' : '' }}">Unit
                    tersedia</a>
                <a href="{{ route('site-plan') }}"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors {{ request()->routeIs('site-plan') ? 'font-bold underline underline-offset-4' : '' }}">Site
                    plan</a>
                <a href="#simulasi"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors">Simulasi</a>
                <a href="#tentang"
                    class="nav-link text-[#393939] text-[16px] font-medium hover:text-black transition-colors">Tentang
                    kami</a>
            </div>

            <!-- Login Button -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('login') }}" class="btn-primary text-sm">Masuk</a>
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
            <a href="{{ route('unit-tersedia') }}" class="text-[#393939] font-medium">Unit tersedia</a>
            <a href="{{ route('site-plan') }}" class="text-[#393939] font-medium">Site plan</a>
            <a href="#simulasi" class="text-[#393939] font-medium">Simulasi</a>
            <a href="#tentang" class="text-[#393939] font-medium">Tentang kami</a>
            <a href="{{ route('login') }}" class="btn-primary text-center mt-2">Masuk</a>
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
        <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] py-14 grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Brand -->
            <div>
                <h3 class="text-2xl font-bold mb-3">Perumahan Zahra</h3>
                <p class="text-gray-300 text-sm leading-7">Hunian modern minimalis dengan desain nyaman untuk keluarga
                    muda.</p>
            </div>
            <!-- About -->
            <div>
                <h4 class="text-lg font-semibold mb-4">About us</h4>
                <p class="text-gray-300 text-sm leading-7">Perumahan Zahra hadir dengan berbagai tipe rumah berkualitas
                    dan harga terjangkau di lokasi strategis Semarang.</p>
            </div>
            <!-- Contact & Location -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <p class="text-gray-300 text-sm mb-2">📞 +62 12345678909</p>
                <h4 class="text-lg font-semibold mt-4 mb-2">Location</h4>
                <p class="text-gray-300 text-sm">📍 Jl. Semangi, Semarang, Jawa Tengah</p>
            </div>
        </div>
        <div class="border-t border-gray-600 text-center py-5 text-gray-400 text-xs">
            © {{ date('Y') }} Perumahan Zahra. All rights reserved.
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
