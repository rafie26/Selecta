# 🎭 Sistem Role Management - Selecta

## 📋 Overview

Sistem ini mengimplementasikan role-based access control (RBAC) dengan 4 role berbeda:
- **Admin** - Akses penuh ke sistem
- **Petugas Loket** - Mengelola tiket dan booking tiket
- **Petugas Hotel** - Mengelola hotel dan booking hotel
- **User** - Pengunjung biasa yang melakukan booking

---

## 🏗️ Struktur Role

### 1. **Admin** (`admin`)
**Dashboard:** `/admin/dashboard`

**Akses Menu:**
- ✅ Dashboard
- ✅ Users Management
- ✅ Restaurants Management
- ✅ Hotel Photos Management
- ✅ View Website

**Tidak Ada Akses:**
- ❌ Bookings (dipindah ke Petugas)
- ❌ Hotels Management (dipindah ke Petugas Hotel)
- ❌ Tickets Management (dipindah ke Petugas Loket)

---

### 2. **Petugas Loket** (`petugas_loket`)
**Dashboard:** `/petugas-loket/dashboard`

**Akses Menu:**
- ✅ Dashboard
- ✅ Paket Tiket (Read-only)
- ✅ Booking Tiket (View & Filter)
- ✅ QR Scanner

**Fitur:**
- Melihat semua paket tiket yang tersedia
- Melihat dan memfilter booking tiket
- Scan QR code untuk check-in pengunjung
- Filter berdasarkan payment status, check-in status, dan paket

**Warna Tema:** Hijau (Green)

---

### 3. **Petugas Hotel** (`petugas_hotel`)
**Dashboard:** `/petugas-hotel/dashboard`

**Akses Menu:**
- ✅ Dashboard
- ✅ Tipe Kamar (Read-only)
- ✅ Booking Hotel (View & Filter)
- ✅ QR Scanner

**Fitur:**
- Melihat semua tipe kamar hotel
- Melihat dan memfilter booking hotel
- Scan QR code untuk check-in tamu hotel
- Filter berdasarkan payment status dan check-in status

**Warna Tema:** Merah (Red)

---

### 4. **User** (`user`)
**Dashboard:** `/` (Homepage)

**Akses:**
- ✅ Browse & Book Tickets
- ✅ Browse & Book Hotels
- ✅ View Booking History
- ✅ Manage Profile

---

## 🔐 Login & Authentication

### Login URLs:
- **Admin, Petugas Loket & Petugas Hotel:** `/admin/login` (Staff Portal)
- **Regular User:** `/login` (User Login)

### Test Accounts:

```
👤 Admin
Email: admin@selecta.com
Password: password
Login URL: /admin/login
Redirect: /admin/dashboard

🎫 Petugas Loket
Email: loket@selecta.com
Password: password
Login URL: /admin/login
Redirect: /petugas-loket/dashboard

🏨 Petugas Hotel
Email: hotel@selecta.com
Password: password
Login URL: /admin/login
Redirect: /petugas-hotel/dashboard

👥 Regular User
Email: user@selecta.com
Password: password
Login URL: /login
Redirect: /
```

---

## 🛠️ Technical Implementation

### 1. **User Model Constants**
```php
// app/Models/User.php
const ROLE_USER = 'user';
const ROLE_ADMIN = 'admin';
const ROLE_PETUGAS_LOKET = 'petugas_loket';
const ROLE_PETUGAS_HOTEL = 'petugas_hotel';
```

### 2. **Helper Methods**
```php
$user->isAdmin();           // Check if admin
$user->isPetugasLoket();    // Check if petugas loket
$user->isPetugasHotel();    // Check if petugas hotel
$user->isUser();            // Check if regular user
$user->isStaff();           // Check if any staff role
```

### 3. **Middleware Usage**
```php
// In routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});

Route::middleware(['auth', 'role:petugas_loket'])->group(function () {
    // Petugas Loket routes
});

Route::middleware(['auth', 'role:petugas_hotel'])->group(function () {
    // Petugas Hotel routes
});
```

### 4. **Controllers**
- `AdminController` - Admin functionality
- `PetugasLoketController` - Petugas Loket functionality
- `PetugasHotelController` - Petugas Hotel functionality

---

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── PetugasLoketController.php
│   │   └── PetugasHotelController.php
│   └── Middleware/
│       └── CheckRole.php
└── Models/
    └── User.php (with role constants)

resources/views/
├── admin/
│   ├── layout.blade.php
│   └── dashboard.blade.php
├── petugas-loket/
│   ├── layout.blade.php
│   ├── dashboard.blade.php
│   ├── packages.blade.php
│   ├── ticket-bookings.blade.php
│   └── qr-scanner.blade.php
└── petugas-hotel/
    ├── layout.blade.php
    ├── dashboard.blade.php
    ├── hotels.blade.php
    ├── hotel-bookings.blade.php
    └── qr-scanner.blade.php

routes/
└── web.php (with role-based route groups)

database/seeders/
└── RoleUsersSeeder.php
```

---

## 🚀 Setup Instructions

### 1. Run Seeder
```bash
php artisan db:seed --class=RoleUsersSeeder
```

### 2. Test Login
- Login dengan salah satu test account
- Sistem akan otomatis redirect ke dashboard sesuai role

### 3. Verify Access
- Admin: Cek menu Users, Restaurants, Hotel Photos
- Petugas Loket: Cek menu Paket Tiket, Booking Tiket, QR Scanner
- Petugas Hotel: Cek menu Tipe Kamar, Booking Hotel, QR Scanner

---

## 🎨 UI Design

### Admin Panel
- **Warna:** Biru (#1e40af)
- **Icon:** Mountain (fas fa-mountain)
- **Fokus:** Management & Configuration

### Petugas Loket Panel
- **Warna:** Hijau (#059669)
- **Icon:** Ticket (fas fa-ticket-alt)
- **Fokus:** Ticket Operations

### Petugas Hotel Panel
- **Warna:** Merah (#dc2626)
- **Icon:** Hotel (fas fa-hotel)
- **Fokus:** Hotel Operations

---

## 🔒 Security Features

1. **Role-based Middleware** - Mencegah akses unauthorized
2. **Automatic Redirect** - Login redirect sesuai role
3. **Session Management** - Proper session handling per role
4. **Access Control** - Setiap role hanya akses menu yang diizinkan

---

## 📊 Dashboard Statistics

### Admin Dashboard
- Total Users
- Total Bookings
- Revenue Statistics
- System Overview

### Petugas Loket Dashboard
- Total Paket Tiket
- Total Booking Tiket
- Pending Payments
- Paid Bookings

### Petugas Hotel Dashboard
- Total Tipe Kamar
- Total Booking Hotel
- Pending Payments
- Paid Bookings

---

## 🔄 Logout Behavior

- **Admin:** Redirect ke `/admin/login`
- **Petugas Loket/Hotel:** Redirect ke `/login`
- **Regular User:** Redirect ke `/`

---

## 📝 Notes

1. **QR Scanner** dapat diakses oleh Petugas Loket dan Petugas Hotel
2. **Read-only Access** untuk Petugas pada data master (Packages, Room Types)
3. **Full CRUD** hanya tersedia untuk Admin
4. **Booking Management** dibagi berdasarkan tipe (ticket/hotel)

---

## 🐛 Troubleshooting

### Issue: User tidak bisa login
**Solution:** Pastikan field `role` di database terisi dengan benar

### Issue: Redirect tidak sesuai
**Solution:** Clear session dan cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Issue: Middleware error
**Solution:** Pastikan middleware sudah terdaftar di `bootstrap/app.php`

---

## 📞 Support

Untuk pertanyaan atau issue, silakan hubungi tim development.

---

**Last Updated:** November 11, 2024
**Version:** 1.0.0
