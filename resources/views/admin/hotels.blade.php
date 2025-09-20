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
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-primary">{{ $roomType->max_adults ?? 2 }} Dewasa</span>
                                    <span class="badge bg-info">{{ $roomType->max_children ?? 0 }} Anak</span>
                                </div>
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
                                            data-max-adults="{{ $roomType->max_adults ?? 2 }}"
                                            data-max-children="{{ $roomType->max_children ?? 0 }}"
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

<!-- Hotel Photos Management Section -->
<div class="card mt-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-images me-2"></i>
                Kelola Foto Hotel
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="filterRoomType" style="width: auto;">
                    <option value="">Semua Tipe Kamar</option>
                    @foreach(App\Models\RoomType::all() as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPhotoModal">
                    <i class="fas fa-plus me-1"></i>
                    Upload Foto
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="photosGrid" class="row g-3">
            <!-- Photos will be loaded here via AJAX -->
        </div>
        <div id="photosLoading" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat foto hotel...</p>
        </div>
        <div id="noPhotos" class="text-center py-4" style="display: none;">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Belum ada foto hotel</p>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_adults" class="form-label">Maksimal Dewasa</label>
                                <input type="number" class="form-control" id="max_adults" name="max_adults" min="1" value="2" required>
                                <div class="form-text">Jumlah maksimal tamu dewasa</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_children" class="form-label">Maksimal Anak</label>
                                <input type="number" class="form-control" id="max_children" name="max_children" min="0" value="0">
                                <div class="form-text">Jumlah maksimal anak-anak</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="total_rooms" class="form-label">Total Kamar</label>
                                <input type="number" class="form-control" id="total_rooms" name="total_rooms" min="1" required>
                                <div class="form-text">Jumlah kamar tersedia</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="max_occupancy" class="form-label">Total Kapasitas <span class="text-muted">(Auto-calculated)</span></label>
                                <input type="number" class="form-control" id="max_occupancy" name="max_occupancy" readonly>
                                <div class="form-text">Total kapasitas akan dihitung otomatis dari dewasa + anak</div>
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_max_adults" class="form-label">Maksimal Dewasa</label>
                                <input type="number" class="form-control" id="edit_max_adults" name="max_adults" min="1" required>
                                <div class="form-text">Jumlah maksimal tamu dewasa</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_max_children" class="form-label">Maksimal Anak</label>
                                <input type="number" class="form-control" id="edit_max_children" name="max_children" min="0">
                                <div class="form-text">Jumlah maksimal anak-anak</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_total_rooms" class="form-label">Total Kamar</label>
                                <input type="number" class="form-control" id="edit_total_rooms" name="total_rooms" min="1" required>
                                <div class="form-text">Jumlah kamar tersedia</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_max_occupancy" class="form-label">Total Kapasitas <span class="text-muted">(Auto-calculated)</span></label>
                                <input type="number" class="form-control" id="edit_max_occupancy" name="max_occupancy" readonly>
                                <div class="form-text">Total kapasitas akan dihitung otomatis dari dewasa + anak</div>
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

<!-- Add Photo Modal -->
<div class="modal fade" id="addPhotoModal" tabindex="-1" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPhotoModalLabel">Upload Foto Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPhotoForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo_title" class="form-label">Judul Foto</label>
                                <input type="text" class="form-control" id="photo_title" name="title" placeholder="Masukkan judul foto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo_room_type" class="form-label">Tipe Kamar</label>
                                <select class="form-select" id="photo_room_type" name="room_type_id">
                                    <option value="">Pilih Tipe Kamar (Opsional)</option>
                                    @foreach(App\Models\RoomType::all() as $roomType)
                                        <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Kosongkan jika foto bersifat umum</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo_sort_order" class="form-label">Urutan Tampil</label>
                        <input type="number" class="form-control" id="photo_sort_order" name="sort_order" value="0" min="0">
                        <div class="form-text">Semakin kecil angka, semakin awal ditampilkan</div>
                    </div>

                    <div class="mb-3">
                        <label for="photo_image" class="form-label">File Foto <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="photo_image" name="image" accept="image/*" required>
                        <div class="form-text">Format yang didukung: JPG, PNG, JPEG, WEBP. Maksimal 5MB.</div>
                    </div>

                    <!-- Photo Preview -->
                    <div id="photoPreview" class="mb-3" style="display: none;">
                        <label class="form-label">Preview Foto</label>
                        <div class="border rounded p-2">
                            <img id="previewImage" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="photo_is_featured" name="is_featured">
                                <label class="form-check-label" for="photo_is_featured">
                                    Foto Unggulan
                                </label>
                                <div class="form-text">Foto unggulan akan ditampilkan lebih menonjol</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="photo_is_active" name="is_active" checked>
                                <label class="form-check-label" for="photo_is_active">
                                    Aktif
                                </label>
                                <div class="form-text">Foto aktif akan ditampilkan di website</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>
                        Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Photo Modal -->
<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPhotoModalLabel">Edit Foto Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPhotoForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_photo_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_photo_title" class="form-label">Judul Foto</label>
                                <input type="text" class="form-control" id="edit_photo_title" name="title" placeholder="Masukkan judul foto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_photo_room_type" class="form-label">Tipe Kamar</label>
                                <select class="form-select" id="edit_photo_room_type" name="room_type_id">
                                    <option value="">Pilih Tipe Kamar (Opsional)</option>
                                    @foreach(App\Models\RoomType::all() as $roomType)
                                        <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_photo_sort_order" class="form-label">Urutan Tampil</label>
                        <input type="number" class="form-control" id="edit_photo_sort_order" name="sort_order" min="0">
                    </div>

                    <!-- Current Photo -->
                    <div class="mb-3">
                        <label class="form-label">Foto Saat Ini</label>
                        <div class="border rounded p-2">
                            <img id="currentPhotoImage" src="" alt="Current Photo" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_photo_image" class="form-label">Ganti Foto (Opsional)</label>
                        <input type="file" class="form-control" id="edit_photo_image" name="image" accept="image/*">
                        <div class="form-text">Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, JPEG, WEBP. Maksimal 5MB.</div>
                    </div>

                    <!-- New Photo Preview -->
                    <div id="editPhotoPreview" class="mb-3" style="display: none;">
                        <label class="form-label">Preview Foto Baru</label>
                        <div class="border rounded p-2">
                            <img id="editPreviewImage" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_photo_is_featured" name="is_featured">
                                <label class="form-check-label" for="edit_photo_is_featured">
                                    Foto Unggulan
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_photo_is_active" name="is_active">
                                <label class="form-check-label" for="edit_photo_is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Photo Confirmation Modal -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePhotoModalLabel">Konfirmasi Hapus Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus foto <strong id="deletePhotoTitle"></strong>?</p>
                <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeletePhoto">Hapus</button>
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
        const maxAdults = $(this).data('max-adults');
        const maxChildren = $(this).data('max-children');
        const totalRooms = $(this).data('total-rooms');
        const isActive = $(this).data('active');
        
        $('#edit_room_type_id').val(id);
        $('#edit_name').val(name);
        $('#edit_description').val(description);
        $('#edit_price_per_night').val(price);
        $('#edit_max_adults').val(maxAdults);
        $('#edit_max_children').val(maxChildren);
        $('#edit_max_occupancy').val(occupancy);
        $('#edit_total_rooms').val(totalRooms);
        $('#edit_is_active').prop('checked', isActive);
        
        $('#editRoomTypeModal').modal('show');
    });
    
    // Auto-calculate total occupancy for add form
    function calculateTotalOccupancy(adultsId, childrenId, totalId) {
        const adults = parseInt($(adultsId).val()) || 0;
        const children = parseInt($(childrenId).val()) || 0;
        const total = adults + children;
        $(totalId).val(total);
    }
    
    // Add form auto-calculation
    $('#max_adults, #max_children').on('input', function() {
        calculateTotalOccupancy('#max_adults', '#max_children', '#max_occupancy');
    });
    
    // Edit form auto-calculation
    $('#edit_max_adults, #edit_max_children').on('input', function() {
        calculateTotalOccupancy('#edit_max_adults', '#edit_max_children', '#edit_max_occupancy');
    });
    
    // Calculate on modal show for edit form
    $('#editRoomTypeModal').on('shown.bs.modal', function() {
        calculateTotalOccupancy('#edit_max_adults', '#edit_max_children', '#edit_max_occupancy');
    });
    
    // Calculate on modal show for add form
    $('#addRoomTypeModal').on('shown.bs.modal', function() {
        calculateTotalOccupancy('#max_adults', '#max_children', '#max_occupancy');
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

    // ============ HOTEL PHOTOS MANAGEMENT ============
    
    let photoIdToDelete = null;
    
    // Load photos on page load
    loadHotelPhotos();
    
    // Filter change handlers
    $('#filterRoomType').on('change', function() {
        loadHotelPhotos();
    });
    
    // Photo preview for add form
    $('#photo_image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#photoPreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#photoPreview').hide();
        }
    });
    
    // Photo preview for edit form
    $('#edit_photo_image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editPreviewImage').attr('src', e.target.result);
                $('#editPhotoPreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#editPhotoPreview').hide();
        }
    });
    
    // Add photo form submission
    $('#addPhotoForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Handle boolean values properly
        formData.set('is_featured', $('#photo_is_featured').is(':checked') ? '1' : '0');
        formData.set('is_active', $('#photo_is_active').is(':checked') ? '1' : '0');
        
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Uploading...');
        
        $.ajax({
            url: '/admin/hotel-photos',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addPhotoModal').modal('hide');
                    showAlert('success', response.message);
                    loadHotelPhotos();
                    $('#addPhotoForm')[0].reset();
                    $('#photoPreview').hide();
                } else {
                    showAlert('error', response.message || 'Terjadi kesalahan saat mengupload foto.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Validasi gagal:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += '- ' + response.errors[key][0] + '\n';
                    });
                    showAlert('error', errorMessage);
                } else {
                    const errorMsg = response?.message || xhr.responseText || 'Terjadi kesalahan saat mengupload foto.';
                    showAlert('error', errorMsg);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Edit photo form submission
    $('#editPhotoForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const photoId = $('#edit_photo_id').val();
        
        // Handle boolean values properly
        formData.set('is_featured', $('#edit_photo_is_featured').is(':checked') ? '1' : '0');
        formData.set('is_active', $('#edit_photo_is_active').is(':checked') ? '1' : '0');
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
        
        // Add _method for PUT request
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: `/admin/hotel-photos/${photoId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editPhotoModal').modal('hide');
                    showAlert('success', response.message);
                    loadHotelPhotos();
                } else {
                    showAlert('error', response.message || 'Terjadi kesalahan saat mengupdate foto.');
                }
            },
            error: function(xhr) {
                console.log('Update error:', xhr);
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Validasi gagal:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += '- ' + response.errors[key][0] + '\n';
                    });
                    showAlert('error', errorMessage);
                } else {
                    showAlert('error', response?.message || 'Terjadi kesalahan saat mengupdate foto.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Confirm delete photo
    $('#confirmDeletePhoto').on('click', function() {
        if (photoIdToDelete) {
            $.ajax({
                url: `/admin/hotel-photos/${photoIdToDelete}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#deletePhotoModal').modal('hide');
                        showAlert('success', response.message);
                        loadHotelPhotos();
                    } else {
                        showAlert('error', response.message || 'Terjadi kesalahan saat menghapus foto.');
                    }
                },
                error: function(xhr) {
                    console.log('Delete error:', xhr);
                    const response = xhr.responseJSON;
                    showAlert('error', response?.message || 'Terjadi kesalahan saat menghapus foto.');
                }
            });
        }
    });
    
    // Load hotel photos function
    function loadHotelPhotos() {
        const roomTypeId = $('#filterRoomType').val();
        
        $('#photosLoading').show();
        $('#photosGrid').hide();
        $('#noPhotos').hide();
        
        $.ajax({
            url: '/admin/hotel-photos',
            type: 'GET',
            data: {
                room_type_id: roomTypeId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#photosLoading').hide();
                
                if (response.success && response.photos.length > 0) {
                    renderPhotos(response.photos);
                    $('#photosGrid').show();
                } else {
                    $('#noPhotos').show();
                }
            },
            error: function(xhr) {
                $('#photosLoading').hide();
                $('#noPhotos').show();
                console.log('Load photos error:', xhr);
                showAlert('error', 'Terjadi kesalahan saat memuat foto hotel.');
            }
        });
    }
    
    // Render photos function
    function renderPhotos(photos) {
        let html = '';
        
        photos.forEach(function(photo) {
            const featuredBadge = photo.is_featured ? '<span class="badge bg-warning text-dark me-1">Unggulan</span>' : '';
            const statusBadge = photo.is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>';
            
            html += `
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <img src="${photo.image_url}" class="card-img-top" alt="${photo.title || 'Hotel Photo'}" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">${photo.title || 'Tanpa Judul'}</h6>
                            <div class="mb-2">
                                ${featuredBadge}
                                ${statusBadge}
                            </div>
                            ${photo.room_type ? `<small class="text-muted d-block mb-2">Tipe: ${photo.room_type}</small>` : '<small class="text-muted d-block mb-2">Foto Umum</small>'}
                            <small class="text-muted d-block mb-2">Urutan: ${photo.sort_order}</small>
                        </div>
                        <div class="card-footer p-2">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm edit-photo" 
                                        data-id="${photo.id}"
                                        data-title="${photo.title || ''}"
                                        data-room-type-id="${photo.room_type_id || ''}"
                                        data-sort-order="${photo.sort_order}"
                                        data-is-featured="${photo.is_featured}"
                                        data-is-active="${photo.is_active}"
                                        data-image-url="${photo.image_url}"
                                        title="Edit Foto">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm toggle-featured" 
                                        data-id="${photo.id}"
                                        title="Toggle Unggulan">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm toggle-status" 
                                        data-id="${photo.id}"
                                        title="Toggle Status">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm delete-photo" 
                                        data-id="${photo.id}"
                                        data-title="${photo.title || 'Foto'}"
                                        title="Hapus Foto">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#photosGrid').html(html);
        
        // Attach event handlers for photo actions
        attachPhotoEventHandlers();
    }
    
    // Attach event handlers for photo actions
    function attachPhotoEventHandlers() {
        // Edit photo button
        $('.edit-photo').on('click', function() {
            const data = $(this).data();
            
            $('#edit_photo_id').val(data.id);
            $('#edit_photo_title').val(data.title);
            $('#edit_photo_room_type').val(data.roomTypeId);
            $('#edit_photo_sort_order').val(data.sortOrder);
            $('#edit_photo_is_featured').prop('checked', data.isFeatured);
            $('#edit_photo_is_active').prop('checked', data.isActive);
            $('#currentPhotoImage').attr('src', data.imageUrl);
            $('#editPhotoPreview').hide();
            
            $('#editPhotoModal').modal('show');
        });
        
        // Delete photo button
        $('.delete-photo').on('click', function() {
            photoIdToDelete = $(this).data('id');
            const photoTitle = $(this).data('title');
            
            $('#deletePhotoTitle').text(photoTitle);
            $('#deletePhotoModal').modal('show');
        });
        
        // Toggle featured button
        $('.toggle-featured').on('click', function() {
            const photoId = $(this).data('id');
            
            $.ajax({
                url: `/admin/hotel-photos/${photoId}/toggle-featured`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        loadHotelPhotos();
                    } else {
                        showAlert('error', response.message || 'Terjadi kesalahan.');
                    }
                },
                error: function(xhr) {
                    console.log('Toggle featured error:', xhr);
                    showAlert('error', 'Terjadi kesalahan saat mengubah status unggulan.');
                }
            });
        });
        
        // Toggle status button
        $('.toggle-status').on('click', function() {
            const photoId = $(this).data('id');
            
            $.ajax({
                url: `/admin/hotel-photos/${photoId}/toggle-status`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        loadHotelPhotos();
                    } else {
                        showAlert('error', response.message || 'Terjadi kesalahan.');
                    }
                },
                error: function(xhr) {
                    console.log('Toggle status error:', xhr);
                    showAlert('error', 'Terjadi kesalahan saat mengubah status foto.');
                }
            });
        });
    }
    
    // Show alert function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        
        const alertDiv = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        
        $('.card').first().before(alertDiv);
        
        // Auto-dismiss alert after 5 seconds
        setTimeout(() => {
            alertDiv.fadeOut();
        }, 5000);
    }
});
</script>
@endpush
@endsection
