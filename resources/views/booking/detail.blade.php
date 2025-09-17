@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Detail Pemesanan</h2>
                    <p class="text-muted mb-0">{{ $booking->booking_code }}</p>
                </div>
                <div>
                    <a href="{{ route('booking.my-bookings') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    @if($booking->payment_status === 'paid' && $booking->qr_code)
                        <button class="btn btn-success" onclick="showQRCode('{{ $booking->qr_code }}', '{{ $booking->booking_code }}')">
                            <i class="fas fa-qrcode me-1"></i>
                            Tampilkan QR Code
                        </button>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Booking Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Pemesanan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kode Booking:</label>
                                        <p class="mb-0">{{ $booking->booking_code }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status Pembayaran:</label>
                                        <p class="mb-0">
                                            <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : ($booking->payment_status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tanggal Pemesanan:</label>
                                        <p class="mb-0">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Total Pembayaran:</label>
                                        <p class="mb-0 text-success fw-bold fs-5">Rp {{ number_format($booking->total_amount) }}</p>
                                    </div>
                                    @if($booking->payment_method)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Metode Pembayaran:</label>
                                            <p class="mb-0">{{ $booking->payment_method }}</p>
                                        </div>
                                    @endif
                                    @if($booking->payment_date)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tanggal Pembayaran:</label>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($booking->payment_date)->format('d M Y, H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    @if($booking->roomBookings->count() > 0)
                        <!-- Hotel Room Bookings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-hotel me-2"></i>
                                    Detail Kamar Hotel
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($booking->roomBookings as $roomBooking)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">{{ $roomBooking->roomType->name }}</h6>
                                                <div class="mb-2">
                                                    <small class="text-muted">Check-in:</small><br>
                                                    <strong>{{ \Carbon\Carbon::parse($roomBooking->check_in_date)->format('d M Y') }}</strong>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted">Check-out:</small><br>
                                                    <strong>{{ \Carbon\Carbon::parse($roomBooking->check_out_date)->format('d M Y') }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <small class="text-muted">Jumlah Kamar:</small><br>
                                                    <strong>{{ $roomBooking->number_of_rooms }} kamar</strong>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted">Jumlah Tamu:</small><br>
                                                    <strong>{{ $roomBooking->number_of_guests }} orang</strong>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-muted">Durasi:</small><br>
                                                    <strong>{{ \Carbon\Carbon::parse($roomBooking->check_in_date)->diffInDays(\Carbon\Carbon::parse($roomBooking->check_out_date)) }} malam</strong>
                                                </div>
                                            </div>
                                        </div>
                                        @if($roomBooking->special_requests)
                                            <div class="mt-3 pt-3 border-top">
                                                <small class="text-muted">Permintaan Khusus:</small><br>
                                                <p class="mb-0">{{ $roomBooking->special_requests }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Package Bookings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-ticket-alt me-2"></i>
                                    Detail Paket Wisata
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Kunjungan:</label>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}</p>
                                </div>
                                @foreach($booking->bookingDetails as $detail)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-1">{{ $detail->package->name }}</h6>
                                                <p class="text-muted mb-0">{{ $detail->package->description }}</p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="mb-1">
                                                    <small class="text-muted">Jumlah:</small>
                                                    <strong>{{ $detail->quantity }} tiket</strong>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Harga:</small>
                                                    <strong>Rp {{ number_format($detail->price) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>
                                Informasi Kontak
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama:</label>
                                <p class="mb-0">{{ $booking->user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p class="mb-0">{{ $booking->user->email }}</p>
                            </div>
                            @if($booking->user->phone)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Telepon:</label>
                                    <p class="mb-0">{{ $booking->user->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($booking->visitors->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Daftar Pengunjung
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($booking->visitors as $visitor)
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="fw-bold">{{ $visitor->name }}</div>
                                        <small class="text-muted">{{ $visitor->id_number }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
