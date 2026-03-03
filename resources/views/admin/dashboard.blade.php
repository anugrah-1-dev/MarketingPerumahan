@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-mouse-pointer"></i></div>
            <div class="stat-content"><h3 id="totalClicks">0</h3><p>Total Klik</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
            <div class="stat-content"><h3 id="totalClosing">0</h3><p>Total Closing</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-content"><h3 id="totalCommission">Rp 0</h3><p>Total Komisi</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div class="stat-content"><h3 id="activeAgents">0</h3><p>Agent Aktif</p></div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="card">
            <div class="card-header"><h2>Tren Klik 7 Hari Terakhir</h2></div>
            <div class="card-body"><canvas id="clickTrendsChart"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><h2>Top 5 Agent</h2></div>
            <div class="card-body">
                <div class="agent-list" id="topAgentsList"></div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header"><h2>Aktivitas Terbaru</h2></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Waktu</th><th>Agent</th><th>Aktivitas</th><th>Status</th></tr>
                    </thead>
                    <tbody id="recentActivityTable"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/dashboard.js') }}"></script>
@endpush
