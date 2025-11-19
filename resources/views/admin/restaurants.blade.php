@extends('admin.layout')

@section('title', 'Kelola Restoran')
@section('page-title', 'Kelola Restoran & Menu')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-utensils me-2"></i>
                Daftar Restoran
            </h5>
            <button class="btn btn-primary btn-sm" id="btnAddRestaurant">
                <i class="fas fa-plus me-1"></i>
                Tambah Restoran
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($restaurants->isEmpty())
            <div class="text-center py-4">
                <i class="fas fa-utensils fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">Belum ada restoran. Tambahkan restoran baru untuk halaman /restaurants.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($restaurants as $restaurant)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="ratio ratio-16x9 bg-light">
                                @php
                                    $imageUrl = $restaurant->image_url ?? '/images/heroresto.png';
                                @endphp
                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $restaurant->name }}" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $restaurant->name }}</h5>
                                        @if($restaurant->cuisine_type)
                                            <span class="badge bg-primary">{{ $restaurant->cuisine_type }}</span>
                                        @endif
                                    </div>
                                    <span class="badge bg-secondary">{{ $restaurant->menuItems->count() }} menu</span>
                                </div>
                                @if($restaurant->description)
                                    <p class="card-text text-muted mb-2" style="font-size: 0.9rem;">
                                        {{ \Illuminate\Support\Str::limit($restaurant->description, 110) }}
                                    </p>
                                @endif
                                @if(is_array($restaurant->features) && count($restaurant->features))
                                    <div class="mb-2">
                                        @foreach($restaurant->features as $feature)
                                            @if(!empty($feature))
                                                <span class="badge bg-light text-dark border me-1 mb-1">{{ $feature }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if($restaurant->operating_hours || $restaurant->location)
                                    <div class="mb-3" style="font-size: 0.85rem;">
                                        @if($restaurant->operating_hours)
                                            <div class="text-muted"><i class="fas fa-clock me-1"></i>{{ $restaurant->operating_hours }}</div>
                                        @endif
                                        @if($restaurant->location)
                                            <div class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $restaurant->location }}</div>
                                        @endif
                                    </div>
                                @endif
                                <div class="mt-auto d-flex justify-content-between">
                                    <button class="btn btn-outline-primary btn-sm btn-edit-restaurant" data-id="{{ $restaurant->id }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <button class="btn btn-outline-success btn-sm btn-manage-menu" data-id="{{ $restaurant->id }}" data-name="{{ $restaurant->name }}">
                                        <i class="fas fa-book-open me-1"></i>Menu
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm btn-delete-restaurant" data-id="{{ $restaurant->id }}" data-name="{{ $restaurant->name }}">
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

<!-- Restaurant Modal (Create / Edit) -->
<div class="modal fade" id="restaurantModal" tabindex="-1" aria-labelledby="restaurantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restaurantModalLabel">Tambah Restoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="restaurantForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="restaurant_id" name="restaurant_id">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="restaurant_name" class="form-label">Nama Restoran</label>
                                <input type="text" class="form-control" id="restaurant_name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="restaurant_description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="restaurant_description" name="description" rows="4" placeholder="Deskripsi singkat restoran..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="restaurant_features" class="form-label">Tag Restoran</label>
                                <input type="text" class="form-control" id="restaurant_features" name="features" placeholder="Contoh: Halal, Keluarga, Outdoor, Live Music">
                                <div class="form-text">Pisahkan dengan koma. Tag akan tampil sebagai badge di bawah deskripsi.</div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="cuisine_type" class="form-label">Tipe Masakan</label>
                                <input type="text" class="form-control" id="cuisine_type" name="cuisine_type" placeholder="Misal: Masakan Jawa, Chinese Cuisine">
                            </div>
                            <div class="mb-3">
                                <label for="operating_hours" class="form-label">Jam Operasional</label>
                                <input type="text" class="form-control" id="operating_hours" name="operating_hours" placeholder="Misal: 08:00 - 21:00 WIB">
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="Alamat restoran">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="restaurant_image" class="form-label">Foto Restoran</label>
                                <input type="file" class="form-control" id="restaurant_image" name="image" accept="image/*">
                                <div class="form-text">Max 5MB, format: JPG, PNG, JPEG, WEBP.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preview Foto</label>
                            <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 160px; overflow: hidden;">
                                <img id="restaurant_image_preview" src="" alt="Preview" style="max-height: 100%; max-width: 100%; display: none; object-fit: cover;">
                                <span id="restaurant_image_placeholder" class="text-muted" style="font-size: 0.9rem;">Belum ada foto</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger mt-3 d-none" id="restaurantErrorBox"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="restaurantSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Menu Items Modal -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="menuModalLabel">Menu Restoran</h5>
                    <small class="text-muted" id="menuModalSubtitle"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="fas fa-book-open me-2"></i>Daftar Menu</h6>
                    <button class="btn btn-primary btn-sm" id="btnAddMenuItem">
                        <i class="fas fa-plus me-1"></i>Tambah Menu
                    </button>
                </div>
                <div id="menuItemsContainer">
                    <div class="text-center py-4" id="menuLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Memuat data menu...</p>
                    </div>
                    <div id="menuEmpty" class="text-center py-4 d-none">
                        <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada menu untuk restoran ini.</p>
                    </div>
                    <div id="menuList" class="row g-3 d-none"></div>
                </div>
                <div class="alert alert-danger mt-3 d-none" id="menuErrorBox"></div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Item Modal (Create / Edit) -->
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-labelledby="menuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemModalLabel">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="menuItemForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="menu_item_id" name="menu_item_id">
                    <input type="hidden" id="menu_restaurant_id" name="restaurant_id">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" id="menu_name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="menu_description" name="description" rows="3" placeholder="Deskripsi menu..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="menu_price" name="price" min="0" step="1000" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" id="menu_category" name="category" required>
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Menu</label>
                                <input type="file" class="form-control" id="menu_image" name="image" accept="image/*">
                                <div class="form-text">Max 5MB, format: JPG, PNG, JPEG, WEBP.</div>
                            </div>
                            <label class="form-label">Preview Foto</label>
                            <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 160px; overflow: hidden;">
                                <img id="menu_image_preview" src="" alt="Preview" style="max-height: 100%; max-width: 100%; display: none; object-fit: cover;">
                                <span id="menu_image_placeholder" class="text-muted" style="font-size: 0.9rem;">Belum ada foto</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger mt-3 d-none" id="menuItemErrorBox"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="menuItemSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRestaurantModal" tabindex="-1" aria-labelledby="deleteRestaurantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRestaurantModalLabel">Konfirmasi Hapus Restoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus restoran <strong id="delete_restaurant_name"></strong>?</p>
                <p class="text-danger small mb-0">Semua menu yang terkait juga akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteRestaurant">Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMenuModalLabel">Konfirmasi Hapus Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus menu <strong id="delete_menu_name"></strong>?</p>
                <p class="text-danger small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMenu">Hapus</button>
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
        getRestaurant: "{{ route('admin.restaurants.get', ':id') }}",
        storeRestaurant: "{{ route('admin.restaurants.store') }}",
        updateRestaurant: "{{ route('admin.restaurants.update', ':id') }}",
        deleteRestaurant: "{{ route('admin.restaurants.delete', ':id') }}",
        getMenuItem: "{{ route('admin.menu-items.get', ':id') }}",
        storeMenuItem: "{{ route('admin.menu-items.store') }}",
        updateMenuItem: "{{ route('admin.menu-items.update', ':id') }}",
        deleteMenuItem: "{{ route('admin.menu-items.delete', ':id') }}",
        toggleMenuItemStatus: "{{ route('admin.menu-items.toggle-status', ':id') }}"
    };

    function buildUrl(template, id) {
        return template.replace(':id', id);
    }

    let currentRestaurantId = null;
    let currentRestaurantName = '';
    let deletingRestaurantId = null;
    let deletingMenuId = null;

    // Helpers
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

    function formatPrice(value) {
        const number = parseInt(value, 10) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
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

    // Restaurant handlers
    $('#btnAddRestaurant').on('click', function() {
        $('#restaurantModalLabel').text('Tambah Restoran');
        $('#restaurantForm')[0].reset();
        $('#restaurant_id').val('');
        $('#restaurant_features').val('');
        $('#restaurant_image_preview').hide().attr('src', '');
        $('#restaurant_image_placeholder').show();
        showError($('#restaurantErrorBox'), null);
        $('#restaurantSubmitBtn').prop('disabled', false).text('Simpan');
        const modal = new bootstrap.Modal(document.getElementById('restaurantModal'));
        modal.show();
    });

    $('.btn-edit-restaurant').on('click', function() {
        const id = $(this).data('id');
        $('#restaurantModalLabel').text('Edit Restoran');
        $('#restaurantForm')[0].reset();
        $('#restaurant_id').val(id);
        showError($('#restaurantErrorBox'), null);
        $('#restaurantSubmitBtn').prop('disabled', false).text('Update');

        $.ajax({
            url: buildUrl(routes.getRestaurant, id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success || !response.data) {
                    alert(response.message || 'Gagal mengambil data restoran.');
                    return;
                }
                const r = response.data;
                $('#restaurant_name').val(r.name || '');
                $('#restaurant_description').val(r.description || '');
                $('#cuisine_type').val(r.cuisine_type || '');
                $('#operating_hours').val(r.operating_hours || '');
                $('#location').val(r.location || '');
                const features = Array.isArray(r.features) ? r.features.join(', ') : '';
                $('#restaurant_features').val(features);

                const imageUrl = r.image_url || '';
                if (imageUrl) {
                    $('#restaurant_image_preview').attr('src', imageUrl).show();
                    $('#restaurant_image_placeholder').hide();
                } else {
                    $('#restaurant_image_preview').hide();
                    $('#restaurant_image_placeholder').show();
                }

                const modal = new bootstrap.Modal(document.getElementById('restaurantModal'));
                modal.show();
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data restoran.');
            }
        });
    });

    $('#restaurant_image').on('change', function() {
        setImagePreview(this, $('#restaurant_image_preview'), $('#restaurant_image_placeholder'));
    });

    $('#restaurantForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#restaurant_id').val();
        const isEdit = !!id;
        const url = isEdit ? buildUrl(routes.updateRestaurant, id) : routes.storeRestaurant;

        const formData = new FormData(this);
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        $('#restaurantSubmitBtn').prop('disabled', true).text('Menyimpan...');
        showError($('#restaurantErrorBox'), null);

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
                    showError($('#restaurantErrorBox'), response.errors || response.message || 'Terjadi kesalahan.');
                    $('#restaurantSubmitBtn').prop('disabled', false).text('Simpan');
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showError($('#restaurantErrorBox'), res?.errors || res?.message || 'Terjadi kesalahan.');
                $('#restaurantSubmitBtn').prop('disabled', false).text('Simpan');
            }
        });
    });

    $('.btn-delete-restaurant').on('click', function() {
        deletingRestaurantId = $(this).data('id');
        $('#delete_restaurant_name').text($(this).data('name'));
        const modal = new bootstrap.Modal(document.getElementById('deleteRestaurantModal'));
        modal.show();
    });

    $('#confirmDeleteRestaurant').on('click', function() {
        if (!deletingRestaurantId) return;
        const url = buildUrl(routes.deleteRestaurant, deletingRestaurantId);
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
                    alert(response.message || 'Terjadi kesalahan saat menghapus restoran.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus restoran.');
            },
            complete: function() {
                $('#confirmDeleteRestaurant').prop('disabled', false).text('Hapus');
            }
        });
    });

    // Menu management
    function renderMenuItems(restaurant) {
        const $list = $('#menuList');
        const items = restaurant.menu_items || [];

        if (!items.length) {
            $('#menuLoading').addClass('d-none');
            $('#menuList').addClass('d-none');
            $('#menuEmpty').removeClass('d-none');
            return;
        }

        let html = '';
        items.forEach(function(item) {
            const imageUrl = item.image_url || '';
            const categoryLabel = item.category === 'minuman' ? 'Minuman' : 'Makanan';
            const badgeClass = item.category === 'minuman' ? 'bg-info' : 'bg-success';
            const rawIsActive = item.is_active;
            const isActive = rawIsActive === true || rawIsActive === 1 || rawIsActive === '1';

            html += '<div class="col-md-4">';
            html += '  <div class="card h-100">';
            html += '    <div class="ratio ratio-16x9 bg-light">';
            if (imageUrl) {
                html += '      <img src="' + imageUrl + '" class="card-img-top" style="object-fit: cover;" alt="' + (item.name || '') + '">';
            } else {
                html += '      <div class="d-flex align-items-center justify-content-center text-muted" style="font-size: 0.9rem;">Tidak ada foto</div>';
            }
            html += '    </div>';
            html += '    <div class="card-body d-flex flex-column">';
            html += '      <div class="d-flex justify-content-between align-items-start mb-1">';
            html += '        <div>'; 
            html += '          <h6 class="mb-1">' + (item.name || '') + '</h6>';
            html += '          <span class="badge ' + badgeClass + ' me-1">' + categoryLabel + '</span>';
            html += '          <span class="badge ' + (isActive ? 'bg-success' : 'bg-secondary') + '">' + (isActive ? 'Aktif' : 'Nonaktif') + '</span>';
            html += '        </div>';
            html += '        <div class="text-end fw-bold text-primary" style="font-size: 0.9rem;">' + formatPrice(item.price) + '</div>';
            html += '      </div>';
            if (item.description) {
                html += '      <p class="text-muted mb-2" style="font-size: 0.85rem;">' + item.description + '</p>';
            }
            html += '      <div class="mt-auto d-flex justify-content-between">';
            html += '        <button class="btn btn-outline-primary btn-sm btn-edit-menu" data-id="' + item.id + '"><i class="fas fa-edit me-1"></i>Edit</button>';
            html += '        <button class="btn btn-outline-warning btn-sm btn-toggle-menu" data-id="' + item.id + '" data-active="' + (isActive ? '1' : '0') + '"><i class="fas ' + (isActive ? 'fa-eye-slash' : 'fa-eye') + ' me-1"></i>' + (isActive ? 'Nonaktifkan' : 'Aktifkan') + '</button>';
            html += '        <button class="btn btn-outline-danger btn-sm btn-delete-menu" data-id="' + item.id + '" data-name="' + (item.name || '') + '"><i class="fas fa-trash me-1"></i>Hapus</button>';
            html += '      </div>';
            html += '    </div>';
            html += '  </div>';
            html += '</div>';
        });

        $list.html(html);
        $('#menuLoading').addClass('d-none');
        $('#menuEmpty').addClass('d-none');
        $('#menuList').removeClass('d-none');
    }

    function loadRestaurantMenus(id, name) {
        currentRestaurantId = id;
        currentRestaurantName = name || '';
        $('#menuModalLabel').text('Menu ' + (currentRestaurantName || 'Restoran'));
        $('#menuModalSubtitle').text('Kelola menu untuk ' + (currentRestaurantName || 'restoran terpilih'));
        $('#menuLoading').removeClass('d-none');
        $('#menuEmpty').addClass('d-none');
        $('#menuList').addClass('d-none').empty();
        showError($('#menuErrorBox'), null);

        $.ajax({
            url: buildUrl(routes.getRestaurant, id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success || !response.data) {
                    showError($('#menuErrorBox'), response.message || 'Gagal memuat data menu.');
                    $('#menuLoading').addClass('d-none');
                    return;
                }
                renderMenuItems(response.data);
            },
            error: function() {
                showError($('#menuErrorBox'), 'Terjadi kesalahan saat memuat data menu.');
                $('#menuLoading').addClass('d-none');
            }
        });
    }

    $('.btn-manage-menu').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const modal = new bootstrap.Modal(document.getElementById('menuModal'));
        modal.show();
        loadRestaurantMenus(id, name);
    });

    $('#btnAddMenuItem').on('click', function() {
        if (!currentRestaurantId) return;
        $('#menuItemModalLabel').text('Tambah Menu');
        $('#menuItemForm')[0].reset();
        $('#menu_item_id').val('');
        $('#menu_restaurant_id').val(currentRestaurantId);
        $('#menu_image_preview').hide().attr('src', '');
        $('#menu_image_placeholder').show();
        showError($('#menuItemErrorBox'), null);
        $('#menuItemSubmitBtn').prop('disabled', false).text('Simpan');
        const menuModalEl = document.getElementById('menuModal');
        const existingMenuModal = bootstrap.Modal.getInstance(menuModalEl);
        if (existingMenuModal) {
            existingMenuModal.hide();
        }

        const menuItemModalEl = document.getElementById('menuItemModal');
        const modal = new bootstrap.Modal(menuItemModalEl);
        modal.show();

        menuItemModalEl.addEventListener('hidden.bs.modal', function handleHidden() {
            menuItemModalEl.removeEventListener('hidden.bs.modal', handleHidden);
            const menuModal = new bootstrap.Modal(menuModalEl);
            menuModal.show();
        });
    });

    $('#menu_image').on('change', function() {
        setImagePreview(this, $('#menu_image_preview'), $('#menu_image_placeholder'));
    });

    $('#menuList').on('click', '.btn-edit-menu', function() {
        const id = $(this).data('id');
        if (!id) return;
        $('#menuItemForm')[0].reset();
        $('#menu_item_id').val(id);
        showError($('#menuItemErrorBox'), null);
        $('#menuItemSubmitBtn').prop('disabled', false).text('Update');

        $.ajax({
            url: buildUrl(routes.getMenuItem, id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (!response.success || !response.data) {
                    alert(response.message || 'Gagal mengambil data menu.');
                    return;
                }
                const m = response.data;
                $('#menu_restaurant_id').val(m.restaurant_id || currentRestaurantId || '');
                $('#menu_name').val(m.name || '');
                $('#menu_description').val(m.description || '');
                $('#menu_category').val(m.category || 'makanan');
                $('#menu_price').val(m.price || '');

                const imageUrl = m.image_url || '';
                if (imageUrl) {
                    $('#menu_image_preview').attr('src', imageUrl).show();
                    $('#menu_image_placeholder').hide();
                } else {
                    $('#menu_image_preview').hide();
                    $('#menu_image_placeholder').show();
                }

                $('#menuItemModalLabel').text('Edit Menu');
                const menuModalEl = document.getElementById('menuModal');
                const existingMenuModal = bootstrap.Modal.getInstance(menuModalEl);
                if (existingMenuModal) {
                    existingMenuModal.hide();
                }

                const menuItemModalEl = document.getElementById('menuItemModal');
                const modal = new bootstrap.Modal(menuItemModalEl);
                modal.show();

                menuItemModalEl.addEventListener('hidden.bs.modal', function handleHidden() {
                    menuItemModalEl.removeEventListener('hidden.bs.modal', handleHidden);
                    const menuModal = new bootstrap.Modal(menuModalEl);
                    menuModal.show();
                });
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data menu.');
            }
        });
    });

    $('#menuItemForm').on('submit', function(e) {
        e.preventDefault();
        if (!currentRestaurantId) {
            alert('Restoran tidak valid.');
            return;
        }
        const id = $('#menu_item_id').val();
        const isEdit = !!id;
        const url = isEdit ? buildUrl(routes.updateMenuItem, id) : routes.storeMenuItem;

        const formData = new FormData(this);
        formData.set('restaurant_id', $('#menu_restaurant_id').val() || currentRestaurantId);
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        $('#menuItemSubmitBtn').prop('disabled', true).text('Menyimpan...');
        showError($('#menuItemErrorBox'), null);

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
                    // setelah simpan, refresh list menu
                    const modalEl = document.getElementById('menuItemModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    loadRestaurantMenus(currentRestaurantId, currentRestaurantName);
                } else {
                    showError($('#menuItemErrorBox'), response.errors || response.message || 'Terjadi kesalahan.');
                    $('#menuItemSubmitBtn').prop('disabled', false).text('Simpan');
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showError($('#menuItemErrorBox'), res?.errors || res?.message || 'Terjadi kesalahan.');
                $('#menuItemSubmitBtn').prop('disabled', false).text('Simpan');
            }
        });
    });

    $('#menuList').on('click', '.btn-delete-menu', function() {
        deletingMenuId = $(this).data('id');
        $('#delete_menu_name').text($(this).data('name'));
        const modal = new bootstrap.Modal(document.getElementById('deleteMenuModal'));
        modal.show();
    });

    $('#confirmDeleteMenu').on('click', function() {
        if (!deletingMenuId) return;
        const url = buildUrl(routes.deleteMenuItem, deletingMenuId);
        $('#confirmDeleteMenu').prop('disabled', true).text('Menghapus...');

        $.ajax({
            url: url,
            type: 'POST',
            data: { _method: 'DELETE' },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    const modalEl = document.getElementById('deleteMenuModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    loadRestaurantMenus(currentRestaurantId, currentRestaurantName);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat menghapus menu.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus menu.');
            },
            complete: function() {
                $('#confirmDeleteMenu').prop('disabled', false).text('Hapus');
            }
        });
    });

    $('#menuList').on('click', '.btn-toggle-menu', function() {
        const id = $(this).data('id');
        if (!id) return;
        const url = buildUrl(routes.toggleMenuItemStatus, id);
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
                    loadRestaurantMenus(currentRestaurantId, currentRestaurantName);
                } else {
                    alert(response.message || 'Terjadi kesalahan saat mengubah status menu.');
                    $btn.prop('disabled', false);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengubah status menu.');
                $btn.prop('disabled', false);
            }
        });
    });
})();
</script>
@endpush
