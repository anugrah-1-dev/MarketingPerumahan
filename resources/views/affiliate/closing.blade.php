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
            <div class="stat-value">{{ $stats['total_closing'] }}</div>
            <span class="stat-badge badge-blue">Sepanjang waktu</span>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Closing Bulan Ini</div>
            <div class="stat-value">{{ $stats['closing_bulan_ini'] }}</div>
            <span class="stat-badge badge-blue">{{ now()->translatedFormat('F Y') }}</span>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Rate Komisi</div>
            <div class="stat-value">{{ $commissionRate }}%</div>
            <span class="stat-badge badge-blue">Per closing</span>
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
                    <th>Nama Customer</th>
                    <th>Tipe Rumah</th>
                    <th>Harga Jual</th>
                    <th>Tanggal Closing</th>
                    <th>Komisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($closings as $closing)
                <tr>
                    <td>
                        <div style="font-weight:600; color:#1e293b;">{{ $closing->customer_name }}</div>
                        <div class="secondary">{{ $closing->customer_phone ?? '-' }}</div>
                    </td>
                    <td>
                        @if($closing->tipeRumah)
                            <span class="badge badge-wa">{{ $closing->tipeRumah->nama }}</span>
                        @else
                            <span class="badge">–</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($closing->harga_jual, 0, ',', '.') }}</td>
                    <td>
                        {{ $closing->tanggal_closing ? $closing->tanggal_closing->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #059669;">Rp {{ number_format($closing->komisi_nominal, 0, ',', '.') }}</div>
                        @if($closing->payment_status === 'paid-off')
                            <span class="badge badge-sudah" style="margin-top: 4px;">Lunas</span>
                        @elseif($closing->payment_status === 'installment')
                            <span class="badge badge-ig" style="margin-top: 4px; background: #dbeafe; color: #1e40af;">Cicilan</span>
                        @else
                            <span class="badge badge-pending" style="margin-top: 4px; background: #fef3c7; color: #92400e;">DP</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:2rem;color:#6b7280;">
                        Belum ada closing dari link Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
