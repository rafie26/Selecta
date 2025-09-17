<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Selecta - Pemesanan Kamar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Midtrans Snap -->
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            color: #333;
            background: #ffffff;
        }

        /* Hero Section with Full Image Slider */
        .hero-section {
            position: relative;
            height: 70vh;
            min-height: 500px;
            overflow: hidden;
        }

        .hero-slider {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide:nth-child(1) {
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                              url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(2) {
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                              url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(3) {
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                              url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(4) {
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                              url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .nav-arrow:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .prev {
            left: 2rem;
        }

        .next {
            right: 2rem;
        }

        /* Dots indicator */
        .dots-container {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: white;
            transform: scale(1.2);
        }

        /* Hotel Info Section */
        .hotel-info {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .hotel-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .hotel-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .hotel-rating {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }

        .stars {
            color: #f59e0b;
            font-size: 1.1rem;
        }

        .award-badge {
            background: #d97706;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .hotel-location {
            color: #6b7280;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Booking Section */
        .booking-section {
            background: #f9fafb;
            padding: 1.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .booking-form {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-input {
            padding: 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .update-btn {
            background: #1d4ed8;
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .update-btn:hover {
            background: #1e40af;
        }

        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Rooms Section */
        .rooms-section {
            background: white;
        }

        .section-header {
            background: #f8fafc;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        /* FIXED: Compact booking info layout - 2x2 grid */
        .booking-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: #6b7280;
            background: #ffffff;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            max-width: 500px;
        }

        .booking-info-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.8rem;
            background: #f8fafc;
            border-radius: 6px;
            font-weight: 500;
            white-space: nowrap;
            justify-content: flex-start;
        }

        .booking-info-item .icon {
            font-size: 0.9rem;
            color: #3b82f6;
            flex-shrink: 0;
        }

        /* Room Card - Updated Layout */
        .room-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            background: white;
            transition: box-shadow 0.2s;
            overflow: hidden;
        }

        .room-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .room-main {
            display: flex;
            padding: 1.5rem;
            gap: 1.5rem;
        }

        .room-image {
            width: 200px;
            height: 150px;
            background-size: cover;
            background-position: center;
            border-radius: 6px;
            flex-shrink: 0;
            position: relative;
        }

        .image-indicator {
            position: absolute;
            bottom: 0.5rem;
            right: 0.5rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .room-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .room-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }

        .room-specs {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .room-spec {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .room-description {
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .room-amenity {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #059669;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .room-pricing {
            flex: 1;
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 200px;
        }

        .starting-from {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .price-display {
            margin-bottom: 1rem;
        }

        .price-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .price-period {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .availability-badge {
            background: #3b82f6;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .availability-warning {
            background: #dc2626;
            color: white;
        }

        .details-book-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .details-book-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .details-book-btn.disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .details-book-btn.disabled:hover {
            background: #9ca3af;
            transform: none;
        }

        .room-card.unavailable {
            opacity: 0.7;
        }

        .unavailable-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(220, 38, 38, 0.8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 6px;
        }

        /* Room Details Sidebar */
        .room-details-sidebar {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .room-details-sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .close-btn:hover {
            background: #e5e7eb;
        }

        .sidebar-content {
            padding: 1.5rem;
        }

        .rate-option {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .rate-option:hover, .rate-option.selected {
            border-color: #2563eb;
            background: #f0f9ff;
        }

        .rate-type {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rate-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2563eb;
        }

        .rate-features {
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .rate-note {
            color: #dc2626;
            font-size: 0.8rem;
            font-style: italic;
        }

        .breakfast-badge {
            background: #059669;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .reserve-btn {
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 1.5rem;
        }

        .reserve-btn:hover {
            background: #15803d;
        }

        /* Sidebar Left */
        .sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .cart-summary {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .cart-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .empty-cart {
            text-align: center;
            color: #6b7280;
            padding: 2rem;
        }

        .empty-cart-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .cart-items {
            margin-bottom: 1rem;
        }

        .cart-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .cart-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .cart-room-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9rem;
        }

        .cart-remove-btn {
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.2rem;
            border-radius: 3px;
            transition: background 0.2s;
        }

        .cart-remove-btn:hover {
            background: #fee2e2;
        }

        .cart-room-rate {
            color: #6b7280;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .cart-quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .quantity-btn {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            width: 28px;
            height: 28px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #e5e7eb;
        }

        .quantity-btn:disabled {
            background: #f9fafb;
            color: #d1d5db;
            cursor: not-allowed;
        }

        .quantity-display {
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        .cart-item-price {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9rem;
            text-align: right;
        }

        .cart-total {
            border-top: 2px solid #e5e7eb;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .cart-subtotal {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .cart-tax {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .cart-grand-total {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1f2937;
            border-top: 1px solid #e5e7eb;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }

        .proceed-to-booking {
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 1rem;
        }

        .proceed-to-booking:hover {
            background: #15803d;
        }

        .proceed-to-booking:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        /* Mobile Cart - Sticky Bottom */
        .mobile-cart {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.15);
            z-index: 100;
            border-top: 1px solid #e5e7eb;
        }

        .mobile-cart-header {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            border-bottom: 1px solid #e5e7eb;
        }

        .mobile-cart-title {
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mobile-cart-total {
            font-weight: 700;
            color: #16a34a;
        }

        .mobile-cart-arrow {
            transition: transform 0.3s ease;
        }

        .mobile-cart.expanded .mobile-cart-arrow {
            transform: rotate(180deg);
        }

        .mobile-cart-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .mobile-cart.expanded .mobile-cart-content {
            max-height: 70vh;
            overflow-y: auto;
        }

        .mobile-cart-inner {
            padding: 1rem;
        }

        .mobile-cart-badge {
            background: #dc2626;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0.5rem;
        }

        /* Loading & Messages */
        .loading-message {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            font-style: italic;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .no-rooms-message {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Icon styles */
        .icon {
            color: #000000;
            font-size: inherit;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                height: 50vh;
                min-height: 300px;
            }

            .nav-arrow {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .prev {
                left: 1rem;
            }

            .next {
                right: 1rem;
            }

            .main-content {
                grid-template-columns: 1fr;
                padding: 1rem;
                padding-bottom: 120px;
            }

            .booking-form {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .room-main {
                flex-direction: column;
            }

            .room-image {
                width: 100%;
                height: 200px;
            }

            .room-pricing {
                text-align: left;
                min-width: auto;
            }

            .booking-info {
                flex-direction: column;
                gap: 0.8rem;
                align-items: stretch;
                padding: 1rem;
            }

            .booking-info-item {
                justify-content: center;
                padding: 0.5rem;
            }

            .room-details-sidebar {
                width: 100%;
                right: -100%;
            }

            .sidebar {
                display: none;
            }

            .mobile-cart {
                display: block;
            }
        }
    </style>
</head>
<body>
      @include('components.navbar')
    <!-- Hero Section with Full Image Slider -->
    <section class="hero-section">
        <div class="hero-slider">
            <div class="slide active"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
        </div>
        
        <!-- Navigation Arrows -->
        <button class="nav-arrow prev" onclick="changeSlide(-1)">❮</button>
        <button class="nav-arrow next" onclick="changeSlide(1)">❯</button>
        
        <!-- Dots Indicator -->
        <div class="dots-container">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
        </div>
    </section>

    <!-- Hotel Info Section -->
    <section class="hotel-info">
        <div class="hotel-container">
            <h1 class="hotel-title">Hotel Selecta Batu Malang</h1>
            <div class="hotel-rating">
                <div class="stars">★★★★</div>
                <div class="award-badge">Website Resmi - Jaminan Harga Terbaik</div>
            </div>
            <div class="hotel-location">
                <i class="fas fa-map-marker-alt icon"></i>
                Jl. Raya Selecta No. 1 desa Tulungrejo, Kec. Bumiaji - 65336 Kota Batu, Indonesia
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <section class="booking-section">
        <div class="hotel-container">
            <form class="booking-form" onsubmit="return false;">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt icon"></i>
                        Check-in
                    </label>
                    <input type="date" class="form-input" id="checkin-date" value="2025-08-11">
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt icon"></i>
                        Check-out
                    </label>
                    <input type="date" class="form-input" id="checkout-date" value="2025-08-12">
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-users icon"></i>
                        Tamu
                    </label>
                    <select class="form-input" id="guests-select">
                        <option value="2">2 Dewasa</option>
                        <option value="1">1 Dewasa</option>
                        <option value="3">3 Dewasa</option>
                        <option value="4">4 Dewasa</option>
                        <option value="5">5+ Dewasa</option>
                    </select>
                </div>
                <!-- Hidden input for number of rooms - always 1 -->
                <input type="hidden" name="number_of_rooms" value="1">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag icon"></i>
                        Kode Promo
                    </label>
                    <input type="text" class="form-input" placeholder="Masukkan kode promo">
                </div>
                <div class="form-group">
                    <button type="button" class="update-btn" onclick="updateAvailability()">CEK KETERSEDIAAN</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Rooms Section -->
        <div class="rooms-section">
            <div class="section-header">
                <h2 class="section-title">Pilih kamar yang paling sesuai untuk Anda</h2>
                <div class="booking-info">
                    <span>
                        <i class="fas fa-home icon"></i>
                        KAMAR 1 DARI 1
                    </span>
                    <span>
                        <i class="fas fa-users icon"></i>
                        2 DEWASA
                    </span>
                    <span>
                        <i class="fas fa-calendar-alt icon"></i>
                        <span id="display-dates">SEN 11 AGS 2025 ➔ SEL 12 AGS 2025</span>
                    </span>
                    <span>
                        <i class="fas fa-moon icon"></i>
                        <span id="display-nights">1 MALAM</span>
                    </span>
                </div>
            </div>

            <div id="rooms-container">
                <!-- Loading message will be shown initially -->
                <div class="loading-message">
                    <i class="fas fa-spinner fa-spin icon"></i>
                    Mencari kamar yang tersedia...
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="cart-title">
                    <i class="fas fa-shopping-cart icon"></i>
                    Pilihan Anda
                </h3>
                <div id="cart-content">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-bag icon"></i>
                        </div>
                        <p>Belum ada kamar yang dipilih</p>
                        <p style="font-size: 0.8rem; margin-top: 0.5rem;">Pilih kamar idaman Anda di atas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Cart - Sticky Bottom for Mobile -->
    <div class="mobile-cart" id="mobileCart">
        <div class="mobile-cart-header" onclick="toggleMobileCart()">
            <div class="mobile-cart-title">
                <i class="fas fa-shopping-cart icon"></i>
                Pilihan Anda
                <span class="mobile-cart-badge" id="mobile-cart-badge" style="display: none;">0</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="mobile-cart-total" id="mobile-cart-total">Rp 0</div>
                <div class="mobile-cart-arrow">
                    <i class="fas fa-chevron-up icon"></i>
                </div>
            </div>
        </div>
        <div class="mobile-cart-content">
            <div class="mobile-cart-inner" id="mobile-cart-content">
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-bag icon"></i>
                    </div>
                    <p>Belum ada kamar yang dipilih</p>
                    <p style="font-size: 0.8rem; margin-top: 0.5rem;">Pilih kamar idaman Anda di atas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details Sidebar -->
    <div class="room-details-sidebar" id="roomDetailsSidebar">
        <div class="sidebar-header">
            <h3 id="sidebar-room-name">Detail Kamar</h3>
            <button class="close-btn" onclick="closeSidebar()">✕</button>
        </div>
        <div class="sidebar-content" id="sidebar-content">
            <!-- Content will be populated dynamically -->
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <script>
        // Global variables
        let roomsData = [];
        let currentBookingData = {
            check_in: null,
            check_out: null,
            guests: 2,
            nights: 1
        };
        
        // Authentication status  
        const isUserLoggedIn = document.querySelector('meta[name="user-authenticated"]').getAttribute('content') === 'true';

        // Cart functionality
        let cart = [];
        let selectedRates = {};

        // Image slider functionality
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }

        function changeSlide(direction) {
            currentSlideIndex += direction;
            
            if (currentSlideIndex >= totalSlides) {
                currentSlideIndex = 0;
            } else if (currentSlideIndex < 0) {
                currentSlideIndex = totalSlides - 1;
            }
            
            showSlide(currentSlideIndex);
        }

        function currentSlide(index) {
            currentSlideIndex = index - 1;
            showSlide(currentSlideIndex);
        }

        // Auto-play slider
        setInterval(() => {
            changeSlide(1);
        }, 5000);

        // Calculate number of nights between dates
        function calculateNights() {
            const checkinDate = new Date(document.getElementById('checkin-date').value);
            const checkoutDate = new Date(document.getElementById('checkout-date').value);
            
            if (!checkinDate || !checkoutDate || checkoutDate <= checkinDate) {
                return 1; // Default to 1 night
            }
            
            const timeDiff = checkoutDate - checkinDate;
            return Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
        }

        // Update display dates and nights with dynamic pricing
        function updateDateDisplay() {
            const checkinDate = new Date(document.getElementById('checkin-date').value);
            const checkoutDate = new Date(document.getElementById('checkout-date').value);
            
            // Indonesian day and month names
            const dayNames = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
            const monthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 
                               'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES'];
            
            const checkinDay = dayNames[checkinDate.getDay()];
            const checkinMonth = monthNames[checkinDate.getMonth()];
            const checkoutDay = dayNames[checkoutDate.getDay()];
            const checkoutMonth = monthNames[checkoutDate.getMonth()];
            
            const checkinFormatted = `${checkinDay} ${checkinDate.getDate()} ${checkinMonth} ${checkinDate.getFullYear()}`;
            const checkoutFormatted = `${checkoutDay} ${checkoutDate.getDate()} ${checkoutMonth} ${checkoutDate.getFullYear()}`;
            
            const nights = calculateNights();
            
            document.getElementById('display-dates').textContent = `${checkinFormatted} ➔ ${checkoutFormatted}`;
            document.getElementById('display-nights').textContent = `${nights} MALAM`;
            
            // Update room prices based on number of nights
            updateRoomPrices();
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount).replace('IDR', 'Rp');
        }

        // Get amenity icon
        function getAmenityIcon(amenity) {
            const iconMap = {
                'AC': 'fas fa-snowflake',
                'Kamar Mandi': 'fas fa-shower',
                'Perlengkapan Mandi Gratis': 'fas fa-soap',
                'Shower': 'fas fa-shower',
                'Handuk': 'fas fa-bath',
                'Seprai': 'fas fa-bed',
                'Pembuat Kopi': 'fas fa-coffee',
                'Mini Bar': 'fas fa-wine-glass',
                'Kulkas': 'fas fa-cube',
                'Pengering Rambut': 'fas fa-wind',
                'Balkon': 'fas fa-mountain',
                'Akses Kolam': 'fas fa-swimming-pool',
                'WiFi Gratis': 'fas fa-wifi'
            };
            return iconMap[amenity] || 'fas fa-check-circle';
        }

        // Update room prices based on selected nights
        function updateRoomPrices() {
            const nights = calculateNights();
            const roomCards = document.querySelectorAll('.room-card');
            
            roomCards.forEach((card, index) => {
                if (index < roomsData.length) {
                    const room = roomsData[index];
                    const totalPrice = room.total_price;
                    
                    const priceAmount = card.querySelector('.price-amount');
                    const pricePeriod = card.querySelector('.price-period');
                    
                    if (priceAmount && pricePeriod) {
                        priceAmount.textContent = formatCurrency(totalPrice);
                        pricePeriod.innerHTML = `untuk <span style="color: #1f2937; font-weight: 600;">${nights}</span> malam`;
                        
                        // Add price breakdown if more than 1 night
                        if (nights > 1) {
                            const priceBreakdown = card.querySelector('.price-breakdown') || document.createElement('div');
                            priceBreakdown.className = 'price-breakdown';
                            priceBreakdown.style.cssText = 'font-size: 0.8rem; color: #6b7280; margin-top: 0.3rem;';
                            priceBreakdown.innerHTML = `${formatCurrency(room.price_per_night)} x ${nights} malam`;
                            
                            if (!card.querySelector('.price-breakdown')) {
                                pricePeriod.parentNode.appendChild(priceBreakdown);
                            }
                        } else {
                            const existingBreakdown = card.querySelector('.price-breakdown');
                            if (existingBreakdown) {
                                existingBreakdown.remove();
                            }
                        }
                    }
                }
            });
        }

        // Render rooms
        function renderRooms(rooms) {
            const container = document.getElementById('rooms-container');
            
            if (rooms.length === 0) {
                container.innerHTML = `
                    <div class="no-rooms-message">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">
                            <i class="fas fa-bed icon"></i>
                        </div>
                        <h3>Tidak ada kamar tersedia</h3>
                        <p>Untuk tanggal yang dipilih. Silakan coba tanggal lain.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = rooms.map(room => {
                const isAvailable = room.is_available;
                const availableRooms = room.available_rooms;
                const totalPrice = room.total_price;
                const nights = room.nights;
                const imageUrl = room.images && room.images.length > 0 ? room.images[0] : 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                
                return `
                <div class="room-card ${!isAvailable ? 'unavailable' : ''}">
                    <div class="room-main">
                        <div class="room-image" style="background-image: url('${imageUrl}')">
                            <div class="image-indicator">
                                <i class="fas fa-camera icon"></i>
                                ${room.images ? room.images.length : 1} / ${room.images ? room.images.length : 1}
                            </div>
                            ${!isAvailable ? '<div class="unavailable-overlay">TIDAK TERSEDIA</div>' : ''}
                        </div>
                        
                        <div class="room-details">
                            <h3 class="room-name">${room.name}</h3>
                            <div class="room-specs">
                                <div class="room-spec">
                                    <i class="fas fa-users icon"></i>
                                    <span>Maks tamu: ${room.max_occupancy}</span>
                                </div>
                            </div>
                            <div class="room-description">
                                ${room.description}
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.3rem; margin-top: 0.5rem;">
                                ${room.amenities ? room.amenities.slice(0, 4).map(amenity => `
                                    <div class="room-amenity">
                                        <i class="${getAmenityIcon(amenity)} icon"></i>
                                        <span>${amenity}</span>
                                    </div>
                                `).join('') : ''}
                                ${room.amenities && room.amenities.length > 4 ? `
                                    <div class="room-amenity" style="color: #6b7280;">
                                        <i class="fas fa-plus icon"></i>
                                        <span>+${room.amenities.length - 4} lainnya</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="room-pricing">
                            ${isAvailable ? (
                                availableRooms <= 3 ? `
                                    <div class="availability-badge ${availableRooms === 1 ? 'availability-warning' : ''}">
                                        ${availableRooms === 1 ? 'SISA 1 KAMAR' : `SISA ${availableRooms} KAMAR`}
                                    </div>
                                ` : ''
                            ) : `
                                <div class="availability-badge availability-warning">
                                    TIDAK TERSEDIA
                                </div>
                            `}
                            <div class="starting-from">Total harga</div>
                            <div class="price-display">
                                <div class="price-amount">${formatCurrency(totalPrice)}</div>
                                <div class="price-period">untuk <span style="color: #1f2937; font-weight: 600;">${nights}</span> malam</div>
                                ${nights > 1 ? `
                                    <div class="price-breakdown" style="font-size: 0.8rem; color: #6b7280; margin-top: 0.3rem;">
                                        ${formatCurrency(room.price_per_night)} x ${nights} malam
                                    </div>
                                ` : ''}
                            </div>
                            <button class="details-book-btn ${!isAvailable ? 'disabled' : ''}" 
                                    onclick="${isAvailable ? `openRoomDetails(${room.id})` : 'void(0)'}" 
                                    ${!isAvailable ? 'disabled' : ''}>
                                ${isAvailable ? 'DETAIL & PESAN' : 'TIDAK TERSEDIA'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            }).join('');
        }

        // Toggle mobile cart
        function toggleMobileCart() {
            const mobileCart = document.getElementById('mobileCart');
            mobileCart.classList.toggle('expanded');
        }

        // Open room details sidebar
        function openRoomDetails(roomId) {
            const room = roomsData.find(r => r.id === roomId);
            if (!room) return;

            const nights = calculateNights();
            document.getElementById('sidebar-room-name').textContent = room.name;
            
            const content = `
                <div style="text-align: center; margin-bottom: 2rem;">
                    <img src="${room.images && room.images.length > 0 ? room.images[0] : 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'}" alt="${room.name}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                    <div style="margin-top: 1rem;">
                        <h4 style="margin-bottom: 1rem; color: #1f2937;">${room.description}</h4>
                        <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                            <span><i class="fas fa-users icon"></i> Maks tamu: ${room.max_occupancy}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 1rem;">
                            ${room.amenities ? room.amenities.map(amenity => `
                                <div style="color: #059669; font-size: 0.9rem; text-align: left; display: flex; align-items: center; gap: 0.3rem;">
                                    <i class="${getAmenityIcon(amenity)} icon"></i>
                                    ${amenity}
                                </div>
                            `).join('') : ''}
                        </div>
                    </div>
                </div>

                <div class="rate-option selected" onclick="selectRate(this)" data-rate-index="0">
                    <div class="rate-type">
                        <span>Tarif Standar</span>
                        <div style="text-align: right;">
                            <div class="rate-price">${formatCurrency(room.total_price)}</div>
                            ${nights > 1 ? `
                                <div style="font-size: 0.8rem; color: #6b7280; font-weight: normal;">
                                    ${formatCurrency(room.price_per_night)} x ${nights} malam
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="rate-features">
                        Tarif terbaik yang tersedia dengan fasilitas lengkap
                        <span class="breakfast-badge"><i class="fas fa-utensils icon"></i>Sarapan Termasuk</span>
                    </div>
                </div>

                <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 1rem; margin: 1rem 0; text-align: center;">
                    <div style="color: #0369a1; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-calendar-alt icon"></i> Durasi Menginap
                    </div>
                    <div style="color: #0c4a6e; font-size: 1.1rem; font-weight: 700;">
                        ${nights} Malam
                    </div>
                    <div style="color: #0369a1; font-size: 0.9rem; margin-top: 0.3rem;">
                        Harga sudah dikalikan sesuai durasi menginap
                    </div>
                </div>

                <button class="reserve-btn" onclick="makeReservation(${room.id})">
                    PESAN SEKARANG
                </button>
            `;

            document.getElementById('sidebar-content').innerHTML = content;
            document.getElementById('roomDetailsSidebar').classList.add('open');
            document.getElementById('overlay').classList.add('active');
        }

        // Close sidebar
        function closeSidebar() {
            document.getElementById('roomDetailsSidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('active');
        }

        // Select rate option
        function selectRate(element) {
            document.querySelectorAll('.rate-option').forEach(option => {
                option.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        // Add to cart
        function addToCart(roomId, rateIndex = 0) {
            const room = roomsData.find(r => r.id === roomId);
            if (!room) return;

            const nights = calculateNights();
            const rate = room.rates[rateIndex];
            const totalPrice = rate.price * nights;
            
            const cartItem = {
                roomId: roomId,
                rateIndex: rateIndex,
                roomName: room.name,
                rateType: rate.type,
                price: totalPrice, // Store total price for the selected nights
                basePrice: rate.price, // Store base price per night
                nights: nights, // Store number of nights
                quantity: 1, // Always 1 room per user
                breakfast: rate.breakfast
            };

            // Check if item already exists in cart
            const existingItemIndex = cart.findIndex(item => 
                item.roomId === roomId && item.rateIndex === rateIndex
            );

            if (existingItemIndex > -1) {
                // Don't increase quantity, just show message that room is already in cart
                alert('Kamar ini sudah ada di keranjang. Setiap user hanya bisa memesan 1 kamar per tipe.');
                return;
            } else {
                cart.push(cartItem);
            }

            updateCartDisplay();
            closeSidebar();
        }

        // Update cart display
        function updateCartDisplay() {
            const cartContent = document.getElementById('cart-content');
            const mobileCartContent = document.getElementById('mobile-cart-content');
            
            if (cart.length === 0) {
                const emptyCartHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-bag icon"></i>
                        </div>
                        <p>Belum ada kamar yang dipilih</p>
                        <p style="font-size: 0.8rem; margin-top: 0.5rem;">Pilih kamar idaman Anda di atas</p>
                    </div>
                `;
                cartContent.innerHTML = emptyCartHTML;
                mobileCartContent.innerHTML = emptyCartHTML;
                
                // Update mobile cart header
                document.getElementById('mobile-cart-badge').style.display = 'none';
                document.getElementById('mobile-cart-total').textContent = 'Rp 0';
                return;
            }

            let subtotal = 0;
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            const cartItemsHTML = cart.map((item, index) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                return `
                    <div class="cart-item">
                        <div class="cart-item-header">
                            <div class="cart-room-name">${item.roomName}</div>
                            <button class="cart-remove-btn" onclick="removeFromCart(${index})" title="Hapus item">
                                ✕
                            </button>
                        </div>
                        <div class="cart-room-rate">
                            ${item.rateType}
                            ${item.breakfast ? '<i class="fas fa-utensils icon"></i>' : ''}
                        </div>
                        ${item.nights > 1 ? `
                            <div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.5rem;">
                                <i class="fas fa-calendar-alt icon"></i> ${item.nights} malam (${formatCurrency(item.basePrice)} per malam)
                            </div>
                        ` : ''}
                        <div class="cart-quantity-controls">
                            <span class="quantity-display">1 Kamar</span>
                        </div>
                        <div class="cart-item-price">${formatCurrency(itemTotal)}</div>
                    </div>
                `;
            }).join('');

            const tax = Math.round(subtotal * 0.1); // 10% tax
            const total = subtotal + tax;

            const fullCartHTML = `
                <div class="cart-items">
                    ${cartItemsHTML}
                </div>
                <div class="cart-total">
                    <div class="cart-total-row cart-subtotal">
                        <span>Subtotal:</span>
                        <span>${formatCurrency(subtotal)}</span>
                    </div>
                    <div class="cart-total-row cart-tax">
                        <span>Pajak & Layanan:</span>
                        <span>${formatCurrency(tax)}</span>
                    </div>
                    <div class="cart-total-row cart-grand-total">
                        <span>Total:</span>
                        <span>${formatCurrency(total)}</span>
                    </div>
                </div>
                <button class="proceed-to-booking" onclick="proceedToBooking()">
                    LANJUTKAN PEMESANAN
                </button>
                <button class="proceed-to-booking" onclick="proceedToPayment()" style="background: #dc2626; margin-top: 0.5rem;">
                    BAYAR SEKARANG
                </button>
            `;

            cartContent.innerHTML = fullCartHTML;
            mobileCartContent.innerHTML = fullCartHTML;
            
            // Update mobile cart header
            const badge = document.getElementById('mobile-cart-badge');
            badge.textContent = totalItems;
            badge.style.display = 'flex';
            document.getElementById('mobile-cart-total').textContent = formatCurrency(total);
        }

        // Remove item from cart (since quantity is always 1)
        function removeFromCart(index) {
            if (index < 0 || index >= cart.length) return;
            cart.splice(index, 1);
            updateCartDisplay();
        }


        // Proceed to booking
        function proceedToBooking() {
            if (cart.length === 0) return;
            
            const totalRooms = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = Math.round(totalAmount * 0.1);
            const grandTotal = totalAmount + tax;
            
            alert(`🎉 Melanjutkan ke pemesanan!\n\nRingkasan:\n- ${totalRooms} kamar dipilih\n- Total: ${formatCurrency(grandTotal)}\n\nMengarahkan ke halaman pembayaran...`);
        }

        // Proceed to payment (for cart items)
        async function proceedToPayment() {
            if (cart.length === 0) {
                showNotification('Keranjang kosong. Pilih kamar terlebih dahulu.', 'error');
                return;
            }

            // Check if user is logged in
            if (!isUserLoggedIn) {
                showNotification('Silakan login terlebih dahulu untuk melakukan pemesanan', 'error');
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 2000);
                return;
            }

            try {
                showNotification('Memproses pemesanan...', 'info');

                // Create booking for the first item in cart (simplified for now)
                const firstItem = cart[0];
                const room = roomsData.find(r => r.id === firstItem.roomId);
                
                if (!room) {
                    throw new Error('Kamar tidak ditemukan');
                }

                const response = await fetch('{{ route("api.hotels.book") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        room_type_id: firstItem.roomId,
                        check_in: currentBookingData.check_in,
                        check_out: currentBookingData.check_out,
                        number_of_rooms: 1,
                        number_of_guests: currentBookingData.guests
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Clear cart after successful booking
                    cart = [];
                    updateCartDisplay();
                    
                    // Initiate payment immediately
                    initiateHotelPayment(data.booking.id);
                } else {
                    throw new Error(data.message || 'Gagal melakukan pemesanan');
                }
            } catch (error) {
                console.error('Cart payment error:', error);
                showNotification(error.message || 'Terjadi kesalahan saat memproses pemesanan', 'error');
            }
        }

        // Make reservation
        async function makeReservation(roomId) {
            const room = roomsData.find(r => r.id === roomId);
            if (!room) {
                showNotification('Kamar tidak ditemukan', 'error');
                return;
            }

            if (!room.is_available) {
                showNotification('Kamar tidak tersedia untuk tanggal yang dipilih', 'error');
                return;
            }

            // Check if user is logged in
            if (!isUserLoggedIn) {
                showNotification('Silakan login terlebih dahulu untuk melakukan pemesanan', 'error');
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 2000);
                return;
            }

            // Show loading
            const reserveBtn = document.querySelector('.reserve-btn');
            const originalText = reserveBtn.textContent;
            reserveBtn.disabled = true;
            reserveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMPROSES...';

            try {
                const response = await fetch('{{ route("api.hotels.book") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        room_type_id: roomId,
                        check_in: currentBookingData.check_in,
                        check_out: currentBookingData.check_out,
                        number_of_rooms: 1,
                        number_of_guests: currentBookingData.guests
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Pemesanan berhasil dibuat!', 'success');
                    closeSidebar();
                    
                    // Directly initiate payment without confirmation dialog
                    setTimeout(() => {
                        initiateHotelPayment(data.booking.id);
                    }, 500);
                    
                    // Refresh availability
                    updateAvailability();
                } else {
                    throw new Error(data.message || 'Gagal melakukan pemesanan');
                }
            } catch (error) {
                console.error('Booking error:', error);
                showNotification(error.message || 'Terjadi kesalahan saat melakukan pemesanan', 'error');
            } finally {
                reserveBtn.disabled = false;
                reserveBtn.textContent = originalText;
            }
        }

        // Initiate hotel payment
        async function initiateHotelPayment(bookingId) {
            try {
                showNotification('Memproses pembayaran...', 'info');
                
                const response = await fetch('{{ route("payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        booking_id: bookingId,
                        booking_type: 'hotel'
                    })
                });

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message || 'Gagal memproses pembayaran');
                }

                if (data.snap_token) {
                    // Open Midtrans Snap payment popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            showNotification('Pembayaran berhasil!', 'success');
                            setTimeout(() => {
                                window.location.href = `/payment/success/${bookingId}`;
                            }, 2000);
                        },
                        onPending: function(result) {
                            showNotification('Pembayaran sedang diproses...', 'info');
                            setTimeout(() => {
                                window.location.href = '/my-bookings';
                            }, 2000);
                        },
                        onError: function(result) {
                            showNotification('Pembayaran gagal. Silakan coba lagi.', 'error');
                            console.error('Payment error:', result);
                        },
                        onClose: function() {
                            showNotification('Pembayaran dibatalkan', 'info');
                        }
                    });
                } else {
                    throw new Error('Token pembayaran tidak ditemukan');
                }
            } catch (error) {
                console.error('Payment initiation error:', error);
                showNotification(error.message || 'Terjadi kesalahan saat memproses pembayaran', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? '#059669' : type === 'info' ? '#2563eb' : '#dc2626';
            
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            toast.innerHTML = `
                <div style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle icon"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            }, 10);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Update availability (real API call)
        async function updateAvailability() {
            const checkinDate = document.getElementById('checkin-date').value;
            const checkoutDate = document.getElementById('checkout-date').value;
            const guests = document.getElementById('guests-select').value;

            if (!checkinDate || !checkoutDate) {
                showNotification('Silakan pilih tanggal check-in dan check-out', 'error');
                return;
            }

            if (new Date(checkoutDate) <= new Date(checkinDate)) {
                showNotification('Tanggal check-out harus setelah tanggal check-in', 'error');
                return;
            }

            // Update current booking data
            currentBookingData = {
                check_in: checkinDate,
                check_out: checkoutDate,
                guests: parseInt(guests),
                nights: calculateNights()
            };

            // Update date display
            updateDateDisplay();

            // Show loading
            document.getElementById('rooms-container').innerHTML = `
                <div class="loading-message">
                    <i class="fas fa-spinner fa-spin icon"></i>
                    Mencari kamar yang tersedia untuk ${guests} orang...
                </div>
            `;

            try {
                const response = await fetch('{{ route("api.hotels.availability") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        check_in: checkinDate,
                        check_out: checkoutDate,
                        guests: guests
                    })
                });

                const data = await response.json();

                if (data.success) {
                    roomsData = data.room_types;
                    renderRooms(roomsData);
                    
                    if (roomsData.length === 0) {
                        showNotification('Tidak ada kamar tersedia untuk tanggal yang dipilih', 'info');
                    }
                } else {
                    throw new Error(data.message || 'Gagal memuat data kamar');
                }
            } catch (error) {
                console.error('Error fetching availability:', error);
                showNotification('Terjadi kesalahan saat memuat data kamar', 'error');
                document.getElementById('rooms-container').innerHTML = `
                    <div class="no-rooms-message">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">
                            <i class="fas fa-exclamation-triangle icon"></i>
                        </div>
                        <h3>Terjadi Kesalahan</h3>
                        <p>Gagal memuat data kamar. Silakan coba lagi.</p>
                        <button onclick="updateAvailability()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">Coba Lagi</button>
                    </div>
                `;
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updateDateDisplay();
            
            // Set minimum dates to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('checkin-date').min = today;
            document.getElementById('checkout-date').min = today;
            
            // Auto-update checkout date when checkin changes
            document.getElementById('checkin-date').addEventListener('change', function() {
                const checkinDate = new Date(this.value);
                const checkoutDate = new Date(checkinDate);
                checkoutDate.setDate(checkoutDate.getDate() + 1);
                
                document.getElementById('checkout-date').min = checkoutDate.toISOString().split('T')[0];
                if (new Date(document.getElementById('checkout-date').value) <= checkinDate) {
                    document.getElementById('checkout-date').value = checkoutDate.toISOString().split('T')[0];
                }
                updateDateDisplay();
                // Automatically update availability when dates change
                setTimeout(() => {
                    updateAvailability();
                }, 500);
            });

            document.getElementById('checkout-date').addEventListener('change', function() {
                updateDateDisplay();
                // Automatically update availability when dates change
                setTimeout(() => {
                    updateAvailability();
                }, 500);
            });

            // Also update when guest count changes
            document.getElementById('guests-count').addEventListener('change', function() {
                setTimeout(() => {
                    updateAvailability();
                }, 500);
            });
            
            // Load initial rooms
            setTimeout(() => {
                renderRooms(roomsData);
            }, 1000);
        });

        // Close sidebar with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>
</body>
</html>