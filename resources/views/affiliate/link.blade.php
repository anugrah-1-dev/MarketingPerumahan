@extends('layouts.affiliate')
@section('title', 'Link Saya – Bukit Shangrilla Asri')

@push('styles')
<style>
/* ── Link Page Content ── */
.link-wrap {
    padding: 36px 36px 40px;
    background: #f9f9f9;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
}

.link-header h1 { font-size: 28px; font-weight: 700; color: #222; }
.link-header p  { color: #888; font-size: 14px; margin-top: 4px; }

/* ── Link Utama ── */
.link-main-box {
    margin-top: 28px;
    background: linear-gradient(135deg, rgba(61,129,175,0.9) 0%, rgba(26,54,73,0.9) 100%);
    border-radius: 16px;
    padding: 28px 32px;
    color: #fff;
    box-shadow: 0 4px 16px rgba(26,54,73,0.2);
}
.link-main-box .box-label {
    font-size: 13px;
    opacity: 0.75;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.link-input-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.link-input-row input {
    flex: 1;
    min-width: 0;
    padding: 12px 16px;
    border-radius: 10px;
    border: 1.5px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    outline: none;
}
.link-input-row input::placeholder { color: rgba(255,255,255,0.5); }
.copy-btn {
    padding: 12px 22px;
    border-radius: 10px;
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid rgba(255,255,255,0.35);
    white-space: nowrap;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
}
.copy-btn:hover { background: rgba(255,255,255,0.3); }

/* ── Dua kolom: QR + Info ── */
.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-top: 18px;
}

/* ── QR Code Card ── */
.qr-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}
.qr-card h3 {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    align-self: flex-start;
}
.qr-card .qr-img-wrap {
    width: 160px; height: 160px;
    border-radius: 12px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.qr-card .qr-img-wrap img { width: 140px; height: 140px; object-fit: contain; }
.qr-placeholder { font-size: 40px; color: #cbd5e1; }
.download-btn {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1.5px solid #d1d5db;
    background: none;
    color: #444;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.2s, color 0.2s;
}
.download-btn:hover { border-color: #3d81af; color: #3d81af; }

/* ── Info Affiliate Card ── */
.info-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.info-card h3 {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    margin-bottom: 18px;
}
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}
.info-row:last-child { border-bottom: none; }
.info-row .info-label { color: #888; }
.info-row .info-val   { font-weight: 600; color: #222; }
.badge-aktif {
    background: rgba(16,185,129,0.12);
    color: #059669;
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* ── Share Sosmed Card ── */
.share-card {
    margin-top: 18px;
    background: #fff;
    border-radius: 14px;
    padding: 28px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.share-card h3 {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    margin-bottom: 18px;
}
.share-icons {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}
.share-icon-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-family: 'Inter', sans-serif;
    transition: opacity 0.2s;
    color: #fff;
    text-decoration: none;
}
.share-icon-btn:hover { opacity: 0.85; }
.share-wa       { background: #25D366; }
.share-fb       { background: #1877F2; }
.share-tg       { background: #2AABEE; }
.share-x        { background: #1a1a1a; }

@media (max-width: 768px) {
    .link-wrap { padding: 20px 16px 30px; }
    .two-col   { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
@php
    $user     = auth()->user();
    $nama     = $user->name ?? 'affiliate';
    $linkUrl  = url('/') . '/' . Str::slug($nama);
    $joinDate = $user->created_at ? $user->created_at->translatedFormat('d F Y') : '–';
@endphp

<div class="link-wrap">

    {{-- Header --}}
    <div class="link-header">
        <h1>Link Affiliate Saya</h1>
        <p>Share link ini kepada calon pembeli untuk mendapatkan komisi</p>
    </div>

    {{-- Link Utama --}}
    <div class="link-main-box">
        <div class="box-label">🔗 Link Affiliate Kamu</div>
        <div class="link-input-row">
            <input type="text" id="affiliateLink" value="{{ $linkUrl }}" readonly>
            <button class="copy-btn" onclick="copyLink()">
                <i class="fas fa-copy"></i> Copy Link
            </button>
        </div>
    </div>

    {{-- QR + Info --}}
    <div class="two-col">

        {{-- QR Code --}}
        <div class="qr-card">
            <h3><i class="fas fa-qrcode" style="color:#3d81af;margin-right:6px;"></i>QR Code</h3>
            <div class="qr-img-wrap">
                <i class="fas fa-qrcode qr-placeholder"></i>
            </div>
            <button class="download-btn">
                <i class="fas fa-download"></i> Download QR
            </button>
        </div>

        {{-- Informasi Affiliate --}}
        <div class="info-card">
            <h3><i class="fas fa-info-circle" style="color:#3d81af;margin-right:6px;"></i>Informasi Affiliate</h3>

            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-val">{{ $user->name ?? '–' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode Affiliate</span>
                <span class="info-val">{{ Str::slug($nama) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="badge-aktif">Aktif</span>
            </div>
            <div class="info-row">
                <span class="info-label">Bergabung Sejak</span>
                <span class="info-val">{{ $joinDate }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Klik Link</span>
                <span class="info-val">52 klik</span>
            </div>
        </div>

    </div>

    {{-- Share Sosial Media --}}
    <div class="share-card">
        <h3><i class="fas fa-share-alt" style="color:#3d81af;margin-right:6px;"></i>Share ke Sosial Media</h3>
        <div class="share-icons">
            <a href="https://wa.me/?text={{ urlencode('Lihat perumahan impian kamu di sini: ' . $linkUrl) }}"
               target="_blank" class="share-icon-btn share-wa">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($linkUrl) }}"
               target="_blank" class="share-icon-btn share-fb">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($linkUrl) }}&text={{ urlencode('Perumahan luar biasa, cek di sini!') }}"
               target="_blank" class="share-icon-btn share-tg">
                <i class="fab fa-telegram-plane"></i> Telegram
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($linkUrl) }}&text={{ urlencode('Cek perumahan impianmu!') }}"
               target="_blank" class="share-icon-btn share-x">
                <i class="fab fa-x-twitter"></i> X (Twitter)
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyLink() {
    const input = document.getElementById('affiliateLink');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i> Copy Link', 2000);
    });
}
</script>
@endpush
