<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Global Search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest only routes (tidak bisa diakses jika sudah login)
Route::middleware(['guest'])->group(function () {
    // User Login & Register
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Admin Login (separate route)
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
    
    // Admin Registration (separate route)
    Route::get('/admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register.post');
    
    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Authenticated routes (perlu login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Booking (perlu login)
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my-bookings');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    
    // Payment routes
    Route::post('/payment', [PaymentController::class, 'pay'])->name('payment');
    Route::get('/payment/success/{bookingId}', [PaymentController::class, 'success'])->name('payment.success');
});

/*
|--------------------------------------------------------------------------
| Public Routes (tidak perlu login)
|--------------------------------------------------------------------------
*/

// Tickets
Route::prefix('tickets')->name('tickets.')->group(function () {
    Route::get('/', [TicketController::class, 'index'])->name('index');
    Route::get('/{id}', [HotelController::class, 'show'])->name('show');
});

// Hotels
Route::prefix('hotels')->name('hotels.')->group(function () {
    Route::get('/', [HotelController::class, 'index'])->name('index');
    Route::get('/{id}', [HotelController::class, 'show'])->name('show');
});

// Restaurants
Route::prefix('restaurants')->name('restaurants.')->group(function () {
    Route::get('/', [RestaurantController::class, 'index'])->name('index');
    Route::get('/{id}', [RestaurantController::class, 'show'])->name('show');
});

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Contact
Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
Route::post('/ticket/book', [TicketController::class, 'book'])->name('ticket.book');

// Review routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::post('/ticket/reviews', [TicketController::class, 'storeReview'])->name('ticket.reviews.store');
    Route::put('/ticket/reviews/{id}', [TicketController::class, 'updateReview'])->name('ticket.reviews.update');
    Route::delete('/ticket/reviews/{id}', [TicketController::class, 'deleteReview'])->name('ticket.reviews.delete');
});

// Public review routes (no auth required)
Route::get('/ticket/reviews', [TicketController::class, 'getReviews'])->name('ticket.reviews.get');

// QR Code Routes
Route::get('/qr/scanner', [App\Http\Controllers\QRController::class, 'scanPage'])->name('qr.scanner');
Route::get('/qr/scan/{qrCode}', [App\Http\Controllers\QRController::class, 'scan'])->name('qr.scan');
Route::post('/qr/checkin/{qrCode}', [App\Http\Controllers\QRController::class, 'checkIn'])->name('qr.checkin');
Route::get('/qr/generate/{booking}', [App\Http\Controllers\QRController::class, 'generateQR'])->name('qr.generate');

// Booking Info (halaman info booking, tidak perlu login)
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');

/*
|--------------------------------------------------------------------------
| API Routes (jika perlu untuk AJAX)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    // Search suggestions
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    
    // Get hotel availability
    Route::post('/hotels/availability', [HotelController::class, 'checkAvailability'])->name('hotels.availability');
    
    // Book hotel room
    Route::post('/hotels/book', [HotelController::class, 'bookRoom'])->name('hotels.book');
    
    // Get attraction pricing
    Route::get('/attractions/{id}/pricing', [AttractionController::class, 'pricing'])->name('attractions.pricing');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (jika perlu admin panel)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/hotel-bookings', [AdminController::class, 'hotelBookings'])->name('hotel-bookings');
    Route::get('/ticket-bookings', [AdminController::class, 'ticketBookings'])->name('ticket-bookings');
    Route::get('/bookings/{id}/detail', [AdminController::class, 'bookingDetail'])->name('bookings.detail');
    Route::post('/bookings/{id}/update-status', [AdminController::class, 'updateBookingStatus'])->name('bookings.update-status');
    Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');

    // Clean design: Hotels, Tickets, Restaurants
    Route::get('/hotels', [AdminController::class, 'hotels'])->name('hotels');
    Route::post('/room-types', [AdminController::class, 'storeRoomType'])->name('room-types.store');
    Route::put('/room-types/{id}', [AdminController::class, 'updateRoomType'])->name('room-types.update');
    Route::delete('/room-types/{id}', [AdminController::class, 'deleteRoomType'])->name('room-types.delete');
    Route::post('/room-types/{id}/adjust-availability', [AdminController::class, 'adjustAvailableRooms'])->name('room-types.adjust-availability');
    Route::get('/packages', [AdminController::class, 'packages'])->name('packages');
    Route::get('/restaurants', [AdminController::class, 'restaurants'])->name('restaurants');

    // Keep existing Package management routes (used for Tickets CRUD under the hood)
    Route::get('/packages', [AdminController::class, 'packages'])->name('packages');
    Route::get('/packages/create', [AdminController::class, 'createPackage'])->name('packages.create');
    Route::post('/packages', [AdminController::class, 'storePackage'])->name('packages.store');
    Route::get('/packages/{id}/edit', [AdminController::class, 'editPackage'])->name('packages.edit');
    Route::post('/packages/{id}/update', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::post('/packages/{id}/toggle-status', [AdminController::class, 'togglePackageStatus'])->name('packages.toggle-status');
    Route::delete('/packages/{id}', [AdminController::class, 'deletePackage'])->name('packages.delete');
});

/*
|--------------------------------------------------------------------------
| Error Pages
|--------------------------------------------------------------------------
*/

// Midtrans notification handler (tidak perlu auth)
Route::post('/midtrans/notification', [PaymentController::class, 'notificationHandler'])->name('midtrans.notification');

// Custom error pages
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});