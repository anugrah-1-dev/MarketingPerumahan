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
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="••••••••" class="input-field pr-11" required
                                autocomplete="current-password">
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-3 flex items-center text-[#676767] hover:text-[#393939] transition-colors"
                                onclick="togglePassword()">
                                {{-- Eye open --}}
                                <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{-- Eye slash --}}
                                <svg id="icon-eye-slash" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.082-3.416M6.53 6.53A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.972 9.972 0 01-4.073 5.373M15 12a3 3 0 00-3-3m0 0a3 3 0 00-2.121.879M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-primary w-full mt-2 py-3 text-base">Masuk</button>
                </form>


            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('icon-eye');
        const eyeSlash = document.getElementById('icon-eye-slash');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeSlash.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeSlash.classList.add('hidden');
        }
    }
</script>
@endsection
