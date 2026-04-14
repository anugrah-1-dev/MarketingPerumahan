@extends('layouts.admin')
@section('title', 'Video Lokasi')
@section('page-title', 'Video Lokasi')

@section('content')
    @if(session('success'))
        <div style="background:#ecfdf5;border:1px solid #6ee7b7;color:#065f46;padding:12px 20px;border-radius:10px;margin-bottom:20px;font-size:.9rem;">
            <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;padding:12px 20px;border-radius:10px;margin-bottom:20px;font-size:.9rem;">
            <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>{{ $errors->first() }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-video" style="margin-right:8px;color:#6366f1;"></i>Video Lokasi Perumahan</h2>
        </div>
        <div class="card-body">
            <p style="color:#64748b;font-size:.9rem;margin-bottom:1.5rem;">
                Unggah video lokasi perumahan yang akan ditampilkan di halaman utama website pada bagian <strong>Lokasi Kami</strong>.
                Format yang didukung: MP4, MOV, WebM, OGG. Maksimal 100 MB.
            </p>

            {{-- Preview video saat ini --}}
            @if($lokasiVideo)
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.5rem;">
                        <i class="fas fa-film" style="margin-right:4px;color:#3b82f6;"></i>Video Saat Ini
                    </label>
                    <div style="background:#f8fafc;border:2px dashed #e2e8f0;border-radius:12px;padding:16px;text-align:center;">
                        <video controls style="max-width:100%;max-height:400px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                            <source src="{{ asset($lokasiVideo) }}" type="video/{{ pathinfo($lokasiVideo, PATHINFO_EXTENSION) === 'mov' ? 'quicktime' : 'mp4' }}">
                        </video>
                        <p style="color:#64748b;font-size:.8rem;margin-top:8px;">{{ basename($lokasiVideo) }}</p>
                    </div>

                    {{-- Hapus video --}}
                    <form action="{{ route('admin.lokasi-video.delete') }}" method="POST"
                          onsubmit="return confirm('Hapus video lokasi ini?')"
                          style="margin-top:10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="font-size:.85rem;">
                            <i class="fas fa-trash" style="margin-right:5px;"></i>Hapus Video
                        </button>
                    </form>
                </div>
            @else
                <div style="background:#f8fafc;border:2px dashed #e2e8f0;border-radius:12px;padding:40px;text-align:center;margin-bottom:1.5rem;">
                    <i class="fas fa-video" style="font-size:48px;color:#cbd5e1;margin-bottom:12px;display:block;"></i>
                    <p style="color:#94a3b8;font-size:.9rem;margin:0;">Belum ada video lokasi. Silakan unggah video di bawah.</p>
                </div>
            @endif

            {{-- Form Upload --}}
            <form action="{{ route('admin.lokasi-video.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group" style="margin-bottom:1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.5rem;">
                        <i class="fas fa-upload" style="margin-right:4px;color:#10b981;"></i>{{ $lokasiVideo ? 'Ganti Video Lokasi' : 'Unggah Video Lokasi' }}
                    </label>
                    <input type="file" name="lokasi_video" accept="video/mp4,video/quicktime,video/webm,video/ogg" required
                           onchange="previewVideo(this)"
                           style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;font-size:.875rem;color:#374151;">
                    <small style="color:#94a3b8;font-size:.78rem;">Format: MP4, MOV, WebM, OGG. Maksimal 100 MB.</small>
                </div>

                {{-- Preview file baru --}}
                <div id="videoPreviewWrap" style="display:none;margin-bottom:1rem;">
                    <p style="font-size:.8rem;color:#64748b;margin-bottom:6px;">Pratinjau video baru:</p>
                    <video id="videoPreview" controls
                           style="max-width:100%;max-height:400px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    </video>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save" style="margin-right:6px;"></i>Simpan Video
                </button>
            </form>
        </div>
    </div>

    <script>
    function previewVideo(input) {
        var wrap  = document.getElementById('videoPreviewWrap');
        var video = document.getElementById('videoPreview');
        if (input.files && input.files[0]) {
            var url = URL.createObjectURL(input.files[0]);
            video.src = url;
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
            video.src = '';
        }
    }
    </script>
@endsection
