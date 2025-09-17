@extends('layouts.app')

@section('title', 'Pemesanan Saya')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Pemesanan Saya
                </h2>
                <a href="{{ route('hotels.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Pesan Lagi
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card booking-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">{{ $booking->booking_code }}</h6>
                                    <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : ($booking->payment_status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    @if($booking->roomBookings->count() > 0)
                                        <!-- Hotel Room Booking -->
                                        @foreach($booking->roomBookings as $roomBooking)
                                            <div class="booking-item mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-hotel text-primary me-2"></i>
                                                    <h6 class="mb-0">{{ $roomBooking->roomType->name }}</h6>
                                                </div>
                                                <div class="booking-details">
                                                    <div class="row text-sm">
                                                        <div class="col-6">
                                                            <strong>Check-in:</strong><br>
                                                            {{ \Carbon\Carbon::parse($roomBooking->check_in_date)->format('d M Y') }}
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>Check-out:</strong><br>
                                                            {{ \Carbon\Carbon::parse($roomBooking->check_out_date)->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                    <div class="row text-sm mt-2">
                                                        <div class="col-6">
                                                            <strong>Kamar:</strong> {{ $roomBooking->number_of_rooms }}
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>Tamu:</strong> {{ $roomBooking->number_of_guests }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Package Booking -->
                                        @foreach($booking->bookingDetails as $detail)
                                            <div class="booking-item mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-ticket-alt text-primary me-2"></i>
                                                    <h6 class="mb-0">{{ $detail->package->name }}</h6>
                                                </div>
                                                <div class="booking-details">
                                                    <div class="text-sm">
                                                        <strong>Tanggal Kunjungan:</strong><br>
                                                        {{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}
                                                    </div>
                                                    <div class="text-sm mt-1">
                                                        <strong>Jumlah:</strong> {{ $detail->quantity }} tiket
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="booking-total mt-3 pt-3 border-top">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>Total:</strong>
                                            <strong class="text-success">Rp {{ number_format($booking->total_amount) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $booking->created_at->format('d M Y, H:i') }}
                                        </small>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if($booking->payment_status === 'paid' && $booking->qr_code)
                                                <button class="btn btn-outline-success btn-sm" onclick="showQRCode('{{ $booking->qr_code }}', '{{ $booking->booking_code }}')">
                                                    <i class="fas fa-qrcode"></i> QR
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Belum Ada Pemesanan</h4>
                    <p class="text-muted mb-4">Anda belum memiliki pemesanan apapun.</p>
                    <a href="{{ route('hotels.index') }}" class="btn btn-primary">
                        <i class="fas fa-hotel me-1"></i>
                        Pesan Kamar Hotel
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">QR Code Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContainer"></div>
                <p class="mt-3 mb-0">Tunjukkan QR Code ini saat check-in</p>
            </div>
        </div>
    </div>
</div>

<style>
.booking-card {
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.booking-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.booking-item {
    border-left: 3px solid #007bff;
    padding-left: 1rem;
}

.text-sm {
    font-size: 0.875rem;
}
</style>

<script>
function showQRCode(qrCode, bookingCode) {
    document.getElementById('qrCodeContainer').innerHTML = `
        <div class="mb-3">
            <img src="data:image/png;base64,${qrCode}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
        </div>
        <p class="fw-bold">${bookingCode}</p>
    `;
    
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    qrModal.show();
}
</script>
@endsection
