<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecta - Wisata Keluarga Terbaik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Golos Text', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8fafc;
            overflow-x: hidden;
        }

        /* FULL SCREEN HERO SECTION */
        .hero-section {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* FULL SCREEN VIDEO */
        .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* Video Overlay */
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(59, 130, 246, 0.1) 0%,
                rgba(30, 64, 175, 0.1) 30%,
                rgba(0, 0, 0, 0.2) 70%,
                rgba(0, 0, 0, 0.4) 100%
            );
            z-index: 2;
        }

        /* GRADIEN PUTIH DI BAWAH */
        .bottom-gradient {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(
                to top,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0.9) 30%,
                rgba(255, 255, 255, 0.5) 60%,
                rgba(255, 255, 255, 0) 100%
            );
            z-index: 3;
        }

        /* Content Over Video */
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 4;
            max-width: 700px;
            padding: 0 2rem;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transform: translateY(50px);
            animation: slideInUp 1.2s ease-out 0.5s forwards;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
            opacity: 0;
            transform: translateY(30px);
            animation: slideInUp 1s ease-out 1s forwards;
        }

        /* BOOKING BAR - FIXED VERSION */
        .booking-bar-container {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            opacity: 0;
            animation: slideUpBooking 1s ease-out 2s forwards;
        }

        .booking-form {
            background: white;
            border-radius: 60px;
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 0px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.5s cubic-bezier(0.4, 0.0, 0.2, 1);
            border: 1px solid rgba(59, 130, 246, 0.1);
            width: 248px; /* LEBIH LEBAR BIAR "PENGALAMAN" GAK KE KROP */
            overflow: hidden;
        }

        .booking-form:hover {
            width: 720px; /* LEBIH LEBAR LAGI BIAR "MENU MAKANAN" GAK KE KROP */
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 6px; /* Sedikit gap untuk spacing */
            width: 100%;
            overflow: visible; /* Changed from hidden */
        }

        .search-trigger {
            background: #1e40af;
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .search-trigger:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .search-options {
            display: flex;
            align-items: center;
            gap: 6px;
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.5s ease 0.2s; /* FIXED: Lebih smooth dan delay lebih lama */
            flex-shrink: 0; /* Prevent shrinking */
            padding-left: 8px;
            white-space: nowrap; /* FIXED: Prevent text wrapping */
        }

        .booking-form:hover .search-options {
            opacity: 1;
            transform: translateX(0);
        }

        .search-option {
            background: #f8fafc;
            color: #374151;
            border: 1px solid #e5e7eb;
            padding: 14px 18px; /* FIXED: Sedikit lebih besar padding */
            border-radius: 40px;
            font-weight: 500; /* FIXED: Konsisten dengan search-trigger */
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 130px; /* FIXED: Lebih lebar untuk text */
            justify-content: center;
            flex-shrink: 0;
        }

        .search-option:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: translateY(-1px);
            border-color: #cbd5e1;
        }

        .search-option .icon {
            width: 14px;
            height: 14px;
            filter: brightness(0.5);
        }

        .search-button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 16px 24px; /* FIXED: Padding yang konsisten */
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 160px; /* FIXED: Ukuran yang pas */
            justify-content: center;
            flex-shrink: 0;
        }

        .search-button:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        /* Floating Decorations */
        .floating-decoration {
            position: absolute;
            pointer-events: none;
            opacity: 0.15;
            animation: floatAround 20s linear infinite;
            z-index: 2;
        }

        .floating-decoration:nth-child(1) {
            top: 15%;
            left: 8%;
            font-size: 3rem;
            animation-delay: 0s;
        }

        .floating-decoration:nth-child(2) {
            top: 25%;
            right: 12%;
            font-size: 2.5rem;
            animation-delay: -5s;
        }

        .floating-decoration:nth-child(3) {
            bottom: 25%;
            left: 10%;
            font-size: 2rem;
            animation-delay: -10s;
        }

        .floating-decoration:nth-child(4) {
            bottom: 30%;
            right: 8%;
            font-size: 3.5rem;
            animation-delay: -15s;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUpBooking {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(60px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        @keyframes floatAround {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-20px) rotate(90deg);
            }
            50% {
                transform: translateY(0px) rotate(180deg);
            }
            75% {
                transform: translateY(-10px) rotate(270deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-center {
                display: none;
            }
            
            .hero-title {
                font-size: 2.8rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .booking-form {
                width: 250px;
            }

            .booking-form:hover {
                width: 90vw;
                max-width: 650px;
            }

            .search-trigger {
                min-width: 180px;
                padding: 14px 20px;
                font-size: 14px;
            }

            .search-option {
                min-width: 110px;
                padding: 12px 14px;
                font-size: 12px;
            }

            .search-button {
                min-width: 140px;
                padding: 14px 18px;
                font-size: 13px;
            }

            .floating-decoration {
                font-size: 1.5rem !important;
            }

            .nav-container {
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .booking-form {
                width: 220px;
            }

            .search-trigger {
                min-width: 160px;
                font-size: 13px;
            }

            .search-option {
                min-width: 100px;
                font-size: 11px;
            }

            .search-button {
                min-width: 120px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
   @include('components.navbar')

    <!-- FULL SCREEN HERO SECTION -->
    <section class="hero-section">
        <!-- Floating Decorations -->
        <div class="floating-decoration"><i class="fa-solid fa-person-skiing"></i></div>
<div class="floating-decoration"><i class="fa-solid fa-mountain"></i></div>
<div class="floating-decoration"><i class="fa-solid fa-ferris-wheel"></i></div>
<div class="floating-decoration"><i class="fa-solid fa-seedling"></i></div>

        <!-- FULL SCREEN VIDEO -->
        <video class="hero-video" autoplay muted loop playsinline preload="auto">
            <source src="{{ asset('videos/selectaa.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Video Overlay -->
        <div class="video-overlay"></div>

        <!-- GRADIEN PUTIH DI BAWAH -->
        <div class="bottom-gradient"></div>

        <!-- Content Over Video -->
        <div class="hero-content">
            <h1 class="hero-title">Selecta Malang</h1>
            <p class="hero-subtitle">Destinasi wisata keluarga terbaik dengan pemandangan pegunungan yang menakjubkan</p>
        </div>

        <!-- BOOKING BAR - FIXED VERSION -->
        <div class="booking-bar-container">
            <div class="booking-form">
                <div class="search-container">
                    <button class="search-trigger" onclick="searchExperience()">
    <i class="fa-solid fa-magnifying-glass"></i> Temukan Pengalaman
</button>
                    
                    <div class="search-options">
                       <a href="{{ route('tickets.index') }}" class="search-option">
    <i class="fa-solid fa-box"></i>
    <span>Paket Pilihan</span>
</a>
<a href="{{ route('hotels.index') }}" class="search-option">
    <i class="fa-solid fa-hotel"></i>
    <span>Pesan Kamar</span>
</a>
<a href="{{ route('restaurants.index') }}" class="search-option">
    <i class="fa-solid fa-utensils"></i>
    <span>Menu Makanan</span>
</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Search experience function
        function searchExperience() {
            window.location.href = "{{ route('search.index') }}";
        }

        // Enhanced parallax effect for floating decorations
        document.addEventListener('mousemove', (e) => {
            const decorations = document.querySelectorAll('.floating-decoration');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            decorations.forEach((decoration, index) => {
                const speed = (index + 1) * 0.02;
                const x = (mouseX - 0.5) * 100 * speed;
                const y = (mouseY - 0.5) * 100 * speed;
                
                decoration.style.transform = `translate(${x}px, ${y}px) rotate(${x * 0.1}deg)`;
                decoration.style.transition = 'transform 0.1s ease-out';
            });
        });

        // Smooth page load animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
            document.body.style.transform = 'translateY(0)';
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(248, 250, 252, 0.95)';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'transparent';
                navbar.style.boxShadow = 'none';
                navbar.style.backdropFilter = 'none';
            }
        });
    </script>
</body>
</html>