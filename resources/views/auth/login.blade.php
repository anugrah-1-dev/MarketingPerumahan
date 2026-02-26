@extends('layouts.app')
@section('title', 'Masuk – Perumahan Zahra')
@section('content')

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-[520px]">

            {{-- Card --}}
            <div class="bg-white rounded-[24px] shadow-lg px-8 py-10 lg:px-12 lg:py-12">
                <h1 class="text-[28px] font-bold text-[#393939] mb-1">Masuk ke akun Anda</h1>
                <p class="text-[#676767] text-sm mb-8">Selamat datang kembali di Perumahan Zahra</p>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#393939] mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="input-field" required
                            autocomplete="email">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-semibold text-[#393939]">Password</label>
                            <a href="#" class="text-[#676767] text-xs hover:text-[#393939] transition-colors">forgot
                                password?</a>
                        </div>
                        <input type="password" name="password" placeholder="••••••••" class="input-field" required
                            autocomplete="current-password">
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-primary w-full mt-2 py-3 text-base">Masuk</button>
                </form>

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-6">
                    <hr class="flex-1 border-gray-200">
                    <span class="text-[#676767] text-sm">or</span>
                    <hr class="flex-1 border-gray-200">
                </div>

                {{-- Google Login --}}
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
                    Masuk dengan Google
                </button>

                {{-- Register link --}}
                <p class="text-center text-sm text-[#676767] mt-6">
                    Belum punya akun?
                    <a href="#" class="text-[#393939] font-semibold hover:underline">Daftar</a>
                </p>
            </div>
        </div>
    </div>

@endsection
