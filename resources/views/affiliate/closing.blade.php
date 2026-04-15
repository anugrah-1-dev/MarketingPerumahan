@extends('layouts.affiliate')
@section('title', 'Daftar Penutupan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/closing.css') }}?v={{ file_exists(public_path('assets/affiliate/css/closing.css')) ? filemtime(public_path('assets/affiliate/css/closing.css')) : '1.0' }}">
@endpush

@section('content')

    <!-- Header -->
    <div class="page-title">
        <h1>Daftar Penutupan</h1>
        <p>Transaksi berhasil dari prospek yang masuk lewat link marketing Anda</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card pink">
            <div class="stat-label">Total Penutupan</div>
            <div class="stat-value">{{ $stats['total_closing'] }}</div>
            <span class="stat-badge badge-blue">Sepanjang waktu</span>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Penutupan Bulan Ini</div>
            <div class="stat-value">{{ $stats['closing_bulan_ini'] }}</div>
            <span class="stat-badge badge-blue">{{ now()->translatedFormat('F Y') }}</span>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Tarif Komisi</div>
            <div class="stat-value">{{ $commissionRate }}%</div>
            <span class="stat-badge badge-blue">Per penutupan</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-search">
            <span class="icon"><i class="fas fa-search"></i></span>
            <input type="text" id="closingSearch" placeholder="Cari nama, no HP..." oninput="filterClosing()">
        </div>
        <select class="filter-select-el" id="filterTipe" onchange="filterClosing()">
            <option value="">Semua Tipe Unit</option>
            @foreach($closings->pluck('tipeRumah')->filter()->unique('id') as $tipe)
                <option value="{{ $tipe->nama_tipe }}">{{ $tipe->nama_tipe }}</option>
            @endforeach
        </select>
        <select class="filter-select-el" id="filterPeriod" onchange="filterClosing()">
            <option value="">Semua Periode</option>
            <option value="30">30 Hari Terakhir</option>
            <option value="90">90 Hari Terakhir</option>
            <option value="365">1 Tahun Terakhir</option>
        </select>
        <select class="filter-select-el" id="filterPayment" onchange="filterClosing()">
            <option value="">Semua Status Komisi</option>
            <option value="paid-off">Lunas</option>
            <option value="installment">Cicilan</option>
            <option value="dp">DP</option>
        </select>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table id="closingTable">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Tipe Rumah</th>
                    <th>Harga Jual</th>
                    <th>Tanggal Penutupan</th>
                    <th>Komisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($closings as $closing)
                <tr
                    data-nama="{{ strtolower($closing->customer_name) }}"
                    data-phone="{{ $closing->customer_phone ?? '' }}"
                    data-tipe="{{ $closing->tipeRumah ? $closing->tipeRumah->nama_tipe : '' }}"
                    data-payment="{{ $closing->payment_status }}"
                    data-date="{{ $closing->tanggal_closing ? $closing->tanggal_closing->format('Y-m-d') : '' }}"
                >
                    <td data-label="Nama Pelanggan">
                        <div style="font-weight:600; color:#1e293b;">{{ $closing->customer_name }}</div>
                        <div class="secondary">{{ $closing->customer_phone ?? '-' }}</div>
                    </td>
                    <td data-label="Tipe Rumah">
                        @if($closing->tipeRumah)
                            <span class="badge badge-wa">{{ $closing->tipeRumah->nama_tipe }}</span>
                        @else
                            <span class="badge">–</span>
                        @endif
                    </td>
                    <td data-label="Harga Jual">Rp {{ number_format($closing->harga_jual, 0, ',', '.') }}</td>
                    <td data-label="Tanggal Closing">
                        {{ $closing->tanggal_closing ? $closing->tanggal_closing->format('d M Y') : '-' }}
                    </td>
                    <td data-label="Komisi">
                        <div style="font-weight: 600; color: #059669;">Rp {{ number_format($closing->komisi_nominal, 0, ',', '.') }}</div>
                        @if($closing->payment_status === 'paid-off')
                            <span class="badge badge-sudah" style="margin-top: 4px;">Lunas</span>
                        @elseif($closing->payment_status === 'installment')
                            <span class="badge badge-ig" style="margin-top: 4px; background: #dbeafe; color: #1e40af;">Cicilan</span>
                        @else
                            <span class="badge badge-pending" style="margin-top: 4px; background: #fef3c7; color: #92400e;">DP</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="5" style="text-align:center;padding:2rem;color:#6b7280;">
                        Belum ada penutupan dari link Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script>
function filterClosing() {
    const search  = document.getElementById('closingSearch').value.toLowerCase();
    const tipe    = document.getElementById('filterTipe').value;
    const payment = document.getElementById('filterPayment').value;
    const days    = parseInt(document.getElementById('filterPeriod').value) || 0;
    const cutoff  = days ? new Date(Date.now() - days * 86400000) : null;

    document.querySelectorAll('#closingTable tbody tr[data-nama]').forEach(row => {
        const nama    = row.dataset.nama;
        const phone   = row.dataset.phone.toLowerCase();
        const rowTipe = row.dataset.tipe;
        const rowPay  = row.dataset.payment;
        const rowDate = row.dataset.date ? new Date(row.dataset.date) : null;

        const okSearch  = !search  || nama.includes(search) || phone.includes(search);
        const okTipe    = !tipe    || rowTipe === tipe;
        const okPayment = !payment || rowPay === payment;
        const okDate    = !cutoff  || (rowDate && rowDate >= cutoff);

        row.style.display = (okSearch && okTipe && okPayment && okDate) ? '' : 'none';
    });
}
</script>
@endpush
