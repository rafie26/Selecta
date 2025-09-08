@extends('admin.layout')

@section('title', 'Kelola Bookings')
@section('page-title', 'Kelola Bookings')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-check me-2"></i>
                Daftar Bookings
            </h5>
            <div class="d-flex gap-3 align-items-center">
                <!-- Search Form -->
                <form method="GET" action="{{ url()->current() }}" class="d-flex gap-2 align-items-center">
                    <div class="input-group" style="min-width: 300px;">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari booking code, nama, atau email..."
                               style="font-size: 0.875rem;">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                    
                    <!-- Filter Dropdowns -->
                    <select name="payment_status" class="form-select form-select-sm" style="width: auto; min-width: 130px;" onchange="this.form.submit()">
                        <option value="">Semua Payment</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="expired" {{ request('payment_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    
                    <select name="check_in_status" class="form-select form-select-sm" style="width: auto; min-width: 130px;" onchange="this.form.submit()">
                        <option value="">Semua Check-in</option>
                        <option value="pending" {{ request('check_in_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="checked_in" {{ request('check_in_status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    </select>
                </form>
                
                <!-- Total Counter -->
                <div class="d-flex gap-2">
                    <span class="badge bg-primary">Total: {{ $bookings->total() }} bookings</span>
                    @if(request()->hasAny(['search', 'payment_status', 'check_in_status']))
                        <span class="badge bg-info">
                            <i class="fas fa-filter me-1"></i>
                            Filtered Results
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'payment_status', 'check_in_status']))
            <div class="mt-3 pt-3 border-top">
                <small class="text-muted me-2">Filter aktif:</small>
                @if(request('search'))
                    <span class="badge bg-light text-dark me-2">
                        <i class="fas fa-search me-1"></i>
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithoutQuery('search') }}" class="text-decoration-none ms-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if(request('payment_status'))
                    <span class="badge bg-light text-dark me-2">
                        Payment: {{ ucfirst(request('payment_status')) }}
                        <a href="{{ request()->fullUrlWithoutQuery('payment_status') }}" class="text-decoration-none ms-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if(request('check_in_status'))
                    <span class="badge bg-light text-dark me-2">
                        Check-in: {{ request('check_in_status') == 'checked_in' ? 'Checked In' : 'Pending' }}
                        <a href="{{ request()->fullUrlWithoutQuery('check_in_status') }}" class="text-decoration-none ms-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>
                    Clear All
                </a>
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User/Pemesan</th>
                        <th>Booking Code</th>
                        <th>Detail Pengunjung</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                        {{ $booking->user ? $booking->user->initials : strtoupper(substr($booking->booker_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $booking->user ? $booking->user->name : $booking->booker_name }}</div>
                                        <small class="text-muted">{{ $booking->user ? $booking->user->email : $booking->booker_email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $booking->booking_code }}</div>
                                <small class="text-muted">Kode Booking</small>
                            </td>
                            <td>
                                @if($booking->visitors->count() > 0)
                                    <div class="small">
                                        <strong>{{ $booking->visitors->count() }} Pengunjung:</strong>
                                        @foreach($booking->visitors as $visitor)
                                            <div class="mb-1">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $visitor->name }} 
                                                <span class="text-muted">({{ $visitor->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $visitor->age }} th)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Tidak ada data pengunjung</span>
                                @endif
                            </td>
                            <td>{{ $booking->visit_date ? \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="fw-medium">Rp {{ number_format($booking->total_amount) }}</span>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : ($booking->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $booking->check_in_status == 'checked_in' ? 'success' : 'secondary' }}">
                                        {{ $booking->check_in_status == 'checked_in' ? 'Check-in' : 'Pending' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-info btn-sm show-booking-detail" data-booking-id="{{ $booking->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm edit-status" 
                                            data-booking-id="{{ $booking->id }}"
                                            data-payment-status="{{ $booking->payment_status }}"
                                            data-checkin-status="{{ $booking->check_in_status }}"
                                            data-booking-code="{{ $booking->booking_code }}">
                                        <i class="fas fa-edit"></i> Status
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-booking" 
                                            data-booking-id="{{ $booking->id }}"
                                            data-booking-code="{{ $booking->booking_code }}"
                                            data-booker-name="{{ $booking->booker_name }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                @if(request()->hasAny(['search', 'payment_status', 'check_in_status']))
                                    <p class="text-muted mb-0">Tidak ada bookings yang sesuai dengan filter</p>
                                    <small class="text-muted">
                                        <a href="{{ url()->current() }}" class="text-decoration-none">Hapus filter</a> untuk melihat semua data
                                    </small>
                                @else
                                    <p class="text-muted mb-0">Belum ada bookings</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status Booking</h5>
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
                            <option value="pending">Pending</option>
                            <option value="checked_in">Checked In</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>
                            <strong>Catatan:</strong> Jika status pembayaran diubah ke "Paid", QR code akan otomatis dibuat. 
                            Jika status check-in diubah ke "Checked In", timestamp akan otomatis diset.
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
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                
                <p>Apakah Anda yakin ingin menghapus booking berikut?</p>
                
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
                        Semua data terkait booking ini (detail tiket, pengunjung) akan ikut terhapus.
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
                <h5 class="modal-title" id="bookingDetailModalLabel">Detail Booking</h5>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        fetch(`/admin/bookings/${bookingId}/detail`)
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
            url: `/admin/bookings/${currentBookingId}/update-status`,
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
            url: `/admin/bookings/${currentBookingId}`,
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