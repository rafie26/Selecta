@extends('layouts.app')

@section('title', 'Menunggu Konfirmasi Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <h3 class="text-warning mb-3">
                        <i class="fas fa-clock me-2"></i>
                        Menunggu Konfirmasi Pembayaran
                    </h3>
                    
                    <p class="text-muted mb-4">
                        Kami sedang memproses pembayaran Anda. Halaman ini akan otomatis terupdate ketika pembayaran berhasil dikonfirmasi.
                    </p>
                    
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-6">
                                    <strong>Kode Booking:</strong><br>
                                    <span class="text-primary">{{ $bookingData['booking_code'] }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Total Pembayaran:</strong><br>
                                    <span class="text-success fw-bold">Rp {{ number_format($bookingData['total_amount'], 0, ',', '.') }}</span>
                                </div>
                                <div class="col-6 mt-3">
                                    <strong>Tanggal Kunjungan:</strong><br>
                                    {{ \Carbon\Carbon::parse($bookingData['visit_date'])->format('d M Y') }}
                                </div>
                                <div class="col-6 mt-3">
                                    <strong>Nama Pemesan:</strong><br>
                                    {{ $bookingData['booker_name'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Jika pembayaran berhasil, data booking akan otomatis tersimpan dan Anda akan diarahkan ke halaman konfirmasi. Jika pembayaran gagal atau dibatalkan, tidak ada data yang akan tersimpan di sistem.
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Tiket
                        </a>
                        <button id="checkStatusBtn" class="btn btn-primary">
                            <i class="fas fa-sync-alt me-2"></i>
                            Cek Status Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let checkInterval;
let checkCount = 0;
const maxChecks = 60; // Check for 5 minutes (60 checks * 5 seconds)

function checkPaymentStatus() {
    checkCount++;
    
    fetch(`/api/payment/status/{{ $orderId }}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                // Payment successful, redirect to success page
                clearInterval(checkInterval);
                window.location.href = `/payment/success/${data.booking_id}`;
            } else if (data.status === 'failed' || data.status === 'expired' || data.status === 'cancelled') {
                // Payment failed, show message and redirect
                clearInterval(checkInterval);
                alert('Pembayaran gagal atau dibatalkan. Anda akan diarahkan kembali ke halaman tiket.');
                window.location.href = '{{ route("tickets.index") }}';
            } else if (checkCount >= maxChecks) {
                // Timeout, stop checking
                clearInterval(checkInterval);
                alert('Timeout menunggu konfirmasi pembayaran. Silakan cek status pembayaran secara manual.');
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
            if (checkCount >= maxChecks) {
                clearInterval(checkInterval);
            }
        });
}

// Start automatic checking every 5 seconds
checkInterval = setInterval(checkPaymentStatus, 5000);

// Manual check button
document.getElementById('checkStatusBtn').addEventListener('click', function() {
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengecek...';
    checkPaymentStatus();
    setTimeout(() => {
        this.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Cek Status Pembayaran';
    }, 2000);
});

// Check immediately on page load
checkPaymentStatus();
</script>
@endpush
@endsection
