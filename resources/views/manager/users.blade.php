@extends('layouts.manager')
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

@endsection

@push('modals')
    <!-- Add/Edit User Modal -->
    <div id="userModal" class="tr-modal-overlay" style="display:none;">
        <div class="tr-modal-box" style="background:#fff;">
            <div class="tr-modal-header">
                <h3 id="userModalTitle">Tambah Pengguna</h3>
                <button onclick="closeUserModal()" class="tr-modal-close" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="userForm">
                <input type="hidden" id="userId">
                <div class="tr-modal-body">
                    <div class="form-group">
                        <label for="userName">Nama Lengkap *</label>
                        <input type="text" id="userName" placeholder="Contoh: Budi Santoso" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email *</label>
                        <input type="email" id="userEmail" placeholder="email@domain.com" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword" id="passwordLabel">Kata Sandi *</label>
                        <div style="position:relative;">
                            <input type="password" id="userPassword" placeholder="Minimal 8 karakter" class="form-input">
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
                        <select id="userRole" class="form-input">
                            <option value="affiliate">Afiliasi</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Batal</button>
                    <button type="button" class="tr-btn-submit" id="saveUserBtn" onclick="saveUser()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
<script src="{{ asset('assets/manager/js/users.js') }}?v={{ filemtime(public_path('assets/manager/js/users.js')) }}"></script>
@endpush

