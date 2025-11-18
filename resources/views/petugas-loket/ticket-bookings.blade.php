@extends('petugas-loket.layout')

@section('title', 'Booking Tiket')
@section('page-title', 'Booking Tiket')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-ticket-alt me-2"></i>
                Daftar Booking Tiket
            </h5>
            <span class="badge bg-success">Total: {{ $bookings->total() }} booking</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
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
                <select name="package_id" class="form-select">
                    <option value="">Semua Paket</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('petugas-loket.ticket-bookings') }}" class="btn btn-secondary w-100">
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
                        <th>Paket</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Check-in</th>
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
                            @foreach($booking->bookingDetails as $detail)
                                <span class="badge bg-info">
                                    {{ $detail->package->name }} ({{ $detail->quantity }}x)
                                </span>
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}</td>
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
                            <p class="text-muted mb-0">Tidak ada booking tiket</p>
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
