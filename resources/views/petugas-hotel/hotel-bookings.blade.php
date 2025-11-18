@extends('petugas-hotel.layout')

@section('title', 'Booking Hotel')
@section('page-title', 'Booking Hotel')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-check me-2"></i>
                Daftar Booking Hotel
            </h5>
            <span class="badge bg-danger">Total: {{ $bookings->total() }} booking</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari booking..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">Semua Payment</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="check_in_status" class="form-select">
                    <option value="">Semua Check-in</option>
                    <option value="pending" {{ request('check_in_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="checked_in" {{ request('check_in_status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('petugas-hotel.hotel-bookings') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama</th>
                        <th>Check-in / Check-out</th>
                        <th>Malam</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <strong>{{ $booking->booking_code }}</strong>
                        </td>
                        <td>
                            {{ $booking->booker_name }}
                            <br>
                            <small class="text-muted">{{ $booking->booker_email }}</small>
                        </td>
                        <td>
                            <small>
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}
                                <br>
                                <i class="fas fa-calendar-check me-1"></i>
                                {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $booking->nights }} malam</span>
                        </td>
                        <td>
                            <strong>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($booking->payment_status == 'paid')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Lunas
                                </span>
                            @elseif($booking->payment_status == 'pending')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Gagal
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($booking->check_in_time)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    {{ \Carbon\Carbon::parse($booking->check_in_time)->format('d M Y H:i') }}
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-minus me-1"></i>Belum
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Tidak ada booking hotel</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} booking
            </div>
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
