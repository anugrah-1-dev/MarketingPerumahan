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
                <h2 id="modalTitle">Tambah Agent</h2>
                <button class="close-btn" onclick="closeAgentModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="agentForm">
                    <input type="hidden" id="agentId">

                    <div class="form-group">
                        <label for="agentName">Nama Agent *</label>
                        <input type="text" id="agentName" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="form-group">
                        <label for="agentJabatan">Jabatan *</label>
                        <input type="text" id="agentJabatan" placeholder="Contoh: Marketing Executive" required>
                    </div>

                    <div class="form-group">
                        <label for="agentEmail">Email</label>
                        <input type="email" id="agentEmail" placeholder="agent@email.com">
                    </div>

                    <div class="form-group">
                        <label for="agentPhone">Telepon</label>
                        <input type="tel" id="agentPhone" placeholder="Contoh: 081234567890">
                    </div>

                    <div class="form-group">
                        <label for="agentCommission">Komisi (%)</label>
                        <input type="number" id="agentCommission" min="0" max="100" step="0.5" placeholder="Contoh: 2.5">
                    </div>

                    <div class="form-group">
                        <label>Slug (URL) — otomatis dari nama</label>
                        <div style="display:flex; align-items:center; gap:.5rem;">
                            <span style="color:#64748b; font-size:.875rem;">{{ url('/') }}/</span>
                            <input type="text" id="agentSlug" style="flex:1;" readonly>
                        </div>
                        <small style="color:#94a3b8;">Digunakan sebagai link landing page agent.</small>
                    </div>
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
<script src="{{ asset('assets/admin/js/agents.js') }}"></script>
@endpush
