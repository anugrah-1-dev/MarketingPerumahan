@extends('layouts.app')
@section('title', 'Daftar – Perumahan Zahra')
@section('content')

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-[520px]">

            {{-- Card --}}
            <div class="bg-white rounded-[24px] shadow-lg px-8 py-10 lg:px-12 lg:py-12">
                <h1 class="text-[28px] font-bold text-[#393939] mb-1">Daftar Akun Baru</h1>
                <p class="text-[#676767] text-sm mb-8">Bergabunglah dengan Perumahan Zahra</p>

                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" class="input-field" required
                            autocomplete="name" autofocus>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="input-field" required
                            autocomplete="email">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Daftar Sebagai</label>
                        <select name="role" class="input-field" required>
                            <option value="" disabled selected>Pilih peran Anda</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Admin</option>
                            <option value="affiliate" {{ old('role') == 'affiliate' ? 'selected' : '' }}>Affiliate</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Password</label>
                        <input type="password" name="password" placeholder="••••••••" class="input-field" required
                            autocomplete="new-password">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" class="input-field" required
                            autocomplete="new-password">
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-primary w-full mt-2 py-3 text-base">Daftar</button>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-6">
                    <hr class="flex-1 border-gray-200">
                    <span class="text-[#676767] text-sm">or</span>
                    <hr class="flex-1 border-gray-200">
                </div>

                {{-- Google Login (Opsional, dibiarkan seperti login) --}}
                <button
                    class="w-full border-2 border-[#D9D9D9] rounded-[25px] py-3 flex items-center justify-center gap-3 font-medium text-[#393939] hover:border-[#393939] transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    Daftar dengan Google
                </button>

                {{-- Login link --}}
                <p class="text-center text-sm text-[#676767] mt-6">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#393939] font-semibold hover:underline">Masuk</a>
                </p>
            </div>
        </div>
    </div>

@endsection
