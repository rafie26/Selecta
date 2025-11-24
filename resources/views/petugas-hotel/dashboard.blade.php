@extends('petugas-hotel.layout')

@section('title', 'Dashboard Petugas Hotel')
@section('page-title', 'Dashboard Petugas Hotel')

@section('content')
<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="card-title mb-2">Total Tipe Kamar</p>
                        <h2 class="mb-0">{{ $totalRoomTypes }}</h2>
                    </div>
                    <div class="text-danger" style="font-size: 2.5rem; opacity: 0.3;">
                        <i class="fas fa-hotel"></i>
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
                        <i class="fas fa-calendar-check"></i>
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
                        <a href="{{ route('petugas-hotel.hotels') }}" class="btn btn-outline-danger w-100 py-3">
                            <i class="fas fa-hotel fa-2x mb-2 d-block"></i>
                            Kelola Tipe Kamar
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('petugas-hotel.hotel-bookings') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-calendar-check fa-2x mb-2 d-block"></i>
                            Lihat Booking Hotel
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('petugas-hotel.qr-scanner') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-qrcode fa-2x mb-2 d-block"></i>
                            Scan QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Room Status -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-bed me-2 text-danger"></i>
                    Status Kamar Saat Ini
                </h5>

                @if(isset($roomTypes) && $roomTypes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipe Kamar</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Tersedia</th>
                                    <th class="text-center">Occupancy</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roomTypes as $roomType)
                                    @php
                                        $totalRooms = $roomType->total_rooms ?? 0;
                                        $availableRooms = $roomType->available_rooms ?? 0;
                                        $occupiedRooms = max($totalRooms - $availableRooms, 0);
                                        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $roomType->name }}</strong>
                                            @if(!$roomType->is_active)
                                                <span class="badge bg-secondary ms-2">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $totalRooms }}</td>
                                        <td class="text-center text-danger fw-semibold">{{ $occupiedRooms }}</td>
                                        <td class="text-center text-success fw-semibold">{{ $availableRooms }}</td>
                                        <td class="text-center" style="min-width: 160px;">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                         style="width: {{ $occupancyRate }}%;"
                                                         aria-valuenow="{{ $occupancyRate }}" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted" style="width: 42px;">
                                                    {{ $occupancyRate }}%
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-bed fa-2x mb-2 opacity-25"></i>
                        <p class="mb-0">Belum ada data tipe kamar hotel.</p>
                    </div>
                @endif
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
                    <i class="fas fa-calendar-check fa-3x mb-3 opacity-25"></i>
                    <p>Lihat semua booking di menu <a href="{{ route('petugas-hotel.hotel-bookings') }}">Booking Hotel</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
