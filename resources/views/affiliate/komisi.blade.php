@extends('layouts.affiliate')
@section('title', 'Komisi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/komisi.css') }}">
@endpush

@section('content')

    <!-- Header -->
    <div class="page-header">
        <h1>Komisi</h1>
        <p>Riwayat komisi dan pembayaran anda</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card unpaid">
            <div class="stat-label">Total Closing</div>
            <div class="stat-value">{{ $totalClosing }}</div>
        </div>
        <div class="stat-card paid">
            <div class="stat-label">Closing Bulan Ini</div>
            <div class="stat-value">{{ $closingBulanIni }}</div>
        </div>
        <div class="stat-card total">
            <div class="stat-label">Rate Komisi</div>
            <div class="stat-value">{{ $commissionRate }}%</div>
        </div>
    </div>

    <!-- Info Row -->
    <div class="info-row">
        <div class="info-box">
            <h3>📅 Jadwal Pembayaran</h3>
            <ul>
                <li>Komisi dibayarkan setiap tanggal 25 setiap bulan</li>
                <li>Transfer langsung ke rekening yang terdaftar</li>
                <li>Minimum penarikan Rp 1.000.000</li>
                <li>Komisi dihitung setelah DP/Pelunasan diterima developer</li>
            </ul>
        </div>
        <div class="info-box">
            <h3>🏦 Rekening Terdaftar</h3>
            <ul>
                <li>Bank: BCA</li>
                <li>No. Rekening: 1234567890</li>
                <li>Atas Nama: {{ auth()->user()->name ?? 'Affiliate' }}</li>
            </ul>
        </div>
    </div>

    <!-- Summary Banner -->
    <div class="next-payment">
        <h2>Total Closing Anda</h2>
        <div class="amount">{{ $totalClosing }} Closing</div>
        <div class="estimate">Rate komisi: <span>{{ $commissionRate }}%</span> per unit terjual</div>
    </div>

    <!-- History Table -->
    <div class="history-card">
        <h3><i class="fas fa-history" style="color:#3d81af; margin-right:8px;"></i>Riwayat Closing</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Closing</th>
                    <th>Referral</th>
                    <th>Catatan</th>
                    <th>Rate Komisi</th>
                    <th>Perangkat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($closings as $closing)
                <tr>
                    <td>{{ $closing->updated_at->format('d M Y H:i') }}</td>
                    <td>{{ $closing->referral_code ?? '-' }}</td>
                    <td>{{ $closing->notes ?: '-' }}</td>
                    <td>{{ $commissionRate }}%</td>
                    <td>{{ $closing->device ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:2rem;color:#6b7280;">
                        Belum ada data closing.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection