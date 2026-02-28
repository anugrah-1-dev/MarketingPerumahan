/**
 * users.js — User Management CRUD via Laravel API
 */

// ──────────────────────────────────────────────────────────────
// Helpers
// ──────────────────────────────────────────────────────────────
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function getRoleBadge(role) {
    if (role === 'super_admin') {
        return '<span class="badge" style="background:#fef3c7;color:#92400e;">Super Admin</span>';
    }
    return '<span class="badge success">Affiliate</span>';
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

function showLoading() {
    document.getElementById('usersTableBody').innerHTML = `
        <tr>
            <td colspan="5" style="text-align:center; padding:2rem; color:#94a3b8;">
                <i class="fas fa-spinner fa-spin"></i> Memuat data…
            </td>
        </tr>`;
}

// ──────────────────────────────────────────────────────────────
// Init
// ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
});

// ──────────────────────────────────────────────────────────────
// READ
// ──────────────────────────────────────────────────────────────
async function loadUsers() {
    showLoading();
    try {
        const resp = await fetch('/admin/users', {
            headers: { 'Accept': 'application/json' }
        });
        if (!resp.ok) throw new Error('Gagal memuat data user.');
        const users = await resp.json();
        renderTable(users);
    } catch (err) {
        document.getElementById('usersTableBody').innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center;padding:2rem;color:#ef4444;">
                    <i class="fas fa-exclamation-circle"></i> ${err.message}
                </td>
            </tr>`;
    }
}

// ──────────────────────────────────────────────────────────────
// Render tabel
// ──────────────────────────────────────────────────────────────
function renderTable(users) {
    const tbody = document.getElementById('usersTableBody');

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center;padding:2rem;color:#94a3b8;">
                    Belum ada user. Klik "Tambah User" untuk menambahkan.
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = '';
    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div class="agent-avatar">${user.name.charAt(0).toUpperCase()}</div>
                    <strong>${user.name}</strong>
                </div>
            </td>
            <td>${user.email}</td>
            <td>${getRoleBadge(user.role)}</td>
            <td style="color:#64748b;font-size:.875rem;">${formatDate(user.created_at)}</td>
            <td>
                <div style="display:flex;gap:.5rem;">
                    <button class="btn-icon" onclick="editUser(${user.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon danger" onclick="deleteUser(${user.id}, '${user.name}')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>`;
        tbody.appendChild(tr);
    });
}

// ──────────────────────────────────────────────────────────────
// Modal helpers
// ──────────────────────────────────────────────────────────────
let _allUsers = [];

function openAddUserModal() {
    document.getElementById('userModalTitle').textContent = 'Tambah User';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('passwordLabel').textContent = 'Password *';
    document.getElementById('passwordHint').textContent = 'Wajib diisi saat membuat user baru.';
    document.getElementById('userPassword').required = true;
    document.getElementById('userModal').style.display = 'flex';
}

function closeUserModal() {
    document.getElementById('userModal').style.display = 'none';
    document.getElementById('userForm').reset();
}

async function editUser(id) {
    try {
        const resp  = await fetch('/admin/users', { headers: { 'Accept': 'application/json' } });
        const users = await resp.json();
        const user  = users.find(u => u.id === id);
        if (!user) { alert('User tidak ditemukan.'); return; }

        _allUsers = users;

        document.getElementById('userModalTitle').textContent = 'Edit User';
        document.getElementById('userId').value    = user.id;
        document.getElementById('userName').value  = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userRole').value  = user.role;
        document.getElementById('userPassword').value    = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('passwordLabel').textContent = 'Ganti Password';
        document.getElementById('passwordHint').textContent  = 'Kosongkan jika tidak ingin mengganti password.';
        document.getElementById('userModal').style.display = 'flex';
    } catch {
        alert('Gagal memuat data user.');
    }
}

function togglePassword() {
    const input = document.getElementById('userPassword');
    const icon  = document.getElementById('passwordEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// ──────────────────────────────────────────────────────────────
// CREATE / UPDATE
// ──────────────────────────────────────────────────────────────
async function saveUser() {
    const id       = document.getElementById('userId').value;
    const name     = document.getElementById('userName').value.trim();
    const email    = document.getElementById('userEmail').value.trim();
    const password = document.getElementById('userPassword').value;
    const role     = document.getElementById('userRole').value;
    const isEdit   = !!id;

    if (!name || !email || !role) {
        alert('Nama, Email, dan Role wajib diisi!');
        return;
    }
    if (!isEdit && !password) {
        alert('Password wajib diisi untuk user baru!');
        return;
    }

    const btn = document.getElementById('saveUserBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan…';

    try {
        const url    = isEdit ? `/admin/users/${id}` : '/admin/users';
        const method = isEdit ? 'PUT' : 'POST';

        const payload = { name, email, role };
        if (password) payload.password = password;

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

        closeUserModal();
        await loadUsers();
    } catch {
        alert('Gagal menyimpan. Cek koneksi atau coba lagi.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    }
}

// ──────────────────────────────────────────────────────────────
// DELETE
// ──────────────────────────────────────────────────────────────
async function deleteUser(id, name) {
    if (!confirm(`Hapus user "${name}"? Tindakan ini tidak dapat dibatalkan!`)) return;
    try {
        const resp = await fetch(`/admin/users/${id}`, {
            method:  'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        });
        if (!resp.ok) {
            const err = await resp.json();
            alert(err.message || 'Gagal menghapus user.');
            return;
        }
        await loadUsers();
    } catch {
        alert('Gagal menghapus user.');
    }
}

// ──────────────────────────────────────────────────────────────
// SEARCH (filter di sisi klien)
// ──────────────────────────────────────────────────────────────
function searchUsers() {
    const term = document.getElementById('searchUser').value.toLowerCase();
    const rows = document.querySelectorAll('#usersTableBody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
}

// Close modal bila klik di luar
window.onclick = function (event) {
    const modal = document.getElementById('userModal');
    if (event.target === modal) closeUserModal();
};
