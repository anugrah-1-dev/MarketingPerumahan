@extends('layouts.affiliate')
@section('title', 'Profile & Akun')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/profile.css') }}?v={{ file_exists(public_path('assets/affiliate/css/profile.css')) ? filemtime(public_path('assets/affiliate/css/profile.css')) : '1.0' }}">
@endpush

@section('content')
<div class="profile-page">

    {{-- -- Header -- --}}
    <div class="page-header">
        <h1 class="page-title">Profil & Akun</h1>
        <p class="page-subtitle">Kelola informasi akun dan pengaturan Anda</p>
    </div>

    {{-- -- Alert Sukses / Error -- --}}
    @if(session('success_biodata'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success_biodata') }}
        </div>
    @endif
    @if(session('success_password'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success_password') }}
        </div>
    @endif

    {{-- -- Kartu Header Profil -- --}}
    <div class="profile-header-card">
        <div class="avatar-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/></svg>
        </div>
        <div class="profile-header-info">
            <h2 class="profile-name">{{ $agent->nama ?? $user->name }}</h2>
            <span class="badge-status">Aktif</span>
            <p class="profile-meta">
                <span>{{ $agent->jabatan ?? 'Marketing Executive' }}</span>
                @if($agent)
                    &nbsp;Â·&nbsp;Kode: <strong>{{ $agent->slug }}</strong>
                @endif
            </p>
            <p class="profile-meta">
                Bergabung sejak: {{ $user->created_at->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>

    <div class="profile-grid">

        {{-- -- Card Informasi Pribadi -- --}}
        <div class="profile-card accordion-card">
            <div class="card-header-section accordion-header" onclick="toggleAccordion('content-biodata', this)">
                <div class="d-flex align-center gap-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="card-icon"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/></svg>
                    <h3>Informasi Pribadi</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="accordion-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
            </div>

            <div id="content-biodata" class="accordion-content">
                <form action="{{ route('affiliate.profile.update') }}" method="POST" id="form-biodata">
                    @csrf
                    @method('PUT')

                    @if($errors->hasAny(['nama','email','phone']))
                        <div class="alert alert-error">
                            <ul>
                                @foreach(['nama','email','phone'] as $field)
                                    @error($field)<li>{{ $message }}</li>@enderror
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <div class="input-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                            <input type="text" id="nama" name="nama"
                                   value="{{ old('nama', $agent->nama ?? $user->name) }}"
                                   class="form-input {{ $errors->has('nama') ? 'input-error' : '' }}"
                                   placeholder="Nama lengkap Anda">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', $agent->email ?? $user->email) }}"
                                   class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                                   placeholder="email@contoh.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon / WhatsApp</label>
                        <p class="field-hint">Nomor ini akan muncul di tombol Chat WhatsApp pada link Anda</p>
                        <div class="input-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3"/></svg>
                            <input type="text" id="phone" name="phone"
                                   value="{{ old('phone', $agent->phone ?? '') }}"
                                   class="form-input {{ $errors->has('phone') ? 'input-error' : '' }}"
                                   placeholder="Contoh: 628123456789">
                        </div>
                    </div>

                    <button type="submit" class="btn-save">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- -- Card Keamanan -- --}}
        <div class="profile-card accordion-card">
            <div class="card-header-section accordion-header" onclick="toggleAccordion('content-security', this)">
                <div class="d-flex align-center gap-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="card-icon"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"/></svg>
                    <h3>Keamanan Akun</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="accordion-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
            </div>

            <div id="content-security" class="accordion-content">
                <form action="{{ route('affiliate.profile.password') }}" method="POST" id="form-password">
                    @csrf
                    @method('PUT')

                    @error('password_lama')
                        <div class="alert alert-error"><p>{{ $message }}</p></div>
                    @enderror
                    @if($errors->has('password_baru'))
                        <div class="alert alert-error"><p>{{ $errors->first('password_baru') }}</p></div>
                    @endif

                    <div class="form-group">
                        <label for="password_lama">Kata Sandi Lama</label>
                        <div class="input-icon-wrap input-password-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            <input type="password" id="password_lama" name="password_lama"
                                   class="form-input {{ $errors->has('password_lama') ? 'input-error' : '' }}"
                                   placeholder="Masukkan kata sandi lama">
                            <button type="button" class="toggle-pw" onclick="togglePw('password_lama', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_baru">Kata Sandi Baru</label>
                        <div class="input-icon-wrap input-password-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            <input type="password" id="password_baru" name="password_baru"
                                   class="form-input {{ $errors->has('password_baru') ? 'input-error' : '' }}"
                                   placeholder="Minimal 8 karakter">
                            <button type="button" class="toggle-pw" onclick="togglePw('password_baru', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_baru_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-icon-wrap input-password-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            <input type="password" id="password_baru_confirmation" name="password_baru_confirmation"
                                   class="form-input"
                                   placeholder="Ulangi kata sandi baru">
                            <button type="button" class="toggle-pw" onclick="togglePw('password_baru_confirmation', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-save btn-password">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Ganti Kata Sandi
                    </button>
                </form>
            </div>
        </div>

    </div>{{-- /.profile-grid --}}

</div>{{-- /.profile-page --}}

<script>
function togglePw(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.style.opacity = isHidden ? '1' : '0.5';
}

function toggleAccordion(contentId, headerEl) {
    const content = document.getElementById(contentId);
    
    // Toggle class active
    headerEl.classList.toggle('active');
    
    if (content.style.maxHeight) {
        // Jika sedang terbuka, tutup
        content.style.maxHeight = null;
        content.style.opacity = 0;
        content.style.paddingTop = "0";
        content.style.paddingBottom = "0";
    } else {
        // Buka panel
        content.style.opacity = 1;
        content.style.paddingTop = "15px"; // Jarak agar tidak mepet header
        content.style.paddingBottom = "15px"; // Jarak bawah agar button tidak terpotong
        // Beri jeda/tambah buffer pixel karena padding ikut mempengaruhi total tinggi
        content.style.maxHeight = content.scrollHeight + 50 + "px";
    }
}
</script>
@endsection

