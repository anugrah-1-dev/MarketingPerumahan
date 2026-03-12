@extends('layouts.app')
@section('title', 'Masuk – Bukit Shangrilla Asri')
@section('content')

    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-[520px]">

            {{-- Card --}}
            <div class="bg-white rounded-[24px] shadow-lg px-8 py-10 lg:px-12 lg:py-12">
                <h1 class="text-[28px] font-bold text-[#393939] mb-1">Masuk ke akun Anda</h1>
                <p class="text-[#676767] text-sm mb-8">Selamat datang kembali di Bukit Shangrilla Asri</p>

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


            </div>
        </div>
    </div>

@endsection
