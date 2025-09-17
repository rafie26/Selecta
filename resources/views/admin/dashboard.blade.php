@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                    <h6 class="card-title">Total User</h6>
                        <h2 class="mb-0">{{ number_format($totalUsers) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Kamar Tersedia</h6>
                        <h2 class="mb-0 text-success">{{ number_format($totalAvailableRooms) }}</h2>
                        <small class="text-muted">dari {{ number_format($totalRooms) }} total kamar</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-bed fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Kamar Terisi</h6>
                        <h2 class="mb-0 text-warning">{{ number_format($occupiedRooms) }}</h2>
                        <small class="text-muted">kamar sedang digunakan</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-door-closed fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Tickets</h6>
                        <h2 class="mb-0">{{ number_format($totalTickets) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Room Availability Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bed me-2"></i>
                    Ketersediaan Kamar per Tipe
                </h5>
            </div>
            <div class="card-body">
                @if($roomTypesAvailability->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tipe Kamar</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Tersedia</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Tingkat Hunian</th>
                                    <th class="text-center">Harga/Malam</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roomTypesAvailability as $room)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-door-open text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $room['name'] }}</strong>
                                                    @if(!$room['is_active'])
                                                        <span class="badge bg-secondary ms-2">Nonaktif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $room['total_rooms'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $room['available_rooms'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $room['occupied_rooms'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                                    <div class="progress-bar @if($room['occupancy_rate'] >= 90) bg-danger @elseif($room['occupancy_rate'] >= 70) bg-warning @else bg-success @endif" style="width: {{ $room['occupancy_rate'] }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $room['occupancy_rate'] }}%</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">Rp {{ number_format($room['price_per_night'], 0, ',', '.') }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($room['status'] == 'full')
                                                <span class="badge bg-danger">Penuh</span>
                                            @elseif($room['status'] == 'low')
                                                <span class="badge bg-warning">Terbatas</span>
                                            @else
                                                <span class="badge bg-success">Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-bed fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada tipe kamar yang terdaftar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentBookings as $booking)
                    <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                            {{ $booking->user ? $booking->user->initials : strtoupper(substr($booking->booker_name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $booking->user ? $booking->user->name : $booking->booker_name }}</h6>
                            <p class="mb-1 text-muted small">{{ $booking->booking_code }}</p>
                            <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-end small text-muted">{{ ucfirst($booking->payment_status) }}</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary w-100">
                            <i class="fas fa-users me-2"></i>
                            Kelola Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.hotels') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-hotel me-2"></i>
                            Kelola Hotels
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.packages') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Kelola Tickets
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.restaurants') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-utensils me-2"></i>
                            Kelola Restaurants
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-external-link-alt me-2"></i>
                            View Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- No chart scripts needed in clean design -->
@endpush
