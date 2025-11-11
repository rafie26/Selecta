<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hotel Selecta Batu Malang - Pemesanan Kamar Online</title>
    <meta name="description" content="Pesan kamar di Hotel Selecta Batu Malang dengan harga terbaik. Booking hotel online mudah dan aman dengan jaminan harga terbaik.">
    <meta name="keywords" content="hotel selecta, batu malang, booking hotel, hotel murah malang, resort selecta">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Nunito+Sans:wght@400;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #16a34a;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast.error {
            background: #dc2626;
        }

        .loading {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        
/* Replace the existing .hero-section rule with this */
.hero-section {
    position: relative;
    height: 60vh;
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), 
                url('/images/hotel4.png');
    background-size: cover;
    background-position: center 85%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: white;
}

/* Fallback if the main image fails */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), 
                url('/images/hotel4.jpg'), /* Try .jpg as fallback */
                url('./images/hotel4.png'), /* Relative path fallback */
                #26265A; /* Color fallback if no image loads */
    background-size: cover;
    background-position: center;
    z-index: -1;
}

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: #f5f5f5;
            border-radius: 30px 30px 0 0;
        }

        .hero-header {
            padding: 7rem;
            text-align: center;
            z-index: 2;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .hero-stars {
            color: #fbbf24;
            font-size: 1.2rem;
        }

        .hero-badge {
            background: #26265A;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .hero-location {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .search-form {
            background: white;
            margin: -5rem 2rem 2rem;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 2;
            position: relative;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #666;
            margin-bottom: 0.3rem;
        }

        .form-input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #26265A;
            box-shadow: 0 0 0 2px rgba(38, 38, 90, 0.1);
        }

        .search-btn {
            background: #26265A;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.2s;
            min-height: 48px;
        }

        .search-btn:hover {
            background: #141430ff;
        }

        .search-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .guest-selector {
            position: relative;
        }

        .guest-summary {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.2s;
            color: #000000ff !important;
        }

        .guest-summary:hover {
            border-color: #26265A;
        }

        .guest-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white !important;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 100;
            padding: 1.5rem;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .guest-dropdown * {
            color: #000000ff !important;
        }

        .guest-dropdown.active {
            display: block;
        }

        .guest-control {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .guest-info {
            display: flex;
            flex-direction: column;
        }

        .guest-type-label {
            font-weight: 600 !important;
            color: #333 !important;
            font-size: 1rem !important;
            display: block !important;
        }

        .guest-desc-label {
            color: #666 !important;
            font-size: 0.8rem !important;
            display: block !important;
            margin-top: 0.3rem !important;
        }

        .counter-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .counter-btn {
            width: 36px;
            height: 36px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            color: #333 !important;
            transition: all 0.2s;
        }

        .counter-btn:hover:not(:disabled) {
            background: #f5f5f5;
            border-color: #26265A;
        }

        .counter-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .guest-count-display {
            font-weight: 600 !important;
            min-width: 40px !important;
            text-align: center !important;
            color: #333 !important;
            font-size: 1.1rem !important;
            display: inline-block !important;
        }

        .children-ages {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .age-selector {
            margin-bottom: 0.8rem;
        }

        .age-selector label {
            font-size: 0.85rem !important;
            color: #666 !important;
            display: block !important;
            margin-bottom: 0.5rem !important;
            font-weight: 500 !important;
        }

        .age-selector select {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #333 !important;
        }

        .done-btn {
            width: 100%;
            background: #26265A;
            color: white !important;
            border: none;
            padding: 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
            transition: background 0.2s;
        }

        .done-btn:hover {
            background: #141430ff;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 2rem;
        }

        .rooms-section {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .booking-summary {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.85rem;
            color: #666;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .room-card {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .room-card:last-child {
            border-bottom: none;
        }

        .room-content {
            display: flex;
            gap: 1.5rem;
        }

        .room-image {
            width: 200px;
            height: 140px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .room-info {
            flex: 1;
        }

        .room-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #26265A;
            margin-bottom: 0.5rem;
        }

        .room-details {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .room-capacity {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.4rem 0.8rem;
            border-radius: 16px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 0.8rem;
        }

        .room-description {
            font-size: 0.9rem;
            color: #555;
            line-height: 1.4;
            margin-bottom: 1rem;
        }

        .room-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            font-size: 0.8rem;
            color: #16a34a;
        }

        .amenity {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .room-pricing {
            text-align: right;
            min-width: 200px;
        }

        .availability-alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .price-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.3rem;
        }

        .price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.3rem;
        }

        .price-period {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .select-btn {
            background: #26265A;
            color: white;
            border: none;
            padding: 0.7rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.2s;
        }

        .select-btn:hover {
            background: #141430ff;
        }

        .sidebar {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .empty-selection {
            text-align: center;
            color: #666;
            padding: 2rem;
        }

        .empty-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        .selection-item {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .selection-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .selection-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .remove-btn:hover {
            transform: scale(1.1);
        }

        .selection-rate {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .qty-btn {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .qty-btn:hover:not(:disabled) {
            background: #e5e7eb;
        }

        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .qty-display {
            font-weight: 600;
            min-width: 15px;
            text-align: center;
            font-size: 0.85rem;
        }

        .selection-price {
            font-weight: 600;
            text-align: right;
            font-size: 0.9rem;
        }

        .total-section {
            border-top: 2px solid #e9ecef;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .grand-total {
            font-weight: 700;
            font-size: 1rem;
            border-top: 1px solid #e9ecef;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }

        .proceed-btn {
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            transition: background 0.2s;
        }

        .proceed-btn:hover {
            background: #15803d;
        }

        .proceed-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        .guest-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 1000;
        }

        .guest-modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .guest-modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .guest-modal-title {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .guest-modal-body {
            padding: 1.5rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #26265A;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        .form-field label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .form-field input,
        .form-field select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .form-field input:focus,
        .form-field select:focus {
            outline: none;
            border-color: #26265A;
            box-shadow: 0 0 0 2px rgba(38, 38, 90, 0.1);
        }


        .confirm-booking-btn {
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            transition: background 0.2s;
        }

        .confirm-booking-btn:hover {
            background: #15803d;
        }

        .mobile-cart-float {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 1rem;
            z-index: 100;
            display: none;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }

        .mobile-cart-button {
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .mobile-cart-button:hover {
            background: #15803d;
        }

        .mobile-cart-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .mobile-cart-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 200;
            display: none;
        }

        .mobile-cart-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-radius: 16px 16px 0 0;
            max-height: 80vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease-out;
        }
        .modal-image-container {
    position: relative;
    margin-bottom: 1rem;
}

.image-slider {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px;
}

.image-slides {
    display: flex;
    width: 500%; /* 5 images x 100% */
    height: 100%;
    transition: transform 0.3s ease;
}

.slide {
    width: 20%; /* 100% / 5 images */
    height: 100%;
    flex-shrink: 0;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: background 0.2s;
}

.slider-nav:hover {
    background: rgba(0, 0, 0, 0.7);
}

.slider-prev {
    left: 10px;
}

.slider-next {
    right: 10px;
}

.slider-indicators {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}

.slider-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: background 0.2s;
}

.slider-dot.active {
    background: white;
}

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .mobile-cart-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
        }

        .mobile-cart-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .mobile-close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .mobile-cart-body {
            padding: 1rem;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 1000;
        }

        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            z-index: 1001;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .modal-content {
            padding: 1.5rem;
        }

        .modal-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .rate-option {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .rate-option:hover, .rate-option.selected {
            border-color: #26265A;
            background: #f0f9ff;
        }

        .rate-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .rate-name {
            font-weight: 600;
        }

        .rate-price {
            font-weight: 700;
            color: #26265A;
            font-size: 1.1rem;
        }

        .rate-features {
            font-size: 0.85rem;
            color: #666;
        }

        .breakfast-included {
            background: #16a34a;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .add-to-cart-btn {
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            transition: background 0.2s;
        }

        .add-to-cart-btn:hover {
            background: #15803d;
        }

        @media (min-width: 769px) {
            .mobile-cart-float,
            .mobile-cart-modal {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                height: auto;
                min-height: 500px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .search-form {
                grid-template-columns: 1fr;
                margin: 0 1rem 1rem;
            }

            .container {
                grid-template-columns: 1fr;
                padding: 1rem 1rem 6rem;
                margin-bottom: 80px;
            }

            .room-content {
                flex-direction: column;
            }

            .room-image {
                width: 100%;
                height: 200px;
            }

            .room-pricing {
                text-align: left;
            }

            .sidebar {
                display: none;
            }

            .mobile-cart-float.show {
                display: block;
            }

            .booking-summary {
                flex-direction: column;
                gap: 0.5rem;
            }

            .modal {
                width: 95%;
                margin: 1rem;
                max-height: 85vh;
            }

            .guest-modal-content {
                width: 95%;
                max-height: 90vh;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .toast {
                bottom: 100px;
                right: 10px;
                left: 10px;
            }
        }
    </style>
</head>
<body>
        @include('components.navbar')
    <section class="hero-section">
        <div class="hero-header">
            <h1 class="hero-title">Hotel Selecta Batu Malang</h1>
            <div class="hero-rating">
                <div class="hero-stars">★★★★</div>
                <div class="hero-badge">Website Resmi - Jaminan Harga Terbaik</div>
            </div>
            <div class="hero-location">
                <i class="fas fa-map-marker-alt"></i>
                Jl. Raya Selecta No. 1, Batu, Malang
            </div>
        </div>

        <form class="search-form">
            <div class="form-group">
                <label class="form-label" for="checkin">Check-in</label>
                <input type="date" class="form-input" id="checkin" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="checkout">Check-out</label>
                <input type="date" class="form-input" id="checkout" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tamu</label>
                <div class="guest-selector" id="guest-selector">
                    <div class="guest-summary" onclick="toggleGuestDropdown()" tabindex="0" role="button" aria-label="Pilih jumlah tamu per kamar">
                        <span id="guest-display">2 dewasa</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="guest-dropdown" id="guest-dropdown">
                        <div class="guest-control">
                            <div class="guest-info">
                                <span class="guest-type-label">Dewasa</span>
                                <small class="guest-desc-label">Usia 17 tahun ke atas</small>
                            </div>
                            <div class="counter-controls">
                                <button type="button" class="counter-btn" onclick="updateGuestCount('adults', -1)" aria-label="Kurangi dewasa">−</button>
                                <span class="guest-count-display" id="adults-count">2</span>
                                <button type="button" class="counter-btn" onclick="updateGuestCount('adults', 1)" aria-label="Tambah dewasa">+</button>
                            </div>
                        </div>
                        <div class="guest-control">
                            <div class="guest-info">
                                <span class="guest-type-label">Anak</span>
                                <small class="guest-desc-label">Usia 0 hingga 16 tahun</small>
                            </div>
                            <div class="counter-controls">
                                <button type="button" class="counter-btn" onclick="updateGuestCount('children', -1)" aria-label="Kurangi anak">−</button>
                                <span class="guest-count-display" id="children-count">0</span>
                                <button type="button" class="counter-btn" onclick="updateGuestCount('children', 1)" aria-label="Tambah anak">+</button>
                            </div>
                        </div>
                        <div class="children-ages" id="children-ages" style="display: none;"></div>
                        <button type="button" class="done-btn" onclick="finishGuestSelection()">Selesai</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="search-btn" onclick="searchRooms()">
                    <span class="search-text">Cari Kamar</span>
                </button>
            </div>
        </form>
    </section>

    <div class="container">
        <div class="rooms-section">
            <div class="section-header">
                <h2 class="section-title">Pilih kamar yang sesuai untuk Anda</h2>
                <div class="booking-summary">
                    <div class="summary-item">
                        <i class="fas fa-users"></i>
                        <span id="guest-summary">2 DEWASA per kamar</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span id="date-range">Pilih tanggal check-in dan check-out</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-moon"></i>
                        <span id="nights">0 MALAM</span>
                    </div>
                </div>
            </div>

            <div id="rooms-list">
                <div style="padding: 2rem; text-align: center; color: #666;">
                    <i class="fas fa-search" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <h3>Mulai pencarian Anda</h3>
                    <p>Pilih tanggal check-in, check-out, dan jumlah tamu untuk melihat kamar yang tersedia</p>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <h3 class="sidebar-title">
                <i class="fas fa-shopping-cart"></i>
                Pilihan Anda
            </h3>
            <div id="cart-content">
                <div class="empty-selection">
                    <div class="empty-icon">🛏️</div>
                    <p>Belum ada kamar yang dipilih</p>
                    <small>Pilih kamar di sebelah kiri</small>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-cart-float" id="mobile-cart-float">
        <button class="mobile-cart-button" onclick="showMobileCart()">
            <span>Lihat Pilihan</span>
            <span class="mobile-cart-count" id="mobile-cart-count">0 kamar</span>
        </button>
    </div>

    <div class="mobile-cart-modal" id="mobile-cart-modal" onclick="hideMobileCart()">
        <div class="mobile-cart-content" onclick="event.stopPropagation()">
            <div class="mobile-cart-header">
                <h3 class="mobile-cart-title">Pilihan Anda</h3>
                <button class="mobile-close-btn" onclick="hideMobileCart()" aria-label="Tutup">&times;</button>
            </div>
            <div class="mobile-cart-body" id="mobile-cart-body"></div>
        </div>
    </div>

    <div class="modal-overlay" id="modal-overlay" onclick="closeModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title">Detail Kamar</h3>
                <button class="close-btn" onclick="closeModal()" aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-content" id="modal-content"></div>
        </div>
    </div>

    <div class="guest-modal" id="guest-modal" onclick="closeGuestModal(event)">
        <div class="guest-modal-content" onclick="event.stopPropagation()">
            <div class="guest-modal-header">
                <h3 class="guest-modal-title">Lengkapi Data Pemesanan</h3>
                <button class="close-btn" onclick="closeGuestModal()" aria-label="Tutup">&times;</button>
            </div>
            <div class="guest-modal-body">
                <form id="booking-form">
                    <div class="form-section">
                        <h3>Data Tamu Utama</h3>
                        <div class="form-row">
                            <div class="form-field">
                                <label for="firstName">Nama Depan *</label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-field">
                                <label for="lastName">Nama Belakang *</label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-field">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-field">
                                <label for="phone">Nomor Telepon *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>
                    </div>


                    <div class="form-section" id="booking-summary-modal"></div>

                    <button type="submit" class="confirm-booking-btn">
                        Konfirmasi Pemesanan
                    </button>
                </form>
            </div>
        </div>
    </div>

    @php
        $hotelConfig = [
            'isAuthenticated' => Auth::check(),
            'loginUrl' => route('login'),
            'apiEndpoints' => [
                'getRooms' => route('hotels.rooms'),
                'bookRoom' => route('hotels.book'),
                'payBooking' => route('payment.pay')
            ],
            'midtrans' => [
                'clientKey' => config('midtrans.client_key'),
                'snapUrl' => config('midtrans.snap_url')
            ],
            'hotelPhotos' => $hotelPhotos
        ];
    @endphp

    <!-- Server Configuration (Hidden from JS Linter) -->
    <div id="hotel-config" style="display: none;" data-config="{{ base64_encode(json_encode($hotelConfig)) }}"></div>
    <script type="text/javascript">
        const configElement = document.getElementById('hotel-config');
        window.hotelConfig = JSON.parse(atob(configElement.dataset.config));
    </script>

    <script>
        // Load rooms from backend
        let rooms = [];
        
        // Use config from window.hotelConfig (set in previous script tag)
        const isAuthenticated = window.hotelConfig.isAuthenticated;
        const loginUrl = window.hotelConfig.loginUrl;
        const apiEndpoints = window.hotelConfig.apiEndpoints;
        const midtransConfig = window.hotelConfig.midtrans;
        const hotelPhotos = window.hotelConfig.hotelPhotos;
        
        // Update hero background with featured photo from database
        function updateHeroBackground() {
            if (hotelPhotos && hotelPhotos.featured && hotelPhotos.featured.length > 0) {
                const heroSection = document.querySelector('.hero-section');
                const featuredPhoto = hotelPhotos.featured[0].image_url;
                heroSection.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('${featuredPhoto}')`;
            }
        }

        // Load rooms from backend
        async function loadRooms() {
            try {
                const response = await fetch(apiEndpoints.getRooms);
                const data = await response.json();
                rooms = data;
                console.log('Rooms loaded:', rooms);
            } catch (error) {
                console.error('Error loading rooms:', error);
                showToast('Gagal memuat data kamar', 'error');
            }
        }

        // Update booking form with Midtrans integration
        async function makeReservation() {
            if (!isAuthenticated) {
                showToast('Silakan login terlebih dahulu', 'error');
                setTimeout(() => {
                    window.location.href = loginUrl;
                }, 1500);
                return;
            }

            if (cart.length === 0) {
                showToast('Pilih kamar terlebih dahulu', 'error');
                return;
            }

            // Show guest modal for booking details
            document.getElementById('guest-modal').style.display = 'block';
        }

        // Handle booking form submission with Midtrans
        document.getElementById('booking-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const bookingData = {
                rooms: cart,
                dates: {
                    checkin: document.getElementById('checkin').value,
                    checkout: document.getElementById('checkout').value,
                    nights: nights
                },
                guest: {
                    firstName: formData.get('firstName'),
                    lastName: formData.get('lastName'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                }
            };
            
            const confirmBtn = document.querySelector('.confirm-booking-btn');
            const originalContent = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<div class="loading"><div class="loading-spinner"></div> Memproses...</div>';
            confirmBtn.disabled = true;
            
            try {
                const response = await fetch(apiEndpoints.bookRoom, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(bookingData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Pemesanan berhasil dibuat!');
                    closeGuestModal();
                    
                    // Initiate payment with Midtrans
                    setTimeout(() => {
                        initiatePayment(result.booking.id);
                    }, 1000);
                    
                    // Clear cart
                    cart = [];
                    updateCart();
                    updateMobileCart();
                } else {
                    showToast(result.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Booking error:', error);
                showToast('Terjadi kesalahan saat memproses pemesanan', 'error');
            } finally {
                confirmBtn.innerHTML = originalContent;
                confirmBtn.disabled = false;
            }
        });

        // Initiate Midtrans payment
        async function initiatePayment(bookingId) {
            try {
                const response = await fetch(apiEndpoints.payBooking, {
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
                
                const result = await response.json();
                
                if (result.snap_token) {
                    // Load Midtrans Snap script if not already loaded
                    if (!window.snap) {
                        const script = document.createElement('script');
                        script.src = midtransConfig.snapUrl;
                        script.setAttribute('data-client-key', midtransConfig.clientKey);
                        document.head.appendChild(script);
                        
                        script.onload = () => {
                            window.snap.pay(result.snap_token, {
                                onSuccess: function(result) {
                                    showToast('Pembayaran berhasil!');
                                    setTimeout(() => {
                                        window.location.href = '/payment/success/' + bookingId;
                                    }, 2000);
                                },
                                onPending: function(result) {
                                    showToast('Pembayaran pending, silakan selesaikan pembayaran');
                                },
                                onError: function(result) {
                                    showToast('Pembayaran gagal', 'error');
                                },
                                onClose: function() {
                                    showToast('Pembayaran dibatalkan', 'error');
                                }
                            });
                        };
                    } else {
                        window.snap.pay(result.snap_token, {
                            onSuccess: function(result) {
                                showToast('Pembayaran berhasil!');
                                setTimeout(() => {
                                    window.location.href = '/payment/success/' + bookingId;
                                }, 2000);
                            },
                            onPending: function(result) {
                                showToast('Pembayaran pending, silakan selesaikan pembayaran');
                            },
                            onError: function(result) {
                                showToast('Pembayaran gagal', 'error');
                            },
                            onClose: function() {
                                showToast('Pembayaran dibatalkan', 'error');
                            }
                        });
                    }
                } else {
                    showToast(result.message || 'Gagal memproses pembayaran', 'error');
                }
            } catch (error) {
                console.error('Payment error:', error);
                showToast('Terjadi kesalahan saat memproses pembayaran', 'error');
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadRooms();
            initializeDates();
            updateGuestDisplay();
            renderChildrenAges();
            updateMobileCart();
            
            document.getElementById('checkin').addEventListener('change', handleDateChange);
            document.getElementById('checkout').addEventListener('change', handleDateChange);
            
            document.addEventListener('click', function(event) {
                const guestSelector = document.getElementById('guest-selector');
                const dropdown = document.getElementById('guest-dropdown');
                
                if (!guestSelector.contains(event.target)) {
                    dropdown.classList.remove('active');
                }
            });
            
            document.querySelector('.guest-summary').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleGuestDropdown();
                }
            });
        });

        // CSRF token already added in head

        // Utility functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount).replace('IDR', 'Rp');
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }

        // Global variables
        let cart = [];
        let guestData = {
            adults: 2,
            children: 0,
            childrenAges: []
        };
        let searchPerformed = false;
        let nights = 0;
        let currentSlideIndex = 0;

function changeSlide(roomId, direction) {
    const room = rooms.find(r => r.id === roomId);
    const totalSlides = room.images.length;
    
    currentSlideIndex += direction;
    
    if (currentSlideIndex >= totalSlides) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = totalSlides - 1;
    }
    
    updateSliderPosition(roomId);
}

function goToSlide(roomId, index) {
    currentSlideIndex = index;
    updateSliderPosition(roomId);
}

function updateSliderPosition(roomId) {
    const slides = document.getElementById(`image-slides-${roomId}`);
    const dots = document.querySelectorAll('.slider-dot');
    
    if (slides) {
        const translateX = -currentSlideIndex * 20; // 20% per slide
        slides.style.transform = `translateX(${translateX}%)`;
        
        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlideIndex);
        });
    }
}

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount).replace('IDR', 'Rp');
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }

        function showLoading(element, text = 'Memuat...') {
            const originalContent = element.innerHTML;
            element.innerHTML = `<div class="loading"><div class="loading-spinner"></div> ${text}</div>`;
            element.disabled = true;
            return originalContent;
        }

        function hideLoading(element, originalContent) {
            element.innerHTML = originalContent;
            element.disabled = false;
        }

        function initializeDates() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            const todayStr = today.toISOString().split('T')[0];
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            document.getElementById('checkin').value = todayStr;
            document.getElementById('checkin').min = todayStr;
            document.getElementById('checkout').value = tomorrowStr;
            document.getElementById('checkout').min = tomorrowStr;
            
            updateDateDisplay();
        }

        function updateDateDisplay() {
            const checkin = new Date(document.getElementById('checkin').value);
            const checkout = new Date(document.getElementById('checkout').value);
            
            if (!checkin || !checkout) return;
            
            const dayNames = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
            const monthNames = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES'];
            
            const checkinDay = dayNames[checkin.getDay()];
            const checkinMonth = monthNames[checkin.getMonth()];
            const checkoutDay = dayNames[checkout.getDay()];
            const checkoutMonth = monthNames[checkout.getMonth()];
            
            const checkinFormatted = `${checkinDay} ${checkin.getDate()} ${checkinMonth} ${checkin.getFullYear()}`;
            const checkoutFormatted = `${checkoutDay} ${checkout.getDate()} ${checkoutMonth} ${checkout.getFullYear()}`;
            
            const timeDiff = checkout - checkin;
            nights = Math.max(1, Math.ceil(timeDiff / (1000 * 60 * 60 * 24)));
            
            document.getElementById('date-range').textContent = `${checkinFormatted} ➔ ${checkoutFormatted}`;
            document.getElementById('nights').textContent = `${nights} MALAM`;
        }

        function updateGuestCount(type, change) {
            if (type === 'adults') {
                const newCount = Math.max(1, guestData.adults + change);
                if (newCount > 6) {
                    showToast('Maksimal 6 dewasa per kamar', 'error');
                    return;
                }
                guestData.adults = newCount;
            } else if (type === 'children') {
                const newCount = Math.max(0, guestData.children + change);
                if (newCount > 4) {
                    showToast('Maksimal 4 anak per kamar', 'error');
                    return;
                }
                if (newCount > guestData.children) {
                    guestData.childrenAges.push(5);
                } else if (newCount < guestData.children) {
                    guestData.childrenAges.pop();
                }
                guestData.children = newCount;
            }
            
            updateGuestDisplay();
            renderChildrenAges();
            
            if (searchPerformed) {
                renderRooms();
            }
        }

        function updateChildAge(childIndex, age) {
            guestData.childrenAges[childIndex] = parseInt(age);
        }

        function renderChildrenAges() {
            const container = document.getElementById('children-ages');
            
            if (guestData.children === 0) {
                container.style.display = 'none';
                return;
            }
            
            container.style.display = 'block';
            container.innerHTML = guestData.childrenAges.map((age, index) => `
                <div class="age-selector">
                    <label>Usia anak ${index + 1}</label>
                    <select onchange="updateChildAge(${index}, this.value)">
                        ${Array.from({length: 17}, (_, i) => `
                            <option value="${i}" ${i === age ? 'selected' : ''}>${i} tahun</option>
                        `).join('')}
                    </select>
                </div>
            `).join('');
        }

        function updateGuestDisplay() {
            document.getElementById('adults-count').textContent = guestData.adults;
            document.getElementById('children-count').textContent = guestData.children;
            
            const adultsMinusBtn = document.querySelector('.counter-controls .counter-btn[onclick="updateGuestCount(\'adults\', -1)"]');
            const childrenMinusBtn = document.querySelector('.counter-controls .counter-btn[onclick="updateGuestCount(\'children\', -1)"]');
            
            adultsMinusBtn.disabled = guestData.adults <= 1;
            childrenMinusBtn.disabled = guestData.children <= 0;
            
            let displayText = `${guestData.adults} dewasa`;
            if (guestData.children > 0) {
                displayText += `, ${guestData.children} anak`;
            }
            
            document.getElementById('guest-display').textContent = displayText;
            
            const adultText = guestData.adults > 1 ? 'DEWASA' : 'DEWASA';
            const childText = guestData.children > 0 ? `, ${guestData.children} ANAK` : '';
            document.getElementById('guest-summary').textContent = `${guestData.adults} ${adultText}${childText} per kamar`;
        }

        function toggleGuestDropdown() {
            const dropdown = document.getElementById('guest-dropdown');
            dropdown.classList.toggle('active');
        }

        function finishGuestSelection() {
            document.getElementById('guest-dropdown').classList.remove('active');
            if (searchPerformed) {
                renderRooms();
            }
        }

        function canAccommodateGuests(room) {
            const totalGuests = guestData.adults + guestData.children;
            return totalGuests <= room.maxGuests && 
                   guestData.adults <= room.maxAdults && 
                   guestData.children <= room.maxChildren;
        }

        function renderRooms() {
            const roomsList = document.getElementById('rooms-list');
            const searchBtn = document.querySelector('.search-btn');
            
            if (!searchPerformed) {
                roomsList.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: #666;">
                        <i class="fas fa-search" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                        <h3>Mulai pencarian Anda</h3>
                        <p>Pilih tanggal check-in, check-out, dan jumlah tamu untuk melihat kamar yang tersedia</p>
                    </div>
                `;
                return;
            }

            const originalContent = showLoading(searchBtn, 'Mencari...');
            
            setTimeout(() => {
                const availableRooms = rooms.filter(canAccommodateGuests);
                
                if (availableRooms.length === 0) {
                    roomsList.innerHTML = `
                        <div style="padding: 2rem; text-align: center; color: #666;">
                            <i class="fas fa-bed" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                            <h3>Maaf, tidak ada kamar yang tersedia</h3>
                            <p>Untuk konfigurasi tamu yang dipilih (${guestData.adults} dewasa${guestData.children > 0 ? ', ' + guestData.children + ' anak' : ''}). Silakan ubah jumlah tamu atau coba tanggal lain.</p>
                        </div>
                    `;
                } else {
                    roomsList.innerHTML = availableRooms.map(room => `
                        <div class="room-card">
                            <div class="room-content">
                                <img src="${room.image}" alt="${room.name}" class="room-image">
                                <div class="room-info">
                                    <h3 class="room-name">${room.name}</h3>
                                    <div class="room-details">
                                        <div style="margin-bottom: 0.5rem;">
                                            <i class=""></i> ${room.size || ''}
                                            <i class="fas fa-bed"></i> ${room.bedType}
                                        </div>
                                        <div style="margin-bottom: 0.5rem; color: #16a34a;">
                                            <i class=""></i> ${room.view || ''}
                                        </div>
                                    </div>
                                    <div class="room-capacity">
                                        1 kamar untuk maksimal ${room.maxGuests} tamu (${room.maxAdults} dewasa, ${room.maxChildren} anak)
                                    </div>
                                    <div class="room-description">${room.description}</div>
                                    <div class="room-amenities">
                                        ${(room.amenities || []).slice(0, 6).map(amenity => `
                                            <div class="amenity">
                                                <i class="fas fa-check" style="color: #16a34a;"></i>
                                                <span>${amenity}</span>
                                            </div>
                                        `).join('')}
                                        ${(room.amenities || []).length > 6 ? `
                                            <div class="amenity" style="color: #26265A; font-weight: 500;">
                                                <i class="fas fa-plus"></i>
                                                <span>+${room.amenities.length - 6} fasilitas lainnya</span>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="room-pricing">
                                    ${room.available <= 3 ? `<div class="availability-alert">Sisa ${room.available} kamar</div>` : ''}
                                    <div class="price">${formatCurrency(room.basePrice * nights)}</div>
                                    <div class="price-period">per kamar / ${nights} malam</div>
                                    <button class="select-btn" onclick="openModal(${room.id})">
                                        Pilih Kamar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
                
                hideLoading(searchBtn, originalContent);
            }, 800);
        }

 function openModal(roomId) {
    const room = rooms.find(r => r.id === roomId);
    const modal = document.getElementById('modal-overlay');
    const title = document.getElementById('modal-title');
    const content = document.getElementById('modal-content');
    
    title.textContent = room.name;
    
    let guestSummary = `${guestData.adults} dewasa`;
    if (guestData.children > 0) {
        const ages = guestData.childrenAges.join(', ');
        guestSummary += `, ${guestData.children} anak (usia: ${ages} tahun)`;
    }
    
    content.innerHTML = `
        <div class="modal-image-container">
            <div class="image-slider" id="image-slider-${roomId}">
                <div class="image-slides" id="image-slides-${roomId}">
                    ${room.images.map((img, index) => `
                        <div class="slide">
                            <img src="${img}" alt="${room.name} - Gambar ${index + 1}">
                        </div>
                    `).join('')}
                </div>
                <button class="slider-nav slider-prev" onclick="changeSlide(${roomId}, -1)">‹</button>
                <button class="slider-nav slider-next" onclick="changeSlide(${roomId}, 1)">›</button>
                <div class="slider-indicators">
                    ${room.images.map((_, index) => `
                        <div class="slider-dot ${index === 0 ? 'active' : ''}" onclick="goToSlide(${roomId}, ${index})"></div>
                    `).join('')}
                </div>
            </div>
        </div>
        
        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 0.5rem; color: #26265A;">Detail Kamar:</h4>
            <div style="margin-bottom: 0.5rem;"><strong>Tipe Kasur:</strong> ${room.bedType}</div>
            <div style="margin-bottom: 0.5rem;"><strong>Kapasitas:</strong> Max ${room.maxGuests} tamu (${room.maxAdults} dewasa, ${room.maxChildren} anak)</div>
        </div>
        
        <div style="background: #e3f2fd; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 0.5rem; color: #1976d2;">Konfigurasi Tamu Anda:</h4>
            <div style="font-size: 0.9rem;">${guestSummary}</div>
            <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #666;">
                <i class="fas fa-info-circle"></i> 1 kamar untuk tamu ini. 
                Untuk menambah kamar yang sama, gunakan tombol + di keranjang.
                Untuk jenis kamar berbeda, pilih dari daftar kamar.
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 1rem; color: #26265A;">Deskripsi:</h4>
            <p>${room.description}</p>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 1rem; color: #26265A;">Fasilitas Lengkap:</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                ${(room.amenities || []).map(amenity => `
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                        <i class="fas fa-check" style="color: #16a34a;"></i>
                        <span>${amenity}</span>
                    </div>
                `).join('')}
            </div>
        </div>
        
        <h4 style="margin: 1.5rem 0 1rem; color: #26265A;">Pilih Paket:</h4>
        ${room.rates.map((rate, index) => `
            <div class="rate-option ${index === 0 ? 'selected' : ''}" onclick="selectRate(this)" data-room-id="${roomId}" data-rate-index="${index}">
                <div class="rate-header">
                    <div>
                        <div class="rate-name">${rate.name}</div>
                        <div style="font-size: 0.8rem; color: #666; margin-top: 0.2rem;">${rate.description}</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="rate-price">${formatCurrency(rate.price * nights)}</div>
                        <div style="font-size: 0.75rem; color: #666;">
                            1 kamar × ${nights} malam
                        </div>
                    </div>
                </div>
                <div class="rate-features">
                    ${rate.features}
                    ${rate.breakfast ? '<span class="breakfast-included">Sarapan Termasuk</span>' : ''}
                    ${rate.cancellation ? ' • <span style="color: #16a34a;">Pembatalan Gratis</span>' : ''}
                </div>
            </div>
        `).join('')}
        
        <button class="add-to-cart-btn" onclick="addToCart()">
            Tambah ke Keranjang
        </button>
    `;
    
    // Initialize slider
    currentSlideIndex = 0;
    updateSliderPosition(roomId);
    
    modal.style.display = 'block';
}
        function selectRate(element) {
            document.querySelectorAll('.rate-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
        }

        function closeModal() {
            document.getElementById('modal-overlay').style.display = 'none';
        }

        function calculateChildPrice(basePrice, age) {
            if (age <= 2) return 0;
            if (age <= 12) return basePrice * 0.5;
            return basePrice;
        }

        function addToCart() {
            const selectedRate = document.querySelector('.rate-option.selected');
            if (!selectedRate) return;
            
            const roomId = parseInt(selectedRate.dataset.roomId);
            const rateIndex = parseInt(selectedRate.dataset.rateIndex);
            const room = rooms.find(r => r.id === roomId);
            const rate = room.rates[rateIndex];
            
            const existingInCart = cart.filter(item => item.roomId === roomId && item.rateIndex === rateIndex)
                .reduce((sum, item) => sum + item.quantity, 0);
            
            if (existingInCart >= room.available) {
                showToast(`Maaf, hanya tersedia ${room.available} kamar ${room.name}. Anda sudah memiliki ${existingInCart} kamar di keranjang.`, 'error');
                return;
            }
            
            let totalPrice = rate.price * nights;
            
            guestData.childrenAges.forEach(age => {
                totalPrice += calculateChildPrice(rate.price, age) * nights;
            });
            
            const existingItem = cart.find(item => item.roomId === roomId && item.rateIndex === rateIndex);
            
            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.totalPrice = (rate.price * nights + 
                    guestData.childrenAges.reduce((sum, age) => sum + calculateChildPrice(rate.price, age) * nights, 0)) 
                    * existingItem.quantity;
                showToast(`1 kamar ${room.name} lagi ditambahkan (total: ${existingItem.quantity} kamar)`);
            } else {
                cart.push({
                    roomId: roomId,
                    rateIndex: rateIndex,
                    roomName: room.name,
                    rateName: rate.name,
                    price: rate.price * nights,
                    totalPrice: totalPrice,
                    quantity: 1,
                    breakfast: rate.breakfast,
                    cancellation: rate.cancellation,
                    nights: nights,
                    guestConfig: {
                        adults: guestData.adults,
                        children: guestData.children,
                        childrenAges: [...guestData.childrenAges]
                    },
                    roomDetails: {
                        size: room.size,
                        bedType: room.bedType,
                        view: room.view
                    }
                });
                showToast(`${room.name} ditambahkan ke keranjang`);
            }
            
            updateCart();
            updateMobileCart();
            closeModal();
        }

        function updateCart() {
            const cartContent = document.getElementById('cart-content');
            
            if (cart.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-selection">
                        <div class="empty-icon">🛏️</div>
                        <p>Belum ada kamar yang dipilih</p>
                        <small>Pilih kamar di sebelah kiri</small>
                    </div>
                `;
                return;
            }
            
            let subtotal = 0;
            const itemsHtml = cart.map((item, index) => {
                subtotal += item.totalPrice;
                
                let guestSummary = `${item.guestConfig.adults} dewasa`;
                if (item.guestConfig.children > 0) {
                    const ages = item.guestConfig.childrenAges.join(', ');
                    guestSummary += `, ${item.guestConfig.children} anak (${ages} th)`;
                }
                
                const features = [];
                if (item.breakfast) features.push('Sarapan');
                if (item.cancellation) features.push('Pembatalan Gratis');
                const featureText = features.length > 0 ? ` • ${features.join(' • ')}` : '';
                
                return `
                    <div class="selection-item">
                        <div class="selection-header">
                            <div class="selection-name">${item.roomName}</div>
                            <button class="remove-btn" onclick="removeFromCart(${index})" aria-label="Hapus dari keranjang">&times;</button>
                        </div>
                        <div class="selection-rate">
                            <strong>${item.rateName}</strong>${featureText}
                            <br><small style="color: #666;">${item.roomDetails.size} • ${item.roomDetails.bedType}</small>
                            <br><small style="color: #666;">${item.roomDetails.view}</small>
                        </div>
                        <div style="font-size: 0.8rem; color: #666; margin: 0.5rem 0; padding: 0.5rem; background: #f8f9fa; border-radius: 4px;">
                            <strong>Per kamar:</strong> ${guestSummary}
                        </div>
                        <div class="quantity-controls">
                            <span style="font-size: 0.8rem; color: #666;">Jumlah kamar:</span>
                            <button class="qty-btn" onclick="updateQuantity(${index}, -1)" ${item.quantity <= 1 ? 'disabled' : ''} aria-label="Kurangi jumlah">-</button>
                            <span class="qty-display">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQuantity(${index}, 1)" aria-label="Tambah jumlah">+</button>
                        </div>
                        <div class="selection-price">
                            ${formatCurrency(item.totalPrice)}
                            <br><small>${item.quantity} kamar × ${item.nights} malam</small>
                        </div>
                    </div>
                `;
            }).join('');
            
            const tax = Math.round(subtotal * 0.11);
            const service = Math.round(subtotal * 0.05);
            const total = subtotal + tax + service;
            
            cartContent.innerHTML = `
                ${itemsHtml}
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>${formatCurrency(subtotal)}</span>
                    </div>
                    <div class="total-row">
                        <span>Pajak (11%):</span>
                        <span>${formatCurrency(tax)}</span>
                    </div>
                    <div class="total-row">
                        <span>Biaya Layanan (5%):</span>
                        <span>${formatCurrency(service)}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total:</span>
                        <span>${formatCurrency(total)}</span>
                    </div>
                </div>
                <button class="proceed-btn" onclick="makeReservation()">Lanjutkan Pemesanan</button>
            `;
        }

        function updateMobileCart() {
            const mobileCartFloat = document.getElementById('mobile-cart-float');
            const mobileCartCount = document.getElementById('mobile-cart-count');
            const mobileCartBody = document.getElementById('mobile-cart-body');
            
            if (cart.length === 0) {
                mobileCartFloat.classList.remove('show');
                mobileCartBody.innerHTML = `
                    <div class="empty-selection">
                        <div class="empty-icon">🛏️</div>
                        <p>Belum ada kamar yang dipilih</p>
                        <small>Pilih kamar di atas</small>
                    </div>
                `;
                return;
            }
            
            mobileCartFloat.classList.add('show');
            
            const totalRooms = cart.reduce((sum, item) => sum + item.quantity, 0);
            mobileCartCount.textContent = `${totalRooms} kamar`;
            
            let subtotal = 0;
            const itemsHtml = cart.map((item, index) => {
                subtotal += item.totalPrice;
                
                let guestSummary = `${item.guestConfig.adults} dewasa`;
                if (item.guestConfig.children > 0) {
                    guestSummary += `, ${item.guestConfig.children} anak`;
                }
                
                return `
                    <div class="selection-item">
                        <div class="selection-header">
                            <div class="selection-name">${item.roomName}</div>
                            <button class="remove-btn" onclick="removeFromCart(${index})" aria-label="Hapus dari keranjang">&times;</button>
                        </div>
                        <div class="selection-rate">
                            ${item.rateName} ${item.breakfast ? '• Sarapan' : ''}
                            <br><small>Per kamar: ${guestSummary}</small>
                        </div>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="updateQuantity(${index}, -1)" ${item.quantity <= 1 ? 'disabled' : ''} aria-label="Kurangi jumlah">-</button>
                            <span class="qty-display">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQuantity(${index}, 1)" aria-label="Tambah jumlah">+</button>
                        </div>
                        <div class="selection-price">
                            ${formatCurrency(item.totalPrice)}
                            <br><small>${item.nights} malam</small>
                        </div>
                    </div>
                `;
            }).join('');
            
            const tax = Math.round(subtotal * 0.11);
            const service = Math.round(subtotal * 0.05);
            const total = subtotal + tax + service;
            
            mobileCartBody.innerHTML = `
                ${itemsHtml}
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>${formatCurrency(subtotal)}</span>
                    </div>
                    <div class="total-row">
                        <span>Pajak (11%):</span>
                        <span>${formatCurrency(tax)}</span>
                    </div>
                    <div class="total-row">
                        <span>Biaya Layanan (5%):</span>
                        <span>${formatCurrency(service)}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total:</span>
                        <span>${formatCurrency(total)}</span>
                    </div>
                </div>
                <button class="proceed-btn" onclick="makeReservation()">Lanjutkan Pemesanan</button>
            `;
        }

        function updateQuantity(index, change) {
            const item = cart[index];
            const room = rooms.find(r => r.id === item.roomId);
            
            if (change > 0) {
                const existingInCart = cart.filter(cartItem => cartItem.roomId === item.roomId && cartItem.rateIndex === item.rateIndex)
                    .reduce((sum, cartItem) => sum + cartItem.quantity, 0);
                
                if (existingInCart >= room.available) {
                    showToast(`Maaf, hanya tersedia ${room.available} kamar ${room.name}`, 'error');
                    return;
                }
            }
            
            item.quantity += change;
            if (item.quantity <= 0) {
                cart.splice(index, 1);
                showToast('Kamar dihapus dari keranjang');
            } else {
                const basePrice = item.price;
                let childPriceTotal = 0;
                item.guestConfig.childrenAges.forEach(age => {
                    childPriceTotal += calculateChildPrice(item.price / nights, age) * nights;
                });
                item.totalPrice = (basePrice + childPriceTotal) * item.quantity;
            }
            
            updateCart();
            updateMobileCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
            updateMobileCart();
            showToast('Kamar dihapus dari keranjang');
        }

        function showMobileCart() {
            document.getElementById('mobile-cart-modal').style.display = 'block';
        }

        function hideMobileCart() {
            document.getElementById('mobile-cart-modal').style.display = 'none';
        }

        function proceedBooking() {
            if (cart.length === 0) {
                showToast('Pilih kamar terlebih dahulu', 'error');
                return;
            }
            
            let subtotal = cart.reduce((sum, item) => sum + item.totalPrice, 0);
            const tax = Math.round(subtotal * 0.11);
            const service = Math.round(subtotal * 0.05);
            const total = subtotal + tax + service;
            
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            
            document.getElementById('booking-summary-modal').innerHTML = `
                <h3>Ringkasan Pemesanan</h3>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <div style="margin-bottom: 0.5rem;"><strong>Check-in:</strong> ${new Date(checkin).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</div>
                    <div style="margin-bottom: 0.5rem;"><strong>Check-out:</strong> ${new Date(checkout).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</div>
                    <div style="margin-bottom: 0.5rem;"><strong>Durasi:</strong> ${nights} malam</div>
                    <div style="margin-bottom: 1rem;"><strong>Total Kamar:</strong> ${cart.reduce((sum, item) => sum + item.quantity, 0)} kamar</div>
                    
                    ${cart.map(item => {
                        let guestSummary = `${item.guestConfig.adults} dewasa`;
                        if (item.guestConfig.children > 0) {
                            guestSummary += `, ${item.guestConfig.children} anak`;
                        }
                        
                        return `
                            <div style="border-top: 1px solid #e9ecef; padding-top: 0.5rem; margin-top: 0.5rem;">
                                <strong>${item.roomName}</strong> - ${item.rateName}<br>
                                <small>Quantity: ${item.quantity} kamar • ${item.nights} malam • ${item.breakfast ? 'Termasuk sarapan' : 'Tanpa sarapan'}</small><br>
                                <small>Per kamar: ${guestSummary}</small><br>
                                <strong>${formatCurrency(item.totalPrice)}</strong>
                            </div>
                        `;
                    }).join('')}
                    
                    <div style="border-top: 2px solid #26265A; margin-top: 1rem; padding-top: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span>Subtotal:</span><strong>${formatCurrency(subtotal)}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span>Pajak & Layanan:</span><strong>${formatCurrency(tax + service)}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 1.1rem; color: #26265A;">
                            <span><strong>Total Pembayaran:</strong></span><strong>${formatCurrency(total)}</strong>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('guest-modal').style.display = 'block';
        }


        function closeGuestModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('guest-modal').style.display = 'none';
        }

        function searchRooms() {
            const checkinInput = document.getElementById('checkin');
            const checkoutInput = document.getElementById('checkout');
            
            const today = new Date().toISOString().split('T')[0];
            if (checkinInput.value < today) {
                showToast('Tanggal check-in tidak boleh sebelum hari ini', 'error');
                checkinInput.value = today;
                return;
            }
            
            if (checkoutInput.value <= checkinInput.value) {
                showToast('Tanggal check-out harus setelah check-in', 'error');
                const checkin = new Date(checkinInput.value);
                checkin.setDate(checkin.getDate() + 1);
                checkoutInput.value = checkin.toISOString().split('T')[0];
                return;
            }
            
            updateDateDisplay();
            searchPerformed = true;
            document.getElementById('guest-dropdown').classList.remove('active');
            renderRooms();
        }

        function handleDateChange() {
            const checkinInput = document.getElementById('checkin');
            const checkoutInput = document.getElementById('checkout');
            
            const today = new Date().toISOString().split('T')[0];
            if (checkinInput.value < today) {
                checkinInput.value = today;
                showToast('Tanggal check-in disesuaikan ke hari ini', 'error');
            }
            
            const checkinDate = new Date(checkinInput.value);
            const checkoutDate = new Date(checkinDate);
            checkoutDate.setDate(checkoutDate.getDate() + 1);
            
            checkoutInput.min = checkoutDate.toISOString().split('T')[0];
            if (new Date(checkoutInput.value) <= checkinDate) {
                checkoutInput.value = checkoutDate.toISOString().split('T')[0];
            }
            
            updateDateDisplay();
            
            if (searchPerformed) {
                renderRooms();
            }
        }

        document.getElementById('booking-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const bookingData = {
                guest: {
                    firstName: formData.get('firstName'),
                    lastName: formData.get('lastName'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                },
                payment: formData.get('payment'),
                rooms: cart,
                dates: {
                    checkin: document.getElementById('checkin').value,
                    checkout: document.getElementById('checkout').value,
                    nights: nights
                },
                guestConfig: guestData,
                total: cart.reduce((sum, item) => sum + item.totalPrice, 0)
            };
            
            const confirmBtn = document.querySelector('.confirm-booking-btn');
            const originalContent = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<div class="loading"><div class="loading-spinner"></div> Memproses...</div>';
            confirmBtn.disabled = true;
            
            setTimeout(() => {
                const bookingId = 'HSB' + Date.now().toString().slice(-6);
                bookingData.bookingId = bookingId;
                localStorage.setItem('lastBooking', JSON.stringify(bookingData));
                
                confirmBtn.innerHTML = originalContent;
                confirmBtn.disabled = false;
                closeGuestModal();
                
                showToast(`Pemesanan berhasil! ID: ${bookingId}`);
                
                cart = [];
                updateCart();
                updateMobileCart();
            }, 2000);
        });

        document.addEventListener('DOMContentLoaded', function() {
            initializeDates();
            updateGuestDisplay();
            renderChildrenAges();
            updateMobileCart();
            
            document.getElementById('checkin').addEventListener('change', handleDateChange);
            document.getElementById('checkout').addEventListener('change', handleDateChange);
            
            document.addEventListener('click', function(event) {
                const guestSelector = document.getElementById('guest-selector');
                const dropdown = document.getElementById('guest-dropdown');
                
                if (!guestSelector.contains(event.target)) {
                    dropdown.classList.remove('active');
                }
            });
            
            document.querySelector('.guest-summary').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleGuestDropdown();
                }
            });
            
            // Initialize hotel photos and load rooms
            updateHeroBackground();
            loadRooms();
        });
    </script>
      <x-footer /> 
</body>
</html>