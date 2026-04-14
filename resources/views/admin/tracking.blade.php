@extends('layouts.admin')
@section('title', 'Pelacakan Klik WA')
@section('page-title', 'Pelacakan Klik WhatsApp')

@section('content')
    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-mouse-pointer"></i></div>
            <div class="stat-content"><h3 id="totalClicksToday">0</h3><p>Klik Hari Ini</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-content"><h3 id="totalPending">0</h3><p>Status Baru</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-phone"></i></div>
            <div class="stat-content"><h3 id="totalFollowUp">0</h3><p>Tindak Lanjut</p></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content"><h3 id="totalConverted">0</h3><p>Tertarik</p></div>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="card">
        <div class="card-header"><h2>Filter &amp; Pencarian</h2></div>
        <div class="card-body">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Tanggal</label>
                    <select id="filterDate" onchange="filterClicks()">
                        <option value="all">Semua Waktu</option>
                        <option value="today" selected>Hari Ini</option>
                        <option value="yesterday">Kemarin</option>
                        <option value="week">7 Hari Terakhir</option>
                        <option value="month">30 Hari Terakhir</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Agen</label>
                    <select id="filterAgen" onchange="filterClicks()">
                        <option value="all">Semua Agen</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Status Prospek</label>
                    <select id="filterStatus" onchange="filterClicks()">
                        <option value="all">Semua Status</option>
                        <option value="new">Baru</option>
                        <option value="follow-up">Tindak Lanjut</option>
                        <option value="interested">Tertarik</option>
                        <option value="not-interested">Tidak Tertarik</option>
                        <option value="closed">Penutupan</option>
                    </select>
                </div>

            </div>
            <div class="search-box" style="margin-top:1rem">
                <i class="fas fa-search"></i>
                <input type="text" id="searchClicks" placeholder="Cari nama, nomor HP, agen..." onkeyup="searchClickData()">
            </div>
        </div>
    </div>

    <!-- Clicks Table -->
    <div class="card">
        <div class="card-header">
            <h2>Data Klik WhatsApp</h2>
            <button class="btn btn-success" onclick="exportClicks()">
                <i class="fas fa-download"></i> Ekspor CSV
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                            <thead>
                                <tr>
                                    <th>Waktu Klik</th>
                                    <th>Nama / No HP</th>
                                    <th>Agen</th>
                                    <th>Perangkat</th>
                                    <th>Status Prospek</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                    <tbody id="clicksTableBody"></tbody>
                </table>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Perbarui Status Prospek</h2>
                <button class="close-btn" onclick="closeStatusModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="clickId">
                    <div class="form-group">
                        <label>Status Prospek *</label>
                        <select id="leadStatus" required>
                            <option value="new">Baru</option>
                            <option value="follow-up">Tindak Lanjut</option>
                            <option value="interested">Tertarik</option>
                            <option value="not-interested">Tidak Tertarik</option>
                            <option value="closed">Penutupan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea id="leadNotes" rows="4" placeholder="Tambahkan catatan tindak lanjut..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Jadwal Tindak Lanjut (opsional)</label>
                        <input type="datetime-local" id="followUpDate">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeStatusModal()">Batal</button>
                <button class="btn btn-primary" onclick="updateClickStatus()">Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/tracking.js') }}"></script>
@endpush

