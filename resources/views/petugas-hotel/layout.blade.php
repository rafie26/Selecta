<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Petugas Hotel Dashboard') - Selecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #dc2626; /* red-600 */
            --primary-600: #ef4444; /* red-500 */
            --primary-100: #fee2e2; /* red-100 */
            --text: #0f172a; /* slate-900 */
            --muted: #475569; /* slate-600 */
            --border: #e5e7eb; /* gray-200 */
            --bg: #f8fafc; /* slate-50 */
            --white: #ffffff;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--white);
            border-right: 1px solid var(--border);
        }
        .sidebar .nav-link {
            color: var(--muted);
            padding: 12px 16px;
            margin: 6px 8px;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-weight: 600;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--primary);
            background: var(--primary-100);
            transform: translateX(4px);
        }
        .main-content {
            background-color: var(--bg);
            min-height: 100vh;
        }
        .card {
            border: 1px solid var(--border);
            box-shadow: none;
            border-radius: 12px;
            background: var(--white);
        }
        .stat-card {
            background: var(--white);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .stat-card .card-title { color: var(--muted); }
        .stat-card h2 { color: var(--primary); font-weight: 800; }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3 text-center border-bottom border-light border-opacity-25">
                        <h4 class="mb-0" style="color: var(--primary);">
                            <i class="fas fa-hotel me-2"></i>
                            Petugas Hotel
                        </h4>
                    </div>
                    <nav class="nav flex-column p-3">
                        <a class="nav-link {{ request()->routeIs('petugas-hotel.dashboard') ? 'active' : '' }}" href="{{ route('petugas-hotel.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('petugas-hotel.hotels') ? 'active' : '' }}" href="{{ route('petugas-hotel.hotels') }}">
                            <i class="fas fa-hotel me-2"></i>
                            Tipe Kamar
                        </a>
                        <a class="nav-link {{ request()->routeIs('petugas-hotel.hotel-bookings') ? 'active' : '' }}" href="{{ route('petugas-hotel.hotel-bookings') }}">
                            <i class="fas fa-calendar-check me-2"></i>
                            Booking Hotel
                        </a>
                        <a class="nav-link {{ request()->routeIs('petugas-hotel.qr-scanner') ? 'active' : '' }}" href="{{ route('petugas-hotel.qr-scanner') }}">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Scanner
                        </a>
                        <hr class="my-3">
                        <a class="nav-link text-danger" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Top Bar -->
                    <div class="bg-white border-bottom border-light border-opacity-25 p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                            <div class="d-flex align-items-center">
                                <span class="me-3 text-muted">
                                    <i class="fas fa-user-circle me-2"></i>
                                    {{ Auth::user()->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
