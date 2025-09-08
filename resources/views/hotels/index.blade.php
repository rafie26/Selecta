<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Selecta - Booking Kamar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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
            margin-bottom: 0.5rem;
        }

        .booking-info {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .booking-info > span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .special-offers {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .offers-title {
            color: #047857;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .offer-item {
            color: #065f46;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            padding-left: 1rem;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
                gap: 0.5rem;
                align-items: flex-start;
            }

            .room-details-sidebar {
                width: 100%;
                right: -100%;
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
            <h1 class="hotel-title">Selecta Hotel Batu Malang</h1>
            <div class="hotel-rating">
                <div class="stars">★★★★</div>
                <div class="award-badge">Official Website - Best Rate Guarantee</div>
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
                        Guests
                    </label>
                    <select class="form-input" id="guests-select">
                        <option value="2">2 Adults</option>
                        <option value="1">1 Adult</option>
                        <option value="3">3 Adults</option>
                        <option value="4">4 Adults</option>
                        <option value="5">5+ Adults</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag icon"></i>
                        Promo Code
                    </label>
                    <input type="text" class="form-input" placeholder="Enter promo code">
                </div>
                <div class="form-group">
                    <button type="button" class="update-btn" onclick="updateAvailability()">CHECK AVAILABILITY</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Rooms Section -->
        <div class="rooms-section">
            <div class="section-header">
                <h2 class="section-title">Select the room that suits you best</h2>
                <div class="booking-info">
                    <span>
                        <i class="fas fa-home icon"></i>
                        ROOM 1 OF 1
                    </span>
                    <span>
                        <i class="fas fa-users icon"></i>
                        2 ADULTS
                    </span>
                    <span>
                        <i class="fas fa-calendar-alt icon"></i>
                        <span id="display-dates">MON 11 AUG 2025 ➔ TUE 12 AUG 2025</span>
                    </span>
                    <span>
                        <i class="fas fa-moon icon"></i>
                        <span id="display-nights">1 NIGHT</span>
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
            <!-- Special Offers -->
            <div class="special-offers">
                <h3 class="offers-title">
                    <i class="fas fa-star icon"></i>
                    Special Offers
                </h3>
                <div class="offer-item">
                    <i class="fas fa-gift icon"></i>
                    Book 3 nights, get 1 night free
                </div>
                <div class="offer-item">
                    <i class="fas fa-gift icon"></i>
                    Free spa treatment for couples
                </div>
                <div class="offer-item">
                    <i class="fas fa-gift icon"></i>
                    Complimentary airport transfer
                </div>
                <div class="offer-item">
                    <i class="fas fa-gift icon"></i>
                    Early check-in available
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="cart-title">
                    <i class="fas fa-shopping-cart icon"></i>
                    Your Selection
                </h3>
                <div id="cart-content">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-bag icon"></i>
                        </div>
                        <p>No rooms selected yet</p>
                        <p style="font-size: 0.8rem; margin-top: 0.5rem;">Choose your perfect room above</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details Sidebar -->
    <div class="room-details-sidebar" id="roomDetailsSidebar">
        <div class="sidebar-header">
            <h3 id="sidebar-room-name">Room Details</h3>
            <button class="close-btn" onclick="closeSidebar()">✕</button>
        </div>
        <div class="sidebar-content" id="sidebar-content">
            <!-- Content will be populated dynamically -->
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <script>
        // Updated room data for Selecta Hotel
        const roomsData = [
            {
                id: 1,
                name: "Superior",
                image: "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
                size: "24 m²",
                maxGuests: 2,
                description: "Superior room with modern amenities and comfortable furnishing. Perfect for couples seeking comfort and relaxation.",
                amenities: ["Air Conditioning", "Bathroom", "Free Toiletries", "Shower", "Towels", "Linen", "Free WiFi"],
                basePrice: 600000,
                availableRooms: 5,
                rates: [
                    {
                        type: "Best Available Rate",
                        price: 600000,
                        features: "Best Available Rate, Non-refundable booking with instant confirmation",
                        note: "Not refundable",
                        breakfast: true
                    }
                ]
            },
            {
                id: 2,
                name: "Suite",
                image: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
                size: "30 m²",
                maxGuests: 2,
                description: "Spacious suite with separate living area and premium amenities. Ideal for extended stays and business travelers.",
                amenities: ["Air Conditioning", "Bathroom", "Free Toiletries", "Shower", "Towels", "Linen", "Mini Bar", "Free WiFi"],
                basePrice: 700000,
                availableRooms: 3,
                rates: [
                    {
                        type: "Standard Rate",
                        price: 700000,
                        features: "Free cancellation, includes breakfast, room service available",
                        note: "",
                        breakfast: true
                    }
                ]
            },
            {
                id: 3,
                name: "Family Suite",
                image: "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
                size: "45 m²",
                maxGuests: 6,
                description: "Perfect for families with connecting rooms and kids-friendly amenities. Spacious layout with mountain views.",
                amenities: ["Air Conditioning", "Bathroom", "Free Toiletries", "Shower", "Towels", "Linen", "Coffee Maker", "Refrigerator", "Free WiFi"],
                basePrice: 1000000,
                availableRooms: 2,
                rates: [
                    {
                        type: "Family Package",
                        price: 1000000,
                        features: "Free breakfast for family, kids activities included, connecting rooms available",
                        note: "",
                        breakfast: true
                    }
                ]
            },
            {
                id: 4,
                name: "Executive Suite",
                image: "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
                size: "50 m²",
                maxGuests: 2,
                description: "Luxurious executive suite with premium facilities and exclusive services. Mountain view with private balcony.",
                amenities: ["Air Conditioning", "Bathroom", "Free Toiletries", "Shower", "Towels", "Linen", "Coffee Maker", "Mini Bar", "Balcony", "Free WiFi"],
                basePrice: 1600000,
                availableRooms: 1,
                rates: [
                    {
                        type: "Executive Rate",
                        price: 1600000,
                        features: "Executive lounge access, premium breakfast, late checkout, complimentary spa access",
                        note: "",
                        breakfast: true
                    }
                ]
            },
            {
                id: 5,
                name: "Deluxe",
                image: "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
                size: "24 m²",
                maxGuests: 2,
                description: "Comfortable deluxe room with garden view and modern amenities. Enhanced comfort with premium bedding.",
                amenities: ["Air Conditioning", "Bathroom", "Free Toiletries", "Shower", "Towels", "Linen", "Hair Dryer", "Free WiFi"],
                basePrice: 750000,
                availableRooms: 4,
                rates: [
                    {
                        type: "Standard Rate",
                        price: 750000,
                        features: "Garden view, free WiFi, complimentary breakfast, daily housekeeping",
                        note: "",
                        breakfast: true
                    }
                ]
            },
            {
                id: 6,
                name: "Suite Pool Side",
                image: "https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
                size: "30 m²",
                maxGuests: 2,
                description: "Suite with direct pool access and stunning pool views. Perfect for romantic getaways with easy pool access.",
                amenities: ["Air Conditioning", "Bathroom", "Free Toiletries", "Shower", "Towels", "Linen", "Pool Access", "Mini Bar", "Free WiFi"],
                basePrice: 800000,
                availableRooms: 3,
                rates: [
                    {
                        type: "Pool Side Rate",
                        price: 800000,
                        features: "Direct pool access, pool view, complimentary breakfast, priority pool service",
                        note: "",
                        breakfast: true
                    }
                ]
            }
        ];

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

        // Update display dates and nights
        function updateDateDisplay() {
            const checkinDate = new Date(document.getElementById('checkin-date').value);
            const checkoutDate = new Date(document.getElementById('checkout-date').value);
            
            const options = { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' };
            const checkinFormatted = checkinDate.toLocaleDateString('en-US', options).toUpperCase();
            const checkoutFormatted = checkoutDate.toLocaleDateString('en-US', options).toUpperCase();
            
            const timeDiff = checkoutDate - checkinDate;
            const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            
            document.getElementById('display-dates').textContent = `${checkinFormatted} ➔ ${checkoutFormatted}`;
            document.getElementById('display-nights').textContent = `${nights} NIGHT${nights > 1 ? 'S' : ''}`;
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
                'Air Conditioning': 'fas fa-snowflake',
                'Bathroom': 'fas fa-shower',
                'Free Toiletries': 'fas fa-soap',
                'Shower': 'fas fa-shower',
                'Towels': 'fas fa-bath',
                'Linen': 'fas fa-bed',
                'Coffee Maker': 'fas fa-coffee',
                'Mini Bar': 'fas fa-wine-glass',
                'Refrigerator': 'fas fa-cube',
                'Hair Dryer': 'fas fa-wind',
                'Balcony': 'fas fa-mountain',
                'Pool Access': 'fas fa-swimming-pool',
                'Free WiFi': 'fas fa-wifi'
            };
            return iconMap[amenity] || 'fas fa-check-circle';
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
                return `
                <div class="room-card">
                    <div class="room-main">
                        <div class="room-image" style="background-image: url('${room.image}')">
                            <div class="image-indicator">
                                <i class="fas fa-camera icon"></i>
                                1 / 1
                            </div>
                        </div>
                        
                        <div class="room-details">
                            <h3 class="room-name">${room.name}</h3>
                            <div class="room-specs">
                                <div class="room-spec">
                                    <i class="fas fa-expand-arrows-alt icon"></i>
                                    <span>${room.size}</span>
                                </div>
                                <div class="room-spec">
                                    <i class="fas fa-users icon"></i>
                                    <span>Max guests: ${room.maxGuests}</span>
                                </div>
                            </div>
                            <div class="room-description">
                                ${room.description}
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.3rem; margin-top: 0.5rem;">
                                ${room.amenities.slice(0, 4).map(amenity => `
                                    <div class="room-amenity">
                                        <i class="${getAmenityIcon(amenity)} icon"></i>
                                        <span>${amenity}</span>
                                    </div>
                                `).join('')}
                                ${room.amenities.length > 4 ? `
                                    <div class="room-amenity" style="color: #6b7280;">
                                        <i class="fas fa-plus icon"></i>
                                        <span>+${room.amenities.length - 4} more</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="room-pricing">
                            ${room.availableRooms && room.availableRooms <= 3 ? `
                                <div class="availability-badge ${room.availableRooms === 1 ? 'availability-warning' : ''}">
                                    ${room.availableRooms === 1 ? 'ONLY 1 LEFT' : `ONLY ${room.availableRooms} LEFT`}
                                </div>
                            ` : ''}
                            <div class="starting-from">Starting from</div>
                            <div class="price-display">
                                <div class="price-amount">${formatCurrency(room.basePrice)}</div>
                                <div class="price-period">for <span style="color: #1f2937; font-weight: 600;">1</span> night</div>
                            </div>
                            <button class="details-book-btn" onclick="openRoomDetails(${room.id})">
                                DETAILS & BOOK
                            </button>
                        </div>
                    </div>
                </div>
            `;
            }).join('');
        }

        // Open room details sidebar
        function openRoomDetails(roomId) {
            const room = roomsData.find(r => r.id === roomId);
            if (!room) return;

            document.getElementById('sidebar-room-name').textContent = room.name;
            
            const content = `
                <div style="text-align: center; margin-bottom: 2rem;">
                    <img src="${room.image}" alt="${room.name}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                    <div style="margin-top: 1rem;">
                        <h4 style="margin-bottom: 1rem; color: #1f2937;">${room.description}</h4>
                        <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                            <span><i class="fas fa-expand-arrows-alt icon"></i> ${room.size}</span>
                            <span><i class="fas fa-users icon"></i> Max guests: ${room.maxGuests}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; margin-top: 1rem;">
                            ${room.amenities.map(amenity => `
                                <div style="color: #059669; font-size: 0.9rem; text-align: left; display: flex; align-items: center; gap: 0.3rem;">
                                    <i class="${getAmenityIcon(amenity)} icon"></i>
                                    ${amenity}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>

                ${room.rates.map((rate, index) => {
                    return `
                    <div class="rate-option ${index === 0 ? 'selected' : ''}" onclick="selectRate(this)" data-rate-index="${index}">
                        <div class="rate-type">
                            <span>${rate.type}</span>
                            <span class="rate-price">${formatCurrency(rate.price)}</span>
                        </div>
                        <div class="rate-features">
                            ${rate.features}
                            ${rate.breakfast ? '<span class="breakfast-badge"><i class="fas fa-utensils icon"></i>Breakfast Included</span>' : ''}
                        </div>
                        ${rate.note ? `<div class="rate-note">${rate.note}</div>` : ''}
                    </div>
                `;
                }).join('')}

                <button class="reserve-btn" onclick="makeReservation(${room.id})">
                    ADD TO CART
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

            const rate = room.rates[rateIndex];
            const cartItem = {
                roomId: roomId,
                rateIndex: rateIndex,
                roomName: room.name,
                rateType: rate.type,
                price: rate.price,
                quantity: 1,
                breakfast: rate.breakfast
            };

            // Check if item already exists in cart
            const existingItemIndex = cart.findIndex(item => 
                item.roomId === roomId && item.rateIndex === rateIndex
            );

            if (existingItemIndex > -1) {
                cart[existingItemIndex].quantity += 1;
            } else {
                cart.push(cartItem);
            }

            updateCartDisplay();
            closeSidebar();
        }

        // Update cart display
        function updateCartDisplay() {
            const cartContent = document.getElementById('cart-content');
            
            if (cart.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-bag icon"></i>
                        </div>
                        <p>No rooms selected yet</p>
                        <p style="font-size: 0.8rem; margin-top: 0.5rem;">Choose your perfect room above</p>
                    </div>
                `;
                return;
            }

            let subtotal = 0;
            const cartItemsHTML = cart.map((item, index) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                return `
                    <div class="cart-item">
                        <div class="cart-item-header">
                            <div class="cart-room-name">${item.roomName}</div>
                            <button class="cart-remove-btn" onclick="removeFromCart(${index})" title="Remove item">
                                ✕
                            </button>
                        </div>
                        <div class="cart-room-rate">
                            ${item.rateType}
                            ${item.breakfast ? '<i class="fas fa-utensils icon"></i>' : ''}
                        </div>
                        <div class="cart-quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity(${index}, -1)" ${item.quantity <= 1 ? 'disabled' : ''}>
                                −
                            </button>
                            <span class="quantity-display">${item.quantity}</span>
                            <button class="quantity-btn" onclick="updateQuantity(${index}, 1)" ${item.quantity >= 5 ? 'disabled' : ''}>
                                +
                            </button>
                        </div>
                        <div class="cart-item-price">${formatCurrency(itemTotal)}</div>
                    </div>
                `;
            }).join('');

            const tax = Math.round(subtotal * 0.1); // 10% tax
            const total = subtotal + tax;

            cartContent.innerHTML = `
                <div class="cart-items">
                    ${cartItemsHTML}
                </div>
                <div class="cart-total">
                    <div class="cart-total-row cart-subtotal">
                        <span>Subtotal:</span>
                        <span>${formatCurrency(subtotal)}</span>
                    </div>
                    <div class="cart-total-row cart-tax">
                        <span>Tax & Service:</span>
                        <span>${formatCurrency(tax)}</span>
                    </div>
                    <div class="cart-total-row cart-grand-total">
                        <span>Total:</span>
                        <span>${formatCurrency(total)}</span>
                    </div>
                </div>
                <button class="proceed-to-booking" onclick="proceedToBooking()">
                    PROCEED TO BOOKING
                </button>
            `;
        }

        // Update quantity
        function updateQuantity(index, change) {
            if (index < 0 || index >= cart.length) return;
            
            cart[index].quantity += change;
            
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            } else if (cart[index].quantity > 5) {
                cart[index].quantity = 5;
            }
            
            updateCartDisplay();
        }

        // Remove from cart
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
            
            alert(`🎉 Proceeding to booking!\n\nSummary:\n- ${totalRooms} room(s) selected\n- Total: ${formatCurrency(grandTotal)}\n\nRedirecting to payment page...`);
        }

        // Make reservation
        function makeReservation(roomId) {
            const room = roomsData.find(r => r.id === roomId);
            const selectedRateElement = document.querySelector('.rate-option.selected');
            
            if (room && selectedRateElement) {
                const rateIndex = parseInt(selectedRateElement.dataset.rateIndex) || 0;
                addToCart(roomId, rateIndex);
                
                // Show success message
                const rate = room.rates[rateIndex];
                showNotification(`Added to cart! ${room.name} - ${rate.type}`, 'success');
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

        // Update availability (simulate API call)
        function updateAvailability() {
            const checkinDate = document.getElementById('checkin-date').value;
            const checkoutDate = document.getElementById('checkout-date').value;
            const guests = document.getElementById('guests-select').value;

            if (!checkinDate || !checkoutDate) {
                alert('Silakan pilih tanggal check-in dan check-out');
                return;
            }

            if (new Date(checkoutDate) <= new Date(checkinDate)) {
                alert('Tanggal check-out harus setelah tanggal check-in');
                return;
            }

            // Update date display
            updateDateDisplay();

            // Show loading
            document.getElementById('rooms-container').innerHTML = `
                <div class="loading-message">
                    <i class="fas fa-spinner fa-spin icon"></i>
                    Mencari kamar yang tersedia untuk ${guests} orang...
                </div>
            `;

            // Simulate API delay
            setTimeout(() => {
                // Filter rooms based on guest capacity
                const availableRooms = roomsData.filter(room => room.maxGuests >= parseInt(guests));
                renderRooms(availableRooms);
            }, 1500);
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
            });

            document.getElementById('checkout-date').addEventListener('change', updateDateDisplay);
            
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