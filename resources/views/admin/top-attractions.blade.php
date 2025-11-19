@extends('admin.layout')

@section('title', 'Kelola Top Wahana')
@section('page-title', 'Kelola Top Wahana')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-mountain-sun me-2"></i>
                Top Wahana Selecta
            </h5>
            <button class="btn btn-primary btn-sm" id="btnAddAttraction">
                <i class="fas fa-plus me-1"></i>
                Tambah Wahana
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($attractions->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-mountain-sun fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">Belum ada data Top Wahana. Tambahkan wahana untuk section Top Wahana di landing page.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($attractions as $attraction)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="ratio ratio-16x9 bg-light">
                                @php
                                    $imageUrl = $attraction->image_url ?? '/images/familycoaster.png';
                                @endphp
                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $attraction->title }}" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $attraction->title }}</h5>
                                        @if($attraction->location)
                                            <span class="badge bg-primary"><i class="fas fa-map-marker-alt me-1"></i>{{ $attraction->location }}</span>
                                        @endif
                                    </div>
                                    <span class="badge {{ $attraction->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $attraction->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                @if($attraction->description)
                                    <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                                        {{ \Illuminate\Support\Str::limit($attraction->description, 110) }}
                                    </p>
                                @endif
                                <div class="mt-auto d-flex justify-content-between">
                                    <button class="btn btn-outline-primary btn-sm btn-edit-attraction" data-id="{{ $attraction->id }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm btn-toggle-attraction" data-id="{{ $attraction->id }}" data-active="{{ $attraction->is_active ? '1' : '0' }}">
                                        <i class="fas {{ $attraction->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                        {{ $attraction->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm btn-delete-attraction" data-id="{{ $attraction->id }}" data-title="{{ $attraction->title }}">
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

<div class="modal fade" id="attractionModal" tabindex="-1" aria-labelledby="attractionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attractionModalLabel">Tambah Wahana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="attractionForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="attraction_id" name="attraction_id">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="attraction_title" class="form-label">Judul Wahana</label>
                                <input type="text" class="form-control" id="attraction_title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="attraction_location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="attraction_location" name="location" placeholder="Contoh: Area Tengah Taman Bunga">
                            </div>
                            <div class="mb-3">
                                <label for="attraction_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="attraction_description" name="description" rows="4" placeholder="Deskripsi singkat wahana..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="attraction_image" class="form-label">Foto Wahana</label>
                                <input type="file" class="form-control" id="attraction_image" name="image" accept="image/*">
                                <div class="form-text">Max 5MB, format: JPG, PNG, JPEG, WEBP.</div>
                            </div>
                            <label class="form-label">Preview Foto</label>
                            <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 160px; overflow: hidden;">
                                <img id="attraction_image_preview" src="" alt="Preview" style="max-height: 100%; max-width: 100%; display: none; object-fit: cover;">
                                <span id="attraction_image_placeholder" class="text-muted" style="font-size: 0.9rem;">Belum ada foto</span>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" value="1" id="attraction_is_active" name="is_active" checked>
                                <label class="form-check-label" for="attraction_is_active">
                                    Tampilkan di landing page
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger mt-3 d-none" id="attractionErrorBox"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="attractionSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAttractionModal" tabindex="-1" aria-labelledby="deleteAttractionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAttractionModalLabel">Konfirmasi Hapus Wahana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus wahana <strong id="delete_attraction_title"></strong>?</p>
                <p class="text-danger small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAttraction">Hapus</button>
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
        getAttraction: "{{ route('admin.top-attractions.get', ':id') }}",
        storeAttraction: "{{ route('admin.top-attractions.store') }}",
        updateAttraction: "{{ route('admin.top-attractions.update', ':id') }}",
        deleteAttraction: "{{ route('admin.top-attractions.delete', ':id') }}",
        toggleAttractionStatus: "{{ route('admin.top-attractions.toggle-status', ':id') }}"
    };

    function buildUrl(template, id) {
        return template.replace(':id', id);
    }

    let deletingAttractionId = null;

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

    $('#btnAddAttraction').on('click', function() {
        $('#attractionModalLabel').text('Tambah Wahana');
        $('#attractionForm')[0].reset();
        $('#attraction_id').val('');
        $('#attraction_image_preview').hide().attr('src', '');
        $('#attraction_image_placeholder').show();
        $('#attraction_is_active').prop('checked', true);
        showError($('#attractionErrorBox'), null);
        $('#attractionSubmitBtn').prop('disabled', false).text('Simpan');
        const modal = new bootstrap.Modal(document.getElementById('attractionModal'));
        modal.show();
    });

    $('#attraction_image').on('change', function() {
        setImagePreview(this, $('#attraction_image_preview'), $('#attraction_image_placeholder'));
    });

    $('.btn-edit-attraction').on('click', function() {
        const id = $(this).data('id');
        $('#attractionModalLabel').text('Edit Wahana');
        $('#attractionForm')[0].reset();
        $('#attraction_id').val(id);
        showError($('#attractionErrorBox'), null);
        $('#attractionSubmitBtn').prop('disabled', false).text('Update');

        $.ajax({
            url: buildUrl(routes.getAttraction, id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success || !response.data) {
                    alert(response.message || 'Gagal mengambil data wahana.');
                    return;
                }
                const a = response.data;
                $('#attraction_title').val(a.title || '');
                $('#attraction_location').val(a.location || '');
                $('#attraction_description').val(a.description || '');
                $('#attraction_is_active').prop('checked', !!a.is_active);

                const imageUrl = a.image_url || '';
                if (imageUrl) {
                    $('#attraction_image_preview').attr('src', imageUrl).show();
                    $('#attraction_image_placeholder').hide();
                } else {
                    $('#attraction_image_preview').hide();
                    $('#attraction_image_placeholder').show();
                }

                const modal = new bootstrap.Modal(document.getElementById('attractionModal'));
                modal.show();
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data wahana.');
            }
        });
    });

    $('#attractionForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#attraction_id').val();
        const isEdit = !!id;
        const url = isEdit ? buildUrl(routes.updateAttraction, id) : routes.storeAttraction;

        const formData = new FormData(this);
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        $('#attractionSubmitBtn').prop('disabled', true).text('Menyimpan...');
        showError($('#attractionErrorBox'), null);

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
                    showError($('#attractionErrorBox'), response.errors || response.message || 'Terjadi kesalahan.');
                    $('#attractionSubmitBtn').prop('disabled', false).text('Simpan');
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showError($('#attractionErrorBox'), res?.errors || res?.message || 'Terjadi kesalahan.');
                $('#attractionSubmitBtn').prop('disabled', false).text('Simpan');
            }
        });
    });

    $('.btn-delete-attraction').on('click', function() {
        deletingAttractionId = $(this).data('id');
        $('#delete_attraction_title').text($(this).data('title'));
        const modal = new bootstrap.Modal(document.getElementById('deleteAttractionModal'));
        modal.show();
    });

    $('#confirmDeleteAttraction').on('click', function() {
        if (!deletingAttractionId) return;
        const url = buildUrl(routes.deleteAttraction, deletingAttractionId);
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
                    alert(response.message || 'Terjadi kesalahan saat menghapus wahana.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus wahana.');
            },
            complete: function() {
                $('#confirmDeleteAttraction').prop('disabled', false).text('Hapus');
            }
        });
    });

    $('.btn-toggle-attraction').on('click', function() {
        const id = $(this).data('id');
        const url = buildUrl(routes.toggleAttractionStatus, id);
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
                    alert(response.message || 'Terjadi kesalahan saat mengubah status wahana.');
                    $btn.prop('disabled', false);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengubah status wahana.');
                $btn.prop('disabled', false);
            }
        });
    });
})();
</script>
@endpush
