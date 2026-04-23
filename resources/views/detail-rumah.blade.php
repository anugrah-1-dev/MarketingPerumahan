@extends('layouts.app')
@section('title')
    Detail Rumah - Blok {{ $unit['blok'] ?? '' }} - Bukit Shangrilla Asri
@endsection

@section('content')
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 pb-20">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-[#676767] mb-8 flex items-center gap-2">
            <a href="{{ route('landing') }}" class="hover:text-[#393939]">Beranda</a>
            <span>/</span>
            <a href="{{ route('tipe-rumah.publik') }}" class="hover:text-[#393939]">Tipe Rumah</a>
            <span>/</span>
            <span class="text-[#393939] font-medium">Blok {{ $unit['blok'] }}</span>
        </nav>

        {{-- Title --}}
        <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[#676767] text-sm uppercase tracking-widest mb-1">Detail Rumah</p>
                <h1 class="text-[#393939] text-[28px] lg:text-[38px] font-bold">{{ $unit['nama'] }}</h1>
                <p class="text-[#676767] mt-2 max-w-[560px]">{{ $unit['deskripsi'] }}</p>
            </div>
            <span
                class="self-start lg:self-auto text-sm font-semibold px-4 py-2 rounded-full
            {{ $unit['status'] === 'Tersedia' ? 'status-tersedia' : 'status-terjual' }}">
                {{ $unit['status'] }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ====== LEFT: Images + Spesifikasi ====== --}}
            <div class="lg:col-span-2 space-y-7">

                {{-- Photo Gallery --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <p class="text-xs text-[#676767] mb-3">Tampilan rumah dari berbagai sisi untuk membantu Anda melihat
                        detail desain dan kualitas bangunan.</p>
                    @php
                        $fotos = $unit['gambar_list'] ?? [
                            ['url' => isset($unit['gambar']) ? $unit['gambar'] : 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80', 'keterangan' => 'Foto Utama'],
                            ['url' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600&q=80', 'keterangan' => 'Tampak Dalam'],
                            ['url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80', 'keterangan' => 'Tipe Jenis'],
                            ['url' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80', 'keterangan' => 'Area Sekitar'],
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($fotos as $foto)
                            <div>
                                <p class="text-sm font-semibold text-[#393939] mb-2">{{ $foto['keterangan'] }}</p>
                                <img src="{{ $foto['url'] }}"
                                    alt="{{ $foto['keterangan'] }}"
                                    class="w-full h-[200px] object-cover rounded-[12px]">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Spesifikasi --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h2 class="text-[#393939] text-lg font-bold mb-5"><i class="fas fa-clipboard-list text-[#676767]"></i> Spesifikasi</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                        @foreach ([['fa-bed', 'Kamar Tidur', $unit['kt'] . ' Kamar'], ['fa-bath', 'Kamar Mandi', $unit['km'] . ' Kamar'], ['fa-layer-group', 'Lantai', $unit['lantai'] . ' Lantai'], ['fa-car', 'Garasi', $unit['garasi'] . ' Mobil'], ['fa-certificate', 'Sertifikat', $unit['sertifikat']], ['fa-ruler-combined', 'Luas Bangunan', $unit['lb']], ['fa-tree', 'Luas Tanah', $unit['lt']]] as $spec)
                            <div class="bg-[#F7F7F7] rounded-[14px] p-4 text-center">
                                <p class="text-xl mb-1 text-[#393939]"><i class="fas {{ $spec[0] }}"></i></p>
                                <p class="text-xs text-[#676767]">{{ $spec[1] }}</p>
                                <p class="text-sm font-bold text-[#393939] mt-1">{{ $spec[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h2 class="text-[#393939] text-lg font-bold mb-4">Fasilitas</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($unit['fasilitas'] as $f)
                            <div class="flex items-center gap-2 text-sm text-[#393939]">
                                <span class="text-green-600 font-bold">&#10003;</span> {{ $f }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ====== RIGHT: Harga & Action ====== --}}
            <div class="space-y-6">

                {{-- Harga --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <p class="text-[#676767] text-sm mb-1">💰 Harga</p>
                    <p class="text-[#393939] text-3xl font-bold mb-5">{{ $unit['harga'] }}</p>

                    <div class="grid grid-cols-2 gap-3 mb-5">
                        @foreach ([['DP 0%', null], ['KPR', null], ['Inhouse 5 Tahun', null], ['Free PPN', null]] as $h)
                            <div class="bg-[#F0F7F2] rounded-[12px] p-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#0B5E41] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-sm font-semibold text-[#0B5E41]">{{ $h[0] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xs text-[#676767] mb-5">* Syarat dan ketentuan berlaku</p>

                    @if ($unit['status'] !== 'Tersedia')
                        <button
                            class="w-full bg-gray-200 text-gray-400 rounded-[25px] py-3 font-semibold cursor-not-allowed">
                            Unit Sudah Terjual
                        </button>
                    @endif
                </div>

                {{-- Status Info --}}
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h3 class="font-bold text-[#393939] mb-3">Informasi Unit</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Status</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['status'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">KT</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['kt'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">KM</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['km'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Lantai</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['lantai'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Garasi</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['garasi'] }} Mobil</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">Sertifikat</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['sertifikat'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">LB</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['lb'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#676767]">LT</span>
                            <span class="font-semibold text-[#393939]">{{ $unit['lt'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                @php
                    $waRawDetail = $agent['wa'] ?? '6283876766055';
                    $waNomorDetail = preg_replace('/\D/', '', $waRawDetail);
                    $waNomorDetail = preg_replace('/^0/', '62', $waNomorDetail);
                    if (!str_starts_with($waNomorDetail, '62')) { $waNomorDetail = '62' . $waNomorDetail; }
                    $waNamaDetail = $agent['nama'] ?? 'Admin';
                    $waPesanDetail = urlencode("Halo, saya tertarik dengan unit " . $unit['nama'] . " di Bukit Shangrilla Asri.");
                    $waUrlDetail = "https://wa.me/{$waNomorDetail}?text={$waPesanDetail}";
                @endphp
                <div class="bg-[#393939] rounded-[20px] p-6 text-white">
                    <h3 class="font-bold mb-2">Butuh Bantuan?</h3>
                    <p class="text-gray-300 text-sm mb-4">Tim kami siap membantu Anda memilih unit terbaik.</p>
                    <a href="{{ $waUrlDetail }}" target="_blank"
                        class="block w-full bg-white text-[#393939] text-center font-semibold rounded-[25px] py-3 hover:bg-gray-100 transition-colors cursor-pointer border-0 no-underline">
                        📞 Chat {{ $waNamaDetail }} via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ================================================================
     POPUP FORM DETAIL - Kumpulkan nama & HP sebelum membuka WhatsApp
     ================================================================ --}}
    <div id="wa-detail-modal"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden"
         style="background:rgba(0,0,0,0.55);"
         onclick="closeDetailWaPopup(event)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative" onclick="event.stopPropagation()">
            <button type="button" onclick="closeDetailWaPopupDirect()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none border-0 bg-transparent cursor-pointer"
                    aria-label="Tutup">&times;</button>

            <div class="flex items-center gap-3 mb-4">
                <div class="bg-[#25D366] rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="white" class="w-6 h-6">
                        <path d="M16.003 2C8.28 2 2 8.28 2 16.003c0 2.46.666 4.843 1.93 6.93L2 30l7.27-1.904A13.938 13.938 0 0016.003 30C23.72 30 30 23.72 30 16.003 30 8.28 23.72 2 16.003 2zm0 25.447a11.93 11.93 0 01-6.09-1.666l-.437-.26-4.316 1.13 1.153-4.204-.284-.45A11.938 11.938 0 014.063 16.003c0-6.582 5.356-11.94 11.94-11.94 6.583 0 11.94 5.358 11.94 11.94 0 6.583-5.357 11.944-11.94 11.944zm6.54-8.942c-.357-.18-2.114-1.043-2.443-1.163-.328-.12-.566-.18-.804.18-.238.358-.924 1.163-1.133 1.402-.208.24-.417.27-.775.09-.357-.18-1.504-.554-2.865-1.77-1.058-.946-1.773-2.116-1.98-2.473-.208-.358-.022-.55.156-.729.16-.16.358-.417.536-.625.18-.208.24-.358.358-.596.12-.24.06-.447-.03-.626-.09-.18-.803-1.938-1.1-2.653-.29-.697-.584-.6-.804-.61-.207-.01-.447-.012-.685-.012-.238 0-.625.09-.953.447-.328.358-1.25 1.22-1.25 2.978 0 1.757 1.28 3.455 1.46 3.694.178.238 2.52 3.847 6.103 5.394.854.37 1.52.59 2.04.756.857.272 1.638.234 2.254.142.688-.102 2.114-.864 2.413-1.7.298-.835.298-1.549.208-1.7-.09-.149-.328-.238-.685-.417z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base leading-tight" id="wa-detail-modal-title">Hubungi Agen</h3>
                    <p class="text-gray-500 text-xs">via WhatsApp</p>
                </div>
            </div>

            <p class="text-gray-600 text-sm mb-4">Silakan isi data Anda agar kami bisa menghubungi Anda kembali.</p>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="wa-detail-name" placeholder="Contoh: Budi Santoso"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="tel" id="wa-detail-phone" placeholder="Contoh: 08123456789"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent">
                </div>
                <p id="wa-detail-error" class="text-red-500 text-xs hidden">Mohon isi nama dan nomor HP terlebih dahulu.</p>
            </div>

            <button type="button" onclick="submitDetailWaPopup()"
                    class="mt-5 w-full bg-[#25D366] hover:bg-[#1ebe5d] text-white font-semibold py-3 rounded-xl
                           transition-colors duration-200 cursor-pointer border-0 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="w-5 h-5">
                    <path d="M16.003 2C8.28 2 2 8.28 2 16.003c0 2.46.666 4.843 1.93 6.93L2 30l7.27-1.904A13.938 13.938 0 0016.003 30C23.72 30 30 23.72 30 16.003 30 8.28 23.72 2 16.003 2zm0 25.447a11.93 11.93 0 01-6.09-1.666l-.437-.26-4.316 1.13 1.153-4.204-.284-.45A11.938 11.938 0 014.063 16.003c0-6.582 5.356-11.94 11.94-11.94 6.583 0 11.94 5.358 11.94 11.94 0 6.583-5.357 11.944-11.94 11.944zm6.54-8.942c-.357-.18-2.114-1.043-2.443-1.163-.328-.12-.566-.18-.804.18-.238.358-.924 1.163-1.133 1.402-.208.24-.417.27-.775.09-.357-.18-1.504-.554-2.865-1.77-1.058-.946-1.773-2.116-1.98-2.473-.208-.358-.022-.55.156-.729.16-.16.358-.417.536-.625.18-.208.24-.358.358-.596.12-.24.06-.447-.03-.626-.09-.18-.803-1.938-1.1-2.653-.29-.697-.584-.6-.804-.61-.207-.01-.447-.012-.685-.012-.238 0-.625.09-.953.447-.328.358-1.25 1.22-1.25 2.978 0 1.757 1.28 3.455 1.46 3.694.178.238 2.52 3.847 6.103 5.394.854.37 1.52.59 2.04.756.857.272 1.638.234 2.254.142.688-.102 2.114-.864 2.413-1.7.298-.835.298-1.549.208-1.7-.09-.149-.328-.238-.685-.417z"/>
                </svg>
                Chat Sekarang
            </button>

            <button type="button" onclick="skipDetailWaPopup()"
                    class="mt-2 w-full text-gray-400 hover:text-gray-600 text-sm py-2 bg-transparent border-0 cursor-pointer">
                Lewati, langsung chat →
            </button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let _detailWaUrl  = null;

    function openDetailWaPopup(waUrl, agentName) {
        _detailWaUrl = waUrl;
        document.getElementById('wa-detail-name').value  = '';
        document.getElementById('wa-detail-phone').value = '';
        document.getElementById('wa-detail-error').classList.add('hidden');
        document.getElementById('wa-detail-modal-title').textContent = 'Hubungi ' + agentName;
        document.getElementById('wa-detail-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('wa-detail-name').focus(), 100);
    }

    function closeDetailWaPopupDirect() {
        document.getElementById('wa-detail-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function closeDetailWaPopup(event) {
        if (event.target === document.getElementById('wa-detail-modal')) {
            closeDetailWaPopupDirect();
        }
    }

    function submitDetailWaPopup() {
        const name  = document.getElementById('wa-detail-name').value.trim();
        const phone = document.getElementById('wa-detail-phone').value.trim();
        const errEl = document.getElementById('wa-detail-error');

        if (!name || !phone) {
            errEl.classList.remove('hidden');
            return;
        }
        errEl.classList.add('hidden');

        const getCookieVal = (n) => {
            const v = document.cookie.split('; ').find(r => r.startsWith(n + '='));
            return v ? decodeURIComponent(v.split('=')[1]) : null;
        };
        const refCode = getCookieVal('affiliate_ref_code') || null;

        fetch('{{ route("wa-click.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({
                slug:          null,
                referral_code: refCode,
                page_url:      window.location.href,
                sender_name:   name,
                sender_phone:  phone
            }),
            keepalive: true
        }).catch(err => console.error("Gagal mencatat klik WA:", err));

        closeDetailWaPopupDirect();
        window.open(_detailWaUrl, '_blank');
    }

    function skipDetailWaPopup() {
        const getCookieVal = (n) => {
            const v = document.cookie.split('; ').find(r => r.startsWith(n + '='));
            return v ? decodeURIComponent(v.split('=')[1]) : null;
        };
        const refCode = getCookieVal('affiliate_ref_code') || null;
        fetch('{{ route("wa-click.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({
                slug:          null,
                referral_code: refCode,
                page_url:      window.location.href
            }),
            keepalive: true
        }).catch(err => console.error("Gagal mencatat klik WA:", err));
        closeDetailWaPopupDirect();
        window.open(_detailWaUrl, '_blank');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDetailWaPopupDirect();
    });
</script>
@endsection


