# Admin Price Management - Panduan Penggunaan

## Fitur Update Harga Tiket dengan Sinkronisasi Midtrans

Fitur ini memungkinkan admin untuk mengubah harga tiket di dashboard admin yang akan otomatis tersinkronisasi dengan sistem pembayaran Midtrans.

## Cara Menggunakan

### 1. Login ke Admin Panel
- Akses: `/admin/login`
- Gunakan akun admin yang sudah terdaftar
- Setelah login, Anda akan diarahkan ke dashboard admin

### 2. Mengelola Packages
- Navigasi ke menu **Packages** di sidebar admin
- Anda akan melihat daftar semua packages yang tersedia

### 3. Mengubah Harga Package

#### Edit Package Existing:
1. Klik tombol **Edit** (ikon pensil) pada package yang ingin diubah
2. Ubah harga di field **Harga**
3. Klik **Simpan Perubahan**
4. Harga akan otomatis tersinkronisasi dengan Midtrans

#### Tambah Package Baru:
1. Klik tombol **Tambah Package**
2. Isi semua field yang diperlukan:
   - Nama Package (wajib)
   - Harga (wajib)
   - Deskripsi
   - Fitur (pisahkan dengan koma)
   - Badge
   - Status Aktif
3. Klik **Tambah Package**

#### Hapus Package:
1. Klik tombol **Hapus** (ikon tempat sampah)
2. Konfirmasi penghapusan
3. Package yang sudah memiliki booking tidak dapat dihapus

## Sinkronisasi dengan Midtrans

### Bagaimana Sinkronisasi Bekerja:
1. **Harga Real-time**: Setiap transaksi baru akan menggunakan harga terbaru dari database
2. **Item Details**: Midtrans akan menerima item details dengan harga yang sudah diupdate
3. **Logging**: Setiap perubahan harga akan dicatat dalam log sistem
4. **Validasi**: Sistem memastikan harga yang dikirim ke Midtrans selalu akurat

### Konfigurasi Midtrans:
- **Server Key**: `SB-Mid-server-GwS6LjPnpotNiagCOBXBzqNB` (Sandbox)
- **Client Key**: `SB-Mid-client-nKsqvar5cn60u2Lv`
- **Environment**: Sandbox (untuk testing)
- **Mode Production**: `false`

## Fitur Keamanan

### Validasi:
- Harga harus berupa angka positif
- Nama package wajib diisi
- Package yang sudah memiliki booking tidak dapat dihapus
- Hanya admin yang dapat mengakses fitur ini

### Logging:
- Setiap perubahan harga dicatat dengan detail:
  - Package ID dan nama
  - Harga lama dan baru
  - User yang melakukan perubahan
  - Timestamp perubahan

## Struktur Database

### Table: packages
```sql
- id (Primary Key)
- name (VARCHAR) - Nama package
- description (TEXT) - Deskripsi package
- price (DECIMAL) - Harga package
- features (JSON) - Array fitur
- badge (VARCHAR) - Badge package
- is_active (BOOLEAN) - Status aktif
- created_at, updated_at
```

### Table: booking_details
```sql
- id (Primary Key)
- booking_id (Foreign Key)
- package_id (Foreign Key)
- quantity (INTEGER)
- unit_price (DECIMAL) - Harga saat booking dibuat
- subtotal (DECIMAL)
```

## API Endpoints

### Admin Package Management:
- `GET /admin/packages` - Daftar packages
- `GET /admin/packages/create` - Form tambah package
- `POST /admin/packages` - Simpan package baru
- `GET /admin/packages/{id}/edit` - Form edit package
- `PUT /admin/packages/{id}` - Update package
- `DELETE /admin/packages/{id}` - Hapus package

## Troubleshooting

### Masalah Umum:

1. **Harga tidak tersinkronisasi**
   - Pastikan konfigurasi Midtrans benar
   - Check log aplikasi untuk error
   - Verifikasi koneksi database

2. **Error saat update harga**
   - Pastikan format harga benar (angka positif)
   - Check permission user admin
   - Verifikasi CSRF token

3. **Package tidak muncul di website**
   - Pastikan status package adalah "Active"
   - Check apakah package memiliki harga valid
   - Verifikasi tidak ada error di frontend

### Log Files:
- Application logs: `storage/logs/laravel.log`
- Midtrans sync logs: Search for "Package price synchronized"

## Testing

### Manual Testing:
1. Login sebagai admin
2. Buat package baru dengan harga tertentu
3. Lakukan booking dari frontend
4. Verifikasi harga di Midtrans sesuai dengan yang di-set
5. Update harga package
6. Lakukan booking baru
7. Verifikasi harga baru sudah tersinkronisasi

### Automated Testing:
```bash
# Jalankan test script
php test_price_update.php
```

## Best Practices

### Untuk Admin:
1. **Backup Data**: Selalu backup database sebelum perubahan besar
2. **Test Environment**: Test perubahan harga di sandbox dulu
3. **Komunikasi**: Informasikan perubahan harga ke tim marketing
4. **Monitoring**: Monitor log setelah perubahan harga

### Untuk Developer:
1. **Validation**: Selalu validasi input harga
2. **Transaction**: Gunakan database transaction untuk update
3. **Logging**: Log semua perubahan penting
4. **Error Handling**: Handle error dengan graceful

## Support

Jika mengalami masalah:
1. Check log aplikasi
2. Verifikasi konfigurasi Midtrans
3. Pastikan database connection normal
4. Contact developer team jika diperlukan

---

**Catatan**: Fitur ini menggunakan Midtrans Sandbox untuk testing. Untuk production, pastikan menggunakan production keys dan environment yang sesuai.
