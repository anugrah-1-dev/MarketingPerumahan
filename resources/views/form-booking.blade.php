@extends('layouts.app')
@section('title', 'Form Booking – Perumahan Zahra')

@section('content')

    <div class="max-w-[900px] mx-auto px-6 pt-10 pb-20">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-[#676767] mb-8 flex items-center gap-2">
            <a href="{{ route('landing') }}" class="hover:text-[#393939]">Home</a>
            <span>/</span>
            <a href="{{ route('unit-tersedia') }}" class="hover:text-[#393939]">Unit Tersedia</a>
            <span>/</span>
            <a href="{{ route('detail-rumah', $blok) }}" class="hover:text-[#393939]">Blok {{ $blok }}</a>
            <span>/</span>
            <span class="text-[#393939] font-medium">Form Booking</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-[#676767] text-sm uppercase tracking-widest mb-1">Form</p>
            <h1 class="text-[#393939] text-[28px] lg:text-[36px] font-bold">Booking Unit</h1>
            <div class="mt-3 inline-block bg-[#393939] text-white text-sm font-semibold px-5 py-2 rounded-[12px]">
                Booking Unit {{ $blok }} - Tipe 54
            </div>
        </div>

        {{-- Progress Steps --}}
        <div class="flex items-center gap-3 mb-10">
            @foreach (['Data Diri', 'Pekerjaan', 'Konfirmasi'] as $i => $step)
                <div class="flex items-center gap-2">
                    <span
                        class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                {{ $i === 0 ? 'bg-[#393939] text-white' : 'bg-[#D9D9D9] text-[#676767]' }}">
                        {{ $i + 1 }}
                    </span>
                    <span
                        class="text-sm font-medium {{ $i === 0 ? 'text-[#393939]' : 'text-[#676767]' }}">{{ $step }}</span>
                </div>
                @if ($i < 2)
                    <div class="h-px flex-1 bg-[#D9D9D9]"></div>
                @endif
            @endforeach
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-[14px] p-4 mb-6">
                <p class="text-red-700 text-sm font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('store-booking') }}" class="space-y-8">
            @csrf

            {{-- ======== SECTION: Data Diri ======== --}}
            <div class="bg-white rounded-[20px] p-6 lg:p-8 shadow-sm">
                <h2 class="text-[#393939] text-lg font-bold mb-6 pb-3 border-b border-gray-100">Data Diri</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            placeholder="Masukkan nama lengkap sesuai KTP"
                            class="input-field @error('nama_lengkap') border-red-400 @enderror" required>
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                            class="input-field @error('email') border-red-400 @enderror" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            No. KTP/NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="16 digit NIK"
                            maxlength="16" class="input-field @error('no_ktp') border-red-400 @enderror" required>
                        @error('no_ktp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            No. WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                            placeholder="08xxxxxxxxxx" class="input-field @error('no_whatsapp') border-red-400 @enderror"
                            required>
                        @error('no_whatsapp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" rows="3" placeholder="Jl. ... No. ..., Kelurahan, Kecamatan, Kota/Kabupaten"
                            class="input-field resize-none @error('alamat') border-red-400 @enderror" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ======== SECTION: Pekerjaan & Penghasilan ======== --}}
            <div class="bg-white rounded-[20px] p-6 lg:p-8 shadow-sm">
                <h2 class="text-[#393939] text-lg font-bold mb-6 pb-3 border-b border-gray-100">Pekerjaan & Penghasilan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            Status Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <select name="status_pekerjaan"
                            class="input-field @error('status_pekerjaan') border-red-400 @enderror" required>
                            <option value="" disabled selected>Pilih status pekerjaan</option>
                            <option value="PNS/TNI/POLRI"
                                {{ old('status_pekerjaan') === 'PNS/TNI/POLRI' ? 'selected' : '' }}>PNS/TNI/POLRI
                            </option>
                            <option value="Karyawan Swasta"
                                {{ old('status_pekerjaan') === 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan
                                Swasta</option>
                            <option value="Wiraswasta/Pengusaha"
                                {{ old('status_pekerjaan') === 'Wiraswasta/Pengusaha' ? 'selected' : '' }}>Wiraswasta /
                                Pengusaha</option>
                            <option value="Profesional/Freelancer"
                                {{ old('status_pekerjaan') === 'Profesional/Freelancer' ? 'selected' : '' }}>Profesional /
                                Freelancer</option>
                            <option value="Lainnya"
                                {{ old('status_pekerjaan') === 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                        @error('status_pekerjaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            Nama Perusahaan / Instansi
                        </label>
                        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                            placeholder="Opsional" class="input-field">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">
                            Penghasilan / Bulan <span class="text-red-500">*</span>
                        </label>
                        <select name="penghasilan" class="input-field @error('penghasilan') border-red-400 @enderror"
                            required>
                            <option value="" disabled selected>Pilih kisaran penghasilan</option>
                            <option value="<3jt" {{ old('penghasilan') === '<3jt' ? 'selected' : '' }}>Di bawah Rp
                                3.000.000</option>
                            <option value="3-5jt" {{ old('penghasilan') === '3-5jt' ? 'selected' : '' }}>Rp 3.000.000
                                – Rp 5.000.000</option>
                            <option value="5-10jt" {{ old('penghasilan') === '5-10jt' ? 'selected' : '' }}>Rp 5.000.000
                                – Rp 10.000.000</option>
                            <option value="10-20jt" {{ old('penghasilan') === '10-20jt' ? 'selected' : '' }}>Rp
                                10.000.000 – Rp 20.000.000</option>
                            <option value=">20jt" {{ old('penghasilan') === '>20jt' ? 'selected' : '' }}>Di atas Rp
                                20.000.000</option>
                        </select>
                        @error('penghasilan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ======== Buttons ======== --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('detail-rumah', $blok) }}" class="btn-outline text-center px-10 py-3">Batal</a>
                <button type="submit" class="btn-primary px-10 py-3">Lanjut ke Pembayaran →</button>
            </div>

        </form>
    </div>

@endsection
