{{-- resources/views/components/navbar.blade.php --}}
<style>
    /* Header & Navigation - NAVBAR TRANSPARAN SEPERTI INDONESIA.TRAVEL */
    .header {
        background: transparent;
        padding: 1rem 0;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        backdrop-filter: none;
        border-bottom: none;
        transition: all 0.3s ease;
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 2rem;
        position: relative;
        z-index: 10;
        height: 60px;
    }

    /* Background putih yang muncul saat hover navbar - DIPERBAIKI FULL WIDTH */
    .header::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: calc(60px + 2rem); /* Header height + padding */
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        opacity: 0;
        transition: opacity 0.3s ease;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        pointer-events: none;
        z-index: 1;
    }

    /* Efek hover pada header - FULL WIDTH */
    .header:hover::before {
        opacity: 1;
    }

    .logo {
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #333;
        z-index: 10;
        position: relative;
        display: flex;
        align-items: center;
        height: 100%;
    }

    .logo:hover {
        transform: scale(1.05);
    }

    /* Logo container untuk menampung kedua gambar */
    .logo-container {
        position: relative;
        display: flex;
        align-items: center;
        z-index: 10;
        height: 100%;
    }

    /* Logo default (putih) */
    .logo-default {
        transition: all 0.3s ease;
        filter: brightness(1);
        position: relative;
        z-index: 3;
        height: 40px;
        width: auto;
    }

    /* Logo hover (berwarna) - tersembunyi secara default */
    .logo-hover {
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 2;
        height: 40px;
        width: auto;
    }

    /* Saat header di-hover, tampilkan logo berwarna */
    .header:hover .logo-default {
        opacity: 0;
    }

    .header:hover .logo-hover {
        opacity: 1;
        filter: brightness(1.1) drop-shadow(0 4px 12px rgba(0,0,0,0.15));
    }

    /* Saat logo di-hover individual */
    .logo-container:hover .logo-hover {
        transform: translateY(-50%) scale(1.05);
    }

    .nav-center {
        display: flex;
        list-style: none;
        gap: 0;
        align-items: center;
        padding: 0;
        margin: 0;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        height: 100%;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        z-index: 10;
        position: relative;
        height: 100%;
    }

    .nav-item {
        position: relative;
        margin: 0;
        display: flex;
        align-items: center;
        height: 100%;
    }

    /* Default state - teks putih transparan */
    .nav-link {
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.8rem 1.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        position: relative;
        border-radius: 6px;
        height: 100%;
        line-height: 1;
    }

    /* Saat header di-hover, semua teks berubah gelap */
    .header:hover .nav-link {
        color: #374151;
        text-shadow: none;
    }

    /* Individual link hover */
    .nav-link:hover {
        color: #1e40af !important;
    }

    /* Active state */
    .nav-link.active {
        position: relative;
    }

    .header:hover .nav-link.active {
        color: #1e40af;
        font-weight: 600;
    }

    /* Login button default state */
    .login-btn {
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.8rem 1.5rem;
        transition: all 0.3s ease;
        background: transparent;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        height: auto;
        line-height: 1;
    }

    /* Login button saat header di-hover */
    .header:hover .login-btn {
        color: #374151;
        text-shadow: none;
        border-color: rgba(55, 65, 81, 0.2);
    }

    .login-btn:hover {
        color: #1e40af !important;
        border-color: rgba(30, 64, 175, 0.3);
    }

    /* User menu styling */
    .user-menu {
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
    }

    .user-name {
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
        line-height: 1;
    }

    .header:hover .user-name {
        color: #374151;
        text-shadow: none;
    }

    .header.scrolled .user-name {
        color: #374151;
        text-shadow: none;
    }

    .logout-btn {
        color: white;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        line-height: 1;
    }

    .header:hover .logout-btn {
        color: #374151;
        text-shadow: none;
        border-color: rgba(55, 65, 81, 0.2);
    }

    .logout-btn:hover {
        color: #dc2626 !important;
        border-color: rgba(220, 38, 38, 0.3);
        background: rgba(220, 38, 38, 0.1);
    }

    .header.scrolled .logout-btn {
        color: #374151;
        text-shadow: none;
        border-color: rgba(55, 65, 81, 0.2);
    }

    .header.scrolled .logout-btn:hover {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.3);
        background: rgba(220, 38, 38, 0.1);
    }

    /* User avatar container - POSISI DINAIKKAN */
    .user-avatar-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: 25px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        height: auto;
        line-height: 1;
        transform: translateY(-2px); /* DINAIKKAN 2px */
    }

    .header:hover .user-avatar-container {
        border-color: rgba(55, 65, 81, 0.2);
        background: rgba(255, 255, 255, 0.05);
    }

    .user-avatar-container:hover {
        border-color: rgba(30, 64, 175, 0.3);
        background: rgba(30, 64, 175, 0.1);
        transform: translateY(-3px); /* HOVER EFFECT NAIK SEDIKIT */
    }

    .header.scrolled .user-avatar-container {
        border-color: rgba(55, 65, 81, 0.2);
    }

    .header.scrolled .user-avatar-container:hover {
        border-color: rgba(30, 64, 175, 0.3);
        background: rgba(30, 64, 175, 0.1);
    }

    /* User avatar - circular image */
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
        display: block;
        flex-shrink: 0;
    }

    .header:hover .user-avatar {
        border-color: rgba(55, 65, 81, 0.3);
    }

    .header.scrolled .user-avatar {
        border-color: rgba(55, 65, 81, 0.3);
    }

    /* User avatar initial - circular with letter */
    .user-avatar-initial {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        border: 2px solid rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
        text-shadow: none;
        flex-shrink: 0;
        line-height: 1;
    }

    .header:hover .user-avatar-initial {
        border-color: rgba(55, 65, 81, 0.3);
        background: linear-gradient(135deg, #1e40af, #1e3a8a);
    }

    .header.scrolled .user-avatar-initial {
        border-color: rgba(55, 65, 81, 0.3);
    }

    /* Profile text - SEDIKIT DINAIKKAN */
    .profile-text {
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
        white-space: nowrap;
        line-height: 1;
        transform: translateY(-1px); /* TEKS DINAIKKAN 1px */
    }

    .header:hover .profile-text {
        color: #374151;
        text-shadow: none;
    }

    .user-avatar-container:hover .profile-text {
        color: #1e40af !important;
    }

    .header.scrolled .profile-text {
        color: #374151;
        text-shadow: none;
    }

    .header.scrolled .user-avatar-container:hover .profile-text {
        color: #1e40af;
    }

    /* Dropdown arrow */
    .dropdown-arrow {
        font-size: 12px;
        transition: transform 0.3s ease;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        line-height: 1;
    }

    .header:hover .dropdown-arrow {
        color: #374151;
        text-shadow: none;
    }

    .header.scrolled .dropdown-arrow {
        color: #374151;
        text-shadow: none;
    }

    .user-avatar-container.active .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Profile dropdown */
    .profile-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
        margin-top: 8px;
    }

    .profile-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 14px;
        line-height: 1;
    }

    .dropdown-item:hover {
        background: #f3f4f6;
        color: #1e40af;
    }

    .dropdown-item:first-child {
        border-radius: 12px 12px 0 0;
    }

    .logout-item {
        color: #dc2626;
        border-radius: 0 0 12px 12px;
    }

    .logout-item:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .dropdown-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 4px 0;
    }

    .dropdown-item i {
        width: 16px;
        text-align: center;
    }

    /* User menu positioning */
    .user-menu {
        position: relative;
    }

    /* Navbar scroll state - DISEDERHANAKAN */
    .header.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* HAPUS ::before UNTUK SCROLLED STATE AGAR TIDAK DOUBLE */
    .header.scrolled::before {
        display: none; /* MENCEGAH DOUBLE BACKGROUND */
    }

    /* Saat di-scroll, gunakan logo berwarna */
    .header.scrolled .logo-default {
        opacity: 0;
    }

    .header.scrolled .logo-hover {
        opacity: 1;
    }

    .header.scrolled .nav-link {
        color: #374151;
        text-shadow: none;
    }

    .header.scrolled .nav-link:hover {
        color: #1e40af;
    }

    .header.scrolled .nav-link.active {
        color: #1e40af;
        font-weight: 600;
    }

    .header.scrolled .login-btn {
        color: #374151;
        text-shadow: none;
        border-color: rgba(55, 65, 81, 0.2);
    }

    .header.scrolled .login-btn:hover {
        color: #1e40af;
    }

    .header.scrolled .logo {
        color: #1e40af;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .nav-center {
            display: none;
        }
        
        .nav-container {
            padding: 0 1rem;
            height: 60px;
        }

        .header {
            width: 100vw;
            left: 0;
        }

        /* Mobile full width hover effect */
        .header::before {
            width: 100vw;
            left: 0;
            right: 0;
        }

        .header {
            padding: 1rem 0;
        }

        .logo-default,
        .logo-hover {
            height: 35px;
        }

        .user-avatar,
        .user-avatar-initial {
            width: 32px;
            height: 32px;
        }

        .user-avatar-initial {
            font-size: 14px;
        }

        .profile-text {
            font-size: 0.85rem;
        }

        .user-avatar-container {
            padding: 0.5rem 0.8rem;
            transform: translateY(-1px);
        }

        .user-avatar-container:hover {
            transform: translateY(-2px);
        }
    }

    /* Smooth animations */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Efek transisi yang lebih smooth */
    .nav-link, .login-btn {
        will-change: color, background-color;
    }
</style>

<!-- Header dengan Navbar -->
<header class="header" id="navbar">
    <div class="nav-container">
        <!-- Logo with image switching effect -->
        <div class="logo">
            <a href="{{ route('home') }}" class="logo-container">
                <!-- Logo putih (default state) -->
                <img src="/images/logo4.png" alt="Selecta" class="logo-default">
                <!-- Logo berwarna (hover state) -->
                <img src="/images/logo2.png" alt="Selecta" class="logo-hover">
            </a>
        </div>
        
        <!-- Center Navigation -->
        <nav>
            <ul class="nav-center" id="navMenu">
                @php
                    $navItems = [
                        ['name' => 'Beranda', 'route' => 'home', 'url' => route('home')],
                        ['name' => 'Tiket', 'route' => 'tickets.*', 'url' => route('tickets.index')],
                        ['name' => 'Hotel', 'route' => 'hotels.*', 'url' => route('hotels.index')],
                        ['name' => 'Restoran', 'route' => 'restaurants.*', 'url' => route('restaurants.index')],
                        ['name' => 'Galeri', 'route' => 'gallery.*', 'url' => route('gallery.index')]
                    ];
                @endphp

                @foreach($navItems as $item)
                    <li class="nav-item">
                        <a href="{{ $item['url'] }}" 
                           class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <!-- Right side - Login Button or User Menu -->
        <div class="nav-right">
            @auth
                <!-- User is logged in - show avatar with dropdown -->
                <div class="user-menu">
                    <div class="user-avatar-container" onclick="toggleProfileDropdown()">
                        @if(Auth::user()->avatar && filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL))
                            <!-- Google profile picture -->
                            <img src="{{ Auth::user()->avatar }}" alt="Profile" class="user-avatar">
                        @elseif(Auth::user()->avatar)
                            <!-- Uploaded profile picture -->
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Profile" class="user-avatar">
                        @else
                            <!-- Initial avatar for manual registration -->
                            <div class="user-avatar-initial">{{ Auth::user()->initials }}</div>
                        @endif
                        <span class="profile-text">Profil Saya</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('booking-history.index') }}" class="dropdown-item">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Pemesanan</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="handleLogout(event)">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- User is not logged in - show login button -->
                <a href="{{ route('login') }}" class="login-btn">Login</a>
            @endauth
        </div>
    </div>
</header>

<script>
    // Improved navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Function to toggle profile dropdown
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const container = dropdown.parentElement.querySelector('.user-avatar-container');
        
        dropdown.classList.toggle('show');
        container.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = document.querySelector('.user-menu');
        const dropdown = document.getElementById('profileDropdown');
        
        if (dropdown && userMenu && !userMenu.contains(event.target)) {
            dropdown.classList.remove('show');
            userMenu.querySelector('.user-avatar-container').classList.remove('active');
        }
    });

    // Function to open login modal - sesuai dengan auth-modal.blade.php
    function openLoginModal() {
        const modal = document.getElementById('authModal');
        if (modal) {
            modal.classList.add('active');
            document.getElementById('loginModal').classList.add('active');
            document.getElementById('registerModal').classList.remove('active');
            document.body.style.overflow = 'hidden';
            console.log('Modal opened successfully');
        } else {
            console.log('Auth modal not found. Make sure to include auth-modal component.');
            window.location.href = '/login';
        }
    }

    // Handle logout with AJAX (robust with CSRF fallback and graceful degrade)
    function handleLogout(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        // Prefer CSRF from meta, fallback to hidden _token from the form
        let csrfToken = '';
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        if (metaCsrf) {
            csrfToken = metaCsrf.getAttribute('content') || '';
        }
        if (!csrfToken) {
            csrfToken = formData.get('_token') || '';
        }

        // If no fetch or token issues, just submit the form normally
        if (!window.fetch || !csrfToken) {
            form.submit();
            return;
        }

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(async response => {
            // Try parse JSON; if fails, fallback to normal submit
            try {
                const data = await response.json();
                if (response.ok && data && data.success) {
                    window.location.href = data.redirect_url || '/';
                } else {
                    form.submit();
                }
            } catch (e) {
                // Non-JSON (e.g., 302 redirect HTML) -> let server handle via normal submit
                form.submit();
            }
        })
        .catch(() => {
            form.submit();
        });
    }

    // Optional: Add subtle animation delay for each nav item
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Navbar initialized');
        
        window.openLoginModal = openLoginModal;
        window.toggleProfileDropdown = toggleProfileDropdown;
        window.handleLogout = handleLogout;
    });
</script>