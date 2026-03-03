@extends('layouts.affiliate')
@section('title', 'Dashboard Affiliate')

@push('styles')
<style>
/* ── Dashboard Content ── */
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
</style>
@endpush

@section('content')
<div class="dash-wrap">

    {{-- Header --}}
    <div class="dash-header">
        <h1>Dashboard Affiliate</h1>
        <p>Selamat datang kembali, <strong>{{ auth()->user()->name ?? 'Affiliate' }}</strong> 👋</p>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-mouse-pointer"></i></div>
            <div class="stat-info">
                <div class="label">Total Klik</div>
                <div class="value">45</div>
                <span class="badge">+8% minggu ini</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="label">Total Lead</div>
                <div class="value">25</div>
                <span class="badge">+5% minggu ini</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="label">Total Closing</div>
                <div class="value">3</div>
                <span class="badge">+0.5% bulan ini</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-coins"></i></div>
            <div class="stat-info">
                <div class="label">Total Komisi</div>
                <div class="value">Rp 1Jt</div>
                <span class="badge">+20% bulan ini</span>
            </div>
        </div>
    </div>

    {{-- Link Saya --}}
    <div class="link-box">
        <div>
            <div class="link-label">🔗 Link Affiliate Saya</div>
            <div class="link-url">https://perumahanzahra.com/{{ auth()->user()->name ?? 'affiliate' }}</div>
        </div>
        <button class="copy-btn" onclick="navigator.clipboard.writeText(this.previousElementSibling.querySelector('.link-url').textContent)">
            <i class="fas fa-copy"></i> Copy Link
        </button>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="activity-card">
        <h3><i class="fas fa-history" style="color:#3d81af; margin-right:8px;"></i>Aktivitas Terbaru</h3>

        <div class="activity-item">
            <div class="activity-dot dot-green"></div>
            <div class="activity-text">
                <div class="act-title">Lead baru klik dari WhatsApp</div>
                <div class="act-time">1 jam yang lalu</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-dot dot-blue"></div>
            <div class="activity-text">
                <div class="act-title">5 klik link affiliate WhatsApp</div>
                <div class="act-time">48 menit yang lalu</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-dot dot-amber"></div>
            <div class="activity-text">
                <div class="act-title">Komisi Rp 1.000.000 telah ready</div>
                <div class="act-time">1 hari yang lalu</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-dot dot-purple"></div>
            <div class="activity-text">
                <div class="act-title">30 klik link Website</div>
                <div class="act-time">4 hari yang lalu</div>
            </div>
        </div>
    </div>

</div>
@endsection
