@extends('admin.layout')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Package: {{ $package->name }}
                    </h5>
                    <a href="{{ route('admin.packages') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="editPackageForm" method="POST" action="{{ route('admin.packages.update', $package->id) }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Package <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $package->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $package->price) }}" 
                                           min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga akan otomatis tersinkronisasi dengan Midtrans</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="features" class="form-label">Fitur</label>
                                <input type="text" class="form-control @error('features') is-invalid @enderror" 
                                       id="features" name="features" 
                                       value="{{ old('features', is_array($package->features) ? implode(', ', $package->features) : '') }}"
                                       placeholder="Pisahkan dengan koma (,)">
                                @error('features')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: WiFi Gratis, Sarapan, Kolam Renang</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="badge" class="form-label">Badge</label>
                                <input type="text" class="form-control @error('badge') is-invalid @enderror" 
                                       id="badge" name="badge" value="{{ old('badge', $package->badge) }}"
                                       placeholder="Contoh: Popular, Best Deal">
                                @error('badge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Package Aktif
                            </label>
                        </div>
                        <small class="text-muted">Package yang tidak aktif tidak akan ditampilkan di website</small>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.packages') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Package
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">ID Package</small>
                    <div class="fw-medium">{{ $package->id }}</div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Harga Saat Ini</small>
                    <div class="fw-medium text-success">Rp {{ number_format($package->price) }}</div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Status</small>
                    <div>
                        <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Dibuat</small>
                    <div class="fw-medium">{{ $package->created_at->format('d M Y H:i') }}</div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Terakhir Diupdate</small>
                    <div class="fw-medium">{{ $package->updated_at->format('d M Y H:i') }}</div>
                </div>
                
                <hr>
                
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tips:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Perubahan harga akan otomatis tersinkronisasi dengan Midtrans</li>
                        <li>Package yang tidak aktif tidak akan muncul di website</li>
                        <li>Fitur dipisahkan dengan koma</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Regular form submission (fallback)
    $('#editPackageForm').on('submit', function(e) {
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');
        // Let form submit normally
    });
    
    // AJAX submission
    $('#submitAjaxBtn').on('click', function(e) {
        e.preventDefault();
        
        const form = $('#editPackageForm');
        const submitBtn = $(this);
        const originalText = submitBtn.html();
        
        // Disable submit button
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    // Show success message
                    const alert = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    $('.card-body').prepend(alert);
                    
                    // Scroll to top
                    $('html, body').animate({ scrollTop: 0 }, 500);
                    
                    // Update price display if changed
                    if (response.package) {
                        $('.text-success').text('Rp ' + new Intl.NumberFormat('id-ID').format(response.package.price));
                    }
                    
                    // Optionally redirect after 2 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.packages") }}';
                    }, 2000);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat menyimpan package.');
                }
            },
            error: function(xhr) {
                console.log('Error response:', xhr);
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    // Show validation errors
                    Object.keys(response.errors).forEach(function(field) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').remove();
                        input.after(`<div class="invalid-feedback">${response.errors[field][0]}</div>`);
                    });
                } else {
                    alert((response && response.message) || 'Terjadi kesalahan saat menyimpan package.');
                }
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Clear validation errors on input
    $('input, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').remove();
    });
});
</script>
@endpush
@endsection
