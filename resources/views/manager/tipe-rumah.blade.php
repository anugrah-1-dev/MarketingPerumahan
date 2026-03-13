@extends('layouts.manager')

@section('title', 'Tipe Rumah')
@section('page-title', 'Tipe Rumah')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/manager/css/tiperumah.css') }}">
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
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Error Alert --}}
    @if($errors->any())
        <div style="margin-bottom:16px;background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:0.85rem;">
            <ul style="margin:0;padding-left:16px;">
                @foreach ($errors->all() as $error)
                    <li>❌ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="tr-search-bar">
        <div class="tr-search-input-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="tr-search-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <input type="text" id="trSearch" class="tr-search-input"
                placeholder="Cari nama tipe, harga, stok..."
                oninput="trFilterTable()" autocomplete="off">
            <button id="trSearchClear" class="tr-search-clear hidden" onclick="trClearSearch()" title="Hapus">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <span id="trSearchCount" class="tr-search-count"></span>
    </div>

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
                    <td class="tr-cell-diskon-harga">{{ $t->harga_diskon_format ?? '—' }}</td>
                    <td class="center">
                        @if($t->is_diskon)
                            <span class="tr-badge-on">✔ Aktif</span>
                        @else
                            <span class="tr-badge-off">—</span>
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
                            <form method="POST" action="{{ route('manager.tipe-rumah.destroy', $t->id) }}"
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
<div id="modal-tambah" class="tr-modal-overlay" style="z-index:9999" onclick="trCloseOnBackdrop(event, 'modal-tambah')">
    <div class="tr-modal-box">
        <div class="tr-modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" style="display:inline;width:18px;height:18px;vertical-align:-3px;margin-right:6px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tipe Rumah
            </h3>
            <button onclick="trCloseModal('modal-tambah')" class="tr-modal-close" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('manager.tipe-rumah.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="tr-modal-body">
                @include('manager.partials.tipe-rumah-form')
            </div>
            <div class="tr-modal-footer">
                <button type="submit" class="tr-btn-submit">
                    💾 &nbsp;Simpan Tipe Rumah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ====================== MODAL EDIT ====================== --}}
<div id="modal-edit" class="tr-modal-overlay" style="z-index:9999" onclick="trCloseOnBackdrop(event, 'modal-edit')">
    <div class="tr-modal-box">
        <div class="tr-modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" style="display:inline;width:18px;height:18px;vertical-align:-3px;margin-right:6px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Tipe Rumah
            </h3>
            <button onclick="trCloseModal('modal-edit')" class="tr-modal-close" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="form-edit" method="POST" action="" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="tr-modal-body">
                @include('manager.partials.tipe-rumah-form')
            </div>
            <div class="tr-modal-footer">
                <button type="submit" class="tr-btn-submit">
                    ✏️ &nbsp;Perbarui Tipe Rumah
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')

<script>
let fotoCount = 0;
let fasilitasCount = 0;

function trAddFotoSlot() {
    const list = document.getElementById('fotoTambahanList');
    const idx  = fotoCount++;
    const div  = document.createElement('div');
    div.id     = 'foto-slot-' + idx;
    div.style  = 'display:flex;gap:8px;align-items:center;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;';
    div.innerHTML = `
        <input type="file" name="foto_tambahan[]" accept="image/*"
               style="flex:1;font-size:13px;" required>
        <input type="text" name="foto_keterangan[]" placeholder="Keterangan (opsional)"
               style="flex:1;border:1px solid #e2e8f0;border-radius:6px;padding:6px 10px;font-size:13px;">
        <button type="button" onclick="document.getElementById('foto-slot-${idx}').remove()" title="Hapus"
                style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:18px;line-height:1;">×</button>
    `;
    list.appendChild(div);
}

function trAddFasilitas(value = '') {
    const list = document.getElementById('fasilitasList');
    const idx  = fasilitasCount++;
    const div  = document.createElement('div');
    div.id     = 'fasilitas-slot-' + idx;
    div.style  = 'display:flex;gap:8px;align-items:center;';
    div.innerHTML = `
        <input type="text" name="fasilitas[]" value="${value}" placeholder="Contoh: Listrik 2200W"
               style="flex:1;border:1px solid #e2e8f0;border-radius:6px;padding:8px 12px;font-size:13px;">
        <button type="button" onclick="document.getElementById('fasilitas-slot-${idx}').remove()" title="Hapus"
                style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:18px;line-height:1;">×</button>
    `;
    list.appendChild(div);
}

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
    form.action = `/manager/tipe-rumah/${data.id}`;
    form.querySelector('[name="nama_tipe"]').value      = data.nama_tipe;
    form.querySelector('[name="luas_bangunan"]').value  = data.luas_bangunan;
    form.querySelector('[name="luas_tanah"]').value     = data.luas_tanah;
    form.querySelector('[name="kamar_tidur"]').value    = data.kamar_tidur ?? 2;
    form.querySelector('[name="kamar_mandi"]').value    = data.kamar_mandi ?? 1;
    form.querySelector('[name="lantai"]').value         = data.lantai ?? 1;
    form.querySelector('[name="harga"]').value          = data.harga;
    form.querySelector('[name="harga_diskon"]').value   = data.harga_diskon ?? '';
    form.querySelector('[name="stok_tersedia"]').value  = data.stok_tersedia;
    form.querySelector('[name="deskripsi"]').value      = data.deskripsi ?? '';
    form.querySelector('[name="is_diskon"]').checked    = data.is_diskon == 1;

    const sertSelect = form.querySelector('[name="sertifikat"]');
    if (sertSelect) sertSelect.value = data.sertifikat ?? 'SHM';

    const fasilitasList = form.querySelector('#fasilitasList');
    if (fasilitasList) {
        fasilitasList.innerHTML = '';
        fasilitasCount = 0;
        const fasilitas = data.fasilitas ? (typeof data.fasilitas === 'string' ? JSON.parse(data.fasilitas) : data.fasilitas) : [];
        fasilitas.forEach(f => trAddFasilitas(f));
    }

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
