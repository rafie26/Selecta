@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Kelola Restoran</h1>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" onclick="openAddRestaurantModal()">
                <i class="fas fa-plus"></i> Tambah Restoran
            </button>
        </div>
    </div>

    <!-- Restaurants Grid -->
    <div class="row" id="restaurantsContainer">
        @forelse($restaurants as $restaurant)
            <div class="col-md-4 mb-4" id="restaurant-{{ $restaurant->id }}">
                <div class="card h-100">
                    <div class="card-img-top" style="height: 200px; background-size: cover; background-position: center;" data-image-url="{{ $restaurant->image_path ? asset('storage/' . $restaurant->image_path) : '/images/placeholder.png' }}"></div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($restaurant->description, 80) }}</p>
                        <div class="mb-3">
                            <small class="badge bg-info">{{ $restaurant->cuisine_type }}</small>
                            <small class="badge bg-secondary">{{ $restaurant->menuItems->count() }} Menu</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <button class="btn btn-sm btn-warning" data-restaurant-id="{{ $restaurant->id }}" onclick="editRestaurant(this.dataset.restaurantId)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-info" data-restaurant-id="{{ $restaurant->id }}" onclick="manageMenuItems(this.dataset.restaurantId)">
                            <i class="fas fa-utensils"></i> Menu
                        </button>
                        <button class="btn btn-sm btn-danger" data-restaurant-id="{{ $restaurant->id }}" onclick="deleteRestaurant(this.dataset.restaurantId)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada restoran. Silakan tambahkan restoran baru.</div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add/Edit Restaurant Modal -->
<div class="modal fade" id="restaurantModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restaurantModalTitle">Tambah Restoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="restaurantForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="restaurantId">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Restoran</label>
                        <input type="text" class="form-control" id="restaurantName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="restaurantDescription" name="description" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Masakan</label>
                            <input type="text" class="form-control" id="restaurantCuisineType" name="cuisine_type" placeholder="Contoh: Masakan Jawa">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Operasional</label>
                            <input type="text" class="form-control" id="restaurantOperatingHours" name="operating_hours" placeholder="Contoh: 08:00 - 21:00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="restaurantLocation" name="location" placeholder="Contoh: Jl. Raya Selecta No. 1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fitur (pisahkan dengan koma)</label>
                        <input type="text" class="form-control" id="restaurantFeatures" name="features" placeholder="Contoh: Family Friendly, Halal, WiFi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Restoran</label>
                        <input type="file" class="form-control" id="restaurantImage" name="image" accept="image/*">
                        <div id="restaurantImagePreview" class="mt-2"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Menu Items Modal -->
<div class="modal fade" id="menuItemsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemsModalTitle">Kelola Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h6>Daftar Menu</h6>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-sm btn-success" onclick="openAddMenuItemModal()">
                            <i class="fas fa-plus"></i> Tambah Menu
                        </button>
                    </div>
                </div>
                <div id="menuItemsContainer"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Menu Item Modal -->
<div class="modal fade" id="menuItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemModalTitle">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="menuItemForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="menuItemId">
                    <input type="hidden" id="menuItemRestaurantId" name="restaurant_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" id="menuItemName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="menuItemDescription" name="description" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-control" id="menuItemCategory" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" id="menuItemPrice" name="price" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Menu</label>
                        <input type="file" class="form-control" id="menuItemImage" name="image" accept="image/*">
                        <div id="menuItemImagePreview" class="mt-2"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card-img-top {
        border-radius: 0.25rem 0.25rem 0 0;
    }
    
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<script>
    let currentRestaurantId = null;
    let currentMenuItemId = null;
    const restaurantModal = new bootstrap.Modal(document.getElementById('restaurantModal'));
    const menuItemsModal = new bootstrap.Modal(document.getElementById('menuItemsModal'));
    const menuItemModal = new bootstrap.Modal(document.getElementById('menuItemModal'));

    // Set background images from data attributes
    document.querySelectorAll('.card-img-top').forEach(el => {
        const imageUrl = el.dataset.imageUrl;
        if (imageUrl) {
            el.style.backgroundImage = `url('${imageUrl}')`;
        }
    });

    // Open Add Restaurant Modal
    function openAddRestaurantModal() {
        document.getElementById('restaurantId').value = '';
        document.getElementById('restaurantForm').reset();
        document.getElementById('restaurantModalTitle').textContent = 'Tambah Restoran';
        document.getElementById('restaurantImagePreview').innerHTML = '';
        restaurantModal.show();
    }

    // Edit Restaurant
    function editRestaurant(id) {
        fetch(`/admin/restaurants/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const restaurant = data.restaurant;
                document.getElementById('restaurantId').value = id;
                document.getElementById('restaurantName').value = restaurant.name;
                document.getElementById('restaurantDescription').value = restaurant.description;
                document.getElementById('restaurantCuisineType').value = restaurant.cuisine_type || '';
                document.getElementById('restaurantOperatingHours').value = restaurant.operating_hours || '';
                document.getElementById('restaurantLocation').value = restaurant.location || '';
                document.getElementById('restaurantFeatures').value = (restaurant.features || []).join(', ');
                
                if (restaurant.image_path) {
                    document.getElementById('restaurantImagePreview').innerHTML = `
                        <img src="/storage/${restaurant.image_path}" style="max-width: 200px; border-radius: 4px;">
                    `;
                }
                
                document.getElementById('restaurantModalTitle').textContent = 'Edit Restoran';
                restaurantModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data restoran');
        });
    }

    // Delete Restaurant
    function deleteRestaurant(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus restoran ini?')) return;

        fetch(`/admin/restaurants/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById(`restaurant-${id}`).remove();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus restoran');
        });
    }

    // Save Restaurant
    document.getElementById('restaurantForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const restaurantId = document.getElementById('restaurantId').value;
        const formData = new FormData(this);
        
        const url = restaurantId ? `/admin/restaurants/${restaurantId}` : '/admin/restaurants';
        const method = restaurantId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                restaurantModal.hide();
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan restoran');
        });
    });

    // Image Preview
    document.getElementById('restaurantImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('restaurantImagePreview').innerHTML = `
                    <img src="${event.target.result}" style="max-width: 200px; border-radius: 4px;">
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Manage Menu Items
    function manageMenuItems(restaurantId) {
        currentRestaurantId = restaurantId;
        document.getElementById('menuItemRestaurantId').value = restaurantId;
        
        fetch(`/admin/restaurants/${restaurantId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const restaurant = data.restaurant;
                document.getElementById('menuItemsModalTitle').textContent = `Menu - ${restaurant.name}`;
                displayMenuItems(restaurant.menu_items || []);
                menuItemsModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat menu');
        });
    }

    // Display Menu Items
    function displayMenuItems(menuItems) {
        const container = document.getElementById('menuItemsContainer');
        
        if (menuItems.length === 0) {
            container.innerHTML = '<div class="alert alert-info">Belum ada menu. Silakan tambahkan menu baru.</div>';
            return;
        }

        container.innerHTML = menuItems.map(item => `
            <div class="card mb-2" id="menu-item-${item.id}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            ${item.image_path ? `<img src="/storage/${item.image_path}" style="width: 100%; border-radius: 4px;">` : '<div class="bg-light p-3 text-center">No Image</div>'}
                        </div>
                        <div class="col-md-7">
                            <h6 class="mb-1">${item.name}</h6>
                            <p class="text-muted small mb-1">${item.description}</p>
                            <small>
                                <span class="badge ${item.category === 'makanan' ? 'bg-warning' : 'bg-info'}">${item.category}</span>
                                <span class="badge bg-success">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</span>
                                <span class="badge ${item.is_active ? 'bg-success' : 'bg-danger'}">${item.is_active ? 'Aktif' : 'Nonaktif'}</span>
                            </small>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-sm btn-warning" onclick="editMenuItem(${item.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-info" onclick="toggleMenuItemStatus(${item.id})">
                                <i class="fas fa-toggle-${item.is_active ? 'on' : 'off'}"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteMenuItem(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Open Add Menu Item Modal
    function openAddMenuItemModal() {
        document.getElementById('menuItemId').value = '';
        document.getElementById('menuItemForm').reset();
        document.getElementById('menuItemModalTitle').textContent = 'Tambah Menu';
        document.getElementById('menuItemImagePreview').innerHTML = '';
        menuItemModal.show();
    }

    // Edit Menu Item
    function editMenuItem(id) {
        fetch(`/admin/menu-items/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.menu_item;
                document.getElementById('menuItemId').value = id;
                document.getElementById('menuItemName').value = item.name;
                document.getElementById('menuItemDescription').value = item.description;
                document.getElementById('menuItemCategory').value = item.category;
                document.getElementById('menuItemPrice').value = item.price;
                
                if (item.image_path) {
                    document.getElementById('menuItemImagePreview').innerHTML = `
                        <img src="/storage/${item.image_path}" style="max-width: 200px; border-radius: 4px;">
                    `;
                }
                
                document.getElementById('menuItemModalTitle').textContent = 'Edit Menu';
                menuItemModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data menu');
        });
    }

    // Delete Menu Item
    function deleteMenuItem(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus menu ini?')) return;

        fetch(`/admin/menu-items/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById(`menu-item-${id}`).remove();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus menu');
        });
    }

    // Toggle Menu Item Status
    function toggleMenuItemStatus(id) {
        fetch(`/admin/menu-items/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                manageMenuItems(currentRestaurantId);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengubah status menu');
        });
    }

    // Save Menu Item
    document.getElementById('menuItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const menuItemId = document.getElementById('menuItemId').value;
        const formData = new FormData(this);
        
        const url = menuItemId ? `/admin/menu-items/${menuItemId}` : '/admin/menu-items';
        const method = menuItemId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                menuItemModal.hide();
                manageMenuItems(currentRestaurantId);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan menu');
        });
    });

    // Menu Item Image Preview
    document.getElementById('menuItemImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('menuItemImagePreview').innerHTML = `
                    <img src="${event.target.result}" style="max-width: 200px; border-radius: 4px;">
                `;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
