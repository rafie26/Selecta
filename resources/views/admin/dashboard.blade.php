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
                        <h6 class="card-title">Total Hotels</h6>
                        <h2 class="mb-0">{{ number_format($totalHotels) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hotel fa-2x text-primary"></i>
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
