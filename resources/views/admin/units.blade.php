@extends('layouts.admin')
@section('title', 'Manajemen Unit')
@section('page-title', 'Manajemen Unit')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/units.css') }}">
@endpush

@section('content')
<div class="unit-page">

    {{-- Header --}}
    <div class="unit-page-header">
        <div>
            <h1>Manajemen Unit</h1>
            <p>Kelola data unit properti beserta statusnya</p>
        </div>
        <button onclick="unitOpenModal('modal-tambah')" class="unit-btn-tambah">
            <i class="fas fa-plus"></i> Tambah Unit
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="unit-alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="unit-alert-error">
            <ul style="margin:0;padding-left:16px">
                @foreach($errors->all() as $e)<li>❌ {{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Stats --}}
    <div class="unit-stats-grid">
        <div class="unit-stat-card">
            <div class="unit-stat-icon blue"><i class="fas fa-home"></i></div>
            <div class="unit-stat-num">{{ $stats['total'] }}</div>
            <div class="unit-stat-label">Total Unit</div>
        </div>
        <div class="unit-stat-card">
            <div class="unit-stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="unit-stat-num">{{ $stats['tersedia'] }}</div>
            <div class="unit-stat-label">Unit Tersedia</div>
        </div>
        <div class="unit-stat-card">
            <div class="unit-stat-icon red"><i class="fas fa-chart-line"></i></div>
            <div class="unit-stat-num">{{ $stats['terjual'] }}</div>
            <div class="unit-stat-label">Unit Terjual</div>
        </div>
        <div class="unit-stat-card">
            <div class="unit-stat-icon orange"><i class="fas fa-calendar-check"></i></div>
            <div class="unit-stat-num">{{ $stats['booking'] }}</div>
            <div class="unit-stat-label">Unit Booking</div>
        </div>
    </div>

    {{-- Controls --}}
    <div class="unit-controls">
        <div class="unit-filter-tabs">
            <button class="unit-tab active" onclick="unitFilter(this,'all')">Semua</button>
            <button class="unit-tab" onclick="unitFilter(this,'tersedia')">Tersedia</button>
            <button class="unit-tab" onclick="unitFilter(this,'booking')">Booking</button>
            <button class="unit-tab" onclick="unitFilter(this,'terjual')">Terjual</button>
        </div>
        <div class="unit-search-wrap">
            <i class="fas fa-search unit-search-icon" style="font-size:13px"></i>
            <input type="text" id="unitSearch" class="unit-search-input"
                placeholder="Cari unit, blok, tipe..." oninput="unitSearch()">
        </div>
    </div>

    {{-- Table --}}
    <div class="unit-table-wrap">
        <table class="unit-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Unit</th>
                    <th>Blok</th>
                    <th>Tipe Rumah</th>
                    <th>Status</th>
                    <th>Harga Jual</th>
                    <th>Catatan</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody id="unitTableBody">
                @forelse($units as $i => $u)
                <tr data-status="{{ $u->status }}"
                    data-search="{{ strtolower($u->nomor_unit . ' ' . $u->blok . ' ' . ($u->tipeRumah->nama_tipe ?? '')) }}">
                    <td style="color:#9ca3af;font-size:12px">{{ $i + 1 }}</td>
                    <td><strong>{{ $u->nomor_unit }}</strong></td>
                    <td>{{ $u->blok ?? '–' }}</td>
                    <td>{{ $u->tipeRumah->nama_tipe ?? '–' }}</td>
                    <td>
                        <span class="unit-badge {{ $u->status }}">
                            @if($u->status === 'tersedia') <i class="fas fa-check"></i> Tersedia
                            @elseif($u->status === 'booking') <i class="fas fa-clock"></i> Booking
                            @else <i class="fas fa-times"></i> Terjual
                            @endif
                        </span>
                    </td>
                    <td>{{ $u->harga_jual_format ?? '–' }}</td>
                    <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $u->catatan ?? '–' }}
                    </td>
                    <td class="center" style="white-space:nowrap">
                        <button class="unit-btn-edit" onclick="unitOpenEdit({{ $u->toJson() }})">
                            <i class="fas fa-pen"></i> Edit
                        </button>
                        <button class="unit-btn-del" onclick="unitOpenDel({{ $u->id }}, '{{ addslashes($u->nomor_unit) }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="unit-empty">Belum ada data unit. Tambah unit pertama!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ===== MODAL TAMBAH ===== --}}
<div id="modal-tambah" class="unit-modal-overlay">
    <div class="unit-modal">
        <div class="unit-modal-header">
            <span class="unit-modal-title"><i class="fas fa-plus-circle" style="color:#0B5E41;margin-right:8px"></i>Tambah Unit</span>
            <button class="unit-modal-close" onclick="unitCloseModal('modal-tambah')">&#x2715;</button>
        </div>
        <form method="POST" action="{{ route('admin.units.store') }}">
            @csrf
            <div class="unit-form-row2">
                <div class="unit-form-group">
                    <label class="unit-form-label">Tipe Rumah <span>*</span></label>
                    <select name="tipe_rumah_id" class="unit-form-control" required>
                        <option value="">— Pilih Tipe —</option>
                        @foreach($tipeRumah as $t)
                        <option value="{{ $t->id }}">{{ $t->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="unit-form-group">
                    <label class="unit-form-label">No Unit <span>*</span></label>
                    <input type="text" name="nomor_unit" class="unit-form-control" placeholder="A.01" required>
                </div>
            </div>
            <div class="unit-form-row2">
                <div class="unit-form-group">
                    <label class="unit-form-label">Blok / Cluster</label>
                    <input type="text" name="blok" class="unit-form-control" placeholder="Blok A">
                </div>
                <div class="unit-form-group">
                    <label class="unit-form-label">Status <span>*</span></label>
                    <select name="status" class="unit-form-control" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="booking">Booking</option>
                        <option value="terjual">Terjual</option>
                    </select>
                </div>
            </div>
            <div class="unit-form-group">
                <label class="unit-form-label">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" class="unit-form-control" placeholder="Kosongkan jika mengikuti harga tipe" min="0">
            </div>
            <div class="unit-form-group">
                <label class="unit-form-label">Catatan</label>
                <textarea name="catatan" class="unit-form-control" rows="2" placeholder="Keterangan tambahan..."></textarea>
            </div>
            <div class="unit-modal-footer">
                <button type="button" class="unit-btn-cancel" onclick="unitCloseModal('modal-tambah')">Batal</button>
                <button type="submit" class="unit-btn-submit"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="modal-edit" class="unit-modal-overlay">
    <div class="unit-modal">
        <div class="unit-modal-header">
            <span class="unit-modal-title"><i class="fas fa-pen" style="color:#3b82f6;margin-right:8px"></i>Edit Unit</span>
            <button class="unit-modal-close" onclick="unitCloseModal('modal-edit')">&#x2715;</button>
        </div>
        <form method="POST" id="form-edit" action="">
            @csrf
            @method('PUT')
            <div class="unit-form-row2">
                <div class="unit-form-group">
                    <label class="unit-form-label">Tipe Rumah <span>*</span></label>
                    <select name="tipe_rumah_id" id="edit-tipe" class="unit-form-control" required>
                        @foreach($tipeRumah as $t)
                        <option value="{{ $t->id }}">{{ $t->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="unit-form-group">
                    <label class="unit-form-label">No Unit <span>*</span></label>
                    <input type="text" name="nomor_unit" id="edit-nomor" class="unit-form-control" required>
                </div>
            </div>
            <div class="unit-form-row2">
                <div class="unit-form-group">
                    <label class="unit-form-label">Blok / Cluster</label>
                    <input type="text" name="blok" id="edit-blok" class="unit-form-control">
                </div>
                <div class="unit-form-group">
                    <label class="unit-form-label">Status <span>*</span></label>
                    <select name="status" id="edit-status" class="unit-form-control" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="booking">Booking</option>
                        <option value="terjual">Terjual</option>
                    </select>
                </div>
            </div>
            <div class="unit-form-group">
                <label class="unit-form-label">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" id="edit-harga" class="unit-form-control" min="0">
            </div>
            <div class="unit-form-group">
                <label class="unit-form-label">Catatan</label>
                <textarea name="catatan" id="edit-catatan" class="unit-form-control" rows="2"></textarea>
            </div>
            <div class="unit-modal-footer">
                <button type="button" class="unit-btn-cancel" onclick="unitCloseModal('modal-edit')">Batal</button>
                <button type="submit" class="unit-btn-submit"><i class="fas fa-save"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL HAPUS ===== --}}
<div id="modal-hapus" class="unit-modal-overlay">
    <div class="unit-modal unit-modal-del">
        <div class="unit-del-icon"><i class="fas fa-trash"></i></div>
        <div class="unit-del-title">Hapus Unit?</div>
        <p class="unit-del-desc">Unit <strong id="del-unit-name"></strong> akan dihapus permanen dan tidak dapat dikembalikan.</p>
        <form method="POST" id="form-hapus" action="" style="margin-top:24px">
            @csrf
            @method('DELETE')
            <div class="unit-modal-footer" style="justify-content:center">
                <button type="button" class="unit-btn-cancel" onclick="unitCloseModal('modal-hapus')">Batal</button>
                <button type="submit" class="unit-btn-del-confirm"><i class="fas fa-trash"></i> Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function unitOpenModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function unitCloseModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
// Close on overlay click
document.querySelectorAll('.unit-modal-overlay').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (e.target === el) unitCloseModal(el.id);
    });
});

function unitOpenEdit(u) {
    document.getElementById('edit-tipe').value   = u.tipe_rumah_id;
    document.getElementById('edit-nomor').value  = u.nomor_unit;
    document.getElementById('edit-blok').value   = u.blok || '';
    document.getElementById('edit-status').value = u.status;
    document.getElementById('edit-harga').value  = u.harga_jual || '';
    document.getElementById('edit-catatan').value= u.catatan || '';
    document.getElementById('form-edit').action  = '/admin/units/' + u.id;
    unitOpenModal('modal-edit');
}
function unitOpenDel(id, nama) {
    document.getElementById('del-unit-name').textContent  = nama;
    document.getElementById('form-hapus').action = '/admin/units/' + id;
    unitOpenModal('modal-hapus');
}

// Filter by status
function unitFilter(btn, status) {
    document.querySelectorAll('.unit-tab').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('#unitTableBody tr[data-status]').forEach(function(row) {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}

// Search
function unitSearch() {
    var q = document.getElementById('unitSearch').value.toLowerCase();
    document.querySelectorAll('#unitTableBody tr[data-search]').forEach(function(row) {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
}

// Escape closes modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.unit-modal-overlay.open').forEach(function(el) {
            unitCloseModal(el.id);
        });
    }
});
</script>
@endpush
