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
.stat-info .badge {
    display: inline-block; margin-top: 6px;
    padding: 2px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 500;
    background: rgba(99,211,174,0.2); color: #065f46;
}

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
                <div class="label">Total Klik</div>
                <div class="value">{{ $stats['total'] }}</div>
                @if($stats['total'] > 0)
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
                <div class="value">Rp {{ number_format($stats['total_komisi'], 0, ',', '.') }}</div>
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
<<<<<<< HEAD
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
=======
                    <table id="leadsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>IP Address</th>
                                <th>Perangkat / Browser</th>
                                <th>Halaman Asal</th>
                                <th>Waktu Klik</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clicks as $i => $click)
                            <tr data-status="{{ $click->status }}" data-device="{{ $click->device }}" data-id="{{ $click->id }}" data-notes="{{ $click->notes }}" data-followup="{{ $click->follow_up_date }}">
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
                                <td data-label="Aksi">
                                    <button class="btn-icon" title="Edit" onclick="openEditModal(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
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
>>>>>>> 838a49331ec14f5815876624a71d1d49d524ee6f

</div>
@endsection

<<<<<<< HEAD
=======
<!-- Modal Edit Status -->
<div id="editModal" class="modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
    <div class="modal-content" style="background:#fff;padding:2rem 1.5rem;border-radius:12px;max-width:400px;width:100%;position:relative;">
        <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:1.2rem;font-weight:700;">Edit Status Klik WA</h2>
            <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.5rem;line-height:1;cursor:pointer;">&times;</button>
        </div>
        <form id="editForm" onsubmit="return saveEditModal()">
            <input type="hidden" id="editId">
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Status Prospek *</label>
                <select id="editStatus" required style="width:100%;padding:.5rem;border-radius:6px;border:1.5px solid #e5e7eb;">
                    <option value="new">Prospek</option>
                    <option value="follow-up">Tindak Lanjut</option>
                    <option value="interested">Tertarik</option>
                    <option value="not-interested">Tidak Tertarik</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Catatan</label>
                <textarea id="editNotes" rows="3" style="width:100%;border-radius:6px;border:1.5px solid #e5e7eb;padding:.5rem;"></textarea>
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Jadwal Tindak Lanjut</label>
                <input type="datetime-local" id="editFollowUp" style="width:100%;border-radius:6px;border:1.5px solid #e5e7eb;padding:.5rem;">
            </div>
            <div style="display:flex;justify-content:flex-end;gap:.5rem;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

>>>>>>> 838a49331ec14f5815876624a71d1d49d524ee6f
@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const device = document.getElementById('filterDevice').value;
<<<<<<< HEAD

=======
>>>>>>> 838a49331ec14f5815876624a71d1d49d524ee6f
    let visible = 0;
    document.querySelectorAll('#leadsTable tbody tr[data-status]').forEach(row => {
        const text      = row.innerText.toLowerCase();
        const rowStatus = row.dataset.status;
        const rowDevice = row.dataset.device;
<<<<<<< HEAD

        const ok = (!search || text.includes(search))
                && (!status || rowStatus === status)
                && (!device || rowDevice === device);

        row.style.display = ok ? '' : 'none';
        if (ok) visible++;
    });

=======
        const ok = (!search || text.includes(search))
                && (!status || rowStatus === status)
                && (!device || rowDevice === device);
        row.style.display = ok ? '' : 'none';
        if (ok) visible++;
    });
>>>>>>> 838a49331ec14f5815876624a71d1d49d524ee6f
    // Update total bar
    const bar = document.querySelector('.total-bar span');
    if (bar) bar.textContent = visible;
}
<<<<<<< HEAD
=======

function openEditModal(btn) {
    const row = btn.closest('tr');
    document.getElementById('editId').value = row.dataset.id;
    document.getElementById('editStatus').value = row.dataset.status;
    document.getElementById('editNotes').value = row.dataset.notes || '';
    document.getElementById('editFollowUp').value = row.dataset.followup || '';
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editForm').reset();
}
function saveEditModal() {
    const id = document.getElementById('editId').value;
    const status = document.getElementById('editStatus').value;
    const notes = document.getElementById('editNotes').value;
    const followUp = document.getElementById('editFollowUp').value;
    fetch(`/affiliate/leads/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status, notes, follow_up_date: followUp })
    })
    .then(r => r.json())
    .then(res => {
        if (res.ok) {
            location.reload();
        } else {
            alert('Gagal menyimpan perubahan.');
        }
    })
    .catch(() => alert('Gagal menyimpan.'));
    return false;
}
>>>>>>> 838a49331ec14f5815876624a71d1d49d524ee6f
</script>
@endpush


