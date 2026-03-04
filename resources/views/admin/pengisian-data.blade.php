@extends('layouts.admin')
@section('title', 'Pengisian Data Client')
@section('page-title', 'Pengisian Data Client')

@push('styles')
<style>
.form-wrap {
    padding: 8px 0 60px;
    font-family: 'Inter', sans-serif;
}
.form-header h2 { font-size: 22px; font-weight: 700; color: #222; margin-bottom: 4px; }
.form-header p  { color: #888; font-size: 14px; }

/* Stepper */
.stepper {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.step {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    padding: 7px 18px;
    border-radius: 20px;
    color: #aaa;
}
.step.active { background: #1a3649; color: #fff; }
.step.done   { background: #059669; color: #fff; }
.step-arrow  { color: #bbb; font-size: 14px; }

/* Form Card */
.pd-form-card {
    margin-top: 22px;
    background: #f0f0f0;
    border-radius: 16px;
    padding: 28px 28px 24px;
    max-width: 560px;
}
.form-group { margin-bottom: 16px; }
.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 7px;
}
.form-group label .req { color: #e53935; margin-left: 3px; }
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: #333;
    background: #fff;
    outline: none;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.form-group input:focus,
.form-group textarea:focus { border-color: #3d81af; }
.form-group input.invalid { border-color: #e53935; }
.form-group input::placeholder,
.form-group textarea::placeholder { color: #b0b0b0; }
.form-group textarea { resize: vertical; min-height: 90px; }
.err-msg { color: #e53935; font-size: 12px; margin-top: 4px; }

/* Review Table */
.review-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 8px;
}
.review-table tr td {
    padding: 10px 4px;
    font-size: 14px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: top;
}
.review-table tr:last-child td { border-bottom: none; }
.review-table td:first-child { color: #888; width: 140px; }
.review-table td:last-child  { color: #222; font-weight: 600; }

/* Success */
.success-icon { text-align: center; padding: 12px 0 8px; }
.success-icon i { font-size: 56px; color: #059669; }
.success-text   { text-align: center; margin-top: 10px; }
.success-text h2{ font-size: 20px; font-weight: 700; color: #222; }
.success-text p { color: #888; font-size: 14px; margin-top: 6px; }

/* Buttons */
.form-actions { display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
.btn-batal {
    padding: 11px 28px; border-radius: 20px;
    background: #e53935; color: #fff;
    font-size: 14px; font-weight: 600;
    font-family: 'Inter', sans-serif; border: none; cursor: pointer;
    transition: opacity 0.2s;
}
.btn-batal:hover { opacity: 0.88; }
.btn-tambah, .btn-confirm {
    padding: 11px 28px; border-radius: 20px;
    background: #3d81af; color: #fff;
    font-size: 14px; font-weight: 600;
    font-family: 'Inter', sans-serif; border: none; cursor: pointer;
    transition: opacity 0.2s;
}
.btn-tambah:hover, .btn-confirm:hover { opacity: 0.88; }
.btn-back {
    padding: 11px 28px; border-radius: 20px;
    background: none; color: #555;
    font-size: 14px; font-weight: 600;
    font-family: 'Inter', sans-serif;
    border: 1.5px solid #ccc; cursor: pointer;
    transition: border-color 0.2s;
}
.btn-back:hover { border-color: #888; }
.btn-new {
    padding: 11px 28px; border-radius: 20px;
    background: #1a3649; color: #fff;
    font-size: 14px; font-weight: 600;
    font-family: 'Inter', sans-serif; border: none; cursor: pointer;
    display: inline-block; text-decoration: none;
    transition: opacity 0.2s;
}
.btn-new:hover { opacity: 0.85; }

@media (max-width: 600px) {
    .pd-form-card { padding: 20px 16px; }
}
</style>
@endpush

@section('content')
<div class="form-wrap">

    {{-- Header --}}
    <div class="form-header">
        <h2>Form Data Client</h2>
        <p>Submit data calon pembeli untuk diproses oleh tim marketing.</p>
    </div>

    @php $step = session('step', 'form'); @endphp

    {{-- Stepper --}}
    <div class="stepper">
        <div class="step {{ $step === 'form' ? 'active' : 'done' }}" id="stepper-1">Data client</div>
        <span class="step-arrow">&#8594;</span>
        <div class="step {{ $step === 'review' ? 'active' : ($step === 'selesai' ? 'done' : '') }}" id="stepper-2">Review</div>
        <span class="step-arrow">&#8594;</span>
        <div class="step {{ $step === 'selesai' ? 'active' : '' }}" id="stepper-3">Selesai</div>
    </div>

    @if($step === 'selesai')
    {{-- ── STEP 3: SELESAI ── --}}
    <div class="pd-form-card" style="max-width:560px; text-align:center;">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="success-text">
            <h2>Data berhasil dikirim!</h2>
            <p>Data calon pembeli telah berhasil disubmit dan akan diproses oleh tim marketing.</p>
        </div>
        <div class="form-actions" style="justify-content:center; margin-top:24px;">
            <a href="{{ route('admin.pengisian-data') }}" class="btn-new">+ Tambah Data Baru</a>
            <a href="{{ route('admin.dashboard') }}" class="btn-back" style="text-decoration:none;">Kembali ke Dashboard</a>
        </div>
    </div>

    @else
    {{-- ── STEP 1: FORM DATA ── --}}
    <div class="pd-form-card" id="step-form">
        <form id="clientForm" novalidate>
            @csrf
            <div class="form-group">
                <label>Nama Lengkap <span class="req">*</span></label>
                <input type="text" id="f_nama" name="nama_lengkap"
                    value="{{ old('nama_lengkap') }}" placeholder="Masukan nama">
                <div class="err-msg" id="err_nama"></div>
            </div>
            <div class="form-group">
                <label>Email <span class="req">*</span></label>
                <input type="email" id="f_email" name="email"
                    value="{{ old('email') }}" placeholder="Masukan email">
                <div class="err-msg" id="err_email"></div>
            </div>
            <div class="form-group">
                <label>No KTP / NIK <span class="req">*</span></label>
                <input type="text" id="f_nik" name="nik"
                    value="{{ old('nik') }}" placeholder="Masukan nik (16 digit)" maxlength="16">
                <div class="err-msg" id="err_nik"></div>
            </div>
            <div class="form-group">
                <label>No Whatsapp <span class="req">*</span></label>
                <input type="text" id="f_wa" name="no_whatsapp"
                    value="{{ old('no_whatsapp') }}" placeholder="Masukan nomor">
                <div class="err-msg" id="err_wa"></div>
            </div>
            <div class="form-group">
                <label>Alamat <span class="req">*</span></label>
                <textarea id="f_alamat" name="alamat" placeholder="Masukan alamat">{{ old('alamat') }}</textarea>
                <div class="err-msg" id="err_alamat"></div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-batal" onclick="confirmBatal()">Batal</button>
                <button type="button" class="btn-tambah" onclick="goToReview()">tambah</button>
            </div>
        </form>
    </div>

    {{-- ── STEP 2: REVIEW ── --}}
    <div class="pd-form-card" id="step-review" style="display:none;">
        <p style="font-size:14px;color:#555;margin-bottom:16px;">Periksa kembali data sebelum dikonfirmasi.</p>
        <table class="review-table">
            <tr><td>Nama Lengkap</td><td id="rv_nama"></td></tr>
            <tr><td>Email</td><td id="rv_email"></td></tr>
            <tr><td>No KTP / NIK</td><td id="rv_nik"></td></tr>
            <tr><td>No WhatsApp</td><td id="rv_wa"></td></tr>
            <tr><td>Alamat</td><td id="rv_alamat"></td></tr>
        </table>

        {{-- Form POST nyata ke server --}}
        <form method="POST" action="{{ route('admin.pengisian-data.store') }}" id="submitForm">
            @csrf
            <input type="hidden" name="nama_lengkap" id="hd_nama">
            <input type="hidden" name="email"        id="hd_email">
            <input type="hidden" name="nik"          id="hd_nik">
            <input type="hidden" name="no_whatsapp"  id="hd_wa">
            <input type="hidden" name="alamat"       id="hd_alamat">
            <div class="form-actions">
                <button type="button" class="btn-back" onclick="backToForm()">&#8592; Kembali</button>
                <button type="submit" class="btn-confirm">Konfirmasi</button>
            </div>
        </form>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function goToReview() {
    let valid = true;

    function check(id, errId, label, extra) {
        const el  = document.getElementById(id);
        const err = document.getElementById(errId);
        const val = el.value.trim();
        if (!val) {
            err.textContent = label + ' wajib diisi.';
            el.classList.add('invalid');
            valid = false;
        } else if (extra && !extra(val)) {
            err.textContent = extra.msg;
            el.classList.add('invalid');
            valid = false;
        } else {
            err.textContent = '';
            el.classList.remove('invalid');
        }
        return val;
    }

    function emailOk(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
    emailOk.msg = 'Format email tidak valid.';

    function nikOk(v) { return /^\d{16}$/.test(v); }
    nikOk.msg = 'NIK harus 16 digit angka.';

    const nama   = check('f_nama',   'err_nama',   'Nama Lengkap');
    const email  = check('f_email',  'err_email',  'Email', emailOk);
    const nik    = check('f_nik',    'err_nik',    'No KTP / NIK', nikOk);
    const wa     = check('f_wa',     'err_wa',     'No WhatsApp');
    const alamat = check('f_alamat', 'err_alamat', 'Alamat');

    if (!valid) return;

    // Isi tabel review
    document.getElementById('rv_nama').textContent   = nama;
    document.getElementById('rv_email').textContent  = email;
    document.getElementById('rv_nik').textContent    = nik;
    document.getElementById('rv_wa').textContent     = wa;
    document.getElementById('rv_alamat').textContent = alamat;

    // Isi hidden inputs
    document.getElementById('hd_nama').value   = nama;
    document.getElementById('hd_email').value  = email;
    document.getElementById('hd_nik').value    = nik;
    document.getElementById('hd_wa').value     = wa;
    document.getElementById('hd_alamat').value = alamat;

    // Update stepper
    document.getElementById('stepper-1').classList.remove('active');
    document.getElementById('stepper-1').classList.add('done');
    document.getElementById('stepper-2').classList.add('active');

    document.getElementById('step-form').style.display   = 'none';
    document.getElementById('step-review').style.display = 'block';
}

function backToForm() {
    document.getElementById('stepper-2').classList.remove('active');
    document.getElementById('stepper-1').classList.remove('done');
    document.getElementById('stepper-1').classList.add('active');
    document.getElementById('step-form').style.display   = 'block';
    document.getElementById('step-review').style.display = 'none';
}

function confirmBatal() {
    if (confirm('Yakin ingin membatalkan pengisian data?')) {
        window.location.href = '{{ route("admin.dashboard") }}';
    }
}
</script>
@endpush
