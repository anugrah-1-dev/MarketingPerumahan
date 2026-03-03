@extends('layouts.admin')
@section('title', 'Manajemen Users')
@section('page-title', 'Manajemen Users')

@section('content')
    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddUserModal()">
            <i class="fas fa-plus"></i> Tambah User
        </button>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchUser" placeholder="Cari user..." onkeyup="searchUsers()">
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="5" style="text-align:center; padding:2rem; color:#94a3b8;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat data…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="userModalTitle">Tambah User</h2>
                <button class="close-btn" onclick="closeUserModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId">

                    <div class="form-group">
                        <label for="userName">Nama Lengkap *</label>
                        <input type="text" id="userName" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="form-group">
                        <label for="userEmail">Email *</label>
                        <input type="email" id="userEmail" placeholder="email@domain.com" required>
                    </div>

                    <div class="form-group">
                        <label for="userPassword" id="passwordLabel">Password *</label>
                        <div style="position:relative;">
                            <input type="password" id="userPassword" placeholder="Minimal 8 karakter">
                            <button type="button"
                                onclick="togglePassword()"
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;">
                                <i class="fas fa-eye" id="passwordEyeIcon"></i>
                            </button>
                        </div>
                        <small id="passwordHint" style="color:#94a3b8;">Wajib diisi saat membuat user baru.</small>
                    </div>

                    <div class="form-group">
                        <label for="userRole">Role *</label>
                        <select id="userRole" style="width:100%;padding:.625rem .875rem;border:1px solid #e2e8f0;border-radius:.5rem;font-size:.9375rem;">
                            <option value="affiliate">Affiliate</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeUserModal()">Batal</button>
                <button class="btn btn-primary" id="saveUserBtn" onclick="saveUser()">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/users.js') }}"></script>
@endpush
