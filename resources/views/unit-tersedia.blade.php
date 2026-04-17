@extends('layouts.app')
@section('title', 'Unit Tersedia – Bukit Shangrilla Asri')

@section('head')
<style>
    .filter-btn {
        border-radius: 25px;
        padding: 8px 22px;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid #D9D9D9;
        transition: all .2s;
        cursor: pointer;
        background: #fff;
        color: #393939;
    }
    .filter-btn.active,
    .filter-btn:hover {
        background: #393939;
        color: #fff;
        border-color: #393939;
    }
    .unit-card {
        transition: transform .2s, box-shadow .2s;
    }
    .unit-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,.12);
    }
    .unit-card.terjual {
        opacity: .65;
        pointer-events: none;
    }
</style>
@endsection

@section('content')

    {{-- Page Header --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pt-10 pb-6">
        <p class="text-[#676767] text-[14px] font-medium uppercase tracking-widest mb-2">Bukit Shangrilla Asri</p>
        <h1 class="text-[#393939] text-[32px] lg:text-[44px] font-bold mb-2">Unit Perumahan Tersedia</h1>
        <p class="text-[#676767] text-[16px]">Pilih unit favoritmu dan segera hubungi kami sebelum kehabisan.</p>
    </section>

    {{-- Summary Stats --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-10">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ([
                ['label' => 'Total Unit',  'value' => '86', 'color' => 'bg-[#393939] text-white'],
                ['label' => 'Tersedia',    'value' => '66', 'color' => 'bg-[#D1FAE5] text-[#065F46]'],
                ['label' => 'Terjual',     'value' => '20', 'color' => 'bg-[#FEE2E2] text-[#991B1B]'],
            ] as $s)
                <div class="rounded-[20px] {{ $s['color'] }} p-5 text-center shadow-sm">
                    <p class="text-3xl font-bold">{{ $s['value'] }}</p>
                    <p class="text-sm mt-1 font-medium">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Filter Buttons --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] mb-8 flex flex-wrap items-center gap-3">
        <span class="text-[#393939] font-semibold text-sm mr-1">Filter:</span>
        <button class="filter-btn active" data-filter="semua">Semua</button>
        <button class="filter-btn" data-filter="tersedia">Tersedia</button>
        <button class="filter-btn" data-filter="terjual">Terjual</button>
    </section>

    {{-- Unit Cards Grid --}}
    <section class="max-w-[1440px] mx-auto px-6 lg:px-[80px] pb-24">
        <div id="unitGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @php
            $allUnits = [
                ['blok'=>'A1','tipe'=>'Tipe 36/72', 'harga'=>'Rp 310.000.000','status'=>'tersedia','kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80'],
                ['blok'=>'A2','tipe'=>'Tipe 36/72', 'harga'=>'Rp 312.000.000','status'=>'tersedia','kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80'],
                ['blok'=>'A3','tipe'=>'Tipe 36/72', 'harga'=>'Rp 315.000.000','status'=>'tersedia', 'kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80'],
                ['blok'=>'A4','tipe'=>'Tipe 36/72', 'harga'=>'Rp 310.000.000','status'=>'terjual','kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600&q=80'],
                ['blok'=>'B1','tipe'=>'Tipe 45/90', 'harga'=>'Rp 390.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80'],
                ['blok'=>'B2','tipe'=>'Tipe 45/90', 'harga'=>'Rp 392.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&q=80'],
                ['blok'=>'B3','tipe'=>'Tipe 45/90', 'harga'=>'Rp 395.000.000','status'=>'tersedia', 'kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80'],
                ['blok'=>'B4','tipe'=>'Tipe 45/90', 'harga'=>'Rp 390.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80'],
                ['blok'=>'B5','tipe'=>'Tipe 45/90', 'harga'=>'Rp 390.000.000','status'=>'terjual','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80'],
                ['blok'=>'C1','tipe'=>'Tipe 54/108','harga'=>'Rp 450.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&q=80'],
                ['blok'=>'C2','tipe'=>'Tipe 54/108','harga'=>'Rp 455.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80'],
                ['blok'=>'C3','tipe'=>'Tipe 54/108','harga'=>'Rp 350.000.000','status'=>'tersedia', 'kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80'],
                ['blok'=>'C4','tipe'=>'Tipe 54/108','harga'=>'Rp 460.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80'],
                ['blok'=>'C5','tipe'=>'Tipe 54/108','harga'=>'Rp 462.000.000','status'=>'terjual','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80'],
                ['blok'=>'D1','tipe'=>'Tipe 60/120','harga'=>'Rp 520.000.000','status'=>'tersedia','kt'=>4,'km'=>2,'img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&q=80'],
                ['blok'=>'D2','tipe'=>'Tipe 60/120','harga'=>'Rp 525.000.000','status'=>'tersedia','kt'=>4,'km'=>2,'img'=>'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80'],
                ['blok'=>'D3','tipe'=>'Tipe 60/120','harga'=>'Rp 530.000.000','status'=>'tersedia', 'kt'=>4,'km'=>2,'img'=>'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80'],
                ['blok'=>'D4','tipe'=>'Tipe 60/120','harga'=>'Rp 520.000.000','status'=>'terjual','kt'=>4,'km'=>2,'img'=>'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80'],
                ['blok'=>'D5','tipe'=>'Tipe 60/120','harga'=>'Rp 535.000.000','status'=>'tersedia','kt'=>4,'km'=>2,'img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&q=80'],
                ['blok'=>'E1','tipe'=>'Tipe 70/140','harga'=>'Rp 620.000.000','status'=>'tersedia','kt'=>4,'km'=>3,'img'=>'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600&q=80'],
                ['blok'=>'E2','tipe'=>'Tipe 70/140','harga'=>'Rp 625.000.000','status'=>'tersedia','kt'=>4,'km'=>3,'img'=>'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80'],
                ['blok'=>'E3','tipe'=>'Tipe 70/140','harga'=>'Rp 630.000.000','status'=>'tersedia', 'kt'=>4,'km'=>3,'img'=>'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=600&q=80'],
                ['blok'=>'E4','tipe'=>'Tipe 70/140','harga'=>'Rp 620.000.000','status'=>'terjual','kt'=>4,'km'=>3,'img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80'],
                ['blok'=>'F1','tipe'=>'Tipe 36/72', 'harga'=>'Rp 308.000.000','status'=>'tersedia','kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80'],
                ['blok'=>'F2','tipe'=>'Tipe 36/72', 'harga'=>'Rp 308.000.000','status'=>'tersedia','kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&q=80'],
                ['blok'=>'F3','tipe'=>'Tipe 36/72', 'harga'=>'Rp 309.000.000','status'=>'tersedia', 'kt'=>2,'km'=>1,'img'=>'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80'],
                ['blok'=>'G1','tipe'=>'Tipe 45/90', 'harga'=>'Rp 388.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80'],
                ['blok'=>'G2','tipe'=>'Tipe 45/90', 'harga'=>'Rp 390.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600&q=80'],
                ['blok'=>'G3','tipe'=>'Tipe 45/90', 'harga'=>'Rp 395.000.000','status'=>'terjual','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&q=80'],
                ['blok'=>'H1','tipe'=>'Tipe 54/108','harga'=>'Rp 448.000.000','status'=>'tersedia','kt'=>3,'km'=>2,'img'=>'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600&q=80'],
            ];
            @endphp

            @foreach ($allUnits as $u)
                @if ($u['status'] !== 'terjual')
                    <a href="{{ route('detail-rumah', $u['blok']) }}"
                       data-status="{{ $u['status'] }}"
                       class="unit-card block bg-white rounded-[20px] overflow-hidden shadow-sm group">
                @else
                    <div data-status="{{ $u['status'] }}"
                         class="unit-card terjual bg-white rounded-[20px] overflow-hidden shadow-sm">
                @endif

                        {{-- Photo --}}
                        <div class="relative h-[190px] overflow-hidden">
                            <img src="{{ $u['img'] }}" alt="Unit {{ $u['blok'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-full capitalize
                                {{ $u['status'] === 'tersedia' ? 'status-tersedia' : 'status-terjual' }}">
                                {{ ucfirst($u['status']) }}
                            </span>
                        </div>

                        {{-- Info --}}
                        <div class="p-5">
                            <p class="text-xs text-[#676767] mb-1">Blok {{ $u['blok'] }} · {{ $u['tipe'] }}</p>
                            <p class="text-lg font-bold text-[#393939] mb-2">{{ $u['harga'] }}</p>
                            <div class="flex items-center gap-3 text-xs text-[#676767] mb-4">
                                <span><i class="fas fa-bed"></i> {{ $u['kt'] }} KT</span>
                                <span><i class="fas fa-bath"></i> {{ $u['km'] }} KM</span>
                            </div>
                            @if ($u['status'] !== 'terjual')
                                <span class="btn-primary text-sm w-full block text-center">Lihat Detail</span>
                            @else
                                <span class="w-full block text-center text-sm font-semibold px-4 py-2 rounded-[25px] bg-gray-100 text-gray-400 cursor-not-allowed">Unit Terjual</span>
                            @endif
                        </div>

                @if ($u['status'] !== 'terjual')
                    </a>
                @else
                    </div>
                @endif
            @endforeach

        </div>

        {{-- Empty state (hidden by default, shown by JS when filter returns 0 results) --}}
        <p id="emptyState" class="hidden text-center text-[#676767] py-16 text-lg">Tidak ada unit yang sesuai filter.</p>
    </section>

@endsection

@section('scripts')
<script>
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards      = document.querySelectorAll('#unitGrid [data-status]');
    const empty      = document.getElementById('emptyState');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            let visible  = 0;

            cards.forEach(card => {
                const show = filter === 'semua' || card.dataset.status === filter;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            empty.classList.toggle('hidden', visible > 0);
        });
    });
</script>
@endsection
