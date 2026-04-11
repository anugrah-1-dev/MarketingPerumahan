@extends('layouts.manager')
@section('title', 'Denah Perumahan')
@section('page-title', 'Denah Perumahan')

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
            <h2><i class="fas fa-map" style="margin-right:8px;color:#6366f1;"></i>Gambar Denah Perumahan</h2>
        </div>
        <div class="card-body">
            <p style="color:#64748b;font-size:.9rem;margin-bottom:1.5rem;">
                Unggah gambar denah/site plan perumahan yang akan ditampilkan di halaman utama website.
                Format yang didukung: JPG, JPEG, PNG, WebP. Maksimal 5 MB.
            </p>

            {{-- Preview gambar saat ini --}}
            @if($denahImage)
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.5rem;">
                        <i class="fas fa-image" style="margin-right:4px;color:#3b82f6;"></i>Denah Saat Ini
                    </label>
                    <div style="background:#f8fafc;border:2px dashed #e2e8f0;border-radius:12px;padding:16px;text-align:center;">
                        <img src="{{ asset($denahImage) }}" alt="Denah Perumahan"
                             style="max-width:100%;max-height:500px;border-radius:8px;object-fit:contain;box-shadow:0 4px 12px rgba(0,0,0,0.1);"
                             onerror="this.parentElement.innerHTML='<p style=\'color:#94a3b8;font-size:.85rem;\'>Gambar tidak ditemukan</p>';">
                    </div>
                </div>
            @else
                <div style="background:#f8fafc;border:2px dashed #e2e8f0;border-radius:12px;padding:40px;text-align:center;margin-bottom:1.5rem;">
                    <i class="fas fa-map" style="font-size:48px;color:#cbd5e1;margin-bottom:12px;display:block;"></i>
                    <p style="color:#94a3b8;font-size:.9rem;margin:0;">Belum ada gambar denah. Silakan unggah gambar di bawah.</p>
                </div>
            @endif

            {{-- Form Upload --}}
            <form action="{{ route('manager.denah.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group" style="margin-bottom:1rem;">
                    <label style="display:block;font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.5rem;">
                        <i class="fas fa-upload" style="margin-right:4px;color:#10b981;"></i>{{ $denahImage ? 'Ganti Gambar Denah' : 'Unggah Gambar Denah' }}
                    </label>
                    <input type="file" name="denah_image" accept="image/jpeg,image/png,image/webp" required
                           onchange="previewDenah(this)"
                           style="width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;font-size:.875rem;color:#374151;">
                    <small style="color:#94a3b8;font-size:.78rem;">Format: JPG, JPEG, PNG, WebP. Maksimal 5 MB.</small>
                </div>

                {{-- Preview file baru --}}
                <div id="denahPreviewWrap" style="display:none;margin-bottom:1rem;">
                    <p style="font-size:.8rem;color:#64748b;margin-bottom:6px;">Pratinjau gambar baru:</p>
                    <img id="denahPreview" src="" alt="Pratinjau"
                         style="max-width:100%;max-height:400px;border-radius:8px;object-fit:contain;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save" style="margin-right:6px;"></i>Simpan Denah
                </button>
            </form>
        </div>
    </div>

    <script>
    function previewDenah(input) {
        var wrap = document.getElementById('denahPreviewWrap');
        var img  = document.getElementById('denahPreview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                wrap.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            wrap.style.display = 'none';
        }
    }
    </script>
@endsection
