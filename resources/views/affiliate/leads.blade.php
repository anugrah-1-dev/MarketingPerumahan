@extends('layouts.affiliate')
@section('title', 'Rekap - Bukit Shangrilla Asri')

@push('styles')
<style>
.leads-wrap {
    padding: 36px 36px 40px;
    background: #f9f9f9;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
}
.leads-header h1 { font-size: 28px; font-weight: 700; color: #222; }
.leads-header p  { color: #888; font-size: 14px; margin-top: 4px; }

/* Stats */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-top: 28px;
}
.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    gap: 14px;
}
.stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.stat-icon.purple { background: rgba(139,92,246,0.12); color: #7c3aed; }
.stat-icon.green  { background: rgba(16,185,129,0.12);  color: #059669; }
.stat-icon.amber  { background: rgba(245,158,11,0.12);  color: #d97706; }
.stat-icon.blue   { background: rgba(59,130,246,0.12);  color: #2563eb; }
.stat-info .label { font-size: 12px; color: #888; font-weight: 500; }
.stat-info .value { font-size: 24px; font-weight: 800; color: #1e1e1e; line-height: 1.2; margin-top: 2px; }

/* Filter Bar */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 24px;
    flex-wrap: wrap;
}
.search-box {
    flex: 1;
    min-width: 220px;
    position: relative;
}
.search-box i {
    position: absolute;
    left: 14px; top: 50%;
    transform: translateY(-50%);
    color: #aaa; font-size: 14px;
}
.search-box input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: #333;
    background: #fff;
    outline: none;
    transition: border-color 0.2s;
}
.search-box input:focus { border-color: #3d81af; }
.filter-select {
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: #333;
    background: #fff;
    outline: none;
    cursor: pointer;
}
.filter-select:focus { border-color: #3d81af; }

/* Table */
.table-card {
    margin-top: 18px;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.table-card table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.table-card thead {
    background: #f8fafc;
    border-bottom: 1px solid #f0f0f0;
}
.table-card thead th {
    padding: 13px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    white-space: nowrap;
}
.table-card tbody tr {
    border-bottom: 1px solid #f5f5f5;
    transition: background 0.15s;
}
.table-card tbody tr:last-child { border-bottom: none; }
.table-card tbody tr:hover { background: #fafcff; }
.table-card tbody td {
    padding: 13px 18px;
    color: #333;
    vertical-align: middle;
}

/* Cells */
.click-no     { font-weight: 700; color: #888; font-size: 13px; }
.click-ip     { font-weight: 600; color: #1e1e1e; font-family: 'Courier New', monospace; font-size: 13px; }
.click-sub    { font-size: 12px; color: #aaa; margin-top: 2px; }
.click-device {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    background: #f3f4f6;
    color: #555;
    padding: 3px 10px;
    border-radius: 7px;
}
.click-date   { font-size: 13px; color: #555; }
.click-time   { font-size: 12px; color: #aaa; margin-top: 2px; }

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}
.badge-new          { background: rgba(59,130,246,0.12);  color: #2563eb; }
.badge-follow-up    { background: rgba(245,158,11,0.12);  color: #d97706; }
.badge-interested   { background: rgba(16,185,129,0.12);  color: #059669; }
.badge-not-interested { background: rgba(156,163,175,0.15); color: #6b7280; }
.badge-closed       { background: rgba(139,92,246,0.12);  color: #7c3aed; }

/* Empty state */
.empty-state {
    padding: 56px 24px;
    text-align: center;
    color: #aaa;
}
.empty-state .es-icon { font-size: 42px; margin-bottom: 12px; }
.empty-state p        { font-size: 14px; }
.empty-state small    { font-size: 12px; color: #bbb; }

/* Total bar */
.total-bar {
    padding: 12px 18px;
    background: #f8fafc;
    border-top: 1px solid #f0f0f0;
    font-size: 13px;
    color: #888;
    font-weight: 500;
}
.total-bar span { color: #1e1e1e; font-weight: 700; }

@media (max-width: 768px) {
    .leads-wrap { padding: 20px 16px 30px; }
    .stats-row  { grid-template-columns: repeat(2,1fr); }
    .table-card { overflow-x: auto !important; -webkit-overflow-scrolling: touch; }
    .table-card table { min-width: 580px; }
    .filter-bar { flex-direction: column; align-items: stretch; }
    .filter-select { width: 100%; }
    .leads-header h1 { font-size: 22px; }
}
@media (max-width: 480px) {
    .stats-row { grid-template-columns: 1fr; }
    .stat-info .value { font-size: 20px; }
}
/* Mobile: stacked cards for leads table */
@media (max-width: 600px) {
    .table-card thead { display: none; }
    .table-card table, .table-card tbody, .table-card tr, .table-card td { display: block; width: 100%; }
    .table-card tbody tr { margin-bottom: 12px; background: #fff; border-radius: 12px; padding: 12px 14px; box-shadow: 0 1px 6px rgba(16,24,40,0.04); }
    .table-card td { padding: 6px 0; border-bottom: none; }
    .table-card td::before { content: attr(data-label); display: block; font-size: 12px; color: #94a3b8; margin-bottom: 6px; font-weight: 600; }
    .table-card td[data-label="#"]::before { content: "#"; }
}
</style>
@endpush

@section('content')
<div class="leads-wrap">

    {{-- Header --}}
    <div class="leads-header">
        <h1>Rekap Klien</h1>
        <p>Semua pengunjung yang klik WhatsApp melalui link referral Anda</p>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-mouse-pointer"></i></div>
            <div class="stat-info">
                <div class="label">Total Klik WA</div>
                <div class="value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-bell"></i></div>
            <div class="stat-info">
                <div class="label">Prospek</div>
                <div class="value">{{ $stats['baru'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-phone-alt"></i></div>
            <div class="stat-info">
                <div class="label">Tindak Lanjut</div>
                <div class="value">{{ $stats['follow_up'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="label">Penutupan</div>
                <div class="value">{{ $stats['closing'] }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari IP, browser, page URL..." oninput="filterTable()">
        </div>
        <select class="filter-select" id="filterStatus" onchange="filterTable()">
            <option value="">Semua Status</option>
            <option value="new">Prospek</option>
            <option value="follow-up">Tindak Lanjut</option>
            <option value="interested">Tertarik</option>
            <option value="not-interested">Tidak Tertarik</option>
            <option value="closed">Penutupan</option>
        </select>
        <select class="filter-select" id="filterDevice" onchange="filterTable()">
            <option value="">Semua Perangkat</option>
            <option value="Mobile">Mobile</option>
            <option value="Desktop">Desktop</option>
        </select>
    </div>

    {{-- Tabel --}}
    <div class="table-card">
        <table id="leadsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>IP Address</th>
                    <th>Perangkat / Browser</th>
                    <th>Halaman Asal</th>
                    <th>Waktu Klik</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clicks as $i => $click)
                <tr data-status="{{ $click->status }}" data-device="{{ $click->device }}">
                    <td class="click-no">{{ $i + 1 }}</td>
                    <td>
                        <div class="click-ip">{{ $click->ip_address ?? '-' }}</div>
                        <div class="click-sub">{{ $click->page_url ? \Illuminate\Support\Str::limit($click->page_url, 40) : '-' }}</div>
                    </td>
                    <td data-label="Perangkat / Browser">
                        <span class="click-device">
                            @if($click->device === 'Mobile')
                                <i class="fas fa-mobile-alt"></i>
                            @else
                                <i class="fas fa-desktop"></i>
                            @endif
                            {{ $click->device ?? '-' }}
                        </span>
                        <div class="click-sub" style="margin-top:5px;">{{ $click->browser ?? '-' }}</div>
                    </td>
                    <td data-label="Halaman Asal">
                        @if($click->page_url)
                            <a href="{{ $click->page_url }}" target="_blank"
                               style="color:#3d81af;font-size:12px;word-break:break-all;text-decoration:none;"
                               title="{{ $click->page_url }}">
                                {{ \Illuminate\Support\Str::limit($click->page_url, 50) }}
                            </a>
                        @else
                            <span style="color:#bbb;">-</span>
                        @endif
                    </td>
                    <td data-label="Waktu Klik">
                        <div class="click-date">{{ $click->created_at->format('d M Y') }}</div>
                        <div class="click-time">{{ $click->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td data-label="Status">
                        @php
                            $badgeMap = [
                                'new'           => 'badge-new',
                                'follow-up'     => 'badge-follow-up',
                                'interested'    => 'badge-interested',
                                'not-interested'=> 'badge-not-interested',
                                'closed'        => 'badge-closed',
                            ];
                            $labelMap = [
                                'new'           => 'Prospek',
                                'follow-up'     => 'Tindak Lanjut',
                                'interested'    => 'Tertarik',
                                'not-interested'=> 'Tidak Tertarik',
                                'closed'        => 'Penutupan',
                            ];
                        @endphp
                        <span class="badge {{ $badgeMap[$click->status] ?? 'badge-new' }}">
                            {{ $labelMap[$click->status] ?? $click->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="es-icon"><i class="fas fa-search"></i></div>
                            <p><strong>Belum ada klik WhatsApp</strong></p>
                            <small>Bagikan link referral Anda agar pengunjung mulai tercatat di sini</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($clicks->isNotEmpty())
        <div class="total-bar">
            Menampilkan <span>{{ $clicks->count() }}</span> klik WA dari link referral Anda
        </div>
        @endif
    </div>

    {{-- ── Formulir Tambah Data Klien ──────────────────────────────────────── --}}
    <div style="margin-top:28px;">
        <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;background:#fff;border-radius:14px;padding:18px 24px;box-shadow:0 2px 8px rgba(0,0,0,0.06);" onclick="togglePengisian()">
            <h3 style="font-size:16px;font-weight:700;color:#222;margin:0;">
                <i class="fas fa-user-plus" style="color:#3d81af;margin-right:8px;"></i>Tambah Data Klien Baru
            </h3>
            <i class="fas fa-chevron-down" id="pengisianChevron" style="color:#aaa;transition:transform 0.25s;"></i>
        </div>

        <div id="pengisianBody" style="display:none;margin-top:12px;">

            @if(session('pengisian_ok'))
            <div style="background:#d1fae5;color:#065f46;padding:13px 18px;border-radius:10px;margin-bottom:14px;font-size:14px;">
                <i class="fas fa-check-circle"></i> Data klien berhasil dikirim ke tim marketing!
            </div>
            @endif

            {{-- Stepper --}}
            <div style="display:flex;align-items:center;gap:6px;margin-bottom:16px;">
                <div id="pStep1" style="padding:6px 18px;border-radius:20px;background:#1a3649;color:#fff;font-size:13px;font-weight:600;">Data Klien</div>
                <span style="color:#bbb;">&#8594;</span>
                <div id="pStep2" style="padding:6px 18px;border-radius:20px;color:#aaa;font-size:13px;font-weight:600;">Review</div>
            </div>

            {{-- Step 1: Input --}}
            <div id="pFormCard" style="background:#f0f0f0;border-radius:14px;padding:24px;max-width:560px;">
                <form id="pClientForm" novalidate>
                    @csrf
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">Nama Lengkap <span style="color:#e53935;">*</span></label>
                        <input type="text" id="pf_nama" placeholder="Masukan nama"
                            style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;outline:none;">
                        <div id="perr_nama" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">Email <span style="color:#e53935;">*</span></label>
                        <input type="email" id="pf_email" placeholder="Masukan email"
                            style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;outline:none;">
                        <div id="perr_email" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">No KTP / NIK <span style="color:#e53935;">*</span></label>
                        <input type="text" id="pf_nik" placeholder="Masukan NIK (16 digit)" maxlength="16"
                            style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;outline:none;">
                        <div id="perr_nik" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">No WhatsApp <span style="color:#e53935;">*</span></label>
                        <input type="text" id="pf_wa" placeholder="Masukan nomor"
                            style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;outline:none;">
                        <div id="perr_wa" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">Alamat <span style="color:#e53935;">*</span></label>
                        <textarea id="pf_alamat" placeholder="Masukan alamat" rows="3"
                            style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;outline:none;resize:vertical;"></textarea>
                        <div id="perr_alamat" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">Tipe Rumah <span style="color:#e53935;">*</span></label>
                        <select id="pf_tipe_rumah"
                            style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;outline:none;background:#fff;cursor:pointer;">
                            <option value="">-- Pilih Tipe Rumah --</option>
                            @foreach($tipeRumah as $tipe)
                            <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }} - Rp {{ number_format($tipe->harga, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                        <div id="perr_tipe_rumah" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;">Bukti Pembayaran <span style="color:#e53935;">*</span> <span style="font-weight:400;color:#888;font-size:12px;">(maks 5 MB)</span></label>
                        <input type="file" id="pf_bukti" accept="image/jpeg,image/png,image/webp"
                            style="width:100%;padding:10px 13px;border:1.5px dashed #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;background:#fafafa;cursor:pointer;">
                        <div style="font-size:12px;color:#888;margin-top:4px;">Format: JPG, PNG, WEBP</div>
                        <div id="perr_bukti" style="color:#e53935;font-size:12px;margin-top:3px;"></div>
                    </div>
                    <div style="display:flex;gap:10px;margin-top:18px;flex-wrap:wrap;">
                        <button type="button" onclick="document.getElementById('pClientForm').reset();document.getElementById('pf_tipe_rumah').value='';" 
                            style="padding:10px 24px;border-radius:20px;background:#e53935;color:#fff;font-size:14px;font-weight:600;border:none;cursor:pointer;">Batal</button>
                        <button type="button" onclick="pGoToReview()"
                            style="padding:10px 24px;border-radius:20px;background:#3d81af;color:#fff;font-size:14px;font-weight:600;border:none;cursor:pointer;">Lanjut &rarr;</button>
                    </div>
                </form>
            </div>

            {{-- Step 2: Review --}}
            <div id="pReviewCard" style="display:none;background:#f0f0f0;border-radius:14px;padding:24px;max-width:560px;">
                <p style="font-size:14px;color:#555;margin-bottom:14px;">Periksa kembali data sebelum dikonfirmasi.</p>
                <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:8px;">
                    <tr><td style="padding:8px 4px;color:#888;width:140px;border-bottom:1px solid #e0e0e0;">Nama Lengkap</td><td id="prv_nama" style="padding:8px 4px;font-weight:600;color:#222;border-bottom:1px solid #e0e0e0;"></td></tr>
                    <tr><td style="padding:8px 4px;color:#888;border-bottom:1px solid #e0e0e0;">Email</td><td id="prv_email" style="padding:8px 4px;font-weight:600;color:#222;border-bottom:1px solid #e0e0e0;"></td></tr>
                    <tr><td style="padding:8px 4px;color:#888;border-bottom:1px solid #e0e0e0;">No KTP / NIK</td><td id="prv_nik" style="padding:8px 4px;font-weight:600;color:#222;border-bottom:1px solid #e0e0e0;"></td></tr>
                    <tr><td style="padding:8px 4px;color:#888;border-bottom:1px solid #e0e0e0;">No WhatsApp</td><td id="prv_wa" style="padding:8px 4px;font-weight:600;color:#222;border-bottom:1px solid #e0e0e0;"></td></tr>
                    <tr><td style="padding:8px 4px;color:#888;border-bottom:1px solid #e0e0e0;">Alamat</td><td id="prv_alamat" style="padding:8px 4px;font-weight:600;color:#222;border-bottom:1px solid #e0e0e0;"></td></tr>
                    <tr><td style="padding:8px 4px;color:#888;border-bottom:1px solid #e0e0e0;">Tipe Rumah</td><td id="prv_tipe_rumah" style="padding:8px 4px;font-weight:600;color:#222;border-bottom:1px solid #e0e0e0;"></td></tr>
                    <tr><td style="padding:8px 4px;color:#888;">Bukti</td><td id="prv_bukti" style="padding:8px 4px;font-weight:600;color:#222;">-</td></tr>
                </table>
                <form method="POST" action="{{ route('affiliate.pengisian-data.store') }}" id="pSubmitForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_from" value="leads">
                    <input type="hidden" name="nama_lengkap" id="phd_nama">
                    <input type="hidden" name="email"        id="phd_email">
                    <input type="hidden" name="nik"          id="phd_nik">
                    <input type="hidden" name="no_whatsapp" id="phd_wa">
                    <input type="hidden" name="alamat"       id="phd_alamat">
                    <input type="hidden" name="tipe_rumah_id" id="phd_tipe_rumah_id">
                    <div id="pBuktiPlaceholder"></div>
                    <div style="display:flex;gap:10px;margin-top:18px;flex-wrap:wrap;">
                        <button type="button" onclick="pBackToForm()"
                            style="padding:10px 24px;border-radius:20px;border:1.5px solid #ccc;background:none;color:#555;font-size:14px;font-weight:600;cursor:pointer;">&larr; Kembali</button>
                        <button type="submit"
                            style="padding:10px 24px;border-radius:20px;background:#1a3649;color:#fff;font-size:14px;font-weight:600;border:none;cursor:pointer;">Konfirmasi</button>
                    </div>
                </form>
            </div>

        </div>{{-- end #pengisianBody --}}
    </div>{{-- end form section --}}

</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const device = document.getElementById('filterDevice').value;

    let visible = 0;
    document.querySelectorAll('#leadsTable tbody tr[data-status]').forEach(row => {
        const text      = row.innerText.toLowerCase();
        const rowStatus = row.dataset.status;
        const rowDevice = row.dataset.device;

        const ok = (!search || text.includes(search))
                && (!status || rowStatus === status)
                && (!device || rowDevice === device);

        row.style.display = ok ? '' : 'none';
        if (ok) visible++;
    });

    // Update total bar
    const bar = document.querySelector('.total-bar span');
    if (bar) bar.textContent = visible;
}

// ── Toggle form pengisian ──────────────────────────────────────────────────
function togglePengisian() {
    const body    = document.getElementById('pengisianBody');
    const chevron = document.getElementById('pengisianChevron');
    const open    = body.style.display !== 'none';
    body.style.display    = open ? 'none' : 'block';
    chevron.style.transform = open ? '' : 'rotate(180deg)';
}

// ── Multi-step form (dalam rekap) ─────────────────────────────────────────
function pGoToReview() {
    let valid = true;
    function pCheck(id, errId, label, extra) {
        const el  = document.getElementById(id);
        const err = document.getElementById(errId);
        const val = (el.tagName === 'SELECT' ? el.value : el.value.trim());
        if (!val) {
            err.textContent = label + ' wajib diisi.';
            valid = false;
        } else if (extra && !extra(val)) {
            err.textContent = extra.msg;
            valid = false;
        } else {
            err.textContent = '';
        }
        return val;
    }
    function emailOk(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
    emailOk.msg = 'Format email tidak valid.';
    function nikOk(v)   { return /^\d{16}$/.test(v); }
    nikOk.msg = 'NIK harus 16 digit angka.';

    const nama   = pCheck('pf_nama',      'perr_nama',      'Nama Lengkap');
    const email  = pCheck('pf_email',     'perr_email',     'Email', emailOk);
    const nik    = pCheck('pf_nik',       'perr_nik',       'No KTP / NIK', nikOk);
    const wa     = pCheck('pf_wa',        'perr_wa',        'No WhatsApp');
    const alamat = pCheck('pf_alamat',    'perr_alamat',    'Alamat');
    const tipe   = pCheck('pf_tipe_rumah','perr_tipe_rumah','Tipe Rumah');

    const buktiEl  = document.getElementById('pf_bukti');
    const buktiErr = document.getElementById('perr_bukti');
    if (!buktiEl.files.length) {
        buktiErr.textContent = 'Bukti pembayaran wajib diunggah.';
        valid = false;
    } else if (buktiEl.files[0].size > 5 * 1024 * 1024) {
        buktiErr.textContent = 'Ukuran file maksimal 5 MB.';
        valid = false;
    } else {
        buktiErr.textContent = '';
    }
    if (!valid) return;

    // Isi preview review
    document.getElementById('prv_nama').textContent       = nama;
    document.getElementById('prv_email').textContent      = email;
    document.getElementById('prv_nik').textContent        = nik;
    document.getElementById('prv_wa').textContent         = wa;
    document.getElementById('prv_alamat').textContent     = alamat;
    document.getElementById('prv_tipe_rumah').textContent =
        document.getElementById('pf_tipe_rumah').options[document.getElementById('pf_tipe_rumah').selectedIndex].text;
    document.getElementById('prv_bukti').textContent = buktiEl.files[0].name;

    // Isi hidden fields
    document.getElementById('phd_nama').value         = nama;
    document.getElementById('phd_email').value        = email;
    document.getElementById('phd_nik').value          = nik;
    document.getElementById('phd_wa').value           = wa;
    document.getElementById('phd_alamat').value       = alamat;
    document.getElementById('phd_tipe_rumah_id').value = tipe;

    // Pindah file input ke form submit
    const placeholder = document.getElementById('pBuktiPlaceholder');
    placeholder.innerHTML = '';
    placeholder.appendChild(buktiEl);

    document.getElementById('pFormCard').style.display   = 'none';
    document.getElementById('pReviewCard').style.display = 'block';
    document.getElementById('pStep1').style.cssText = 'padding:6px 18px;border-radius:20px;background:#059669;color:#fff;font-size:13px;font-weight:600;';
    document.getElementById('pStep2').style.cssText = 'padding:6px 18px;border-radius:20px;background:#1a3649;color:#fff;font-size:13px;font-weight:600;';
}

function pBackToForm() {
    // Kembalikan file input ke form 1
    const formDiv = document.getElementById('pClientForm');
    const buktiEl = document.getElementById('pf_bukti');
    if (!formDiv.contains(buktiEl)) {
        const fg = document.createElement('div');
        fg.style.marginBottom = '14px';
        const label = document.createElement('label');
        label.innerHTML = 'Bukti Pembayaran <span style="color:#e53935;">*</span> <span style="font-weight:400;color:#888;font-size:12px;">(maks 5 MB)</span>';
        label.style.cssText = 'display:block;font-size:14px;font-weight:600;color:#333;margin-bottom:6px;';
        fg.appendChild(label);
        fg.appendChild(buktiEl);
        const errDiv = document.createElement('div');
        errDiv.id = 'perr_bukti';
        errDiv.style.cssText = 'color:#e53935;font-size:12px;margin-top:3px;';
        fg.appendChild(errDiv);
        formDiv.insertBefore(fg, formDiv.querySelector('div:last-child'));
    }
    document.getElementById('pFormCard').style.display   = 'block';
    document.getElementById('pReviewCard').style.display = 'none';
    document.getElementById('pStep1').style.cssText = 'padding:6px 18px;border-radius:20px;background:#1a3649;color:#fff;font-size:13px;font-weight:600;';
    document.getElementById('pStep2').style.cssText = 'padding:6px 18px;border-radius:20px;color:#aaa;font-size:13px;font-weight:600;';
}

// Auto buka form jika ada pesan sukses
@if(session('pengisian_ok'))
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('pengisianBody').style.display = 'block';
    document.getElementById('pengisianChevron').style.transform = 'rotate(180deg)';
});
@endif
</script>
@endpush


