@extends('layouts.admin')

@section('title', 'Tipe Rumah')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tipe Rumah</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola daftar tipe rumah yang tersedia</p>
        </div>
        <button onclick="openModal('modal-tambah')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tipe Rumah
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Gambar</th>
                    <th class="px-4 py-3 text-left">Nama Tipe</th>
                    <th class="px-4 py-3 text-left">LB / LT</th>
                    <th class="px-4 py-3 text-left">Harga</th>
                    <th class="px-4 py-3 text-left">Harga Diskon</th>
                    <th class="px-4 py-3 text-center">Diskon</th>
                    <th class="px-4 py-3 text-center">Stok</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tipeRumah as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <img src="{{ $t->gambar_url }}" alt="{{ $t->nama_tipe }}"
                            class="w-16 h-12 object-cover rounded-lg">
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $t->nama_tipe }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $t->luas_bangunan }}m² / {{ $t->luas_tanah }}m²</td>
                    <td class="px-4 py-3 text-gray-800">{{ $t->harga_format }}</td>
                    <td class="px-4 py-3 text-green-600">{{ $t->harga_diskon_format ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($t->is_diskon)
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">Aktif</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full">Tidak</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center font-semibold text-gray-800">{{ $t->stok_tersedia }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick='openEditModal({{ json_encode($t) }})'
                                class="text-blue-600 hover:text-blue-800 p-1 rounded" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.tipe-rumah.destroy', $t->id) }}"
                                onsubmit="return confirm('Hapus tipe rumah ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                        Belum ada data tipe rumah. Klik "Tambah Tipe Rumah" untuk menambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b">
            <h3 class="text-lg font-bold text-gray-800">Tambah Tipe Rumah</h3>
            <button onclick="closeModal('modal-tambah')" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.tipe-rumah.store') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            @include('admin.partials.tipe-rumah-form')
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition">
                Simpan
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b">
            <h3 class="text-lg font-bold text-gray-800">Edit Tipe Rumah</h3>
            <button onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit" method="POST" action="" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf @method('PUT')
            @include('admin.partials.tipe-rumah-form')
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition">
                Perbarui
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function openEditModal(data) {
    const form = document.getElementById('form-edit');
    form.action = `/admin/tipe-rumah/${data.id}`;
    form.querySelector('[name="nama_tipe"]').value = data.nama_tipe;
    form.querySelector('[name="luas_bangunan"]').value = data.luas_bangunan;
    form.querySelector('[name="luas_tanah"]').value = data.luas_tanah;
    form.querySelector('[name="harga"]').value = data.harga;
    form.querySelector('[name="harga_diskon"]').value = data.harga_diskon ?? '';
    form.querySelector('[name="stok_tersedia"]').value = data.stok_tersedia;
    form.querySelector('[name="deskripsi"]').value = data.deskripsi ?? '';
    form.querySelector('[name="is_diskon"]').checked = data.is_diskon == 1;
    openModal('modal-edit');
}
</script>
@endpush
@endsection
