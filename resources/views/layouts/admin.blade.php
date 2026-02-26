<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – Properti Management</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Properti Admin</h2>
            <button class="toggle-btn" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.agents') }}" class="nav-item {{ request()->routeIs('admin.agents') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Manajemen Agent</span>
            </a>
            <a href="{{ route('admin.tracking') }}" class="nav-item {{ request()->routeIs('admin.tracking') ? 'active' : '' }}">
                <i class="fas fa-mouse-pointer"></i>
                <span>Tracking Klik WA</span>
            </a>
            <a href="{{ route('admin.closing') }}" class="nav-item {{ request()->routeIs('admin.closing') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>Manajemen Closing</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div style="padding:12px 16px; font-size:13px; color:#aaa; border-top:1px solid rgba(255,255,255,0.1)">
                <i class="fas fa-user-shield" style="margin-right:6px"></i>
                {{ auth()->user()->name ?? 'Super Admin' }}
                <span style="display:block;font-size:10px;opacity:0.6;margin-top:2px;text-transform:uppercase;letter-spacing:1px">
                    {{ auth()->user()->role ?? 'super_admin' }}
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
                    <span class="user-name">{{ auth()->user()->name ?? 'Super Admin' }}</span>
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
    <script src="{{ asset('assets/admin/js/sidebar.js') }}"></script>
    @stack('scripts')
</body>
</html>
