@extends('layouts.app')
@section('title', 'Unit Tersedia – Perumahan Zahra')

@section('head')
    <style>
        .unit-card.tersedia {
            border: 2px solid #D1FAE5;
        }

        .unit-card.booking {
            border: 2px solid #FEF3C7;
        }

        .unit-card.terjual {
            border: 2px solid #FEE2E2;
            opacity: .7;
            pointer-events: none;
        }

        .unit-card {
            transition: transform .15s, box-shadow .15s;
        }

        .unit-card:not(.terjual):hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
        }

        .filter-btn {
            border-radius: 25px;
            padding: 8px 22px;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid #D9D9D9;
            transition: all .2s;
            cursor: pointer;
            background: #fff;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: #393939;
            color: #fff;
            border-color: #393939;
        }
    </style>
@endsection

@section('content')

    {{-- Page Header --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 pb-6">
        <h1 class="text-[#393939] text-[32px] lg:text-[44px] font-bold mb-2">Temukan Unit Rumah yang<br
                class="hidden lg:block"> Tersedia Hari Ini</h1>
        <p class="text-[#676767] text-[16px]">Pilih unit favoritmu dan segera lakukan booking.</p>
    </section>

    {{-- Summary Cards --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([['label' => 'Total Unit', 'value' => '86', 'color' => 'bg-[#393939] text-white'], ['label' => 'Tersedia', 'value' => '40', 'color' => 'bg-[#D1FAE5] text-[#065F46]'], ['label' => 'Terjual', 'value' => '20', 'color' => 'bg-[#FEE2E2] text-[#991B1B]'], ['label' => 'Booking', 'value' => '26', 'color' => 'bg-[#FEF3C7] text-[#92400E]']] as $s)
                <div class="rounded-[20px] {{ $s['color'] }} p-5 text-center shadow-sm">
                    <p class="text-3xl font-bold">{{ $s['value'] }}</p>
                    <p class="text-sm mt-1 font-medium">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Keterangan + Filter --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-8 flex flex-wrap items-center justify-between gap-4">
        {{-- Legend --}}
        <div class="flex flex-wrap items-center gap-5">
            <span class="text-[#393939] font-semibold text-sm">Keterangan:</span>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-sm bg-[#D1FAE5] inline-block border border-[#065F46]"></span>
                <span class="text-xs text-[#676767]">Tersedia – Unit siap dibeli</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-sm bg-[#FEF3C7] inline-block border border-[#92400E]"></span>
                <span class="text-xs text-[#676767]">Booking – Sedang dalam proses</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-sm bg-[#FEE2E2] inline-block border border-[#991B1B]"></span>
                <span class="text-xs text-[#676767]">Terjual – Unit sudah terjual</span>
            </div>
        </div>
        {{-- Filter Buttons --}}
        <div class="flex flex-wrap gap-2">
            <button class="filter-btn active" data-filter="semua">Semua</button>
            <button class="filter-btn" data-filter="tersedia">Tersedia</button>
            <button class="filter-btn" data-filter="booking">Booking</button>
            <button class="filter-btn" data-filter="terjual">Terjual</button>
        </div>
    </section>

    {{-- Unit Grid --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-20">
        <div id="unitGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($units as $u)
                <div class="unit-card bg-white rounded-[16px] p-4 cursor-pointer {{ $u['status'] }}"
                    data-status="{{ $u['status'] }}"
                    @if ($u['status'] !== 'terjual') onclick="window.location='{{ route('detail-rumah', $u['blok']) }}'" @endif>
                    <div class="text-center">
                        <p class="text-[#393939] font-bold text-lg">{{ $u['blok'] }}</p>
                        <p class="text-[#676767] text-xs mb-2">{{ $u['tipe'] }}</p>
                        <p class="text-[#393939] text-xs font-semibold">{{ $u['harga'] }}</p>
                        <span
                            class="mt-2 inline-block text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize
                    {{ $u['status'] === 'tersedia' ? 'status-tersedia' : ($u['status'] === 'booking' ? 'status-booking' : 'status-terjual') }}">
                            {{ ucfirst($u['status']) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;
                document.querySelectorAll('.unit-card').forEach(card => {
                    card.style.display = (filter === 'semua' || card.dataset.status === filter) ?
                        '' : 'none';
                });
            });
        });
    </script>
@endsection
