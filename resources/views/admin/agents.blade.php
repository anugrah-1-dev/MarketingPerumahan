@extends('layouts.admin')
@section('title', 'Manajemen Agent')
@section('page-title', 'Manajemen Agent')

@section('content')
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
                            <th>Agent</th><th>Email / Telepon</th><th>Link Affiliate</th>
                            <th>Klik</th><th>Closing</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="agentsTableBody"></tbody>
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
                        <input type="text" id="agentName" required>
                    </div>
                    <div class="form-group">
                        <label for="agentEmail">Email *</label>
                        <input type="email" id="agentEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="agentPhone">Telepon *</label>
                        <input type="tel" id="agentPhone" required>
                    </div>
                    <div class="form-group">
                        <label for="agentCommission">Komisi (%) *</label>
                        <input type="number" id="agentCommission" min="0" max="100" step="0.5" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAgentModal()">Batal</button>
                <button class="btn btn-primary" onclick="saveAgent()">Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/agents.js') }}"></script>
@endpush
