@php
    $routePrefix = request()->routeIs('manager.*') ? 'manager' : 'admin';
@endphp

@if (session('success'))
    <div style="background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;padding:12px 18px;border-radius:10px;margin-bottom:1rem;font-size:.9rem;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 18px;border-radius:10px;margin-bottom:1rem;font-size:.88rem;">
        <div style="font-weight:600;margin-bottom:.4rem;">Terdapat kesalahan:</div>
        <ul style="margin:0;padding-left:1.2rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:grid;grid-template-columns:1fr;gap:1rem;">
    <div class="card">
        <div class="card-body">
            <h3 style="margin:0 0 .9rem;font-size:1rem;font-weight:700;color:#1e293b;">Tambah Slide Baru</h3>
            <form method="POST" action="{{ route($routePrefix . '.hero-slides.store') }}" enctype="multipart/form-data" style="display:grid;gap:.8rem;max-width:680px;">
                @csrf
                <div>
                    <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:.25rem;color:#334155;">Judul (Opsional)</label>
                    <input type="text" name="title" maxlength="150" placeholder="Contoh: Tampak Depan Cluster"
                           style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 12px;font-size:.9rem;">
                </div>

                <div style="display:grid;grid-template-columns:1fr 170px 170px;gap:.6rem;align-items:end;">
                    <div>
                        <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:.25rem;color:#334155;">Foto Slide *</label>
                        <input type="file" name="image" accept="image/*" required
                               style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:8px 10px;font-size:.85rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:.25rem;color:#334155;">Urutan</label>
                        <input type="number" name="sort_order" min="0" max="9999" value="{{ $slides->count() + 1 }}"
                               style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 12px;font-size:.9rem;">
                    </div>
                    <label style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;font-weight:600;color:#334155;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Aktif
                    </label>
                </div>

                <div>
                    <button type="submit" style="padding:10px 16px;border:none;border-radius:8px;background:#16a34a;color:white;font-weight:700;cursor:pointer;">
                        Simpan Slide
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 style="margin:0 0 .9rem;font-size:1rem;font-weight:700;color:#1e293b;">Daftar Slide Hero</h3>

            @if($slides->isEmpty())
                <p style="margin:0;color:#64748b;font-size:.9rem;">Belum ada slide hero. Tambahkan slide pertama Anda.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width:120px;">Pratinjau</th>
                            <th>Detail</th>
                            <th style="width:120px;">Status</th>
                            <th style="width:360px;">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($slides as $slide)
                            <tr>
                                <td>
                                    <img src="{{ $slide->image_url }}" alt="{{ e($slide->title ?: 'Hero Slide') }}"
                                         style="width:100px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                                </td>
                                <td>
                                    <form method="POST" action="{{ route($routePrefix . '.hero-slides.update', $slide->id) }}" enctype="multipart/form-data"
                                          style="display:grid;gap:.45rem;max-width:360px;">
                                        @csrf
                                        @method('PUT')

                                        <input type="text" name="title" value="{{ $slide->title }}" maxlength="150" placeholder="Judul slide"
                                               style="width:100%;border:1px solid #cbd5e1;border-radius:7px;padding:8px 10px;font-size:.82rem;">

                                        <div style="display:grid;grid-template-columns:120px 1fr;gap:.45rem;">
                                            <input type="number" name="sort_order" min="0" max="9999" value="{{ $slide->sort_order }}"
                                                   style="width:100%;border:1px solid #cbd5e1;border-radius:7px;padding:8px 10px;font-size:.82rem;">
                                            <input type="file" name="image" accept="image/*"
                                                   style="width:100%;border:1px solid #cbd5e1;border-radius:7px;padding:6px 8px;font-size:.75rem;">
                                        </div>

                                        <label style="display:flex;align-items:center;gap:.45rem;font-size:.78rem;color:#334155;">
                                            <input type="checkbox" name="is_active" value="1" {{ $slide->is_active ? 'checked' : '' }}>
                                            Aktifkan slide
                                        </label>

                                        <button type="submit"
                                                style="width:max-content;padding:7px 12px;border:none;border-radius:7px;background:#1d4ed8;color:#fff;font-size:.78rem;font-weight:600;cursor:pointer;">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span style="display:inline-flex;padding:5px 10px;border-radius:999px;font-size:.75rem;font-weight:700;
                                        background:{{ $slide->is_active ? '#dcfce7' : '#fee2e2' }};
                                        color:{{ $slide->is_active ? '#166534' : '#991b1b' }};">
                                        {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;gap:.45rem;flex-wrap:wrap;align-items:center;">
                                        <form method="POST" action="{{ route($routePrefix . '.hero-slides.toggle', $slide->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="padding:7px 11px;border:none;border-radius:7px;background:#f1f5f9;color:#0f172a;font-size:.78rem;font-weight:600;cursor:pointer;">
                                                {{ $slide->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route($routePrefix . '.hero-slides.destroy', $slide->id) }}"
                                              onsubmit="return confirm('Hapus slide ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="padding:7px 11px;border:none;border-radius:7px;background:#fee2e2;color:#991b1b;font-size:.78rem;font-weight:600;cursor:pointer;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
