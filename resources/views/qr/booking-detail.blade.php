<div class="booking-info">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>
                    {{ $booking->booking_code }}
                </h6>
                <span class="badge bg-{{ $booking->check_in_status == 'checked_in' ? 'success' : 'warning' }}">
                    {{ $booking->check_in_status == 'checked_in' ? 'Sudah Check-in' : 'Belum Check-in' }}
                </span>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <strong>Pemesan:</strong>
            <div>{{ $booking->booker_name }}</div>
        </div>
        <div class="col-6">
            <strong>Tanggal Kunjungan:</strong>
            <div>{{ $booking->visit_date ? $booking->visit_date->format('d M Y') : '-' }}</div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <strong>Status Pembayaran:</strong>
            <div>
                <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'danger' }}">
                    {{ ucfirst($booking->payment_status) }}
                </span>
            </div>
        </div>
        <div class="col-6">
            <strong>Total:</strong>
            <div class="fw-bold">Rp {{ number_format($booking->total_amount) }}</div>
        </div>
    </div>

    @if($booking->visitors->count() > 0)
        <div class="mb-3">
            <strong>Pengunjung ({{ $booking->visitors->count() }} orang):</strong>
            <div class="mt-2">
                @foreach($booking->visitors as $visitor)
                    <div class="border rounded p-2 mb-2 bg-light">
                        <div class="d-flex justify-content-between">
                            <span>{{ $visitor->name }}</span>
                            <small class="text-muted">
                                {{ $visitor->gender == 'L' ? 'L' : 'P' }}, {{ $visitor->age }} th
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($booking->bookingDetails->count() > 0)
        <div class="mb-3">
            <strong>Detail Tiket:</strong>
            <div class="mt-2">
                @foreach($booking->bookingDetails as $detail)
                    <div class="d-flex justify-content-between border-bottom py-1">
                        <span>{{ $detail->package->name }} ({{ $detail->quantity }}x)</span>
                        <span>Rp {{ number_format($detail->subtotal) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($booking->check_in_status == 'checked_in')
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Sudah Check-in</strong><br>
            <small>{{ $booking->checked_in_at->format('d M Y H:i') }}</small>
        </div>
    @else
        @if($booking->payment_status == 'paid')
            <div class="text-center mt-3">
                <button class="btn btn-success btn-lg" onclick="showCheckinModal('{{ $booking->qr_code }}', '{{ $booking->booking_code }}', '{{ $booking->booker_name }}')">
                    <i class="fas fa-check me-2"></i>
                    Check-in Sekarang
                </button>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Booking belum dibayar. Check-in tidak dapat dilakukan.
            </div>
        @endif
    @endif
</div>
