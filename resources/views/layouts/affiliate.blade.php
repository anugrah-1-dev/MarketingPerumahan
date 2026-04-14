<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Afiliasi') - Perumahan Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/affiliate/css/sidebar.css') }}?v={{ file_exists(public_path('assets/affiliate/css/sidebar.css')) ? filemtime(public_path('assets/affiliate/css/sidebar.css')) : '1.0' }}">
    @stack('styles')
</head>
<body>

    <!-- -- Mobile Top Bar (hanya terlihat di &le;1024px) -- -->
    <div class="mobile-topbar">
        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Buka menu" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="topbar-title">Panel Afiliasi</div>
    </div>

    <!-- -- Overlay (untuk menutup sidebar saat klik di luar) -- -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- -- Sidebar -- -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h2>Panel Afiliasi</h2>
            <p>Perumahan premium</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('affiliate.dashboard') }}" class="nav-item {{ request()->routeIs('affiliate.dashboard') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                Beranda
            </a>
            <a href="{{ route('affiliate.link') }}" class="nav-item {{ request()->routeIs('affiliate.link') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-link"></i></span>
                Tautan Saya
            </a>
            <a href="{{ route('affiliate.leads') }}" class="nav-item {{ request()->routeIs('affiliate.leads') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                Rekap
            </a>
            <a href="{{ route('affiliate.closing') }}" class="nav-item {{ request()->routeIs('affiliate.closing') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-check-circle"></i></span>
                Penutupan
            </a>
            <a href="{{ route('affiliate.komisi') }}" class="nav-item {{ request()->routeIs('affiliate.komisi') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-coins"></i></span>
                Komisi
            </a>
            <a href="{{ route('affiliate.pengisian-data') }}" class="nav-item {{ request()->routeIs('affiliate.pengisian-data') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                Pengisian Data
            </a>
            <a href="{{ route('affiliate.profile') }}" class="nav-item {{ request()->routeIs('affiliate.profile') ? 'active' : '' }}" onclick="closeSidebarOnNav()">
                <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                Profil
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- -- Main Konten -- -->
    <main class="main">
        @yield('content')
    </main>

    @stack('scripts')

    <script>
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-open');
    }

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
    }

    // Tutup sidebar saat nav item diklik (di mobile, agar halaman baru terasa responsif)
    function closeSidebarOnNav() {
        if (window.innerWidth <= 1024) {
            closeSidebar();
        }
    }

    // Tutup sidebar saat tekan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
    </script>
</body>
</html>


