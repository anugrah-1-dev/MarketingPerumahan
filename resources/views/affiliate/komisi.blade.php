@extends('layouts.affiliate')
@section('title', 'Komisi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/komisi.css') }}?v={{ file_exists(public_path('assets/affiliate/css/komisi.css')) ? filemtime(public_path('assets/affiliate/css/komisi.css')) : '1.0' }}">
@endpush

@section('content')

    <!-- Header -->
    <div class="page-header">
        <h1>Komisi</h1>
        <p>Riwayat komisi dan pembayaran anda</p>
    </div>

    @if(!$agent)
    <!-- No agent linked -->
    <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:.75rem;padding:1.5rem;margin-bottom:1.5rem;text-align:center">
        <i class="fas fa-exclamation-triangle" style="color:#f59e0b;font-size:1.5rem;margin-bottom:.5rem;display:block"></i>
        <strong>Akun anda belum terhubung ke data agent.</strong><br>
        Silakan hubungi administrator untuk mengaitkan akun anda.
    </div>
    @else

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
        <div class="stat-card" style="border-top:4px solid #10b981">
            <div class="stat-label">Total Komisi</div>
            <div class="stat-value" style="font-size:1.1rem">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card" style="border-top:4px solid #f59e0b">
            <div class="stat-label">Komisi Pending</div>
            <div class="stat-value" style="font-size:1.1rem;color:#f59e0b">Rp {{ number_format($komisiPending, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card" style="border-top:4px solid #3b82f6">
            <div class="stat-label">Komisi Terbayar</div>
            <div class="stat-value" style="font-size:1.1rem;color:#3b82f6">Rp {{ number_format($komisiTerbayar, 0, ',', '.') }}</div>
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
            <h3>👤 Informasi Agent</h3>
            <ul>
                <li>Nama: <strong>{{ $agent->nama }}</strong></li>
                <li>Rate Komisi: <strong>{{ $commissionRate }}%</strong></li>
                <li>Status: <strong>{{ $agent->aktif ? 'Aktif' : 'Tidak Aktif' }}</strong></li>
                <li>Atas Nama: <strong>{{ auth()->user()->name }}</strong></li>
            </ul>
        </div>
    </div>

    <!-- Summary Banner -->
    <div class="next-payment">
        <h2>Ringkasan Komisi Anda</h2>
        <div class="amount">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</div>
        <div class="estimate">
            Komisi terbayar: <span>Rp {{ number_format($komisiTerbayar, 0, ',', '.') }}</span> &nbsp;|&nbsp;
            Pending: <span>Rp {{ number_format($komisiPending, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- History Table -->
    <div class="history-card">
        <h3><i class="fas fa-history" style="color:#3d81af; margin-right:8px;"></i>Riwayat Closing & Komisi</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Closing</th>
                    <th>Tipe Rumah</th>
                    <th>Customer</th>
                    <th>Harga Jual</th>
                    <th>Rate Komisi</th>
                    <th>Komisi</th>
                    <th>Status Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($closings as $closing)
                <tr>
                    <td data-label="Tanggal Closing">{{ $closing->tanggal_closing?->format('d M Y') ?? '-' }}</td>
                    <td data-label="Tipe Rumah">{{ $closing->tipeRumah?->nama_tipe ?? '-' }}</td>
                    <td data-label="Customer">
                        <div><strong>{{ $closing->customer_name }}</strong></div>
                        <div style="font-size:.8rem;color:#6b7280">{{ $closing->customer_phone }}</div>
                    </td>
                    <td data-label="Harga Jual">Rp {{ number_format($closing->harga_jual, 0, ',', '.') }}</td>
                    <td data-label="Rate Komisi">{{ $closing->komisi_persen }}%</td>
                    <td data-label="Komisi" style="font-weight:600;color:#10b981">
                        Rp {{ number_format($closing->komisi_nominal, 0, ',', '.') }}
                    </td>
                    <td data-label="Status Bayar">
                        @if($closing->payment_status === 'paid-off')
                            <span style="background:#d1fae5;color:#065f46;padding:.25rem .6rem;border-radius:1rem;font-size:.8rem;font-weight:600">Lunas</span>
                        @elseif($closing->payment_status === 'installment')
                            <span style="background:#dbeafe;color:#1e40af;padding:.25rem .6rem;border-radius:1rem;font-size:.8rem;font-weight:600">Cicilan</span>
                        @else
                            <span style="background:#fef3c7;color:#92400e;padding:.25rem .6rem;border-radius:1rem;font-size:.8rem;font-weight:600">DP</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:#6b7280;">
                        Belum ada data closing.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @endif

@endsection