/**
 * tracking.js – Tracking Klik WA (Live dari Database)
 * Semua data diambil dari API /admin/tracking/data
 */

let clicks         = [];
let filteredClicks = [];
let currentPage    = 1;
const itemsPerPage = 10;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ─── Init ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

// ─── Ambil data dari server ──────────────────────────────────────────────────
function loadData() {
    const params = new URLSearchParams({
        date:   document.getElementById('filterDate')?.value   ?? 'all',
        agent:  document.getElementById('filterAgent')?.value  ?? 'all',
        status: document.getElementById('filterStatus')?.value ?? 'all',
        search: document.getElementById('searchClicks')?.value ?? '',
    });

    // Tampilkan loading
    document.getElementById('clicksTableBody').innerHTML =
        '<tr><td colspan="8" style="text-align:center;padding:2rem;color:#94a3b8;">' +
        '<i class="fas fa-spinner fa-spin"></i> Memuat data…</td></tr>';

    fetch(`/admin/tracking/data?${params}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(({ clicks: data, stats, agents }) => {
        clicks = data;
        filteredClicks = data;

        updateStats(stats);
        populateAgentFilter(agents);
        renderTable();
    })
    .catch(() => {
        document.getElementById('clicksTableBody').innerHTML =
            '<tr><td colspan="8" style="text-align:center;padding:2rem;color:#ef4444;">' +
            '<i class="fas fa-exclamation-circle"></i> Gagal memuat data.</td></tr>';
    });
}

// ─── Update kartu statistik ──────────────────────────────────────────────────
function updateStats(stats) {
    document.getElementById('totalClicksToday').textContent = stats.today;
    document.getElementById('totalPending').textContent     = stats.new;
    document.getElementById('totalFollowUp').textContent    = stats.follow_up;
    document.getElementById('totalConverted').textContent   = stats.interested;
}

// ─── Isi dropdown Agent dari database ───────────────────────────────────────
function populateAgentFilter(agents) {
    const sel = document.getElementById('filterAgent');
    // Hapus option lama kecuali "Semua Agent"
    while (sel.options.length > 1) sel.remove(1);
    agents.forEach(a => {
        const opt = document.createElement('option');
        opt.value       = a.id;
        opt.textContent = a.nama;
        sel.appendChild(opt);
    });
}

// ─── Filter dipanggil saat user ubah dropdown/search ────────────────────────
function filterClicks() { loadData(); }
function searchClickData() { loadData(); }

// ─── Render tabel ────────────────────────────────────────────────────────────
function renderTable() {
    const tbody = document.getElementById('clicksTableBody');
    const start = (currentPage - 1) * itemsPerPage;
    const page  = filteredClicks.slice(start, start + itemsPerPage);

    if (page.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="8" style="text-align:center;padding:2rem;color:#64748b;">' +
            'Belum ada data klik WhatsApp.</td></tr>';
        renderPagination();
        return;
    }

    tbody.innerHTML = page.map(c => `
        <tr>
            <td>
                <div>${formatDateTime(c.timestamp)}</div>
                <div style="color:#64748b;font-size:.8rem;">${formatTimeAgo(c.timestamp)}</div>
            </td>
            <td>
                <strong>${c.agentName}</strong>
                <div style="color:#64748b;font-size:.8rem;">/${c.agentSlug}</div>
            </td>
            <td style="font-size:.85rem;color:#64748b;">${c.pageUrl}</td>
            <td>
                <div style="font-size:.85rem;">
                    <i class="fas fa-${c.device === 'Mobile' ? 'mobile-alt' : 'desktop'}"></i>
                    ${c.device}
                </div>
                <div style="color:#64748b;font-size:.8rem;">${c.browser}</div>
            </td>
            <td style="font-size:.8rem;color:#64748b;">${c.ipAddress}</td>
            <td>${getStatusBadge(c.status)}</td>
            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.8rem;">
                ${c.notes || '<span style="color:#cbd5e1">-</span>'}
            </td>
            <td>
                <div style="display:flex;gap:.4rem;">
                    <button class="btn-icon" onclick="openStatusModal(${c.id})" title="Update Status">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination();
}

// ─── Badge status ─────────────────────────────────────────────────────────────
function getStatusBadge(status) {
    const map = {
        'new':           '<span class="badge info">Baru</span>',
        'follow-up':     '<span class="badge warning">Follow Up</span>',
        'interested':    '<span class="badge success">Tertarik</span>',
        'not-interested':'<span class="badge danger">Tidak Tertarik</span>',
        'closed':        '<span class="badge" style="background:#8b5cf6;color:white;">Closing</span>',
    };
    return map[status] ?? '<span class="badge">-</span>';
}

// ─── Modal Update Status ──────────────────────────────────────────────────────
function openStatusModal(id) {
    const c = filteredClicks.find(x => x.id === id);
    if (!c) return;
    document.getElementById('clickId').value        = id;
    document.getElementById('leadStatus').value     = c.status;
    document.getElementById('leadNotes').value      = c.notes || '';
    document.getElementById('followUpDate').value   = c.followUpDate
        ? c.followUpDate.replace(' ', 'T').substring(0, 16) : '';
    document.getElementById('statusModal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
    document.getElementById('statusForm').reset();
}

function updateClickStatus() {
    const id           = document.getElementById('clickId').value;
    const status       = document.getElementById('leadStatus').value;
    const notes        = document.getElementById('leadNotes').value;
    const followUpDate = document.getElementById('followUpDate').value;

    fetch(`/admin/tracking/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf(),
        },
        body: JSON.stringify({ status, notes, follow_up_date: followUpDate || null }),
    })
    .then(r => r.json())
    .then(() => {
        closeStatusModal();
        loadData(); // refresh tabel
    })
    .catch(() => alert('Gagal menyimpan. Coba lagi.'));
}

// ─── Pagination ───────────────────────────────────────────────────────────────
function renderPagination() {
    const el         = document.getElementById('pagination');
    const totalPages = Math.ceil(filteredClicks.length / itemsPerPage);
    if (totalPages <= 1) { el.innerHTML = ''; return; }

    let html = '<div style="display:flex;justify-content:center;gap:.5rem;margin-top:1.5rem;">';
    html += `<button class="btn btn-secondary" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})"><i class="fas fa-chevron-left"></i></button>`;
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            html += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-secondary'}" onclick="changePage(${i})">${i}</button>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            html += '<span style="padding:.625rem;">…</span>';
        }
    }
    html += `<button class="btn btn-secondary" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})"><i class="fas fa-chevron-right"></i></button>`;
    html += '</div>';
    el.innerHTML = html;
}

function changePage(page) { currentPage = page; renderTable(); }

// ─── Export CSV ───────────────────────────────────────────────────────────────
function exportClicks() {
    const params = new URLSearchParams({
        date:   document.getElementById('filterDate')?.value   ?? 'all',
        agent:  document.getElementById('filterAgent')?.value  ?? 'all',
        status: document.getElementById('filterStatus')?.value ?? 'all',
    });
    // Buat CSV dari data yang sudah ada di memory
    const header = ['Waktu','Agent','Slug','Device','Browser','IP','Status','Catatan'];
    const rows = filteredClicks.map(c => [
        c.timestamp, c.agentName, c.agentSlug, c.device, c.browser,
        c.ipAddress, c.status, `"${(c.notes||'').replace(/"/g,'""')}"`
    ]);
    const csv = [header, ...rows].map(r => r.join(',')).join('\n');
    const a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = `tracking-wa-${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function formatDateTime(dt) {
    return new Date(dt).toLocaleString('id-ID', {
        day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'
    });
}

function formatTimeAgo(dt) {
    const diff = Math.floor((Date.now() - new Date(dt)) / 1000);
    if (diff < 60)    return 'Baru saja';
    if (diff < 3600)  return `${Math.floor(diff/60)} menit lalu`;
    if (diff < 86400) return `${Math.floor(diff/3600)} jam lalu`;
    return `${Math.floor(diff/86400)} hari lalu`;
}

// ─── Tutup modal klik luar ────────────────────────────────────────────────────
window.onclick = e => {
    if (e.target === document.getElementById('statusModal')) closeStatusModal();
};
