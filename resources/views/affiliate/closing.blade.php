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
                    <th>Catatan Lead</th>
                    <th>Sumber Referral</th>
                    <th>Perangkat</th>
                    <th>Tanggal Closing</th>
                    <th>Komisi ({{ $commissionRate }}%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($closings as $closing)
                <tr>
                    <td>
                        {{ $closing->notes ?: '(tidak ada catatan)' }}
                        <div class="secondary">{{ $closing->ip_address ?? '-' }}</div>
                    </td>
                    <td>
                        @if($closing->referral_code)
                            <span class="badge badge-wa">🔗 {{ $closing->referral_code }}</span>
                        @else
                            <span class="badge">–</span>
                        @endif
                    </td>
                    <td>{{ $closing->device ?? '-' }} / {{ $closing->browser ?? '-' }}</td>
                    <td>
                        {{ $closing->updated_at->format('d M Y') }}
                        <div class="secondary">{{ $closing->updated_at->format('H:i') }}</div>
                    </td>
                    <td><span class="badge badge-sudah">{{ $commissionRate }}%</span></td>
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

@endsection
