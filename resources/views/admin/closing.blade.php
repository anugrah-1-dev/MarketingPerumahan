@extends('layouts.admin')
@section('title', 'Manajemen Closing')
@section('page-title', 'Manajemen Closing')

@section('content')
    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
            <div class="stat-content"><h3 id="totalClosing">0</h3><p>Total Closing</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
            <div class="stat-content"><h3 id="totalSales">Rp 0</h3><p>Total Penjualan</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-content"><h3 id="totalCommission">Rp 0</h3><p>Total Komisi</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-content"><h3 id="closingThisMonth">0</h3><p>Closing Bulan Ini</p></div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddClosingModal()">
            <i class="fas fa-plus"></i> Input Closing Baru
        </button>
        <div class="filter-group" style="display:flex;gap:1rem">
            <select id="filterMonth" onchange="filterClosings()">
                <option value="all">Semua Bulan</option>
                <option value="current" selected>Bulan Ini</option>
                <option value="last">Bulan Lalu</option>
            </select>
            <select id="filterAgent" onchange="filterClosings()">
                <option value="all">Semua Agent</option>
            </select>
            <select id="filterPaymentStatus" onchange="filterClosings()">
                <option value="all">Semua Status</option>
                <option value="dp">DP</option>
                <option value="installment">Cicilan</option>
                <option value="paid-off">Lunas</option>
            </select>
        </div>
    </div>

    <!-- Closings Table -->
    <div class="card">
        <div class="card-header">
            <h2>Data Closing</h2>
            <button class="btn btn-success" onclick="exportClosings()">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th><th>Agent</th><th>Customer</th>
                            <th>Properti</th><th>Harga Jual</th><th>Komisi</th>
                            <th>Status Pembayaran</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="closingsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Closing Modal -->
    <div id="closingModal" class="modal">
        <div class="modal-content" style="max-width:600px">
            <div class="modal-header">
                <h2 id="modalTitle">Input Closing Baru</h2>
                <button class="close-btn" onclick="closeClosingModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="closingForm">
                    <input type="hidden" id="closingId">
                    <div class="form-group">
                        <label>Tanggal Closing *</label>
                        <input type="date" id="closingDate" required>
                    </div>
                    <div class="form-group">
                        <label>Agent *</label>
                        <select id="closingAgent" required onchange="updateCommissionPreview()">
                            <option value="">Pilih Agent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Properti *</label>
                        <select id="closingProperty" required onchange="updateCommissionPreview()">
                            <option value="">Pilih Properti</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Customer *</label>
                        <input type="text" id="customerName" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon Customer *</label>
                        <input type="tel" id="customerPhone" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label>Harga Jual *</label>
                        <input type="number" id="salePrice" required min="0" step="1000000" onchange="updateCommissionPreview()" placeholder="0">
                        <small style="color:#64748b">Harga dalam Rupiah</small>
                    </div>
                    <div class="form-group">
                        <label>Status Pembayaran *</label>
                        <select id="paymentStatus" required>
                            <option value="dp">DP (Down Payment)</option>
                            <option value="installment">Cicilan</option>
                            <option value="paid-off">Lunas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea id="closingNotes" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div id="commissionPreview" style="display:none;background:#f0fdf4;border:1px solid #86efac;border-radius:.5rem;padding:1rem;margin-top:1rem">
                        <h4 style="margin-bottom:.5rem;color:#166534">💰 Perhitungan Komisi</h4>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;font-size:.875rem">
                            <div>Harga Jual:</div><div style="font-weight:600" id="previewPrice">Rp 0</div>
                            <div>Rate Komisi:</div><div style="font-weight:600" id="previewRate">0%</div>
                            <div style="border-top:1px solid #86efac;padding-top:.5rem">Total Komisi:</div>
                            <div style="border-top:1px solid #86efac;padding-top:.5rem;font-weight:700;color:#166534;font-size:1.125rem" id="previewCommission">Rp 0</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeClosingModal()">Batal</button>
                <button class="btn btn-primary" onclick="saveClosing()">
                    <i class="fas fa-save"></i> Simpan Closing
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/closing.js') }}"></script>
@endpush
