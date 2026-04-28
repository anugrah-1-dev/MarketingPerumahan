@extends('layouts.affiliate')
@section('title', 'Beranda Afiliasi')

@push('styles')
<style>
/* -- Dasbor Konten -- */
.dash-wrap {
    padding: 36px 36px 40px;
    background: #f9f9f9;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
}
.dash-header h1 { font-size: 28px; font-weight: 700; color: #222; }
.dash-header p  { color: #888; font-size: 14px; margin-top: 4px; }

/* Stats */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-top: 28px;
}
.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 22px 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    gap: 16px;
}
.stat-icon {
    width: 50px; height: 50px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-icon.purple { background: rgba(139,92,246,0.12); color: #7c3aed; }
.stat-icon.green  { background: rgba(16,185,129,0.12); color: #059669; }
.stat-icon.blue   { background: rgba(59,130,246,0.12); color: #2563eb; }
.stat-icon.amber  { background: rgba(245,158,11,0.12); color: #d97706; }
.stat-info .label { font-size: 13px; color: #888; font-weight: 500; }
.stat-info .value { font-size: 26px; font-weight: 700; color: #1e1e1e; line-height: 1.2; margin-top: 2px; }
.stat-info .badge {
    display: inline-block; margin-top: 6px;
    padding: 2px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 500;
    background: rgba(99,211,174,0.2); color: #065f46;
}

/* Link saya */
.link-box {
    margin-top: 24px;
    background: linear-gradient(135deg, rgba(61,129,175,0.9) 0%, rgba(26,54,73,0.9) 100%);
    border-radius: 16px;
    padding: 24px 28px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    box-shadow: 0 4px 16px rgba(26,54,73,0.2);
}
.link-box .link-label { font-size: 14px; opacity: 0.8; margin-bottom: 4px; }
.link-box .link-url   { font-size: 15px; font-weight: 600; word-break: break-all; }
.link-box .copy-btn {
    padding: 10px 22px; border-radius: 8px;
    background: rgba(255,255,255,0.2);
    color: #fff; font-size: 14px; font-weight: 600;
    cursor: pointer; border: 1px solid rgba(255,255,255,0.3);
    white-space: nowrap; transition: background 0.2s;
    display: flex; align-items: center; gap: 8px;
}
.link-box .copy-btn:hover { background: rgba(255,255,255,0.3); }

/* Aktivitas */
.activity-card {
    margin-top: 24px;
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.activity-card h3 { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 18px; }
.activity-item {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 10px; height: 10px; border-radius: 50%;
    margin-top: 5px; flex-shrink: 0;
}
.dot-green  { background: #10b981; }
.dot-blue   { background: #3b82f6; }
.dot-amber  { background: #f59e0b; }
.dot-purple { background: #8b5cf6; }
.activity-text { flex: 1; }
.activity-text .act-title { font-size: 14px; color: #333; font-weight: 500; }
.activity-text .act-time  { font-size: 12px; color: #aaa; margin-top: 2px; }
.empty-activity { 
    text-align: center; 
    padding: 30px 0; 
    color: #999; 
    font-size: 14px; 
}

@media (max-width: 1024px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .stats-row { grid-template-columns: 1fr; }
    .dash-wrap { padding: 20px 16px 30px; }
}
</style>
@endpush

@section('content')
<div class="dash-wrap">

    {{-- Header --}}
    <div class="dash-header">
        <h1>Beranda Afiliasi</h1>
        <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> </p>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-mouse-pointer"></i></div>
            <div class="stat-info">
                <div class="label">Total Klik</div>
                <div class="value">{{ $stats['total_klik'] }}</div>
                @if($stats['total_klik'] > 0)
                    <span class="badge">{{ $stats['klik_bulan'] }} bln ini</span>
                @else
                    <span class="badge" style="background:#f3f4f6;color:#6b7280;">Bulan ini</span>
                @endif
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="label">Total Prospek</div>
                <div class="value">{{ $stats['total_leads'] }}</div>
                @if($stats['conversion_rate'] > 0)
                    <span class="badge" style="background:rgba(59,130,246,0.12);color:#2563eb;">{{ $stats['conversion_rate'] }}% Konv.</span>
                @else
                    <span class="badge" style="background:#f3f4f6;color:#6b7280;">Tingkat Konv.</span>
                @endif
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="label">Total Penutupan</div>
                <div class="value">{{ $stats['total_closing'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-coins"></i></div>
            <div class="stat-info">
                <div class="label">Total Komisi</div>
                <div class="value">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Link Saya --}}
    <div class="link-box">
        <div>
            <div class="link-label"><i class="fas fa-link"></i> Tautan Afiliasi Utama Saya</div>
            <div class="link-url">{{ auth()->user()->referral_link }}</div>
        </div>
        <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ auth()->user()->referral_link }}'); alert('Link berhasil disalin!');">
            <i class="fas fa-copy"></i> Salin Tautan
        </button>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="activity-card">
        <h3><i class="fas fa-history" style="color:#3d81af; margin-right:8px;"></i>Aktivitas Terbaru</h3>

        @forelse($activities as $activity)
            <div class="activity-item">
                @php
                    $dotClass = match($activity->status) {
                        'new' => 'dot-blue',
                        'follow-up' => 'dot-amber',
                        'interested' => 'dot-purple',
                        'closed' => 'dot-green',
                        default => 'dot-blue'
                    };
                    $statusText = match($activity->status) {
                        'new' => 'Klik link WhatsApp baru',
                        'follow-up' => 'Proses Tindak Lanjut Prospek',
                        'interested' => 'Prospek tertarik produk',
                        'closed' => 'Penutupan berhasil',
                        default => 'Aktivitas WhatsApp'
                    };
                @endphp
                
                <div class="activity-dot {{ $dotClass }}"></div>
                <div class="activity-text">
                    <div class="act-title">
                        {{ $statusText }} dari {{ $activity->ip_address }}
                    </div>
                    <div class="act-time">
                        {{ $activity->created_at->diffForHumans() }} 
                        &bull; Via {{ $activity->device }} ({{ $activity->browser }})
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-activity">
                Belum ada aktivitas klik atau leads dari link referral Anda.
            </div>
        @endforelse
    </div>

</div>
@endsection


