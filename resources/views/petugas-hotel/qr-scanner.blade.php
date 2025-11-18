@extends('petugas-hotel.layout')

@section('title', 'QR Scanner')
@section('page-title', 'QR Scanner')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-qrcode fa-5x text-danger mb-4"></i>
                <h3 class="mb-3">QR Code Scanner</h3>
                <p class="text-muted mb-4">
                    Scan QR code dari booking hotel untuk melakukan check-in
                </p>
                <a href="{{ route('qr.scanner') }}" class="btn btn-danger btn-lg">
                    <i class="fas fa-camera me-2"></i>
                    Buka Scanner
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    Cara Menggunakan
                </h5>
                <ol class="mb-0">
                    <li class="mb-2">Klik tombol "Buka Scanner" di atas</li>
                    <li class="mb-2">Izinkan akses kamera jika diminta</li>
                    <li class="mb-2">Arahkan kamera ke QR code pada booking hotel</li>
                    <li class="mb-2">Sistem akan otomatis membaca dan memproses check-in</li>
                    <li>Konfirmasi status check-in tamu hotel</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
