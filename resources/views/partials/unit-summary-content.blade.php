@php
    $routePrefix = $panel . '.units';
    $storeRoute = route($routePrefix . '.store');
    $updateRoute = route($routePrefix . '.update', 'summary');
    $destroyRoute = route($routePrefix . '.destroy', 'summary');
    $formAction = $summary['configured'] ? $updateRoute : $storeRoute;
    $formTotal = old('total', $summary['total']);
    $formTerjual = old('terjual', $summary['terjual']);
    $formBooking = old('booking', $summary['booking']);
    $formTersedia = max((int) $formTotal - (int) $formTerjual - (int) $formBooking, 0);
@endphp

<div class="unit-page">
    <div class="unit-page-header">
        <div>
            <h1>Ringkasan Unit</h1>
            <p>Kelola total unit, unit terjual, unit booking, dan unit tersedia otomatis tanpa input per unit.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="unit-alert-success"><i class="fas fa-check-circle text-green-500 mr-2"></i> {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="unit-alert-error">
            <ul style="margin:0;padding-left:16px">
                @foreach($errors->all() as $e)<li><i class="fas fa-times-circle text-red-500"></i> {{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="unit-stats-grid">
        <div class="unit-stat-card">
            <div class="unit-stat-icon blue"><i class="fas fa-building"></i></div>
            <div class="unit-stat-num">{{ number_format($stats['total']) }}</div>
            <div class="unit-stat-label">Total Unit</div>
        </div>
        <div class="unit-stat-card">
            <div class="unit-stat-icon green"><i class="fas fa-door-open"></i></div>
            <div class="unit-stat-num">{{ number_format($stats['tersedia']) }}</div>
            <div class="unit-stat-label">Unit Tersedia</div>
        </div>
        <div class="unit-stat-card">
            <div class="unit-stat-icon red"><i class="fas fa-house-circle-xmark"></i></div>
            <div class="unit-stat-num">{{ number_format($stats['terjual']) }}</div>
            <div class="unit-stat-label">Unit Terjual</div>
        </div>
        <div class="unit-stat-card">
            <div class="unit-stat-icon orange"><i class="fas fa-bookmark"></i></div>
            <div class="unit-stat-num">{{ number_format($stats['booking']) }}</div>
            <div class="unit-stat-label">Unit Booking</div>
        </div>
    </div>

    <div class="unit-summary-layout">
        <div class="unit-summary-card unit-summary-form-card">
            <div class="unit-summary-head">
                <div>
                    <h2>Form Ringkasan Unit</h2>
                    <p>Simpan satu data utama untuk seluruh statistik unit.</p>
                </div>
                <span class="unit-summary-badge {{ $summary['configured'] ? 'active' : 'draft' }}">
                    {{ $summary['configured'] ? 'Data Tersimpan' : 'Belum Disimpan' }}
                </span>
            </div>

            <form method="POST" action="{{ $formAction }}" id="unit-summary-form">
                @csrf
                @if($summary['configured'])
                    @method('PUT')
                @endif

                <div class="unit-form-row2">
                    <div class="unit-form-group">
                        <label class="unit-form-label">Total Unit <span>*</span></label>
                        <input
                            type="number"
                            name="total"
                            id="summary-total"
                            class="unit-form-control"
                            min="0"
                            value="{{ $formTotal }}"
                            required
                        >
                    </div>
                    <div class="unit-form-group">
                        <label class="unit-form-label">Unit Terjual <span>*</span></label>
                        <input
                            type="number"
                            name="terjual"
                            id="summary-terjual"
                            class="unit-form-control"
                            min="0"
                            value="{{ $formTerjual }}"
                            required
                        >
                    </div>
                </div>

                <div class="unit-form-row2">
                    <div class="unit-form-group">
                        <label class="unit-form-label">Unit Booking <span>*</span></label>
                        <input
                            type="number"
                            name="booking"
                            id="summary-booking"
                            class="unit-form-control"
                            min="0"
                            value="{{ $formBooking }}"
                            required
                        >
                    </div>
                    <div class="unit-form-group">
                        <label class="unit-form-label">Unit Tersedia</label>
                        <input
                            type="number"
                            id="summary-tersedia"
                            class="unit-form-control unit-form-control-readonly"
                            value="{{ $formTersedia }}"
                            readonly
                        >
                    </div>
                </div>

                <div class="unit-summary-note">
                    <i class="fas fa-circle-info"></i>
                    <span>Unit tersedia dihitung otomatis dari total unit dikurangi unit terjual dan unit booking.</span>
                </div>

                <div class="unit-modal-footer unit-summary-actions">
                    <button type="submit" class="unit-btn-submit">
                        <i class="fas fa-save"></i>
                        {{ $summary['configured'] ? 'Perbarui Ringkasan' : 'Simpan Ringkasan' }}
                    </button>
                </div>
            </form>

            @if($summary['configured'])
                <form method="POST" action="{{ $destroyRoute }}" class="unit-summary-delete-form" onsubmit="return confirm('Hapus ringkasan unit ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="unit-btn-del-confirm">Hapus Data</button>
                </form>
            @endif
        </div>

        <div class="unit-summary-card unit-summary-info-card">
            <div class="unit-summary-head">
                <div>
                    <h2>Informasi</h2>
                    <p>Ringkasan ini dipakai untuk angka unit di halaman panel dan landing page.</p>
                </div>
            </div>

            <div class="unit-summary-list">
                <div class="unit-summary-list-item">
                    <span>Total Unit</span>
                    <strong>{{ number_format($stats['total']) }}</strong>
                </div>
                <div class="unit-summary-list-item">
                    <span>Unit Terjual</span>
                    <strong>{{ number_format($stats['terjual']) }}</strong>
                </div>
                <div class="unit-summary-list-item">
                    <span>Unit Booking</span>
                    <strong>{{ number_format($stats['booking']) }}</strong>
                </div>
                <div class="unit-summary-list-item highlight">
                    <span>Unit Tersedia</span>
                    <strong>{{ number_format($stats['tersedia']) }}</strong>
                </div>
            </div>

            <div class="unit-summary-helper">
                @if($summary['configured'])
                    Data saat ini sudah memakai ringkasan manual. Perubahan di form akan langsung memperbarui angka statistik.
                @else
                    Data awal masih mengikuti hitungan unit lama. Klik simpan untuk mulai memakai ringkasan manual.
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var totalInput = document.getElementById('summary-total');
    var soldInput = document.getElementById('summary-terjual');
    var bookingInput = document.getElementById('summary-booking');
    var availableInput = document.getElementById('summary-tersedia');

    if (!totalInput || !soldInput || !bookingInput || !availableInput) {
        return;
    }

    function calculateAvailable() {
        var total = parseInt(totalInput.value || '0', 10);
        var sold = parseInt(soldInput.value || '0', 10);
        var booking = parseInt(bookingInput.value || '0', 10);
        var available = total - sold - booking;

        availableInput.value = available > 0 ? available : 0;
    }

    totalInput.addEventListener('input', calculateAvailable);
    soldInput.addEventListener('input', calculateAvailable);
    bookingInput.addEventListener('input', calculateAvailable);
    calculateAvailable();
});
</script>
@endpush

