@extends('layouts.admin')
@section('title', 'Manajemen Agent')
@section('page-title', 'Manajemen Agent')

@section('content')
    {{-- CSRF token untuk dipakai oleh agents.js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddAgentModal()">
            <i class="fas fa-plus"></i> Tambah Agent
        </button>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchAgent" placeholder="Cari agent..." onkeyup="searchAgents()">
        </div>
    </div>

    <!-- Agents Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Email / Telepon</th>
                            <th>Link Affiliate</th>
                            <th>Komisi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="agentsTableBody">
                        <tr>
                            <td colspan="6" style="text-align:center; padding:2rem; color:#94a3b8;">
                                <i class="fas fa-spinner fa-spin"></i> Memuat data…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Agent Modal -->
    <div id="agentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Tambah Affiliate</h2>
                <button class="close-btn" onclick="closeAgentModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="agentForm">
                    <input type="hidden" id="agentId">

                    <div class="form-group">
                        <label for="agentName">Nama Lengkap *</label>
                        <input type="text" id="agentName" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <!-- Jabatan hidden (default to Affiliate in JS/Backend) -->
                    <input type="hidden" id="agentJabatan" value="Affiliate">

                    <div class="form-group" id="groupPassword">
                        <label for="agentPassword">Password *</label>
                        <input type="password" id="agentPassword" placeholder="Password untuk login Affiliate">
                        <small style="color:#94a3b8; display:none;" id="helpPasswordEdit">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>

                    <div class="form-group">
                        <label for="agentEmail">Email *</label>
                        <input type="email" id="agentEmail" placeholder="affiliate@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="agentPhone">Telepon</label>
                        <input type="tel" id="agentPhone" placeholder="Contoh: 081234567890">
                    </div>

                    <!-- Commission hidden (default to 0 in Backend) -->
                    <input type="hidden" id="agentCommission" value="0">


                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAgentModal()">Batal</button>
                <button class="btn btn-primary" id="saveBtn" onclick="saveAgent()">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/agents.js') }}?v={{ filemtime(public_path('assets/admin/js/agents.js')) }}"></script>
@endpush
