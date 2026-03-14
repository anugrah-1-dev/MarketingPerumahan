<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Manager') – Properti Management</title>
    <link rel="stylesheet" href="{{ asset('assets/manager/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Properti Manager</h2>
            <button class="toggle-btn" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('manager.dashboard') }}" class="nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('manager.agents') }}" class="nav-item {{ request()->routeIs('manager.agents*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Manajemen Agent</span>
            </a>
            <a href="{{ route('manager.tipe-rumah') }}" class="nav-item {{ request()->routeIs('manager.tipe-rumah*') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Tipe Rumah</span>
            </a>
            <a href="{{ route('manager.units') }}" class="nav-item {{ request()->routeIs('manager.units*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Manajemen Unit</span>
            </a>
            <a href="{{ route('manager.pengisian-data') }}" class="nav-item {{ request()->routeIs('manager.pengisian-data') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Pengisian Data Client</span>
            </a>
            <a href="{{ route('manager.users') }}" class="nav-item {{ request()->routeIs('manager.users*') ? 'active' : '' }}">
                <i class="fas fa-user-lock"></i>
                <span>Manajemen Users</span>
            </a>
            <a href="{{ route('manager.tracking') }}" class="nav-item {{ request()->routeIs('manager.tracking') ? 'active' : '' }}">
                <i class="fas fa-mouse-pointer"></i>
                <span>Tracking Klik WA</span>
            </a>
            <a href="{{ route('manager.closing') }}" class="nav-item {{ request()->routeIs('manager.closing') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>Manajemen Closing</span>
            </a>
            <a href="{{ route('manager.social-media') }}" class="nav-item {{ request()->routeIs('manager.social-media*') ? 'active' : '' }}">
                <i class="fas fa-share-alt"></i>
                <span>Social Media</span>
            </a>
            <a href="{{ route('manager.settings') }}" class="nav-item {{ request()->routeIs('manager.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div style="padding:12px 16px; font-size:13px; color:#aaa; border-top:1px solid rgba(255,255,255,0.1)">
                <i class="fas fa-user-cog" style="margin-right:6px"></i>
                {{ auth()->user()->name ?? 'Admin' }}
                <span style="display:block;font-size:10px;opacity:0.6;margin-top:2px;text-transform:uppercase;letter-spacing:1px">
                    Admin
                </span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/manager/js/sidebar.js') }}"></script>
    @stack('scripts')
</body>
</html>
