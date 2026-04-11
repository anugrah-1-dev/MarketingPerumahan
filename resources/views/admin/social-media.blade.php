@extends('layouts.admin')
@section('title', 'Konten Media Sosial')
@section('page-title', 'Etalase Media Sosial')

@section('content')

    {{-- Flash messages --}}
    @if (session('success'))
        <div style="background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;padding:12px 18px;border-radius:10px;margin-bottom:1.5rem;display:flex;align-items:center;gap:10px;font-size:.9rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 18px;border-radius:10px;margin-bottom:1.5rem;display:flex;align-items:center;gap:10px;font-size:.9rem;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 18px;border-radius:10px;margin-bottom:1.5rem;font-size:.88rem;">
            <div style="font-weight:600;margin-bottom:.4rem;"><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</div>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Action Bar -->
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah Konten
        </button>
        <p style="font-size:.85rem;color:#64748b;margin:0;">
            Kelola konten YouTube, Instagram, TikTok, atau file foto/video lokal untuk showcase homepage.
        </p>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            @if($items->isEmpty())
                <div style="text-align:center;padding:3rem;color:#94a3b8;">
                    <i class="fas fa-photo-video" style="font-size:2.5rem;margin-bottom:1rem;display:block;opacity:.4;"></i>
                    <p style="font-weight:600;margin-bottom:.5rem;">Belum ada konten.</p>
                    <p style="font-size:.85rem;">Tambahkan link media sosial atau unggah foto/video lokal.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:90px;">Pratinjau</th>
                            <th style="width:110px;">Platform</th>
                            <th>Judul</th>
                            <th style="width:180px;">Sumber</th>
                            <th style="width:80px;">Status</th>
                            <th style="width:80px;">Tanggal</th>
                            <th style="width:110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        @php
                            $cfg = $item->config;
                            $platformLabels = ['youtube' => 'YouTube', 'tiktok' => 'TikTok', 'instagram' => 'Instagram'];
                        @endphp
                        <tr>
                            {{-- Thumbnail --}}
                            <td>
                                @if($item->thumbnail_src)
                                    <img src="{{ $item->thumbnail_src }}" alt="{{ e($item->title) }}"
                                         style="width:72px;height:48px;object-fit:cover;border-radius:8px;display:block;">
                                @elseif($item->media_type === 'image' && $item->media_src)
                                    <img src="{{ $item->media_src }}" alt="{{ e($item->title) }}"
                                         style="width:72px;height:48px;object-fit:cover;border-radius:8px;display:block;">
                                @elseif($item->media_type === 'video' && $item->media_src)
                                    <video muted playsinline preload="metadata"
                                           style="width:72px;height:48px;object-fit:cover;border-radius:8px;display:block;background:#0f172a;">
                                        <source src="{{ $item->media_src }}">
                                    </video>
                                @else
                                    <div style="width:72px;height:48px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                                        <svg style="width:20px;height:20px;fill:{{ $cfg['color'] }};" viewBox="0 0 24 24">
                                            <path d="{{ $cfg['svg'] }}"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>

                            {{-- Platform --}}
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:28px;height:28px;border-radius:8px;background:{{ $cfg['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg style="width:13px;height:13px;fill:white;" viewBox="0 0 24 24">
                                            <path d="{{ $cfg['svg'] }}"/>
                                        </svg>
                                    </div>
                                    <span style="font-size:.8rem;font-weight:600;">{{ $platformLabels[$item->platform] ?? $item->platform }}</span>
                                </div>
                            </td>

                            {{-- Title + desc --}}
                            <td>
                                <p style="font-weight:600;font-size:.88rem;color:#1e293b;margin:0 0 2px;">{{ Str::limit($item->title, 55) }}</p>
                                @if($item->description)
                                    <p style="font-size:.78rem;color:#64748b;margin:0;">{{ Str::limit($item->description, 70) }}</p>
                                @endif
                            </td>

                            {{-- Source --}}
                            <td>
                                @if($item->content_url)
                                    <a href="{{ $item->content_url }}" target="_blank" rel="noopener noreferrer"
                                       style="color:#6366f1;font-size:.78rem;text-decoration:none;display:flex;align-items:center;gap:4px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        <i class="fas fa-external-link-alt" style="font-size:.65rem;flex-shrink:0;"></i>
                                        {{ parse_url($item->content_url, PHP_URL_HOST) }}
                                    </a>
                                @elseif($item->media_src)
                                    <span style="font-size:.78rem;color:#0f172a;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                                        <i class="fas {{ $item->media_type === 'video' ? 'fa-video' : 'fa-image' }}"></i>
                                        Unggah {{ $item->media_type === 'video' ? 'video' : 'foto' }}
                                    </span>
                                @else
                                    <span style="font-size:.78rem;color:#94a3b8;">Tidak ada sumber</span>
                                @endif
                            </td>

                            {{-- Status toggle --}}
                            <td>
                                <form method="POST" action="{{ route('admin.social-media.toggle', $item->id) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="
                                        border:none;cursor:pointer;border-radius:20px;padding:4px 12px;font-size:.78rem;font-weight:600;
                                        background:{{ $item->is_active ? '#d1fae5' : '#fee2e2' }};
                                        color:{{ $item->is_active ? '#065f46' : '#991b1b' }};">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Date --}}
                            <td style="font-size:.78rem;color:#94a3b8;white-space:nowrap;">
                                {{ $item->created_at->format('d M Y') }}
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div style="display:flex;gap:5px;">
                                    <button onclick='openEditModal(@json($item))'
                                        style="padding:5px 10px;border:none;border-radius:7px;background:#e0e7ff;color:#3730a3;font-size:.78rem;cursor:pointer;font-weight:600;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.social-media.destroy', $item->id) }}"
                                          onsubmit="return confirm('Hapus konten ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="padding:5px 10px;border:none;border-radius:7px;background:#fee2e2;color:#991b1b;font-size:.78rem;cursor:pointer;font-weight:600;">
                                            <i class="fas fa-trash"></i>
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

    {{-- ── ADD / EDIT MODAL ─────────────────────────────────────────────── --}}
    <div id="smModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9000;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:#fff;border-radius:16px;width:100%;max-width:560px;max-height:92vh;overflow-y:auto;padding:2rem;">

            <div style="display:flex;align-items:center;margin-bottom:1.5rem;">
                <h3 id="smModalTitle" style="font-size:1.1rem;font-weight:700;color:#1e293b;flex:1;margin:0;">Tambah Konten</h3>
                <button onclick="closeModal()" style="background:none;border:none;cursor:pointer;font-size:1.4rem;color:#94a3b8;line-height:1;">&times;</button>
            </div>

            <form id="smForm" method="POST" action="{{ route('admin.social-media.store') }}" enctype="multipart/form-data">
                @csrf
                <span id="smMethod"></span>

                {{-- Platform --}}
                <div style="margin-bottom:1.1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.45rem;">
                        Platform <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="platform" id="f_platform" required
                        style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px;font-size:.9rem;color:#1e293b;background:#fff;">
                        <option value="youtube">YouTube</option>
                        <option value="tiktok">TikTok</option>
                        <option value="instagram">Instagram</option>
                    </select>
                </div>

                {{-- Title --}}
                <div style="margin-bottom:1.1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.45rem;">
                        Judul Konten <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="title" id="f_title" required maxlength="150"
                        placeholder="e.g. Tur Rumah Minimalis 2 Lantai"
                        style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px;font-size:.9rem;color:#1e293b;box-sizing:border-box;">
                </div>

                {{-- Description --}}
                <div style="margin-bottom:1.1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.45rem;">Deskripsi Singkat</label>
                    <textarea name="description" id="f_description" rows="2" maxlength="300"
                        placeholder="Caption singkat yang tampil di bawah judul..."
                        style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px;font-size:.9rem;color:#1e293b;resize:vertical;box-sizing:border-box;"></textarea>
                </div>

                {{-- Content URL --}}
                <div style="margin-bottom:1.1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.45rem;">
                        URL Konten
                    </label>
                    <input type="url" name="content_url" id="f_content_url" maxlength="500"
                        placeholder="https://youtube.com/... atau https://instagram.com/..."
                        style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px;font-size:.9rem;color:#1e293b;box-sizing:border-box;">
                    <p style="font-size:.75rem;color:#94a3b8;margin:.3rem 0 0;">Bisa YouTube, TikTok, atau Instagram. Jika diisi, kartu homepage akan menuju link ini.</p>
                </div>

                {{-- Media file --}}
                <div style="margin-bottom:1.1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.45rem;">Unggah Foto / Video</label>

                    <div id="mediaCurrentWrap" style="margin-bottom:.75rem;display:none;">
                        <p style="font-size:.78rem;color:#64748b;margin-bottom:.4rem;">Media saat ini:</p>
                        <img id="mediaCurrentImage" src="" alt="" style="max-height:120px;border-radius:10px;object-fit:cover;display:none;">
                        <video id="mediaCurrentVideo" controls playsinline style="max-height:140px;border-radius:10px;display:none;background:#0f172a;"></video>
                    </div>
                    <div id="mediaPreviewWrap" style="margin-bottom:.75rem;display:none;">
                        <p style="font-size:.78rem;color:#64748b;margin-bottom:.4rem;">Pratinjau baru:</p>
                        <img id="mediaPreviewImage" src="" alt="Preview" style="max-height:120px;border-radius:10px;object-fit:cover;display:none;">
                        <video id="mediaPreviewVideo" controls playsinline style="max-height:140px;border-radius:10px;display:none;background:#0f172a;"></video>
                    </div>

                    <input type="file" name="media_file" id="f_media_file" accept="image/*,video/*"
                        style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:8px 12px;font-size:.85rem;color:#374151;"
                        onchange="previewMedia(this)">
                    <p style="font-size:.75rem;color:#94a3b8;margin:.3rem 0 0;">Opsional. Unggah foto atau video lokal. Minimal salah satu dari URL atau file unggahan harus diisi.</p>
                </div>

                {{-- Thumbnail --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.45rem;">Thumbnail</label>

                    <div id="thumbCurrentWrap" style="margin-bottom:.75rem;display:none;">
                        <p style="font-size:.78rem;color:#64748b;margin-bottom:.4rem;">Thumbnail saat ini:</p>
                        <img id="thumbCurrent" src="" alt=""
                             style="max-height:100px;border-radius:10px;object-fit:cover;display:block;">
                    </div>
                    <div id="thumbPreviewWrap" style="margin-bottom:.75rem;display:none;">
                        <p style="font-size:.78rem;color:#64748b;margin-bottom:.4rem;">Pratinjau baru:</p>
                        <img id="thumbPreview" src="" alt="Preview"
                             style="max-height:100px;border-radius:10px;object-fit:cover;display:block;">
                    </div>

                    <input type="file" name="thumbnail" id="f_thumbnail" accept="image/*"
                        style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:8px 12px;font-size:.85rem;color:#374151;"
                        onchange="previewThumb(this)">
                    <p style="font-size:.75rem;color:#94a3b8;margin:.3rem 0 0;">Opsional. Jika upload video, thumbnail ini dipakai sebagai cover card. JPG/PNG/WEBP, maks 2 MB.</p>
                </div>

                <div style="display:flex;gap:.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid #f1f5f9;">
                    <button type="button" onclick="closeModal()"
                        style="padding:10px 20px;border:1px solid #cbd5e1;border-radius:8px;background:#fff;color:#374151;font-size:.9rem;cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <span id="smSubmitLabel">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var smModal   = document.getElementById('smModal');
        var smForm    = document.getElementById('smForm');
        var smMethod  = document.getElementById('smMethod');
        var baseStore = "{{ route('admin.social-media.store') }}";

        function openAddModal() {
            document.getElementById('smModalTitle').textContent  = 'Tambah Konten';
            document.getElementById('smSubmitLabel').textContent = 'Simpan';
            smForm.action = baseStore;
            smMethod.innerHTML = '';
            document.getElementById('f_platform').value    = 'youtube';
            document.getElementById('f_title').value       = '';
            document.getElementById('f_description').value = '';
            document.getElementById('f_content_url').value = '';
            document.getElementById('f_media_file').value  = '';
            document.getElementById('f_thumbnail').value   = '';
            document.getElementById('thumbPreviewWrap').style.display = 'none';
            document.getElementById('thumbCurrentWrap').style.display = 'none';
            resetMediaPreview();
            resetCurrentMedia();
            smModal.style.display = 'flex';
        }

        function openEditModal(item) {
            document.getElementById('smModalTitle').textContent  = 'Edit Konten';
            document.getElementById('smSubmitLabel').textContent = 'Perbarui';
            smForm.action  = '/admin/social-media/' + item.id;
            smMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('f_platform').value    = item.platform      || 'youtube';
            document.getElementById('f_title').value       = item.title         || '';
            document.getElementById('f_description').value = item.description   || '';
            document.getElementById('f_content_url').value = item.content_url   || '';
            document.getElementById('f_media_file').value  = '';
            document.getElementById('f_thumbnail').value   = '';
            document.getElementById('thumbPreviewWrap').style.display = 'none';
            resetMediaPreview();

            var currentWrap = document.getElementById('thumbCurrentWrap');
            var currentImg  = document.getElementById('thumbCurrent');
            if (item.thumbnail_src) {
                currentImg.src = item.thumbnail_src;
                currentWrap.style.display = 'block';
            } else {
                currentWrap.style.display = 'none';
            }

            setCurrentMedia(item.media_type || '', item.media_src || '');

            smModal.style.display = 'flex';
        }

        function closeModal() { smModal.style.display = 'none'; }

        function previewThumb(input) {
            var wrap = document.getElementById('thumbPreviewWrap');
            var img  = document.getElementById('thumbPreview');
            if (input.files && input.files[0]) {
                img.src = URL.createObjectURL(input.files[0]);
                wrap.style.display = 'block';
            } else {
                wrap.style.display = 'none';
            }
        }

        function resetCurrentMedia() {
            document.getElementById('mediaCurrentWrap').style.display = 'none';
            document.getElementById('mediaCurrentImage').style.display = 'none';
            document.getElementById('mediaCurrentVideo').style.display = 'none';
            document.getElementById('mediaCurrentImage').src = '';
            document.getElementById('mediaCurrentVideo').src = '';
        }

        function setCurrentMedia(type, src) {
            resetCurrentMedia();
            if (!src) return;

            var wrap = document.getElementById('mediaCurrentWrap');
            if (type === 'video') {
                var video = document.getElementById('mediaCurrentVideo');
                video.src = src;
                video.style.display = 'block';
            } else {
                var image = document.getElementById('mediaCurrentImage');
                image.src = src;
                image.style.display = 'block';
            }
            wrap.style.display = 'block';
        }

        function resetMediaPreview() {
            document.getElementById('mediaPreviewWrap').style.display = 'none';
            document.getElementById('mediaPreviewImage').style.display = 'none';
            document.getElementById('mediaPreviewVideo').style.display = 'none';
            document.getElementById('mediaPreviewImage').src = '';
            document.getElementById('mediaPreviewVideo').src = '';
        }

        function previewMedia(input) {
            resetMediaPreview();
            if (!(input.files && input.files[0])) return;

            var file = input.files[0];
            var url = URL.createObjectURL(file);
            var wrap = document.getElementById('mediaPreviewWrap');
            if (file.type.indexOf('video/') === 0) {
                var video = document.getElementById('mediaPreviewVideo');
                video.src = url;
                video.style.display = 'block';
            } else {
                var image = document.getElementById('mediaPreviewImage');
                image.src = url;
                image.style.display = 'block';
            }
            wrap.style.display = 'block';
        }

        smModal.addEventListener('click', function(e) { if (e.target === smModal) closeModal(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
    </script>

@endsection