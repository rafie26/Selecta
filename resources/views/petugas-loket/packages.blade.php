@extends('petugas-loket.layout')

@section('title', 'Kelola Ticket')
@section('page-title', 'Kelola Ticket')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(request('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ request('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-box me-2"></i>
                Daftar Ticket
            </h5>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">Total: {{ $packages->total() }} tickets</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                    <i class="fas fa-plus me-1"></i>
                    Tambah Ticket
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
                        <th>Nama Ticket</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>
                                <div class="fw-medium">{{ $package->name }}</div>
                                @if($package->description)
                                    <small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-medium text-success">Rp {{ number_format($package->price) }}</span>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="{{ $package->id }}"
                                           {{ $package->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>{{ $package->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm edit-package" 
                                            data-id="{{ $package->id }}"
                                            data-name="{{ $package->name }}"
                                            data-description="{{ $package->description }}"
                                            data-price="{{ $package->price }}"
                                            data-features="{{ is_array($package->features) ? implode(', ', $package->features) : '' }}"
                                            data-badge="{{ $package->badge }}"
                                            data-active="{{ $package->is_active }}"
                                            title="Edit Package">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm delete-ticket" 
                                            data-id="{{ $package->id }}" 
                                            data-name="{{ $package->name }}"
                                            title="Hapus Ticket">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Belum ada tickets</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($packages->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPackageModalLabel">Tambah Paket Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPackageForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="features" class="form-label">Fitur (pisahkan dengan koma)</label>
                        <textarea class="form-control" id="features" name="features" rows="2" placeholder="Fitur 1, Fitur 2, Fitur 3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="badge" class="form-label">Badge (Opsional)</label>
                        <input type="text" class="form-control" id="badge" name="badge" placeholder="Premium, Best Seller, dll">
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

<!-- Edit Package Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPackageModalLabel">Edit Paket Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPackageForm">
                <input type="hidden" id="edit_package_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit_price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_features" class="form-label">Fitur (pisahkan dengan koma)</label>
                        <textarea class="form-control" id="edit_features" name="features" rows="2" placeholder="Fitur 1, Fitur 2, Fitur 3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_badge" class="form-label">Badge (Opsional)</label>
                        <input type="text" class="form-control" id="edit_badge" name="badge" placeholder="Premium, Best Seller, dll">
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus ticket <strong id="ticketName"></strong>?</p>
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
<script>
$(document).ready(function() {
    let ticketIdToDelete = null;
    
    // Handle edit button click
    $('.edit-package').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const price = $(this).data('price');
        const features = $(this).data('features');
        const badge = $(this).data('badge');
        const isActive = $(this).data('active');
        
        $('#edit_package_id').val(id);
        $('#edit_name').val(name);
        $('#edit_description').val(description);
        $('#edit_price').val(price);
        $('#edit_features').val(features);
        $('#edit_badge').val(badge);
        $('#edit_is_active').prop('checked', isActive);
        
        $('#editPackageModal').modal('show');
    });
    
    // Handle add form submission
    $('#addPackageForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/petugas-loket/packages',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addPackageModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message || 'Terjadi kesalahan saat menambah paket.');
                }
            },
            error: function(xhr) {
                console.log('Add error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat menambah paket.');
            }
        });
    });
    
    // Handle edit form submission
    $('#editPackageForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#edit_package_id').val();
        
        $.ajax({
            url: `/petugas-loket/packages/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editPackageModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message || 'Terjadi kesalahan saat mengupdate paket.');
                }
            },
            error: function(xhr) {
                console.log('Edit error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat mengupdate paket.');
            }
        });
    });
    
    // Handle delete button click
    $('.delete-ticket').on('click', function() {
        ticketIdToDelete = $(this).data('id');
        const ticketName = $(this).data('name');
        
        $('#ticketName').text(ticketName);
        $('#deleteModal').modal('show');
    });
    
    // Handle confirm delete
    $('#confirmDelete').on('click', function() {
        if (ticketIdToDelete) {
            $.ajax({
                url: `/petugas-loket/packages/${ticketIdToDelete}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message || 'Terjadi kesalahan saat menghapus ticket.');
                    }
                },
                error: function(xhr) {
                    console.log('Delete error:', xhr);
                    const response = xhr.responseJSON;
                    alert(response?.message || 'Terjadi kesalahan saat menghapus ticket.');
                }
            });
        }
    });
    
    // Handle status toggle
    $('.toggle-status').on('change', function() {
        const packageId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        const $badge = $(this).siblings('label').find('.badge');
        
        $.ajax({
            url: `/petugas-loket/packages/${packageId}/toggle-status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Update badge
                    if (response.is_active) {
                        $badge.removeClass('bg-secondary').addClass('bg-success').text('Active');
                    } else {
                        $badge.removeClass('bg-success').addClass('bg-secondary').text('Inactive');
                    }
                    
                    // Show success message
                    $('body').prepend(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Auto dismiss after 3 seconds
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 3000);
                } else {
                    // Revert checkbox state
                    $(this).prop('checked', !isChecked);
                    alert(response.message || 'Terjadi kesalahan saat mengubah status.');
                }
            }.bind(this),
            error: function(xhr) {
                // Revert checkbox state
                $(this).prop('checked', !isChecked);
                console.log('Toggle error:', xhr);
                const response = xhr.responseJSON;
                alert(response?.message || 'Terjadi kesalahan saat mengubah status.');
            }.bind(this)
        });
    });
});
</script>
@endpush
@endsection
