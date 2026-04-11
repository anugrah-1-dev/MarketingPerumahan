@extends('layouts.affiliate')
@section('title', 'Tautan Afiliasi Saya â€“ Bukit Shangrilla Asri')

@push('styles')
<style>
/* -- Link Page Konten -- */
.link-wrap {
    padding: 36px 36px 40px;
    background: #f9f9f9;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
}
.link-header h1 { font-size: 28px; font-weight: 700; color: #222; }
.link-header p  { color: #888; font-size: 14px; margin-top: 4px; }

/* -- Kode Referral Besar -- */
.ref-code-box {
    margin-top: 24px;
    background: linear-gradient(135deg, #3d81af 0%, #1a3649 100%);
    border-radius: 16px;
    padding: 28px 32px;
    color: #fff;
    box-shadow: 0 4px 20px rgba(26,54,73,0.25);
}
.ref-code-box .box-label {
    font-size: 12px;
    opacity: 0.7;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
}
.ref-code-display {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.ref-code-badge {
    background: rgba(255,255,255,0.18);
    border: 2px solid rgba(255,255,255,0.35);
    border-radius: 12px;
    padding: 10px 28px;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: 4px;
    color: #fff;
    font-family: 'Courier New', monospace;
}
.ref-code-hint {
    font-size: 13px;
    opacity: 0.7;
    line-height: 1.5;
}

/* -- Link Input Row -- */
.box-sublabel {
    font-size: 12px;
    opacity: 0.65;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    margin-bottom: 8px;
}
.link-input-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.link-input-row input {
    flex: 1;
    min-width: 0;
    padding: 11px 16px;
    border-radius: 10px;
    border: 1.5px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.12);
    color: #fff;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    outline: none;
}
.link-input-row input::placeholder { color: rgba(255,255,255,0.45); }
.copy-btn {
    padding: 11px 20px;
    border-radius: 10px;
    background: rgba(255,255,255,0.22);
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
.copy-btn:hover { background: rgba(255,255,255,0.32); }
.copy-btn.copied { background: rgba(16,185,129,0.5); border-color: rgba(16,185,129,0.6); }

/* -- Dua kolom: QR + Info -- */
.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-top: 18px;
}

/* -- QR Code Card -- */
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
.qr-img-wrap {
    width: 170px; height: 170px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 8px;
}
.qr-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
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
    text-decoration: none;
}
.download-btn:hover { border-color: #3d81af; color: #3d81af; }

/* -- Info Affiliate Card -- */
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
.code-inline {
    background: #f1f5f9;
    color: #1e40af;
    border-radius: 6px;
    padding: 2px 10px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 1px;
}

/* -- Share Sosmed Card -- */
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
    margin-bottom: 8px;
}
.share-card .share-sub {
    font-size: 13px;
    color: #888;
    margin-bottom: 18px;
}
.share-icons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.share-icon-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-family: 'Inter', sans-serif;
    transition: opacity 0.2s, transform 0.15s;
    color: #fff;
    text-decoration: none;
}
.share-icon-btn:hover { opacity: 0.88; transform: translateY(-1px); }
.share-wa  { background: #25D366; }
.share-fb  { background: #1877F2; }
.share-tg  { background: #2AABEE; }
.share-x   { background: #1a1a1a; }

@media (max-width: 768px) {
    .link-wrap  { padding: 20px 16px 30px; }
    .two-col    { grid-template-columns: 1fr; }
    .ref-code-badge { font-size: 22px; letter-spacing: 2px; }
    .klik-stats { grid-template-columns: repeat(2, 1fr); }
    .link-header h1 { font-size: 22px; }
    .ref-code-box { padding: 20px 18px; }
}
@media (max-width: 480px) {
    .klik-stats { grid-template-columns: repeat(2, 1fr); }
    .ref-code-badge { font-size: 16px; letter-spacing: 1px; padding: 8px 16px; }
    .klik-stat-card .ks-val { font-size: 22px; }
    .link-input-row { flex-direction: column; }
    .link-input-row input { width: 100%; }
    .copy-btn { width: 100%; justify-content: center; }
}

/* -- Stat Klik Cards -- */
.klik-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 18px;
}
.klik-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    text-align: center;
}
.klik-stat-card .ks-icon  { font-size: 20px; margin-bottom: 8px; }
.klik-stat-card .ks-label { font-size: 12px; color: #888; margin-bottom: 6px; }
.klik-stat-card .ks-val   { font-size: 28px; font-weight: 800; color: #1a3649; line-height: 1; }
.klik-stat-card .ks-sub   { font-size: 11px; color: #10b981; margin-top: 5px; font-weight: 500; }
</style>
@endpush

@section('content')
@php
    use Illuminate\Support\Str;
    $user      = auth()->user();
    $refCode   = $user->referral_code ?? 'BSA-????';
    $linkUrl   = $user->referral_link ?? url('/ref/' . $refCode);
    $joinDate  = $user->created_at ? $user->created_at->translatedFormat('d F Y') : 'â€“';
    $qrUrl     = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&format=png&data=' . urlencode($linkUrl);
    $shareText = 'Temukan rumah impianmu di Bukit Shangrilla Asri! Cek di sini: ' . $linkUrl;
@endphp

<div class="link-wrap">

    {{-- Header --}}
    <div class="link-header">
        <h1>Tautan Afiliasi Saya</h1>
        <p>Share kode atau link berikut kepada calon pembeli untuk mendapatkan komisi</p>
    </div>

    {{-- Kode Referral + Link --}}
    <div class="ref-code-box">
        <div class="box-label">ðŸŽ¯ Kode Referral Unik Anda</div>
        <div class="ref-code-display">
            <div class="ref-code-badge">{{ $refCode }}</div>
            <div class="ref-code-hint">
                Kode ini unik dan permanen.<br>
                Setiap prospek yang datang via link ini<br>
                tercatat sebagai leads Anda.
            </div>
        </div>

        <div class="box-sublabel">ðŸ”— Tautan Afiliasi</div>
        <div class="link-input-row">
            <input type="text" id="affiliateLink" value="{{ $linkUrl }}" readonly>
            <button class="copy-btn" id="copyBtn" onclick="copyLink()">
                <i class="fas fa-copy"></i> Salin Tautan
            </button>
        </div>
    </div>

    {{-- Stats Klik WA --}}
    <div class="klik-stats">
        <div class="klik-stat-card">
            <div class="ks-icon">ðŸ“Š</div>
            <div class="ks-label">Total Klik WA</div>
            <div class="ks-val">{{ $totalKlik ?? 0 }}</div>
            <div class="ks-sub">Semua waktu</div>
        </div>
        <div class="klik-stat-card">
            <div class="ks-icon">ðŸ“…</div>
            <div class="ks-label">Bulan Ini</div>
            <div class="ks-val">{{ $klikBulanIni ?? 0 }}</div>
            <div class="ks-sub">{{ now()->translatedFormat('F Y') }}</div>
        </div>
        <div class="klik-stat-card">
            <div class="ks-icon">âš¡</div>
            <div class="ks-label">Hari Ini</div>
            <div class="ks-val">{{ $klikHariIni ?? 0 }}</div>
            <div class="ks-sub">{{ now()->translatedFormat('d M Y') }}</div>
        </div>
        <div class="klik-stat-card">
            <div class="ks-icon">ðŸ”¥</div>
            <div class="ks-label">Prospek Tertarik</div>
            <div class="ks-val">{{ $klikInterest ?? 0 }}</div>
            <div class="ks-sub">Status: Interested</div>
        </div>
    </div>

    {{-- QR + Info --}}
    <div class="two-col">

        {{-- QR Code (real, dari qrserver.com) --}}
        <div class="qr-card">
            <h3><i class="fas fa-qrcode" style="color:#3d81af;margin-right:6px;"></i>QR Code Link Anda</h3>
            <div class="qr-img-wrap">
                <img id="qrImg"
                     src="{{ $qrUrl }}"
                     alt="QR Code {{ $refCode }}"
                     loading="lazy">
            </div>
            <a class="download-btn"
               href="{{ $qrUrl }}&format=png"
               download="QR-{{ $refCode }}.png"
               target="_blank">
                <i class="fas fa-download"></i> Download QR Code
            </a>
        </div>

        {{-- Info Affiliate --}}
        <div class="info-card">
            <h3><i class="fas fa-id-card" style="color:#3d81af;margin-right:6px;"></i>Informasi Afiliasi</h3>

            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-val">{{ $user->name ?? 'â€“' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode Referral</span>
                <span class="code-inline">{{ $refCode }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="badge-aktif">âœ” Aktif</span>
            </div>
            <div class="info-row">
                <span class="info-label">Bergabung Sejak</span>
                <span class="info-val">{{ $joinDate }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Link Aktif</span>
                <span class="info-val" style="color:#3d81af;font-size:12px;word-break:break-all;">{{ $linkUrl }}</span>
            </div>
        </div>

    </div>

    {{-- Share Sosial Media --}}
    <div class="share-card">
        <h3><i class="fas fa-share-alt" style="color:#3d81af;margin-right:6px;"></i>Share ke Sosial Media</h3>
        <p class="share-sub">Semakin banyak yang melihat, semakin besar peluang komisi Anda</p>
        <div class="share-icons">
            <a href="https://wa.me/?text={{ urlencode($shareText) }}"
               target="_blank" class="share-icon-btn share-wa">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($linkUrl) }}"
               target="_blank" class="share-icon-btn share-fb">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($linkUrl) }}&text={{ urlencode($shareText) }}"
               target="_blank" class="share-icon-btn share-tg">
                <i class="fab fa-telegram-plane"></i> Telegram
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($linkUrl) }}&text={{ urlencode('Cek perumahan impianmu!') }}"
               target="_blank" class="share-icon-btn share-x">
                <i class="fab fa-x-twitter"></i> X / Twitter
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyLink() {
    const input = document.getElementById('affiliateLink');
    const btn   = document.getElementById('copyBtn');

    // Modern clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(input.value).then(() => showCopied(btn));
    } else {
        // Fallback untuk hosting HTTP atau browser lama
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        showCopied(btn);
    }
}

function showCopied(btn) {
    btn.classList.add('copied');
    btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
    setTimeout(() => {
        btn.classList.remove('copied');
        btn.innerHTML = '<i class="fas fa-copy"></i> Salin Tautan';
    }, 2500);
}
</script>
@endpush


