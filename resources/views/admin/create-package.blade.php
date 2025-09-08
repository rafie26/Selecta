@extends('admin.layout')

@section('title', 'Tambah Package')
@section('page-title', 'Tambah Package')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Tambah Package Baru
                    </h5>
                    <a href="{{ route('admin.packages') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="createPackageForm" method="POST" action="{{ route('admin.packages.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Package <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
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
                                           id="price" name="price" value="{{ old('price') }}" 
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
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="features" class="form-label">Fitur</label>
                                <input type="text" class="form-control @error('features') is-invalid @enderror" 
                                       id="features" name="features" value="{{ old('features') }}"
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
                                       id="badge" name="badge" value="{{ old('badge') }}"
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
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Package Aktif
                            </label>
                        </div>
                        <small class="text-muted">Package yang tidak aktif tidak akan ditampilkan di website</small>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.packages') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Package
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
                    Panduan Membuat Package
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tips Membuat Package:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Gunakan nama yang jelas dan menarik</li>
                        <li>Set harga yang kompetitif</li>
                        <li>Deskripsi yang detail akan menarik lebih banyak pengunjung</li>
                        <li>Fitur dipisahkan dengan koma (,)</li>
                        <li>Badge membantu package menonjol</li>
                    </ul>
                </div>
                
                <div class="alert alert-success">
                    <i class="fas fa-sync-alt me-2"></i>
                    <strong>Sinkronisasi Midtrans:</strong>
                    <p class="mb-0">Harga yang Anda set akan otomatis tersinkronisasi dengan sistem pembayaran Midtrans untuk memastikan konsistensi harga.</p>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-eye me-2"></i>
                    <strong>Visibilitas:</strong>
                    <p class="mb-0">Package yang tidak aktif tidak akan ditampilkan di website publik, tetapi masih dapat diedit di admin panel.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#createPackageForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Disable submit button
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Membuat...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Redirect to packages list with success message
                    window.location.href = '{{ route("admin.packages") }}?success=' + encodeURIComponent(response.message);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat membuat package.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response.errors) {
                    // Show validation errors
                    Object.keys(response.errors).forEach(function(field) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').remove();
                        input.after(`<div class="invalid-feedback">${response.errors[field][0]}</div>`);
                    });
                } else {
                    alert(response.message || 'Terjadi kesalahan saat membuat package.');
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
    
    // Format price input
    $('#price').on('input', function() {
        let value = $(this).val();
        if (value) {
            // Remove non-numeric characters except decimal point
            value = value.replace(/[^0-9]/g, '');
            $(this).val(value);
        }
    });
});
</script>
@endpush
@endsection
