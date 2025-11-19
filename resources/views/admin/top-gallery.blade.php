@extends('admin.layout')

@section('title', 'Kelola Top Galeri')
@section('page-title', 'Kelola Top Galeri')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-images me-2"></i>
                Top Galeri Selecta
            </h5>
            <button class="btn btn-primary btn-sm" id="btnAddGallery">
                <i class="fas fa-plus me-1"></i>
                Tambah Foto
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($galleries->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-images fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">Belum ada data Top Galeri. Tambahkan foto untuk section galeri.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($galleries as $photo)
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative ratio ratio-4x3 bg-light">
                                @php
                                    $imageUrl = $photo->image_url ?? '/images/galeri1.jpeg';
                                @endphp
                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $photo->title }}" style="object-fit: cover;">
                               @if($photo->photo_date)
    <span class="badge position-absolute top-0 start-0 m-0" style="background: rgba(0,0,0,0.5); border-radius: 4px; font-size: 0.65rem; padding: 4px 8px; color: white;">
        {{ $photo->photo_date->format('d M Y') }}
    </span>
@endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="card-title mb-1">{{ $photo->title }}</h6>
                                        <small class="text-muted">Urutan: {{ $photo->sort_order }}</small>
                                    </div>
                                    <span class="badge {{ $photo->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $photo->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <div class="mt-auto d-flex justify-content-between">
                                    <button class="btn btn-outline-primary btn-sm btn-edit-gallery" data-id="{{ $photo->id }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <button class="btn btn-outline-success btn-sm btn-toggle-gallery" data-id="{{ $photo->id }}" data-active="{{ $photo->is_active ? '1' : '0' }}">
                                        <i class="fas {{ $photo->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                        {{ $photo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm btn-delete-gallery" data-id="{{ $photo->id }}" data-title="{{ $photo->title }}">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel">Tambah Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="galleryForm" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="gallery_id" name="gallery_id">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="gallery_title" class="form-label">Judul Foto</label>
                                <input type="text" class="form-control" id="gallery_title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="gallery_date" class="form-label">Tanggal Foto / Upload</label>
                                <input type="date" class="form-control" id="gallery_date" name="photo_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="gallery_sort_order" class="form-label">Urutan Tampil</label>
                                <input type="number" class="form-control" id="gallery_sort_order" name="sort_order" value="0" min="0">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="gallery_is_active" name="is_active" checked>
                                <label class="form-check-label" for="gallery_is_active">
                                    Tampilkan di halaman galeri
                                </label>
                            </div>
                            <div class="alert alert-danger mt-3 d-none" id="galleryErrorBox"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="gallery_image" class="form-label">File Foto</label>
                                <input type="file" class="form-control" id="gallery_image" name="image" accept="image/*">
                                <div class="form-text">Max 5MB, format: JPG, PNG, JPEG, WEBP.</div>
                            </div>
                            <label class="form-label">Preview Foto</label>
                            <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 180px; overflow: hidden;">
                                <img id="gallery_image_preview" src="" alt="Preview" style="max-height: 100%; max-width: 100%; display: none; object-fit: cover;">
                                <span id="gallery_image_placeholder" class="text-muted" style="font-size: 0.9rem;">Belum ada foto</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="gallerySubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteGalleryModal" tabindex="-1" aria-labelledby="deleteGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGalleryModalLabel">Konfirmasi Hapus Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus foto <strong id="delete_gallery_title"></strong>?</p>
                <p class="text-danger small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteGallery">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const routes = {
        get: "{{ route('admin.top-gallery.get', ':id') }}",
        store: "{{ route('admin.top-gallery.store') }}",
        update: "{{ route('admin.top-gallery.update', ':id') }}",
        delete: "{{ route('admin.top-gallery.delete', ':id') }}",
        toggleStatus: "{{ route('admin.top-gallery.toggle-status', ':id') }}"
    };

    function buildUrl(template, id) {
        return template.replace(':id', id);
    }

    function setImagePreview(input, $img, $placeholder) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $img.attr('src', e.target.result).show();
                $placeholder.hide();
            };
            reader.readAsDataURL(file);
        } else {
            $img.hide();
            $placeholder.show();
        }
    }

    function showError($box, errors) {
        if (!errors) {
            $box.addClass('d-none');
            $box.html('');
            return;
        }
        let html = '';
        if (typeof errors === 'string') {
            html = errors;
        } else {
            html = '<ul class="mb-0">';
            Object.keys(errors).forEach(function(key) {
                errors[key].forEach(function(msg) {
                    html += '<li>' + msg + '</li>';
                });
            });
            html += '</ul>';
        }
        $box.removeClass('d-none').html(html);
    }

    function setTodayDate() {
        const input = document.getElementById('gallery_date');
        if (!input) return;
        if (input.value) return;
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        input.value = year + '-' + month + '-' + day;
    }

    let deletingGalleryId = null;

    $('#btnAddGallery').on('click', function() {
        $('#galleryModalLabel').text('Tambah Foto');
        $('#galleryForm')[0].reset();
        $('#gallery_id').val('');
        $('#gallery_image_preview').hide().attr('src', '');
        $('#gallery_image_placeholder').show();
        $('#gallery_is_active').prop('checked', true);
        $('#gallerySubmitBtn').prop('disabled', false).text('Simpan');
        showError($('#galleryErrorBox'), null);
        setTodayDate();
        const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
        modal.show();
    });

    $('#gallery_image').on('change', function() {
        setImagePreview(this, $('#gallery_image_preview'), $('#gallery_image_placeholder'));
    });

    $('.btn-edit-gallery').on('click', function() {
        const id = $(this).data('id');
        $('#galleryModalLabel').text('Edit Foto');
        $('#galleryForm')[0].reset();
        $('#gallery_id').val(id);
        showError($('#galleryErrorBox'), null);
        $('#gallerySubmitBtn').prop('disabled', false).text('Update');

        $.ajax({
            url: buildUrl(routes.get, id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success || !response.data) {
                    alert(response.message || 'Gagal mengambil data foto.');
                    return;
                }
                const p = response.data;
                $('#gallery_title').val(p.title || '');
                $('#gallery_date').val(p.photo_date || '');
                $('#gallery_sort_order').val(p.sort_order);
                $('#gallery_is_active').prop('checked', !!p.is_active);

                const imageUrl = p.image_url || '';
                if (imageUrl) {
                    $('#gallery_image_preview').attr('src', imageUrl).show();
                    $('#gallery_image_placeholder').hide();
                } else {
                    $('#gallery_image_preview').hide();
                    $('#gallery_image_placeholder').show();
                }

                const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
                modal.show();
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data foto.');
            }
        });
    });

    $('#galleryForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#gallery_id').val();
        const isEdit = !!id;
        const url = isEdit ? buildUrl(routes.update, id) : routes.store;

        const formData = new FormData(this);
        if (isEdit) {
            formData.append('_method', 'PUT');
        }
        if (!formData.has('is_active')) {
            formData.append('is_active', '0');
        }

        $('#gallerySubmitBtn').prop('disabled', true).text('Menyimpan...');
        showError($('#galleryErrorBox'), null);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showError($('#galleryErrorBox'), response.errors || response.message || 'Terjadi kesalahan.');
                    $('#gallerySubmitBtn').prop('disabled', false).text('Simpan');
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showError($('#galleryErrorBox'), res?.errors || res?.message || 'Terjadi kesalahan.');
                $('#gallerySubmitBtn').prop('disabled', false).text('Simpan');
            }
        });
    });

    $('.btn-delete-gallery').on('click', function() {
        deletingGalleryId = $(this).data('id');
        $('#delete_gallery_title').text($(this).data('title'));
        const modal = new bootstrap.Modal(document.getElementById('deleteGalleryModal'));
        modal.show();
    });

    $('#confirmDeleteGallery').on('click', function() {
        if (!deletingGalleryId) return;
        const url = buildUrl(routes.delete, deletingGalleryId);
        $(this).prop('disabled', true).text('Menghapus...');

        $.ajax({
            url: url,
            type: 'POST',
            data: { _method: 'DELETE' },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Terjadi kesalahan saat menghapus foto.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus foto.');
            },
            complete: function() {
                $('#confirmDeleteGallery').prop('disabled', false).text('Hapus');
            }
        });
    });

    $('.btn-toggle-gallery').on('click', function() {
        const id = $(this).data('id');
        const url = buildUrl(routes.toggleStatus, id);
        const $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Terjadi kesalahan saat mengubah status foto.');
                    $btn.prop('disabled', false);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengubah status foto.');
                $btn.prop('disabled', false);
            }
        });
    });
})();
</script>
@endpush
