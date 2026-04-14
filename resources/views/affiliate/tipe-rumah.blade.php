@extends('layouts.affiliate')

@section('title', 'Tipe Rumah')
@section('page-title', 'Tipe Rumah')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/tiperumah.css') }}">
<style>
/* -- Tipe Rumah - Responsive Overrides untuk Affiliate Layout -- */
.p-6 { padding: 24px; }
.tr-table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.tr-table { min-width: 680px; }
.tr-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
@media (max-width: 600px) {
    .p-6 { padding: 12px; }
    .tr-page-header h1 { font-size: 20px; }
    .tr-btn-tambah { width: 100%; justify-content: center; }
    .tr-modal-box { margin: 12px; width: calc(100% - 24px); max-height: calc(100dvh - 24px); }
    .tr-modal-body { max-height: 65vh; overflow-y: auto; }
}
</style>
@endpush

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="tr-page-header">
        <div>
            <h1>Tipe Rumah</h1>
            <p>Kelola daftar tipe rumah yang tersedia</p>
        </div>
        <button onclick="trOpenModal('modal-tambah')" class="tr-btn-tambah">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tipe Rumah
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div style="margin-bottom:16px;background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:10px;font-size:0.85rem;">
            <i class="fas fa-check-circle text-green-500 mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="tr-table-wrapper">
        <table class="tr-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Tipe</th>
                    <th>LB / LT</th>
                    <th>Harga Normal</th>
                    <th>Harga Diskon</th>
                    <th class="center">Diskon</th>
                    <th class="center">Stok</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody id="trTableBody">
                @forelse($tipeRumah as $t)
                <tr>
                    <td>
                        <img src="{{ $t->gambar_url }}"
                             alt="{{ $t->nama_tipe }}"
                             class="tr-thumb"
                             onerror="this.src='https://placehold.co/68x52/f3f4f6/9ca3af?text=No+Img'">
                    </td>
                    <td class="tr-cell-name">{{ $t->nama_tipe }}</td>
                    <td class="tr-cell-ukuran">{{ $t->luas_bangunan }}m² / {{ $t->luas_tanah }}m²</td>
                    <td class="tr-cell-harga">{{ $t->harga_format }}</td>
                    <td class="tr-cell-diskon-harga">{{ $t->harga_diskon_format ?? '-' }}</td>
                    <td class="center">
                        @if($t->is_diskon)
                            <span class="tr-badge-on">&#10003; Aktif</span>
                        @else
                            <span class="tr-badge-off">-</span>
                        @endif
                    </td>
                    <td class="center tr-stok">{{ $t->stok_tersedia }}</td>
                    <td class="center">
                        <div class="tr-actions">
                            <button type="button"
                                onclick='trOpenEditModal({{ json_encode($t) }})'
                                class="tr-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('affiliate.tipe-rumah.destroy', $t->id) }}"
                                onsubmit="return confirm('Yakin hapus tipe rumah ini?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="tr-btn-delete" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="tr-empty-row">
                    <td colspan="8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0 auto 10px;display:block;color:#d1d5db">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Belum ada data. Klik <strong>Tambah Tipe Rumah</strong> untuk menambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ====================== MODAL TAMBAH ====================== --}}
<div id="modal-tambah" class="tr-modal-overlay" onclick="trCloseOnBackdrop(event, 'modal-tambah')">
    <div class="tr-modal-box">
        <div class="tr-modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" style="display:inline;width:18px;height:18px;vertical-align:-3px;margin-right:6px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tipe Rumah
            </h3>
            <button type="button" onclick="trCloseModal('modal-tambah')" class="tr-modal-close" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('affiliate.tipe-rumah.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="tr-modal-body">
                @include('admin.partials.tipe-rumah-form')
            </div>
            <div class="tr-modal-footer">
                <button type="submit" class="tr-btn-submit">
                    <i class="fas fa-save"></i> &nbsp;Simpan Tipe Rumah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ====================== MODAL EDIT ====================== --}}
<div id="modal-edit" class="tr-modal-overlay" onclick="trCloseOnBackdrop(event, 'modal-edit')">
    <div class="tr-modal-box">
        <div class="tr-modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" style="display:inline;width:18px;height:18px;vertical-align:-3px;margin-right:6px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Tipe Rumah
            </h3>
            <button type="button" onclick="trCloseModal('modal-edit')" class="tr-modal-close" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="form-edit" method="POST" action="" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="tr-modal-body">
                @include('admin.partials.tipe-rumah-form')
            </div>
            <div class="tr-modal-footer">
                <button type="submit" class="tr-btn-submit">
                    <i class="fas fa-edit"></i> &nbsp;Perbarui Tipe Rumah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function trOpenModal(id) {
    const el = document.getElementById(id);
    el.classList.add('is-open');
    document.body.style.overflow = 'hidden';
}

function trCloseModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('is-open');
    document.body.style.overflow = '';
}

function trCloseOnBackdrop(event, id) {
    if (event.target === document.getElementById(id)) {
        trCloseModal(id);
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        trCloseModal('modal-tambah');
        trCloseModal('modal-edit');
    }
});

function trOpenEditModal(data) {
    const form = document.getElementById('form-edit');
    form.action = `/affiliate/tipe-rumah/${data.id}`;
    form.querySelector('[name="nama_tipe"]').value      = data.nama_tipe;
    form.querySelector('[name="luas_bangunan"]').value  = data.luas_bangunan;
    form.querySelector('[name="luas_tanah"]').value     = data.luas_tanah;
    form.querySelector('[name="harga"]').value          = data.harga;
    form.querySelector('[name="harga_diskon"]').value   = data.harga_diskon ?? '';
    form.querySelector('[name="stok_tersedia"]').value  = data.stok_tersedia;
    form.querySelector('[name="deskripsi"]').value      = data.deskripsi ?? '';
    form.querySelector('[name="is_diskon"]').checked    = data.is_diskon == 1;
    trOpenModal('modal-edit');
}

function trFilterTable() {
    const q     = document.getElementById('trSearch').value.toLowerCase().trim();
    const rows  = document.querySelectorAll('#trTableBody tr:not(.tr-empty-row)');
    const clear = document.getElementById('trSearchClear');
    const count = document.getElementById('trSearchCount');
    let visible = 0;

    clear.classList.toggle('hidden', q === '');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const show = text.includes(q);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const emptyRow = document.querySelector('#trTableBody .tr-empty-row');
    if (emptyRow) emptyRow.style.display = (visible === 0 && q !== '') ? '' : 'none';

    count.textContent = q === '' ? '' : visible + ' hasil ditemukan';
}

function trClearSearch() {
    document.getElementById('trSearch').value = '';
    trFilterTable();
    document.getElementById('trSearch').focus();
}
</script>
@endpush


