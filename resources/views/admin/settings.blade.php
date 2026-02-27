@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')

    {{-- Flash sukses --}}
    @if (session('success'))
        <div class="alert alert-success" style="
            background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7;
            padding: 12px 18px; border-radius: 10px; margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 10px; font-size: .9rem;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="max-width: 560px;">
        <div class="card-body">

            <h3 style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-bottom:.4rem;">
                <i class="fab fa-whatsapp" style="color:#25D366; margin-right:6px;"></i>
                Nomor WhatsApp Admin / Kantor
            </h3>
            <p style="font-size:.85rem; color:#64748b; margin-bottom:1.5rem; line-height:1.6;">
                Nomor ini digunakan untuk tombol <strong>Chat Admin</strong> yang muncul di semua
                halaman website. Format: <code>62</code> diikuti nomor tanpa awalan 0
                (contoh: <code>6281234567890</code>).
            </p>

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <div class="form-group" style="margin-bottom: 1.2rem;">
                    <label for="wa_admin" style="display:block; font-weight:600; font-size:.875rem; color:#374151; margin-bottom:.5rem;">
                        Nomor WhatsApp
                    </label>
                    <div style="display:flex; align-items:center; gap:.5rem;">
                        {{-- Prefix icon --}}
                        <span style="
                            background:#f1f5f9; border:1px solid #cbd5e1;
                            border-radius:8px 0 0 8px; padding:10px 14px;
                            color:#64748b; font-size:.9rem; white-space:nowrap;">
                            <i class="fab fa-whatsapp" style="color:#25D366;"></i>
                            +
                        </span>
                        <input
                            type="text"
                            id="wa_admin"
                            name="wa_admin"
                            value="{{ old('wa_admin', $waAdmin) }}"
                            placeholder="6281234567890"
                            style="
                                flex:1; border:1px solid #cbd5e1; border-left:none;
                                border-radius:0 8px 8px 0; padding:10px 14px;
                                font-size:.9rem; color:#1e293b; outline:none;
                                transition:border-color .2s;
                            "
                            onFocus="this.style.borderColor='#6366f1'"
                            onBlur="this.style.borderColor='#cbd5e1'"
                            required
                        >
                    </div>

                    @error('wa_admin')
                        <p style="color:#ef4444; font-size:.8rem; margin-top:.4rem;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror

                    <p style="font-size:.775rem; color:#94a3b8; margin-top:.4rem;">
                        Link yang akan dipakai:
                        <a id="preview_wa" href="#" target="_blank" style="color:#6366f1; font-family:monospace; font-size:.775rem;"></a>
                    </p>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Preview link WA realtime
    const input   = document.getElementById('wa_admin');
    const preview = document.getElementById('preview_wa');

    function updatePreview() {
        const nomor = input.value.trim();
        const url = nomor ? `https://wa.me/${nomor}` : '';
        preview.href = url;
        preview.textContent = url || '-';
    }

    input.addEventListener('input', updatePreview);
    updatePreview(); // jalankan saat halaman load
</script>
@endpush
