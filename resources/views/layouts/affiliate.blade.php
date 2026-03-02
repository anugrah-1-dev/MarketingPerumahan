<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Affiliate Panel') – Perumahan Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/affiliate/css/sidebar.css') }}">
    @stack('styles')
</head>
<body>

    <!-- ── Sidebar ── -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>Affiliate Panel</h2>
            <p>Perumahan premium</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('affiliate.dashboard') }}" class="nav-item {{ request()->routeIs('affiliate.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                Dashboard
            </a>
            <a href="{{ route('affiliate.link') }}" class="nav-item {{ request()->routeIs('affiliate.link') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-link"></i></span>
                Link saya
            </a>
            <a href="{{ route('affiliate.leads') }}" class="nav-item {{ request()->routeIs('affiliate.leads') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                Leads
            </a>
            <a href="{{ route('affiliate.closing') }}" class="nav-item {{ request()->routeIs('affiliate.closing') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-check-circle"></i></span>
                Closing
            </a>
            <a href="{{ route('affiliate.komisi') }}" class="nav-item {{ request()->routeIs('affiliate.komisi') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-coins"></i></span>
                Komisi
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><i class="fas fa-home"></i></span>
                Unit rumah
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><i class="fas fa-tools"></i></span>
                Marketing tools
            </a>
            <a href="{{ route('affiliate.profile') }}" class="nav-item {{ request()->routeIs('affiliate.profile') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ── Main Content ── -->
    <main class="main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
