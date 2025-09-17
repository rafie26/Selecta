<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Selecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af; /* blue-800 */
            --primary-600: #2563eb; /* blue-600 */
            --primary-100: #dbeafe; /* blue-100 */
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
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3 text-center border-bottom border-light border-opacity-25">
                        <h4 class="mb-0" style="color: var(--primary);">
                            <i class="fas fa-mountain me-2"></i>
                            Selecta Admin
                        </h4>
                    </div>
                    <nav class="nav flex-column p-3">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>
                            Users
                        </a>
                        <!-- Bookings Dropdown -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.bookings*') || request()->routeIs('admin.hotel-bookings') || request()->routeIs('admin.ticket-bookings') ? 'active' : '' }}" 
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-calendar-check me-2"></i>
                                Bookings
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ request()->routeIs('admin.bookings') ? 'active' : '' }}" href="{{ route('admin.bookings') }}">
                                    <i class="fas fa-list me-2"></i>Semua Bookings
                                </a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('admin.hotel-bookings') ? 'active' : '' }}" href="{{ route('admin.hotel-bookings') }}">
                                    <i class="fas fa-hotel me-2"></i>Hotel Bookings
                                </a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('admin.ticket-bookings') ? 'active' : '' }}" href="{{ route('admin.ticket-bookings') }}">
                                    <i class="fas fa-ticket-alt me-2"></i>Ticket Bookings
                                </a></li>
                            </ul>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.hotels') ? 'active' : '' }}" href="{{ route('admin.hotels') }}">
                            <i class="fas fa-hotel me-2"></i>
                            Hotels
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.packages*') ? 'active' : '' }}" href="{{ route('admin.packages') }}">
                            <i class="fas fa-ticket-alt me-2"></i>
                            Tickets
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.restaurants') ? 'active' : '' }}" href="{{ route('admin.restaurants') }}">
                            <i class="fas fa-utensils me-2"></i>
                            Restaurants
                        </a>
                        <hr class="border-light border-opacity-25">
                        <a class="nav-link" href="{{ route('qr.scanner') }}" target="_blank">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Scanner
                        </a>
                        <a class="nav-link" href="{{ url('/') }}" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>
                            View Website
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3" onsubmit="handleAdminLogout(event)">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Header -->
                    <div class="bg-white border-bottom px-4 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-3">Selamat datang, {{ Auth::user()->name }}</span>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ Auth::user()->initials }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Handle admin logout with AJAX
        function handleAdminLogout(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to admin login page
                    window.location.href = data.redirect_url || '/admin/login';
                } else {
                    console.error('Logout failed:', data.message);
                    // Fallback to regular form submission
                    form.submit();
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                // Fallback to regular form submission
                form.submit();
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
