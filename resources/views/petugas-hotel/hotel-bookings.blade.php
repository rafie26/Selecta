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
                        <th>Aksi</th>
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
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-info btn-sm show-booking-detail" data-booking-id="{{ $booking->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <button class="btn btn-outline-warning btn-sm edit-status" 
                                        data-booking-id="{{ $booking->id }}"
                                        data-payment-status="{{ $booking->payment_status }}"
                                        data-checkin-status="{{ $booking->check_in_status ?? 'pending' }}"
                                        data-booking-code="{{ $booking->booking_code }}">
                                    <i class="fas fa-edit"></i> Status
                                </button>
                                <button class="btn btn-outline-danger btn-sm delete-booking" 
                                        data-booking-id="{{ $booking->id }}"
                                        data-booking-code="{{ $booking->booking_code }}"
                                        data-booker-name="{{ $booking->booker_name }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
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

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status Booking Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <div class="mb-3">
                        <strong>Kode Booking:</strong> <span id="modalBookingCode"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalPaymentStatus" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="modalPaymentStatus" name="payment_status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modalCheckInStatus" class="form-label">Status Check-in</label>
                        <select class="form-select" id="modalCheckInStatus" name="check_in_status" required>
                            <option value="pending">Pending / Checked Out</option>
                            <option value="checked_in">Checked In</option>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Pilih "Pending / Checked Out" untuk checkout dan mengembalikan kamar
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>
                            <strong>Catatan:</strong><br>
                            • Status "Paid" → QR code otomatis dibuat<br>
                            • Status "Checked In" → Kamar dikurangi dari ketersediaan<br>
                            • Status "Pending/Checked Out" → Kamar dikembalikan ke ketersediaan
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">
                    <i class="fas fa-save me-1"></i>
                    Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Booking Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                
                <p>Apakah Anda yakin ingin menghapus booking hotel berikut?</p>
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <strong>Kode Booking:</strong><br>
                                <span id="deleteBookingCode"></span>
                            </div>
                            <div class="col-6">
                                <strong>Nama Pemesan:</strong><br>
                                <span id="deleteBookerName"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Semua data terkait booking hotel ini akan ikut terhapus.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-1"></i>
                    Ya, Hapus Booking
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Booking Detail Modal -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel">Detail Booking Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let currentBookingId = null;
    
    // Show booking detail
    $('.show-booking-detail').on('click', function() {
        const bookingId = $(this).data('booking-id');
        
        // Show loading
        $('#bookingDetailContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
        $('#bookingDetailModal').modal('show');
        
        // Fetch booking detail
        fetch(`/petugas-hotel/hotel-bookings/${bookingId}/detail`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#bookingDetailContent').html(data.html);
                } else {
                    $('#bookingDetailContent').html('<div class="alert alert-danger">Gagal memuat detail booking.</div>');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#bookingDetailContent').html('<div class="alert alert-danger">Terjadi kesalahan saat memuat data.</div>');
            });
    });
    
    // Show status update modal
    $('.edit-status').on('click', function() {
        currentBookingId = $(this).data('booking-id');
        const bookingCode = $(this).data('booking-code');
        const paymentStatus = $(this).data('payment-status');
        const checkinStatus = $(this).data('checkin-status');
        
        $('#modalBookingCode').text(bookingCode);
        $('#modalPaymentStatus').val(paymentStatus);
        $('#modalCheckInStatus').val(checkinStatus);
        
        $('#statusModal').modal('show');
    });
    
    // Show delete confirmation modal
    $('.delete-booking').on('click', function() {
        currentBookingId = $(this).data('booking-id');
        const bookingCode = $(this).data('booking-code');
        const bookerName = $(this).data('booker-name');
        
        $('#deleteBookingCode').text(bookingCode);
        $('#deleteBookerName').text(bookerName);
        
        $('#deleteModal').modal('show');
    });
    
    // Confirm status update
    $('#confirmStatusUpdate').on('click', function() {
        if (!currentBookingId) return;
        
        const paymentStatus = $('#modalPaymentStatus').val();
        const checkInStatus = $('#modalCheckInStatus').val();
        
        $.ajax({
            url: `/petugas-hotel/hotel-bookings/${currentBookingId}/update-status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                payment_status: paymentStatus,
                check_in_status: checkInStatus
            },
            success: function(response) {
                if (response.success) {
                    $('#statusModal').modal('hide');
                    
                    // Show success message
                    $('body').prepend(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat mengupdate status.');
                }
            },
            error: function(xhr) {
                console.log('Update error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat mengupdate status.');
            }
        });
    });
    
    // Confirm delete booking
    $('#confirmDelete').on('click', function() {
        if (!currentBookingId) return;
        
        $.ajax({
            url: `/petugas-hotel/hotel-bookings/${currentBookingId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    
                    // Show success message
                    $('body').prepend(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat menghapus booking.');
                }
            },
            error: function(xhr) {
                console.log('Delete error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat menghapus booking.');
            }
        });
    });
});
</script>
@endpush

@endsection
