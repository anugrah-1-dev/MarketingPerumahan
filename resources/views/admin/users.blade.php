@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddUserModal()">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </button>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchUser" placeholder="Cari pengguna..." onkeyup="searchPengguna()">
        </div>
    </div>

    <!-- Pengguna Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="5" style="text-align:center; padding:2rem; color:#94a3b8;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal (custom overlay) -->
    <div id="userModal" class="tr-modal-overlay" style="z-index:9999; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(30,41,59,0.25); display:none; align-items:center; justify-content:center;">
        <div class="tr-modal-box">
            <div class="tr-modal-header">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" style="display:inline;width:18px;height:18px;vertical-align:-3px;margin-right:6px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span id="userModalTitle">Tambah Pengguna</span>
                </h3>
                <button onclick="closeUserModal()" class="tr-modal-close" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="userForm">
                <input type="hidden" id="userId">
                <div class="tr-modal-body">
                    <div class="form-group">
                        <label for="userName">Nama Lengkap *</label>
                        <input type="text" id="userName" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email *</label>
                        <input type="email" id="userEmail" placeholder="email@domain.com" required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword" id="passwordLabel">Kata Sandi *</label>
                        <div style="position:relative;">
                            <input type="password" id="userPassword" placeholder="Minimal 8 karakter">
                            <button type="button"
                                onclick="togglePassword()"
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;">
                                <i class="fas fa-eye" id="passwordEyeIcon"></i>
                            </button>
                        </div>
                        <small id="passwordHint" style="color:#94a3b8;">Wajib diisi saat membuat pengguna baru.</small>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Peran *</label>
                        <select id="userRole" style="width:100%;padding:.625rem .875rem;border:1px solid #e2e8f0;border-radius:.5rem;font-size:.9375rem;">
                            <option value="affiliate">Afiliasi</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveUserBtn" onclick="saveUser()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/users.js') }}?v={{ filemtime(public_path('assets/admin/js/users.js')) }}"></script>
<script>
document.getElementById('userModal').addEventListener('mousedown', function(e) {
    if (e.target === this) {
        // Jangan tutup modal jika klik di luar modal-content
        e.stopPropagation();
    }
});
</script>
@endpush

