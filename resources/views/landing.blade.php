@extends('layouts.app')

@section('title', 'Perumahan Zahra – Pilihan Rumah Terbaik')

@php
    // Bangun URL & pesan WhatsApp berdasarkan agent yang aktif
    $waPeran  = $agent['jabatan'] ?? 'Marketing';
    $waNama   = $agent['nama'] ?? 'Tim Kami';
    $waRaw    = $agent['wa'] ?? '6283876766055';

    // Normalisasi nomor ke format internasional:
    // 1. Hapus SEMUA karakter bukan angka (spasi, -, (, ), +, dll)
    // 2. Ganti awalan 0 → 62
    // 3. Jika belum diawali 62, tambahkan di depan
    $waNomor = preg_replace('/\D/', '', $waRaw);          // hapus semua non-digit
    $waNomor = preg_replace('/^0/', '62', $waNomor);      // 081xxx → 6281xxx
    if (!str_starts_with($waNomor, '62')) {
        $waNomor = '62' . $waNomor;                       // 81xxx → 6281xxx
    }

    $waPesan  = urlencode("Halo, saya tertarik dengan Perumahan Zahra. Saya dari website - PIC {$waNama}.");
    $waUrl    = "https://wa.me/{$waNomor}?text={$waPesan}";
@endphp

@section('content')

    {{-- ================================================================
     HERO SECTION
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 lg:pt-16 pb-20 relative overflow-hidden">

        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-10">

            {{-- Left: Text --}}
            <div class="w-full lg:w-1/2 text-center lg:text-left z-10">
                <p class="text-[#676767] text-[15px] font-medium uppercase tracking-widest mb-4">Perumahan Zahra</p>
                <h1 class="text-[#393939] text-[40px] lg:text-[56px] font-bold leading-tight mb-5">
                    Pilihan Rumah Terbaik<br class="hidden lg:block"> untuk Keluarga Anda
                </h1>
                <p
                    class="text-[#676767] text-[17px] lg:text-[19px] font-medium leading-8 max-w-[500px] mx-auto lg:mx-0 mb-8">
                    Berbagai tipe rumah dengan desain modern dan harga terjangkau.
                    Lihat ketersediaan unit, cek lokasi, dan pesan sekarang juga.
                </p>

            </div>

            {{-- Right: Image --}}
            <div class="w-full lg:w-1/2 relative flex justify-center">
                <div class="absolute right-0 top-[-20px] w-[90%] h-[105%] bg-[#D9D9D9] rounded-[20px] z-0 hidden lg:block">
                </div>
                <img src="{{ asset('images/hero.png') }}"
                    onerror="this.src='https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80';this.onerror=null;"
                    alt="Rumah Impian"
                    class="relative z-10 w-[85%] lg:w-[95%] rounded-[15px] object-cover shadow-2xl hover:scale-[1.01] transition-transform duration-700">
            </div>

        </div>

        {{-- Stats bar --}}
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([['86', 'Total Unit'], ['40', 'Unit Tersedia'], ['20', 'Unit Terjual'], ['26', 'Unit Booking']] as $stat)
                <div class="card text-center shadow-sm">
                    <p class="text-3xl font-bold text-[#393939]">{{ $stat[0] }}</p>
                    <p class="text-[#676767] text-sm mt-1">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================
     DILIHAT BARU-BARU INI
     ================================================================ --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-20">
        <h2 class="text-[#393939] text-[28px] lg:text-[36px] font-bold mb-2">Unit Perumahan</h2>
        <p class="text-[#676767] text-[15px] mb-8">Pilih unit yang sesuai kebutuhan keluarga Anda</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([['blok' => 'A1', 'tipe' => 'Tipe 36/72', 'harga' => 'Rp 310.000.000', 'status' => 'tersedia', 'img' => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80'], ['blok' => 'B3', 'tipe' => 'Tipe 45/90', 'harga' => 'Rp 390.000.000', 'status' => 'tersedia', 'img' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80'], ['blok' => 'C3', 'tipe' => 'Tipe 54/108', 'harga' => 'Rp 350.000.000', 'status' => 'booking', 'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80']] as $u)
                <a href="{{ route('detail-rumah', $u['blok']) }}" class="block bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="relative h-[200px] overflow-hidden">
                        <img src="{{ $u['img'] }}" alt="Unit {{ $u['blok'] }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span
                            class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-full capitalize
                    {{ $u['status'] === 'tersedia' ? 'status-tersedia' : ($u['status'] === 'booking' ? 'status-booking' : 'status-terjual') }}">
                            {{ ucfirst($u['status']) }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-[#676767] mb-1">Blok {{ $u['blok'] }} · {{ $u['tipe'] }}</p>
                        <p class="text-lg font-bold text-[#393939] mb-3">{{ $u['harga'] }}</p>
                        <span class="btn-primary text-sm w-full block text-center">Lihat Detail</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>





    {{-- ================================================================
     FLOATING WHATSAPP BUTTON – dinamis berdasarkan agent di URL
     ================================================================ --}}
    <a  href="{{ $waUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        title="Chat dengan {{ $waNama }}"
        id="wa-float-btn"
        onclick="recordWaClick(event, '{{ $waUrl }}', '{{ $agent['slug'] ?? '' }}')"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-[#25D366] text-white
               px-5 py-3 rounded-full shadow-2xl
               hover:bg-[#1ebe5d] hover:scale-105 active:scale-95
               transition-all duration-200 group">

        {{-- Ikon WA --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor"
             class="w-6 h-6 shrink-0">
            <path d="M16.003 2C8.28 2 2 8.28 2 16.003c0 2.46.666 4.843 1.93 6.93L2 30l7.27-1.904A13.938 13.938 0 0016.003 30C23.72 30 30 23.72 30 16.003 30 8.28 23.72 2 16.003 2zm0 25.447a11.93 11.93 0 01-6.09-1.666l-.437-.26-4.316 1.13 1.153-4.204-.284-.45A11.938 11.938 0 014.063 16.003c0-6.582 5.356-11.94 11.94-11.94 6.583 0 11.94 5.358 11.94 11.94 0 6.583-5.357 11.944-11.94 11.944zm6.54-8.942c-.357-.18-2.114-1.043-2.443-1.163-.328-.12-.566-.18-.804.18-.238.358-.924 1.163-1.133 1.402-.208.24-.417.27-.775.09-.357-.18-1.504-.554-2.865-1.77-1.058-.946-1.773-2.116-1.98-2.473-.208-.358-.022-.55.156-.729.16-.16.358-.417.536-.625.18-.208.24-.358.358-.596.12-.24.06-.447-.03-.626-.09-.18-.803-1.938-1.1-2.653-.29-.697-.584-.6-.804-.61-.207-.01-.447-.012-.685-.012-.238 0-.625.09-.953.447-.328.358-1.25 1.22-1.25 2.978 0 1.757 1.28 3.455 1.46 3.694.178.238 2.52 3.847 6.103 5.394.854.37 1.52.59 2.04.756.857.272 1.638.234 2.254.142.688-.102 2.114-.864 2.413-1.7.298-.835.298-1.549.208-1.7-.09-.149-.328-.238-.685-.417z"/>
        </svg>

        {{-- Label --}}
        <span class="font-semibold text-sm leading-tight">
            Chat {{ $waNama }}
        </span>
    </a>

    <script>
    function recordWaClick(e, waUrl, slug) {
        e.preventDefault();
        // Kirim data ke backend (fire-and-forget), lalu buka WA
        fetch('{{ route("wa-click.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({
                slug: slug || null,
                page_url: window.location.href
            })
        }).catch(() => {}).finally(() => {
            window.open(waUrl, '_blank', 'noopener,noreferrer');
        });
    }
    </script>

@endsection

@section('scripts')

@endsection
