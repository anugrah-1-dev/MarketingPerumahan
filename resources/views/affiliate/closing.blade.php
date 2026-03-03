@extends('layouts.affiliate')
@section('title', 'Daftar Closing')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/closing.css') }}">
@endpush

@section('content')

    <!-- Header -->
    <div class="page-title">
        <h1>Daftar Closing</h1>
        <p>Transaksi berhasil dari leads yang masuk lewat link marketing Anda</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card pink">
            <div class="stat-label">Total Closing</div>
            <div class="stat-value">25</div>
            <span class="stat-badge badge-blue">Sepanjang waktu</span>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Closing Bulan Ini</div>
            <div class="stat-value">5</div>
            <span class="stat-badge badge-blue">↑ 2 dari bulan lalu</span>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Komisi Diperoleh</div>
            <div class="stat-value">Rp 50Jt</div>
            <span class="stat-badge badge-blue">3 pending cair</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-search">
            <span class="icon"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Cari nama, no HP, atau email">
        </div>
        <div class="filter-select">
            Semua Sumber <span class="chevron"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="filter-select">
            Semua Tipe Unit <span class="chevron"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="filter-select">
            30 hari terakhir <span class="chevron"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="filter-select">
            Semua Komisi <span class="chevron"><i class="fas fa-chevron-down"></i></span>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Pembeli</th>
                    <th>Sumber Link</th>
                    <th>Unit Dibeli</th>
                    <th>Tanggal Closing</th>
                    <th>Komisi (2%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Andi Saputra
                        <div class="secondary">+62 812-3456-7890</div>
                    </td>
                    <td><span class="badge badge-wa">🟢 WhatsApp</span></td>
                    <td>Tipe 45 – Blok A</td>
                    <td>
                        25 Feb 2026
                        <div class="secondary">AJB Notaris</div>
                    </td>
                    <td><span class="badge badge-sudah">✔ Sudah Cair</span></td>
                </tr>
                <tr>
                    <td>
                        Andi Saputra
                        <div class="secondary">+62 812-3456-7890</div>
                    </td>
                    <td><span class="badge badge-ig">📸 Instagram</span></td>
                    <td>Tipe 45 – Blok A</td>
                    <td>
                        25 Feb 2026
                        <div class="secondary">AJB Notaris</div>
                    </td>
                    <td><span class="badge badge-pending">⏳ Pending</span></td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection
