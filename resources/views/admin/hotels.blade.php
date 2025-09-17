@extends('admin.layout')

@section('title', 'Kelola Hotel')
@section('page-title', 'Kelola Hotel')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-hotel me-2"></i>
                Kelola Tipe Kamar Hotel
            </h5>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">Total: {{ App\Models\RoomType::count() }} tipe kamar</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomTypeModal">
                    <i class="fas fa-plus me-1"></i>
                    Tambah Tipe Kamar
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Kamar</th>
                        <th>Harga/Malam</th>
                        <th>Kapasitas</th>
                        <th>Total Kamar</th>
                        <th>Kamar Tersedia</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(App\Models\RoomType::latest()->get() as $roomType)
                        <tr>
                            <td>{{ $roomType->id }}</td>
                            <td>
                                <div class="fw-medium">{{ $roomType->name }}</div>
                                @if($roomType->description)
                                    <small class="text-muted">{{ Str::limit($roomType->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-medium text-success">Rp {{ number_format($roomType->price_per_night) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $roomType->max_occupancy }} orang</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $roomType->total_rooms }} kamar</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success">{{ $roomType->available_rooms ?? 0 }}</span>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" 
                                                class="btn btn-outline-success btn-sm adjust-rooms" 
                                                data-id="{{ $roomType->id }}"
                                                data-name="{{ $roomType->name }}"
                                                data-available="{{ $roomType->available_rooms ?? 0 }}"
                                                data-total="{{ $roomType->total_rooms }}"
                                                data-action="add"
                                                title="Tambah Kamar Tersedia">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-warning btn-sm adjust-rooms" 
                                                data-id="{{ $roomType->id }}"
                                                data-name="{{ $roomType->name }}"
                                                data-available="{{ $roomType->available_rooms ?? 0 }}"
                                                data-total="{{ $roomType->total_rooms }}"
                                                data-action="subtract"
                                                title="Kurangi Kamar Tersedia">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $roomType->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $roomType->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $roomType->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm edit-room-type" 
                                            data-id="{{ $roomType->id }}"
                                            data-name="{{ $roomType->name }}"
                                            data-description="{{ $roomType->description }}"
                                            data-price="{{ $roomType->price_per_night }}"
                                            data-occupancy="{{ $roomType->max_occupancy }}"
                                            data-total-rooms="{{ $roomType->total_rooms }}"
                                            data-active="{{ $roomType->is_active }}"
                                            title="Edit Tipe Kamar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm delete-room-type" 
                                            data-id="{{ $roomType->id }}" 
                                            data-name="{{ $roomType->name }}"
                                            title="Hapus Tipe Kamar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-hotel fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Belum ada tipe kamar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Room Type Modal -->
<div class="modal fade" id="addRoomTypeModal" tabindex="-1" aria-labelledby="addRoomTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomTypeModalLabel">Tambah Tipe Kamar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addRoomTypeForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kamar</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_per_night" class="form-label">Harga per Malam</label>
                                <input type="number" class="form-control" id="price_per_night" name="price_per_night" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_occupancy" class="form-label">Kapasitas Maksimal</label>
                                <input type="number" class="form-control" id="max_occupancy" name="max_occupancy" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_rooms" class="form-label">Total Kamar</label>
                                <input type="number" class="form-control" id="total_rooms" name="total_rooms" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Room Type Modal -->
<div class="modal fade" id="editRoomTypeModal" tabindex="-1" aria-labelledby="editRoomTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomTypeModalLabel">Edit Tipe Kamar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoomTypeForm">
                <input type="hidden" id="edit_room_type_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Nama Kamar</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_price_per_night" class="form-label">Harga per Malam</label>
                                <input type="number" class="form-control" id="edit_price_per_night" name="price_per_night" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_max_occupancy" class="form-label">Kapasitas Maksimal</label>
                                <input type="number" class="form-control" id="edit_max_occupancy" name="max_occupancy" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_total_rooms" class="form-label">Total Kamar</label>
                                <input type="number" class="form-control" id="edit_total_rooms" name="total_rooms" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Adjust Available Rooms Modal -->
<div class="modal fade" id="adjustRoomsModal" tabindex="-1" aria-labelledby="adjustRoomsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adjustRoomsModalLabel">Atur Ketersediaan Kamar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adjustRoomsForm">
                <input type="hidden" id="adjust_room_type_id" name="room_type_id">
                <input type="hidden" id="adjust_action" name="action">
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 id="adjustRoomTypeName" class="text-primary"></h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tersedia saat ini:</small>
                                <div class="fw-bold text-success" id="currentAvailable">0</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Total kamar:</small>
                                <div class="fw-bold text-secondary" id="totalRooms">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adjustment" class="form-label">Jumlah Kamar</label>
                        <input type="number" class="form-control" id="adjustment" name="adjustment" min="1" required>
                        <div class="form-text" id="adjustmentHelp"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" id="confirmAdjust">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Tipe Kamar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus tipe kamar <strong id="roomTypeName"></strong>?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let roomTypeIdToDelete = null;
    
    // Handle edit button click
    $('.edit-room-type').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const price = $(this).data('price');
        const occupancy = $(this).data('occupancy');
        const totalRooms = $(this).data('total-rooms');
        const isActive = $(this).data('active');
        
        $('#edit_room_type_id').val(id);
        $('#edit_name').val(name);
        $('#edit_description').val(description);
        $('#edit_price_per_night').val(price);
        $('#edit_max_occupancy').val(occupancy);
        $('#edit_total_rooms').val(totalRooms);
        $('#edit_is_active').prop('checked', isActive);
        
        $('#editRoomTypeModal').modal('show');
    });
    
    // Handle adjust rooms button click
    $('.adjust-rooms').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const available = $(this).data('available');
        const total = $(this).data('total');
        const action = $(this).data('action');
        
        $('#adjust_room_type_id').val(id);
        $('#adjust_action').val(action);
        $('#adjustRoomTypeName').text(name);
        $('#currentAvailable').text(available);
        $('#totalRooms').text(total);
        
        if (action === 'add') {
            $('#adjustRoomsModalLabel').text('Tambah Kamar Tersedia');
            $('#adjustmentHelp').text(`Maksimal dapat menambah ${total - available} kamar`);
            $('#confirmAdjust').removeClass('btn-warning').addClass('btn-success').text('Tambah Kamar');
            $('#adjustment').attr('max', total - available);
        } else {
            $('#adjustRoomsModalLabel').text('Kurangi Kamar Tersedia');
            $('#adjustmentHelp').text(`Maksimal dapat mengurangi ${available} kamar`);
            $('#confirmAdjust').removeClass('btn-success').addClass('btn-warning').text('Kurangi Kamar');
            $('#adjustment').attr('max', available);
        }
        
        $('#adjustment').val('');
        $('#adjustRoomsModal').modal('show');
    });
    
    // Handle delete button click
    $('.delete-room-type').on('click', function() {
        roomTypeIdToDelete = $(this).data('id');
        const roomTypeName = $(this).data('name');
        
        $('#roomTypeName').text(roomTypeName);
        $('#deleteModal').modal('show');
    });
    
    // Handle add form submission
    $('#addRoomTypeForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/admin/room-types',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addRoomTypeModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message || 'Terjadi kesalahan saat menambah tipe kamar.');
                }
            },
            error: function(xhr) {
                console.log('Add error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat menambah tipe kamar.');
            }
        });
    });
    
    // Handle edit form submission
    $('#editRoomTypeForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#edit_room_type_id').val();
        
        $.ajax({
            url: `/admin/room-types/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editRoomTypeModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message || 'Terjadi kesalahan saat mengupdate tipe kamar.');
                }
            },
            error: function(xhr) {
                console.log('Edit error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat mengupdate tipe kamar.');
            }
        });
    });
    
    // Handle adjust rooms form submission
    $('#adjustRoomsForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#adjust_room_type_id').val();
        const action = $('#adjust_action').val();
        const adjustment = $('#adjustment').val();
        
        if (!adjustment || adjustment <= 0) {
            alert('Masukkan jumlah kamar yang valid.');
            return;
        }
        
        $.ajax({
            url: `/admin/room-types/${id}/adjust-availability`,
            type: 'POST',
            data: {
                action: action,
                adjustment: adjustment
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#adjustRoomsModal').modal('hide');
                    
                    // Show success message
                    const alertDiv = $(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    $('.card').before(alertDiv);
                    
                    // Update the available rooms display in the table
                    const row = $(`.adjust-rooms[data-id="${id}"]`).closest('tr');
                    const availableBadge = row.find('.badge.bg-success');
                    availableBadge.text(response.data.available_rooms);
                    
                    // Update data attributes for buttons
                    row.find('.adjust-rooms').attr('data-available', response.data.available_rooms);
                    
                    // Auto-dismiss alert after 5 seconds
                    setTimeout(() => {
                        alertDiv.fadeOut();
                    }, 5000);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat mengatur ketersediaan kamar.');
                }
            },
            error: function(xhr) {
                console.log('Adjust rooms error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat mengatur ketersediaan kamar.');
            }
        });
    });
    
    // Handle confirm delete
    $('#confirmDelete').on('click', function() {
        if (roomTypeIdToDelete) {
            $.ajax({
                url: `/admin/room-types/${roomTypeIdToDelete}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message || 'Terjadi kesalahan saat menghapus tipe kamar.');
                    }
                },
                error: function(xhr) {
                    console.log('Delete error:', xhr);
                    const response = xhr.responseJSON;
                    alert(response?.message || 'Terjadi kesalahan saat menghapus tipe kamar.');
                }
            });
        }
    });
});
</script>
@endpush
@endsection
