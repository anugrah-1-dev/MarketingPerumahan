@extends('layouts.manager')
@section('title', 'Data Klien')
@section('page-title', 'Data Klien')

@section('content')
    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-content"><h3 id="totalClients">0</h3><p>Total Klien</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
            <div class="stat-content"><h3 id="totalBaru">0</h3><p>Baru</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-hand-holding-dollar"></i></div>
            <div class="stat-content"><h3 id="totalDp">0</h3><p>DP</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content"><h3 id="totalLunas">0</h3><p>Lunas</p></div>
        </div>
    </div>

    <!-- Filter -->
    <div class="action-bar">
        <div class="filter-group" style="display:flex;gap:1rem">
            <select id="filterStatus" onchange="renderTable()">
                <option value="all">Semua Status</option>
                <option value="baru">Baru</option>
                <option value="dp">DP</option>
                <option value="lunas">Lunas</option>
                <option value="cancel">Cancel</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header"><h2>Daftar Data Klien</h2></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th><th>No WA</th><th>Tipe Rumah</th>
                            <th>Status Pembayaran</th><th>Agent</th><th>Dibuat Oleh</th><th>Tanggal</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="clientTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content" style="max-width:420px">
            <div class="modal-header">
                <h2>Ubah Status Pembayaran</h2>
                <button class="close-btn" onclick="closeStatusModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="statusClientId">
                    <div class="form-group">
                        <label>Status Pembayaran *</label>
                        <select id="statusSelect" required onchange="toggleAgentSelect()">
                            <option value="baru">Baru</option>
                            <option value="dp">DP</option>
                            <option value="lunas">Lunas</option>
                            <option value="cancel">Cancel</option>
                        </select>
                    </div>
                    <div class="form-group" id="agentGroup" style="display:none">
                        <label>Agent (Opsional)</label>
                        <select id="agentSelect">
                            <option value="">— Otomatis dari pembuat —</option>
                            @foreach($agents as $a)
                                <option value="{{ $a->id }}">{{ $a->nama }} ({{ $a->commission }}%)</option>
                            @endforeach
                        </select>
                        <small style="color:#64748b">Kosongkan jika ingin menggunakan agent dari akun pengisi form.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeStatusModal()">Batal</button>
                <button class="btn btn-primary" onclick="saveStatus()">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const CLIENT_PANEL = 'manager';
const clientBasePath = '/' + CLIENT_PANEL + '/client-data';
let clients = [];

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

document.addEventListener('DOMContentLoaded', fetchClients);

async function fetchClients() {
    try {
        const res = await fetch(clientBasePath, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        clients = await res.json();
        clients.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } catch (e) {
        console.error(e);
        clients = [];
        const tbody = document.getElementById('clientTableBody');
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:2rem;color:#e53935">Gagal memuat data klien. Periksa koneksi database.</td></tr>';
        return;
    }
    updateStats();
    renderTable();
}

function updateStats() {
    document.getElementById('totalClients').textContent = clients.length;
    document.getElementById('totalBaru').textContent  = clients.filter(c => c.status_pembayaran === 'baru').length;
    document.getElementById('totalDp').textContent    = clients.filter(c => c.status_pembayaran === 'dp').length;
    document.getElementById('totalLunas').textContent = clients.filter(c => c.status_pembayaran === 'lunas').length;
}

function getStatusBadge(status) {
    const map = {
        baru:   '<span class="badge info">Baru</span>',
        dp:     '<span class="badge warning">DP</span>',
        lunas:  '<span class="badge success">Lunas</span>',
        cancel: '<span class="badge danger">Cancel</span>',
    };
    return map[status] ?? '<span class="badge">-</span>';
}

function renderTable() {
    const filter = document.getElementById('filterStatus').value;
    const list = filter === 'all' ? clients : clients.filter(c => c.status_pembayaran === filter);
    const tbody = document.getElementById('clientTableBody');

    if (list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:2rem;color:#64748b">Tidak ada data klien</td></tr>';
        return;
    }

    tbody.innerHTML = list.map(c => `
        <tr>
            <td>
                <strong>${c.nama_lengkap}</strong>
                <div style="color:#64748b;font-size:.875rem">${c.email ?? ''}</div>
            </td>
            <td>
                <a href="https://wa.me/${(c.no_whatsapp ?? '').replace(/^0/,'62')}" target="_blank" style="color:#10b981">
                    <i class="fab fa-whatsapp"></i> ${c.no_whatsapp ?? '-'}
                </a>
            </td>
            <td>${c.tipe_rumah_nama}</td>
            <td>${getStatusBadge(c.status_pembayaran)}</td>
            <td>${c.agent_name}</td>
            <td>${c.created_by_name}</td>
            <td style="white-space:nowrap;font-size:.85rem">${c.created_at ? new Date(c.created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'}) + ' ' + new Date(c.created_at).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}) : '-'}</td>
            <td>
                <div style="display:flex;gap:.5rem">
                    <button class="btn-icon" onclick="openStatusModal(${c.id})" title="Ubah Status">
                        <i class="fas fa-edit"></i>
                    </button>
                    ${c.bukti_pembayaran ? '<a class="btn-icon" href="' + c.bukti_pembayaran + '" target="_blank" title="Lihat Bukti"><i class="fas fa-image"></i></a>' : ''}
                </div>
            </td>
        </tr>`).join('');
}

function openStatusModal(id) {
    const c = clients.find(x => x.id === id);
    if (!c) return;
    document.getElementById('statusClientId').value = c.id;
    document.getElementById('statusSelect').value = c.status_pembayaran;
    document.getElementById('agentSelect').value = '';
    toggleAgentSelect();
    document.getElementById('statusModal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
}

function toggleAgentSelect() {
    const val = document.getElementById('statusSelect').value;
    document.getElementById('agentGroup').style.display = (val === 'dp' || val === 'lunas') ? 'block' : 'none';
}

async function saveStatus() {
    const id     = document.getElementById('statusClientId').value;
    const status = document.getElementById('statusSelect').value;
    const agentId = document.getElementById('agentSelect').value;

    const payload = { status_pembayaran: status };
    if (agentId) payload.agent_id = agentId;

    try {
        const res = await fetch(clientBasePath + '/' + id + '/status', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            alert('Gagal: ' + (err.message ?? res.statusText));
            return;
        }
        alert('Status berhasil diperbarui!');
        closeStatusModal();
        await fetchClients();
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan jaringan.');
    }
}

window.onclick = function (event) {
    if (event.target === document.getElementById('statusModal')) closeStatusModal();
};
</script>
@endpush
