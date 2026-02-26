/**
 * agents.js — Agent CRUD via Laravel API
 * Kolom: nama, jabatan, email, phone, commission, slug, aktif
 */

// ──────────────────────────────────────────────────────────────
// Helpers
// ──────────────────────────────────────────────────────────────
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
}

function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .trim();
}

function showLoading(cols = 6) {
    document.getElementById('agentsTableBody').innerHTML = `
        <tr>
            <td colspan="${cols}" style="text-align:center; padding:2rem; color:#94a3b8;">
                <i class="fas fa-spinner fa-spin"></i> Memuat data…
            </td>
        </tr>`;
}

// ──────────────────────────────────────────────────────────────
// Init
// ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadAgents();

    // Auto-generate slug dari input nama
    document.getElementById('agentName').addEventListener('input', function () {
        document.getElementById('agentSlug').value = slugify(this.value);
    });
});

// ──────────────────────────────────────────────────────────────
// READ — Load agents dari API
// ──────────────────────────────────────────────────────────────
async function loadAgents() {
    showLoading();
    try {
        const resp = await fetch('/admin/agents', {
            headers: { 'Accept': 'application/json' }
        });
        if (!resp.ok) throw new Error('Gagal memuat data agent.');

        const agents = await resp.json();
        renderTable(agents);
    } catch (err) {
        document.getElementById('agentsTableBody').innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center;padding:2rem;color:#ef4444;">
                    <i class="fas fa-exclamation-circle"></i> ${err.message}
                </td>
            </tr>`;
    }
}

// ──────────────────────────────────────────────────────────────
// Render tabel
// ──────────────────────────────────────────────────────────────
function renderTable(agents) {
    const tbody = document.getElementById('agentsTableBody');

    if (agents.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center;padding:2rem;color:#94a3b8;">
                    Belum ada agent. Klik "Tambah Agent" untuk menambahkan.
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = '';
    agents.forEach(agent => {
        const tr          = document.createElement('tr');
        const statusBadge = agent.aktif
            ? '<span class="badge success">Aktif</span>'
            : '<span class="badge danger">Nonaktif</span>';

        const origin        = window.location.origin;
        const affiliateLink = `${origin}/${agent.slug}`;
        const emailRow      = agent.email ? `<div>${agent.email}</div>` : '';
        const phoneRow      = agent.phone
            ? `<div style="color:#64748b;font-size:.875rem;">${agent.phone}</div>`
            : '';
        const commission    = agent.commission != null ? `${agent.commission}%` : '—';

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
                ${emailRow}
                ${phoneRow}
                ${!agent.email && !agent.phone ? '<span style="color:#94a3b8;">—</span>' : ''}
            </td>
            <td>
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <code style="background:#f1f5f9;padding:.25rem .5rem;border-radius:.25rem;font-size:.875rem;">
                        /${agent.slug}
                    </code>
                    <button class="btn-icon" onclick="copyLink('${affiliateLink}', event)" title="Salin link affiliate">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </td>
            <td>${commission}</td>
            <td>${statusBadge}</td>
            <td>
                <div style="display:flex;gap:.5rem;">
                    <button class="btn-icon" onclick="editAgent(${agent.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon" onclick="toggleStatus(${agent.id})" title="${agent.aktif ? 'Nonaktifkan' : 'Aktifkan'}">
                        <i class="fas fa-${agent.aktif ? 'ban' : 'check'}"></i>
                    </button>
                    <button class="btn-icon danger" onclick="deleteAgent(${agent.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// ──────────────────────────────────────────────────────────────
// Modal helpers
// ──────────────────────────────────────────────────────────────
function openAddAgentModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Agent';
    document.getElementById('agentForm').reset();
    document.getElementById('agentId').value   = '';
    document.getElementById('agentSlug').value = '';
    document.getElementById('agentModal').style.display = 'flex';
}

function closeAgentModal() {
    document.getElementById('agentModal').style.display = 'none';
    document.getElementById('agentForm').reset();
}

async function editAgent(id) {
    try {
        const resp   = await fetch('/admin/agents', { headers: { 'Accept': 'application/json' } });
        const agents = await resp.json();
        const agent  = agents.find(a => a.id === id);
        if (!agent) { alert('Agent tidak ditemukan.'); return; }

        document.getElementById('modalTitle').textContent     = 'Edit Agent';
        document.getElementById('agentId').value              = agent.id;
        document.getElementById('agentName').value            = agent.nama;
        document.getElementById('agentJabatan').value         = agent.jabatan;
        document.getElementById('agentEmail').value           = agent.email   ?? '';
        document.getElementById('agentPhone').value           = agent.phone   ?? '';
        document.getElementById('agentCommission').value      = agent.commission ?? '';
        document.getElementById('agentSlug').value            = agent.slug;
        document.getElementById('agentModal').style.display  = 'flex';
    } catch {
        alert('Gagal memuat data agent.');
    }
}

// ──────────────────────────────────────────────────────────────
// CREATE / UPDATE
// ──────────────────────────────────────────────────────────────
async function saveAgent() {
    const nama       = document.getElementById('agentName').value.trim();
    const jabatan    = document.getElementById('agentJabatan').value.trim();
    const email      = document.getElementById('agentEmail').value.trim();
    const phone      = document.getElementById('agentPhone').value.trim();
    const commission = document.getElementById('agentCommission').value;
    const id         = document.getElementById('agentId').value;

    if (!nama || !jabatan) {
        alert('Nama dan Jabatan wajib diisi!');
        return;
    }

    const btn = document.getElementById('saveBtn');
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Menyimpan…';

    try {
        const isEdit = !!id;
        const url    = isEdit ? `/admin/agents/${id}` : '/admin/agents';
        const method = isEdit ? 'PUT' : 'POST';

        const payload = { nama, jabatan };
        if (email)      payload.email      = email;
        if (phone)      payload.phone      = phone;
        if (commission) payload.commission = parseFloat(commission);

        const resp = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
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

        closeAgentModal();
        await loadAgents();
    } catch {
        alert('Gagal menyimpan. Cek koneksi atau coba lagi.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    }
}

// ──────────────────────────────────────────────────────────────
// TOGGLE STATUS
// ──────────────────────────────────────────────────────────────
async function toggleStatus(id) {
    if (!confirm('Ubah status agent ini?')) return;
    try {
        const resp = await fetch(`/admin/agents/${id}/status`, {
            method:  'PATCH',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        });
        if (!resp.ok) throw new Error();
        await loadAgents();
    } catch {
        alert('Gagal mengubah status agent.');
    }
}

// ──────────────────────────────────────────────────────────────
// DELETE
// ──────────────────────────────────────────────────────────────
async function deleteAgent(id) {
    if (!confirm('Hapus agent ini? Data tidak dapat dikembalikan!')) return;
    try {
        const resp = await fetch(`/admin/agents/${id}`, {
            method:  'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        });
        if (!resp.ok) throw new Error();
        await loadAgents();
    } catch {
        alert('Gagal menghapus agent.');
    }
}

// ──────────────────────────────────────────────────────────────
// SEARCH (filter di sisi klien)
// ──────────────────────────────────────────────────────────────
function searchAgents() {
    const term = document.getElementById('searchAgent').value.toLowerCase();
    const rows = document.querySelectorAll('#agentsTableBody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
}

// ──────────────────────────────────────────────────────────────
// COPY LINK
// ──────────────────────────────────────────────────────────────
function copyLink(link, event) {
    navigator.clipboard.writeText(link).then(() => {
        const btn = event.target.closest('button');
        const ori = btn.innerHTML;
        btn.innerHTML   = '<i class="fas fa-check"></i>';
        btn.style.color = '#10b981';
        setTimeout(() => { btn.innerHTML = ori; btn.style.color = ''; }, 2000);
    }).catch(() => alert('Gagal menyalin link.'));
}

// Close modal bila klik di luar
window.onclick = function (event) {
    const modal = document.getElementById('agentModal');
    if (event.target === modal) closeAgentModal();
};
