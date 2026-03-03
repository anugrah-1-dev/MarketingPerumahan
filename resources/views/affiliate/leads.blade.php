@extends('layouts.affiliate')
@section('title', 'Daftar Leads – Perumahan Zahra')

@push('styles')
<style>
/* ── Leads Page ── */
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
    grid-template-columns: repeat(3, 1fr);
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
.stat-icon.green  { background: rgba(16,185,129,0.12);  color: #059669; }
.stat-icon.blue   { background: rgba(59,130,246,0.12);  color: #2563eb; }
.stat-info .label { font-size: 13px; color: #888; font-weight: 500; }
.stat-info .value { font-size: 26px; font-weight: 700; color: #1e1e1e; line-height: 1.2; margin-top: 2px; }
.stat-info .sub   { font-size: 12px; color: #10b981; margin-top: 4px; font-weight: 500; }

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
    transition: border-color 0.2s;
}
.filter-select:focus { border-color: #3d81af; }

/* Table Card */
.table-card {
    margin-top: 18px;
    background: #fff;
    border-radius: 14px;
    padding: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    overflow: hidden;
}
.table-card table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.table-card thead {
    background: #f8fafc;
}
.table-card thead th {
    padding: 14px 18px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #f0f0f0;
}
.table-card tbody tr {
    border-bottom: 1px solid #f5f5f5;
    transition: background 0.15s;
}
.table-card tbody tr:last-child { border-bottom: none; }
.table-card tbody tr:hover { background: #fafafa; }
.table-card tbody td {
    padding: 14px 18px;
    color: #333;
    vertical-align: middle;
}
.lead-name  { font-weight: 600; color: #1e1e1e; }
.lead-phone { font-size: 12px; color: #888; margin-top: 2px; }
.lead-date  { font-size: 13px; color: #555; }
.lead-time  { font-size: 12px; color: #aaa; }

/* Badge Status */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}
.badge-baru      { background: rgba(59,130,246,0.12); color: #2563eb; }
.badge-followup  { background: rgba(245,158,11,0.12); color: #d97706; }
.badge-closing   { background: rgba(16,185,129,0.12); color: #059669; }
.badge-hot       { background: rgba(239,68,68,0.12);  color: #dc2626; }

/* Source Badge */
.source-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    background: #f3f4f6;
    color: #555;
}

/* Empty state */
.empty-state {
    padding: 48px;
    text-align: center;
    color: #aaa;
    font-size: 14px;
}
.empty-state i { font-size: 36px; margin-bottom: 10px; display: block; }

@media (max-width: 768px) {
    .leads-wrap { padding: 20px 16px 30px; }
    .stats-row  { grid-template-columns: 1fr; }
    .table-card { overflow-x: auto; }
}
</style>
@endpush

@section('content')
<div class="leads-wrap">

    {{-- Header --}}
    <div class="leads-header">
        <h1>Daftar Leads</h1>
        <p>Pantau semua prospek yang masuk melalui link affiliate Anda</p>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="label">Total Leads</div>
                <div class="value">148</div>
                <div class="sub">↑ Bulan ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-user-plus"></i></div>
            <div class="stat-info">
                <div class="label">Leads Baru</div>
                <div class="value">50</div>
                <div class="sub">↑ 12 minggu ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="label">Closing</div>
                <div class="value">25</div>
                <div class="sub">↑ Bulan ini</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari nama, no HP, atau email…" id="searchInput" oninput="filterTable()">
        </div>
        <select class="filter-select" id="filterStatus" onchange="filterTable()">
            <option value="">Semua Status</option>
            <option value="Baru">Baru</option>
            <option value="Hot Lead">Hot Lead</option>
            <option value="Follow Up">Follow Up</option>
            <option value="Closing">Closing</option>
        </select>
        <select class="filter-select" id="filterPeriod">
            <option>30 hari terakhir</option>
            <option>7 hari terakhir</option>
            <option>Bulan ini</option>
        </select>
    </div>

    {{-- Tabel Leads --}}
    <div class="table-card">
        <table id="leadsTable">
            <thead>
                <tr>
                    <th>Nama / Kontak</th>
                    <th>Tanggal Masuk</th>
                    <th>Minat Unit</th>
                    <th>Sumber</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                $leads = [
                    ['name'=>'Andi Saputra',  'phone'=>'+62 812-3456-7890', 'date'=>'27 Feb 2026', 'time'=>'09:14 WIB', 'unit'=>'Tipe 45 – Blok A', 'source'=>'WhatsApp',  'source_icon'=>'fab fa-whatsapp',         'status'=>'Follow Up'],
                    ['name'=>'Budi Prasetyo', 'phone'=>'+62 813-2345-6789', 'date'=>'26 Feb 2026', 'time'=>'14:32 WIB', 'unit'=>'Tipe 36 – Blok B', 'source'=>'Instagram',  'source_icon'=>'fab fa-instagram',        'status'=>'Baru'],
                    ['name'=>'Siti Rahayu',   'phone'=>'+62 858-8765-4321', 'date'=>'26 Feb 2026', 'time'=>'11:05 WIB', 'unit'=>'Tipe 54 – Blok C', 'source'=>'TikTok',     'source_icon'=>'fab fa-tiktok',           'status'=>'Hot Lead'],
                    ['name'=>'Dian Pertiwi',  'phone'=>'+62 821-9988-7766', 'date'=>'25 Feb 2026', 'time'=>'16:45 WIB', 'unit'=>'Tipe 45 – Blok A', 'source'=>'WhatsApp',  'source_icon'=>'fab fa-whatsapp',         'status'=>'Closing'],
                    ['name'=>'Rudi Hartono',  'phone'=>'+62 878-1234-5678', 'date'=>'24 Feb 2026', 'time'=>'08:20 WIB', 'unit'=>'Tipe 36 – Blok D', 'source'=>'Facebook',   'source_icon'=>'fab fa-facebook-f',       'status'=>'Follow Up'],
                ];
                @endphp

                @forelse($leads as $lead)
                @php
                    $badgeClass = match($lead['status']) {
                        'Baru'      => 'badge-baru',
                        'Hot Lead'  => 'badge-hot',
                        'Follow Up' => 'badge-followup',
                        'Closing'   => 'badge-closing',
                        default     => 'badge-baru',
                    };
                @endphp
                <tr data-status="{{ $lead['status'] }}">
                    <td>
                        <div class="lead-name">{{ $lead['name'] }}</div>
                        <div class="lead-phone">{{ $lead['phone'] }}</div>
                    </td>
                    <td>
                        <div class="lead-date">{{ $lead['date'] }}</div>
                        <div class="lead-time">{{ $lead['time'] }}</div>
                    </td>
                    <td>{{ $lead['unit'] }}</td>
                    <td>
                        <span class="source-badge">
                            <i class="{{ $lead['source_icon'] }}"></i>
                            {{ $lead['source'] }}
                        </span>
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $lead['status'] }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            Belum ada leads yang masuk
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    document.querySelectorAll('#leadsTable tbody tr[data-status]').forEach(row => {
        const text       = row.innerText.toLowerCase();
        const rowStatus  = row.dataset.status;
        const matchText  = !search || text.includes(search);
        const matchStat  = !status || rowStatus === status;
        row.style.display = (matchText && matchStat) ? '' : 'none';
    });
}
</script>
@endpush
