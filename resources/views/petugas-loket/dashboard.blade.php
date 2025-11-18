@extends('petugas-loket.layout')

@section('title', 'Dashboard Petugas Loket')
@section('page-title', 'Dashboard Petugas Loket')

@section('content')
<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title mb-2">Total Paket</p>
                        <h2 class="mb-0">{{ $totalPackages }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title mb-2">Total Booking</p>
                        <h2 class="mb-0">{{ $totalBookings }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title mb-2">Menunggu Bayar</p>
                        <h2 class="mb-0">{{ $pendingBookings }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title mb-2">Sudah Bayar</p>
                        <h2 class="mb-0">{{ $paidBookings }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Aksi Cepat
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('petugas-loket.packages') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-box fa-2x mb-2 d-block"></i>
                            Kelola Paket Tiket
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('petugas-loket.ticket-bookings') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-ticket-alt fa-2x mb-2 d-block"></i>
                            Lihat Booking Tiket
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('petugas-loket.qr-scanner') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-qrcode fa-2x mb-2 d-block"></i>
                            Scan QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-history me-2 text-info"></i>
                    Booking Terbaru
                </h5>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-ticket-alt fa-3x mb-3 opacity-25"></i>
                    <p>Lihat semua booking di menu <a href="{{ route('petugas-loket.ticket-bookings') }}">Booking Tiket</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
