@extends('layouts.manager')
@section('title', 'Manajemen Agen')
@section('page-title', 'Manajemen Agen')

@section('content')
    {{-- CSRF token untuk dipakai oleh agents.js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddAgentModal()">
            <i class="fas fa-plus"></i> Tambah Agen
        </button>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchAgen" placeholder="Cari agen..." onkeyup="searchAgents()">
        </div>
    </div>

    <!-- Agens Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Agen</th>
                            <th>Email / Telepon</th>
                            <th>Tautan Afiliasi</th>
                            <th>Komisi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="agentsTableBody">
                        <tr>
                            <td colspan="6" style="text-align:center; padding:2rem; color:#94a3b8;">
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
    <!-- Detail Agent Modal -->
    <div id="agentDetailModal" class="modal">
        <div class="modal-content" style="max-width:600px;">
            <div class="modal-header">
                <h2><i class="fas fa-user-circle" style="margin-right:.4rem;"></i> Detail Agen</h2>
                <button class="close-btn" onclick="closeDetailModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="agentDetailBody">
                <div style="text-align:center;padding:2rem;color:#94a3b8;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat detail…
                </div>
            </div>
        </div>
    </div>
    <!-- Add/Edit Agen Modal -->
    <div id="agentModal" class="tr-modal-overlay" style="display:none;">
        <div class="tr-modal-box" style="background:#fff;">
            <div class="tr-modal-header">
                <h3 id="modalTitle">Tambah Afiliasi</h3>
                <button onclick="closeAgentModal()" class="tr-modal-close" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="agentForm">
                <input type="hidden" id="agentId">
                <input type="hidden" id="agentJabatan" value="Affiliate">
                <div class="tr-modal-body">
                    <div class="form-group">
                        <label for="agentName">Nama Lengkap *</label>
                        <input type="text" id="agentName" placeholder="Contoh: Budi Santoso" class="form-input" required>
                    </div>
                    <div class="form-group" id="groupPassword">
                        <label for="agentPassword">Kata Sandi *</label>
                        <input type="password" id="agentPassword" placeholder="Kata sandi untuk login Afiliasi" class="form-input">
                        <small style="color:#94a3b8; display:none;" id="helpPasswordEdit">Kosongkan jika tidak ingin mengubah kata sandi.</small>
                    </div>
                    <div class="form-group">
                        <label for="agentEmail">Email *</label>
                        <input type="email" id="agentEmail" placeholder="affiliate@email.com" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="agentPhone">Telepon</label>
                        <input type="tel" id="agentPhone" placeholder="Contoh: 081234567890" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="agentCommission">Komisi (%)</label>
                        <input type="number" id="agentCommission" placeholder="Contoh: 2.5" min="0" max="100" step="0.01" value="0" class="form-input">
                        <small style="color:#94a3b8;">Persentase komisi penjualan (0-100%)</small>
                    </div>
                    <hr style="border:none;border-top:1px solid #e2e8f0;margin:1rem 0">
                    <h4 style="margin:0 0 .75rem;color:#334155;font-size:.95rem"><i class="fas fa-university" style="margin-right:.4rem;color:#3d81af"></i>Informasi Rekening</h4>
                    <div class="form-group">
                        <label for="agentNamaBank">Nama Bank</label>
                        <input type="text" id="agentNamaBank" placeholder="Contoh: BCA, BRI, Mandiri" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="agentNoRekening">Nomor Rekening</label>
                        <input type="text" id="agentNoRekening" placeholder="Contoh: 1234567890" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="agentAtasNama">Atas Nama Rekening</label>
                        <input type="text" id="agentAtasNama" placeholder="Nama pemilik rekening" class="form-input">
                    </div>
                </div>
                <div class="tr-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAgentModal()">Batal</button>
                    <button type="button" class="tr-btn-submit" id="saveBtn" onclick="saveAgent()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
<script src="{{ asset('assets/manager/js/agents.js') }}?v={{ filemtime(public_path('assets/manager/js/agents.js')) }}"></script>
@endpush

