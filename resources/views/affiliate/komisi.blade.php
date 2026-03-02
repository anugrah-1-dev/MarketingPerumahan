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
            <div class="stat-label">Belum dibayar</div>
            <div class="stat-value" id="unpaidAmount">Rp. 2.000.000</div>
        </div>
        <div class="stat-card paid">
            <div class="stat-label">Sudah di bayar</div>
            <div class="stat-value" id="paidAmount">Rp. 2.000.000</div>
        </div>
        <div class="stat-card total">
            <div class="stat-label">Total Komisi</div>
            <div class="stat-value" id="totalAmount">Rp. 4.000.000</div>
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

    <!-- Next Payment Banner -->
    <div class="next-payment">
        <h2>Pembayaran berikutnya</h2>
        <div class="amount" id="nextAmount">Rp 2.000.000</div>
        <div class="estimate">Estimasi tanggal: &nbsp;<span id="nextDate">26 Februari 2026</span></div>
    </div>

    <!-- History Table -->
    <div class="history-card">
        <h3><i class="fas fa-history" style="color:#3d81af; margin-right:8px;"></i>Riwayat Komisi</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pembeli</th>
                    <th>Unit</th>
                    <th>Nilai Komisi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="komisiTableBody">
                <!-- Populated by JS -->
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('assets/affiliate/js/komisi.js') }}"></script>
@endpush
