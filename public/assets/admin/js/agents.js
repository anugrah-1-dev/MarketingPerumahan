/**
 * agents.js — Agent CRUD via Laravel API
 * Optimized: local in-memory cache + optimistic UI updates
 * - Edit: baca dari cache, TIDAK fetch ulang ke server
 * - Toggle/Delete: update DOM langsung (optimistic), rollback jika server error
 * - Create: loadAgents() sekali untuk dapat referral_code baru
 */

// ── In-memory cache ──────────────────────────────────────────
let agentsCache = [];

// ── Helpers ──────────────────────────────────────────────────
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}
function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
}
function showLoading(cols = 6) {
    document.getElementById('agentsTableBody').innerHTML = `
        <tr><td colspan="${cols}" style="text-align:center;padding:2rem;color:#94a3b8;">
            <i class="fas fa-spinner fa-spin"></i> Memuat data…
        </td></tr>`;
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadAgents();

    document.getElementById('agentsTableBody').addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;
        const act = btn.dataset.action;
        const id  = btn.dataset.id;   // string, tidak pakai parseInt

        if (act === 'copy')   { copyLink(btn.dataset.link, e); return; }
        if (!id)              return;

        if (act === 'edit')   editAgent(id);
        if (act === 'toggle') toggleStatus(id);
        if (act === 'delete') deleteAgent(id);
    });
});

// ── READ — Load semua agents dari server ─────────────────────
async function loadAgents() {
    showLoading();
    try {
        const resp = await fetch('/admin/agents', {
            headers: { 'Accept': 'application/json' }
        });
        if (!resp.ok) throw new Error('Gagal memuat data agent.');
        agentsCache = await resp.json();
        renderTable(agentsCache);
    } catch (err) {
        document.getElementById('agentsTableBody').innerHTML = `
            <tr><td colspan="6" style="text-align:center;padding:2rem;color:#ef4444;">
                <i class="fas fa-exclamation-circle"></i> ${err.message}
            </td></tr>`;
    }
}

// ── Render tabel dari cache ───────────────────────────────────
function renderTable(agents) {
    const tbody = document.getElementById('agentsTableBody');
    if (!agents.length) {
        tbody.innerHTML = `
            <tr><td colspan="6" style="text-align:center;padding:2rem;color:#94a3b8;">
                Belum ada agent. Klik "Tambah Agent" untuk menambahkan.
            </td></tr>`;
        return;
    }
    tbody.innerHTML = '';
    agents.forEach(agent => tbody.appendChild(buildRow(agent)));
}

// ── Build satu baris <tr> ─────────────────────────────────────
function buildRow(agent) {
    const tr = document.createElement('tr');
    tr.dataset.agentId = String(agent.id);

    const statusBadge  = agent.aktif
        ? '<span class="badge success">Aktif</span>'
        : '<span class="badge danger">Nonaktif</span>';
    const origin       = window.location.origin;
    const refCode      = agent.user?.referral_code || agent.slug;
    const affiliateLink = `${origin}/?ref=${refCode}`;
    const emailRow     = agent.email ? `<div>${agent.email}</div>` : '';
    const phoneRow     = agent.phone
        ? `<div style="color:#64748b;font-size:.875rem;">${agent.phone}</div>` : '';
    const commission   = agent.commission != null ? `${agent.commission}%` : '—';

    tr.innerHTML = `
        <td>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div class="agent-avatar">${getInitials(agent.nama)}</div>
                <div>
                    <strong>${agent.nama}</strong>
                    <div style="color:#64748b;font-size:.875rem;">${agent.jabatan}</div>
                </div>
            </div>
        </td>
        <td>
            ${emailRow}${phoneRow}
            ${!agent.email && !agent.phone ? '<span style="color:#94a3b8;">—</span>' : ''}
        </td>
        <td>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <code style="background:#f1f5f9;padding:.25rem .5rem;border-radius:.25rem;font-size:.875rem;">/?ref=${refCode}</code>
                <button class="btn-icon" data-action="copy" data-link="${affiliateLink}" title="Salin link affiliate">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </td>
        <td>${commission}</td>
        <td>${statusBadge}</td>
        <td>
            <div style="display:flex;gap:.5rem;">
                <button class="btn-icon" data-action="edit" data-id="${agent.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-icon" data-action="toggle" data-id="${agent.id}"
                    title="${agent.aktif ? 'Nonaktifkan' : 'Aktifkan'}">
                    <i class="fas fa-${agent.aktif ? 'ban' : 'check'}"></i>
                </button>
                <button class="btn-icon danger" data-action="delete" data-id="${agent.id}" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
    return tr;
}

// ── Cari baris di DOM ─────────────────────────────────────────
function findRow(id) {
    return document.querySelector(`tr[data-agent-id="${id}"]`);
}

// ── Modal helpers ─────────────────────────────────────────────
function openAddAgentModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Affiliate';
    document.getElementById('agentForm').reset();
    document.getElementById('agentId').value = '';
    document.getElementById('agentPassword').required = true;
    document.getElementById('helpPasswordEdit').style.display = 'none';
    document.getElementById('agentModal').style.display = 'flex';
}
function closeAgentModal() {
    document.getElementById('agentModal').style.display = 'none';
    document.getElementById('agentForm').reset();
}

// ── EDIT — pakai cache lokal, TIDAK fetch ke server ───────────
function editAgent(id) {
    const agent = agentsCache.find(a => String(a.id) === String(id));
    if (!agent) { alert('Agent tidak ditemukan.'); return; }

    document.getElementById('modalTitle').textContent    = 'Edit Affiliate';
    document.getElementById('agentId').value             = agent.id;
    document.getElementById('agentName').value           = agent.nama;
    document.getElementById('agentJabatan').value        = agent.jabatan;
    document.getElementById('agentEmail').value          = agent.email     ?? '';
    document.getElementById('agentPhone').value          = agent.phone     ?? '';
    document.getElementById('agentCommission').value     = agent.commission ?? '';
    document.getElementById('agentPassword').value       = '';
    document.getElementById('agentPassword').required    = false;
    document.getElementById('helpPasswordEdit').style.display = 'block';
    document.getElementById('agentModal').style.display  = 'flex';
}

// ── CREATE / UPDATE ───────────────────────────────────────────
async function saveAgent() {
    const nama       = document.getElementById('agentName').value.trim();
    const jabatan    = document.getElementById('agentJabatan').value.trim();
    const email      = document.getElementById('agentEmail').value.trim();
    const phone      = document.getElementById('agentPhone').value.trim();
    const password   = document.getElementById('agentPassword').value;
    const commission = document.getElementById('agentCommission').value;
    const id         = document.getElementById('agentId').value;
    const isEdit     = !!id;

    if (!nama || !email || (!isEdit && !password)) {
        alert('Nama, Email, dan Password wajib diisi!');
        return;
    }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan…';

    try {
        const url    = isEdit ? `/admin/agents/${id}` : '/admin/agents';
        const method = isEdit ? 'PUT' : 'POST';

        const payload = { nama, jabatan, email };
        if (phone)      payload.phone      = phone;
        if (password)   payload.password   = password;
        if (commission) payload.commission = parseFloat(commission);

        const resp = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept'      : 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: JSON.stringify(payload),
        });

        if (!resp.ok) {
            const err = await resp.json();
            const msg = err.errors
                ? Object.values(err.errors).flat().join('\n')
                : (err.message || 'Terjadi kesalahan.');
            alert(msg);
            return;
        }

        const saved = await resp.json();
        closeAgentModal();

        if (isEdit) {
            // Update cache dan baris yang ada di DOM — tanpa fetch ulang
            const idx = agentsCache.findIndex(a => String(a.id) === String(id));
            if (idx !== -1) {
                // Pertahankan user.referral_code dari cache lama
                agentsCache[idx] = {
                    ...agentsCache[idx],
                    nama      : saved.nama      ?? nama,
                    jabatan   : saved.jabatan   ?? jabatan,
                    email     : saved.email     ?? email,
                    phone     : saved.phone     ?? (phone || null),
                    commission: saved.commission ?? agentsCache[idx].commission,
                    aktif     : saved.aktif     ?? agentsCache[idx].aktif,
                };
                const oldRow = findRow(id);
                if (oldRow) oldRow.replaceWith(buildRow(agentsCache[idx]));
            }
        } else {
            // Create: perlu fetch sekali untuk dapat user.referral_code baru
            await loadAgents();
        }
    } catch {
        alert('Gagal menyimpan. Cek koneksi atau coba lagi.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    }
}

// ── TOGGLE STATUS — optimistic UI ────────────────────────────
async function toggleStatus(id) {
    if (!confirm('Ubah status agent ini?')) return;

    const agent = agentsCache.find(a => String(a.id) === String(id));
    if (!agent) return;

    // Optimistic: balik status di cache dan DOM seketika
    agent.aktif = !agent.aktif;
    const oldRow = findRow(id);
    if (oldRow) oldRow.replaceWith(buildRow(agent));

    try {
        const resp = await fetch(`/admin/agents/${id}/status`, {
            method : 'PATCH',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        });
        if (!resp.ok) throw new Error();
    } catch {
        // Rollback: balik cache dan DOM ke state semula
        agent.aktif = !agent.aktif;
        const rolledRow = findRow(id);
        if (rolledRow) rolledRow.replaceWith(buildRow(agent));
        alert('Gagal mengubah status agent.');
    }
}

// ── DELETE — optimistic UI ────────────────────────────────────
async function deleteAgent(id) {
    if (!confirm('Hapus agent ini? Data tidak dapat dikembalikan!')) return;

    // Simpan snapshot untuk rollback
    const snapshot  = [...agentsCache];
    const deletedIdx = agentsCache.findIndex(a => String(a.id) === String(id));

    // Optimistic: hapus dari cache dan DOM seketika
    agentsCache = agentsCache.filter(a => String(a.id) !== String(id));
    const row = findRow(id);
    if (row) row.remove();
    if (!agentsCache.length) renderTable([]);

    try {
        const resp = await fetch(`/admin/agents/${id}`, {
            method : 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        });
        if (!resp.ok) throw new Error();
    } catch {
        // Rollback: kembalikan cache dan render ulang
        agentsCache = snapshot;
        renderTable(agentsCache);
        alert('Gagal menghapus agent.');
    }
}

// ── SEARCH — filter di sisi klien ────────────────────────────
function searchAgents() {
    const term = document.getElementById('searchAgent').value.toLowerCase();
    document.querySelectorAll('#agentsTableBody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
}

// ── COPY LINK ─────────────────────────────────────────────────
function copyLink(link, event) {
    navigator.clipboard.writeText(link).then(() => {
        const btn = event.target.closest('button');
        const ori = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = '#10b981';
        setTimeout(() => { btn.innerHTML = ori; btn.style.color = ''; }, 2000);
    }).catch(() => alert('Gagal menyalin link.'));
}

// Close modal bila klik di luar
window.onclick = function(event) {
    const modal = document.getElementById('agentModal');
    if (event.target === modal) closeAgentModal();
};
