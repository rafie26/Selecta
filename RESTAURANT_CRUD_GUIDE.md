# Panduan Sistem CRUD Restoran Selecta

## 📋 Daftar Isi
1. [Fitur Utama](#fitur-utama)
2. [Struktur Database](#struktur-database)
3. [Cara Menggunakan](#cara-menggunakan)
4. [API Endpoints](#api-endpoints)
5. [File-File Penting](#file-file-penting)

---

## 🎯 Fitur Utama

### 1. **CRUD Restoran**
- ✅ Tambah restoran baru dengan foto
- ✅ Edit informasi restoran (nama, deskripsi, tipe masakan, jam operasional, lokasi, fitur)
- ✅ Hapus restoran beserta semua menu items
- ✅ Upload/ganti foto restoran

### 2. **CRUD Menu Items**
- ✅ Tambah menu item per restoran
- ✅ Edit menu item (nama, deskripsi, kategori, harga, foto)
- ✅ Hapus menu item
- ✅ Toggle status aktif/nonaktif menu
- ✅ Upload/ganti foto menu item
- ✅ Kategori: Makanan atau Minuman

### 3. **Fitur Tambahan**
- ✅ Validasi form server-side
- ✅ Upload foto dengan preview
- ✅ Responsive design (mobile-friendly)
- ✅ AJAX operations (tanpa reload halaman)
- ✅ Error handling yang informatif
- ✅ Automatic sort order untuk menu items

---

## 🗄️ Struktur Database

### Tabel: `restaurants`
```sql
- id (Primary Key)
- name (string, unique) - Nama restoran
- slug (string, unique) - URL slug
- description (text) - Deskripsi lengkap
- image_path (string) - Path foto restoran
- cuisine_type (string) - Tipe masakan (Masakan Jawa, Chinese, dll)
- features (json) - Array fitur (Family Friendly, Halal, dll)
- operating_hours (string) - Jam operasional (08:00 - 21:00)
- location (string) - Lokasi restoran
- is_active (boolean) - Status aktif/nonaktif
- timestamps - created_at, updated_at
```

### Tabel: `menu_items`
```sql
- id (Primary Key)
- restaurant_id (Foreign Key) - Referensi ke restaurants
- name (string) - Nama menu
- description (text) - Deskripsi menu
- image_path (string) - Path foto menu
- category (enum) - Kategori: 'makanan' atau 'minuman'
- price (decimal) - Harga menu
- is_active (boolean) - Status aktif/nonaktif
- sort_order (integer) - Urutan tampil
- timestamps - created_at, updated_at
```

---

## 📖 Cara Menggunakan

### Akses Halaman Admin
1. Login ke admin panel: `http://127.0.0.1:8000/admin/login`
2. Navigasi ke: `http://127.0.0.1:8000/admin/restaurants`

### Tambah Restoran Baru
1. Klik tombol **"+ Tambah Restoran"**
2. Isi form:
   - **Nama Restoran** (required)
   - **Deskripsi** (required)
   - **Tipe Masakan** (optional, contoh: "Masakan Jawa")
   - **Jam Operasional** (optional, contoh: "08:00 - 21:00")
   - **Lokasi** (optional)
   - **Fitur** (optional, pisahkan dengan koma)
   - **Foto Restoran** (optional, max 5MB)
3. Klik **"Simpan"**

### Edit Restoran
1. Klik tombol **"Edit"** pada kartu restoran
2. Ubah data yang diperlukan
3. Klik **"Simpan"**

### Kelola Menu Restoran
1. Klik tombol **"Menu"** pada kartu restoran
2. Akan muncul modal dengan daftar menu items
3. Klik **"+ Tambah Menu"** untuk menambah menu baru

### Tambah Menu Item
1. Klik **"+ Tambah Menu"** di dalam modal menu
2. Isi form:
   - **Nama Menu** (required)
   - **Deskripsi** (required)
   - **Kategori** (required: Makanan atau Minuman)
   - **Harga** (required, dalam Rupiah)
   - **Foto Menu** (optional, max 5MB)
3. Klik **"Simpan"**

### Edit Menu Item
1. Klik tombol **"Edit"** (icon pensil) pada menu item
2. Ubah data yang diperlukan
3. Klik **"Simpan"**

### Toggle Status Menu Item
1. Klik tombol **"Toggle"** (icon toggle) pada menu item
2. Status akan berubah dari Aktif ke Nonaktif atau sebaliknya

### Hapus Menu Item
1. Klik tombol **"Hapus"** (icon trash) pada menu item
2. Konfirmasi penghapusan
3. Menu item akan dihapus beserta fotonya

### Hapus Restoran
1. Klik tombol **"Hapus"** pada kartu restoran
2. Konfirmasi penghapusan
3. Restoran akan dihapus beserta semua menu items dan fotonya

---

## 🔌 API Endpoints

### Restoran Endpoints

#### Get All Restaurants
```
GET /admin/restaurants
Response: View dengan daftar restoran
```

#### Get Restaurant Detail
```
GET /admin/restaurants/{id}
Response: JSON
{
  "success": true,
  "restaurant": { ... },
  "menu_items": [ ... ]
}
```

#### Create Restaurant
```
POST /admin/restaurants
Headers: X-CSRF-TOKEN
Body: FormData
  - name (required)
  - description (required)
  - cuisine_type (optional)
  - operating_hours (optional)
  - location (optional)
  - features (optional)
  - image (optional, file)
Response: JSON
{
  "success": true,
  "message": "Restoran berhasil ditambahkan!",
  "restaurant": { ... }
}
```

#### Update Restaurant
```
PUT /admin/restaurants/{id}
Headers: X-CSRF-TOKEN
Body: FormData (same as create)
Response: JSON
{
  "success": true,
  "message": "Restoran berhasil diupdate!",
  "restaurant": { ... }
}
```

#### Delete Restaurant
```
DELETE /admin/restaurants/{id}
Headers: X-CSRF-TOKEN
Response: JSON
{
  "success": true,
  "message": "Restoran berhasil dihapus!"
}
```

### Menu Item Endpoints

#### Get Menu Item Detail
```
GET /admin/menu-items/{id}
Response: JSON
{
  "success": true,
  "menu_item": { ... }
}
```

#### Create Menu Item
```
POST /admin/menu-items
Headers: X-CSRF-TOKEN
Body: FormData
  - restaurant_id (required)
  - name (required)
  - description (required)
  - category (required: makanan/minuman)
  - price (required)
  - image (optional, file)
Response: JSON
{
  "success": true,
  "message": "Menu berhasil ditambahkan!",
  "menuItem": { ... }
}
```

#### Update Menu Item
```
PUT /admin/menu-items/{id}
Headers: X-CSRF-TOKEN
Body: FormData (same as create, tanpa restaurant_id)
Response: JSON
{
  "success": true,
  "message": "Menu berhasil diupdate!",
  "menuItem": { ... }
}
```

#### Delete Menu Item
```
DELETE /admin/menu-items/{id}
Headers: X-CSRF-TOKEN
Response: JSON
{
  "success": true,
  "message": "Menu berhasil dihapus!"
}
```

#### Toggle Menu Item Status
```
POST /admin/menu-items/{id}/toggle-status
Headers: X-CSRF-TOKEN
Response: JSON
{
  "success": true,
  "message": "Status menu berhasil diubah!",
  "is_active": true/false
}
```

---

## 📁 File-File Penting

### Database
- `database/migrations/2025_11_18_000001_create_restaurants_table.php`
- `database/migrations/2025_11_18_000002_create_menu_items_table.php`
- `database/seeders/RestaurantSeeder.php`

### Models
- `app/Models/Restaurant.php`
- `app/Models/MenuItem.php`

### Controller
- `app/Http/Controllers/AdminController.php` (methods: restaurants, getRestaurant, getMenuItem, storeRestaurant, updateRestaurant, deleteRestaurant, storeMenuItem, updateMenuItem, deleteMenuItem, toggleMenuItemStatus)

### Views
- `resources/views/admin/restaurants.blade.php`

### Routes
- `routes/web.php` (admin restaurant routes)

---

## 🚀 Data Seeder

Sistem sudah dilengkapi dengan seeder yang menambahkan 3 restoran default:

### 1. Restoran Bahagia (Masakan Jawa)
- Sop Buntut Istimewa - Rp 45.000
- Rawon Spesial - Rp 50.000
- Soto Ayam Istimewa - Rp 35.000
- Ginseng Coffee - Rp 35.000
- Wedang Jahe - Rp 35.000
- Kopi Tubruk - Rp 35.000

### 2. Restoran Asri (Chinese Cuisine)
- Cwimie Ayam Spesial - Rp 55.000
- Nasi Goreng Hongkong - Rp 48.000
- Mie Ayam - Rp 32.000
- Cappucino - Rp 32.000
- Jus Sirsak - Rp 32.000
- Soda Gembira - Rp 32.000

### 3. Restoran Cantik (Makanan Bakar)
- Gurami Asam Manis - Rp 185.000
- Sate Kelinci Spesial - Rp 220.000
- Gurami Goreng - Rp 165.000
- Es Jeruk - Rp 165.000
- Jus Strawberry - Rp 165.000
- Es Teh - Rp 165.000

---

## 🔒 Keamanan

- ✅ CSRF Protection (X-CSRF-TOKEN)
- ✅ Authentication required (admin middleware)
- ✅ Authorization checks
- ✅ Input validation (server-side)
- ✅ File upload validation (type, size)
- ✅ Database transaction untuk data consistency

---

## 📝 Catatan Penting

1. **Foto Restoran & Menu**: Disimpan di `storage/app/public/restaurants/` dan `storage/app/public/menu_items/`
2. **Storage Link**: Pastikan sudah menjalankan `php artisan storage:link`
3. **Validasi Harga**: Harga menu harus numeric dan minimal 0
4. **Kategori Menu**: Hanya ada 2 kategori: "makanan" dan "minuman"
5. **Features Restoran**: Dipisahkan dengan koma saat input, disimpan sebagai JSON array di database

---

## 🐛 Troubleshooting

### Foto tidak tampil
- Pastikan sudah menjalankan `php artisan storage:link`
- Cek path foto di database (storage/restaurants/ atau storage/menu_items/)

### Error saat upload foto
- Pastikan ukuran file tidak lebih dari 5MB
- Format file harus: JPEG, PNG, JPG, atau WEBP

### Menu tidak muncul di restoran
- Pastikan menu item memiliki `is_active = true`
- Cek apakah restaurant_id sudah benar

---

## 📞 Support

Untuk pertanyaan atau masalah, silakan hubungi tim development.

---

**Last Updated**: 18 November 2025
**Version**: 1.0
