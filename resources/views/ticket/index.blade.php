<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecta - Pemesanan Tiket</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Midtrans Snap -->
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Authentication Status -->
    <meta name="auth-status" content="{{ auth()->check() ? '1' : '0' }}">
    <!-- User Data -->
    <meta name="user-name" content="{{ auth()->user()->name ?? '' }}">
    <meta name="user-email" content="{{ auth()->user()->email ?? '' }}">
    <meta name="user-phone" content="{{ auth()->user()->phone ?? '' }}">
    <script>
        // Authentication status
        const isUserAuthenticated = document.querySelector('meta[name="auth-status"]').content === '1';
        
        // Debug Midtrans Snap loading
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Midtrans client key:', '{{ config("midtrans.client_key") }}');
            console.log('Snap object available:', typeof window.snap);
            if (typeof window.snap === 'undefined') {
                console.error('Midtrans Snap not loaded properly!');
            }
        });
        
        // Package mapping data - will be populated by server
        window.packageMappingData = {};
        window.ticketDataFromServer = {};
    </script>
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

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), 
                        url('/images/herotiket2.png');
            background-size: cover;
            background-position: center 70%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .form-select, .form-input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            background: white;
        }

        .ticket-counter {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            min-height: 48px;
        }

        .counter-display {
            font-size: 0.9rem;
            color: #333;
            font-weight: 500;
        }

        .counter-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .counter-btn {
            background: #26265A;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .counter-btn:hover {
            background: #141430;
        }

        .counter-btn:active {
            transform: scale(0.95);
        }

        .counter-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
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
            transition: background 0.3s ease;
        }

        .search-btn:hover {
            background: #141430;
        }

        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .right-sidebar {
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .content-section {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            min-height: 400px;
        }

        .section-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .section-description {
            color: #666;
            font-size: 0.9rem;
        }

        /* Tentang Selecta Section */
        .about-content {
            padding: 1.5rem;
        }

        .about-text {
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* Compact Ticket Styles */
        .ticket-info-section {
            margin-bottom: 2rem;
        }

        .ticket-types-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .ticket-type-card-compact {
            border: 1px solid #26265A;
            border-radius: 8px;
            padding: 1rem;
            background: white;
            position: relative;
        }

        .ticket-type-card-compact.premium {
            border: 1px solid #26265A;
            background: white;
        }

        .ticket-header-compact {
            margin-bottom: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .ticket-title-compact {
            font-size: 1rem;
            font-weight: 600;
            color: #26265A;
        }

        .ticket-price-compact {
            font-size: 1.2rem;
            font-weight: 700;
            color: #26265A;
        }

        .badge-compact {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(238, 90, 36, 0.3);
        }

        .ticket-brief {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
        }

        .detail-btn {
            background: none;
            border: none;
            color: #26265A;
            font-weight: 500;
            cursor: pointer;
            text-decoration: underline;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            padding: 0;
        }

        .detail-btn:hover {
            color: #141430;
        }

        /* Facilities Simple Styles */
        .facilities-simple {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
        }

        .facility-item-simple {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .facility-icon-simple {
            color: #26265A;
            width: 14px;
            font-size: 0.8rem;
        }

        /* Improved Ticket Modal Styles */
        .ticket-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .ticket-modal.active {
            display: flex;
        }

        .ticket-modal-content {
            background: white;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
        }

        .ticket-modal-header {
            background: #26265A;
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .ticket-modal-title {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .ticket-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .ticket-modal-close:hover {
            background: rgba(255,255,255,0.1);
        }

        .ticket-modal-body {
            padding: 0;
            overflow-y: auto;
            flex: 1;
        }

        .modal-content-section {
            padding: 2rem;
            scroll-margin-top: 60px;
        }

        /* Modal Navigation Tabs */
        .modal-tabs {
            background: white;
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modal-tab {
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
            flex: 1;
            text-align: center;
            position: relative;
            white-space: nowrap;
        }

        .modal-tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
            font-weight: 600;
        }

        .modal-tab:hover:not(.active) {
            color: #374151;
            background: #f9fafb;
        }

        .ticket-features {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .feature-check {
            color: #22c55e;
            font-size: 0.9rem;
            min-width: 14px;
            margin-top: 0.1rem;
        }

        .feature-cross {
            color: #ef4444;
            font-size: 0.9rem;
            min-width: 14px;
            margin-top: 0.1rem;
        }

        .modal-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .modal-section h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #26265A;
            margin-bottom: 0.8rem;
        }

        .modal-section ul {
            list-style: none;
            padding: 0;
        }

        .modal-section li {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.3rem;
            padding-left: 0.8rem;
            position: relative;
        }

        .modal-section li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #26265A;
        }

        /* Review Section */
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .rating-summary {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .rating-score {
            font-size: 1.2rem;
            font-weight: 700;
            color: #26265A;
        }

        .rating-stars {
            color: #fbbf24;
        }

        .review-count {
            color: #666;
            font-size: 0.85rem;
        }

        .view-all-reviews {
            color: #26265A;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .review-carousel {
            position: relative;
            overflow: hidden;
        }

        .review-container {
            display: flex;
            transition: transform 0.3s ease;
        }

        .review-card {
            min-width: 100%;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-right: 1rem;
            min-height: 220px;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #26265A;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .reviewer-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .review-date {
            color: #666;
            font-size: 0.8rem;
        }

        .review-rating {
            color: #fbbf24;
            margin-bottom: 0.5rem;
        }

        .review-text {
            font-size: 0.85rem;
            line-height: 1.4;
            color: #555;
        }

        .carousel-nav {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .nav-btn {
            background: #26265A;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .nav-btn:hover {
            background: #141430;
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Photo Gallery Section */
        .photo-gallery {
            background: white;
            border-radius: 8px;
            margin-top: 1rem;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .gallery-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .gallery-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .gallery-count {
            color: #666;
            font-size: 0.85rem;
        }

        .view-all-photos {
            color: #26265A;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .view-all-photos:hover {
            color: #141430;
        }

        .gallery-grid {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .gallery-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.5s ease;
        }

        /* Gallery Modal */
        .gallery-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.95);
            display: none;
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .gallery-modal.active {
            display: flex;
        }

        .gallery-modal-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-modal img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .gallery-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            cursor: pointer;
            z-index: 2001;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-prev {
            left: 20px;
        }

        .gallery-next {
            right: 20px;
        }

        /* Review Modal */
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

        .review-modal {
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            width: 400px;
            background: white;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 1001;
            overflow-y: auto;
        }

        .review-modal.active {
            transform: translateX(0);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #26265A;
            color: white;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .modal-content {
            padding: 1.5rem;
        }

        .all-reviews {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .full-review-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
        }

        .review-media {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .review-image {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
        }

        /* Review Actions & Buttons */
        .review-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .btn-add-review, .btn-edit-review, .btn-login-review {
            background: #26265A;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-add-review:hover, .btn-edit-review:hover, .btn-login-review:hover {
            background: #141430;
        }

        .btn-edit-review {
            background: #f59e0b;
        }

        .btn-edit-review:hover {
            background: #d97706;
        }

        .btn-login-review {
            background: #6b7280;
        }

        .btn-login-review:hover {
            background: #4b5563;
        }

        /* Review Form Modal */
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.9rem;
            resize: vertical;
            min-height: 100px;
        }

        .char-count {
            text-align: right;
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.3rem;
        }

        /* Star Rating */
        .star-rating {
            display: flex;
            gap: 0.2rem;
            margin-bottom: 0.5rem;
        }

        .star {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star:hover,
        .star.active {
            color: #fbbf24;
        }

        /* Image Upload */
        .image-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            transition: border-color 0.3s ease;
        }

        .image-upload-area:hover {
            border-color: #26265A;
        }

        .upload-placeholder {
            cursor: pointer;
            color: #666;
        }

        .upload-placeholder i {
            font-size: 2rem;
            color: #26265A;
            margin-bottom: 0.5rem;
        }

        .image-preview {
            position: relative;
            display: inline-block;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 6px;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .btn-cancel {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-cancel:hover {
            background: #4b5563;
        }

        .btn-submit {
            background: #26265A;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background: #141430;
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* User Review Actions */
        .review-actions-user {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e9ecef;
        }

        .btn-edit-user-review, .btn-delete-user-review {
            background: none;
            border: 1px solid #ddd;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-edit-user-review {
            color: #f59e0b;
            border-color: #f59e0b;
        }

        .btn-edit-user-review:hover {
            background: #f59e0b;
            color: white;
        }

        .btn-delete-user-review {
            color: #ef4444;
            border-color: #ef4444;
        }

        .btn-delete-user-review:hover {
            background: #ef4444;
            color: white;
        }

        /* No Reviews State */
        .no-reviews, .no-reviews-modal {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .no-reviews-modal h4 {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .btn-add-review-modal, .btn-login-review-modal {
            background: #26265A;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-add-review-modal:hover {
            background: #141430;
        }

        .btn-login-review-modal {
            background: #6b7280;
        }

        .btn-login-review-modal:hover {
            background: #4b5563;
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #26265A;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 400px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: #22c55e;
        }

        .notification.error {
            background: #ef4444;
        }

        .notification.warning {
            background: #f59e0b;
        }

        .notification .close-notification {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0.7;
        }

        .notification .close-notification:hover {
            opacity: 1;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .search-form {
                grid-template-columns: 1fr;
                margin: 0 1rem 1rem;
            }

            .container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .review-modal {
                width: 100%;
            }

            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .ticket-types-grid {
                grid-template-columns: 1fr;
            }

            .facilities-simple {
                grid-template-columns: repeat(2, 1fr);
            }

            .ticket-modal {
                padding: 10px;
            }

            .ticket-modal-content {
                width: 100%;
                max-width: none;
                max-height: 95vh;
            }

            .modal-tabs {
                flex-wrap: wrap;
            }

            .modal-tab {
                flex: 1 1 50%;
                text-align: center;
                padding: 0.75rem 0.5rem;
                font-size: 0.8rem;
            }

            .modal-content-section {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .ticket-modal {
                padding: 5px;
            }

            .modal-tab {
                flex: 1 1 100%;
                font-size: 0.75rem;
                padding: 0.6rem 0.4rem;
            }

            .modal-content-section {
                padding: 1rem;
            }

            .ticket-modal-header {
                padding: 1rem;
            }

            .ticket-modal-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
      @include('components.navbar')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-header">
            <h1 class="hero-title">Taman Rekreasi Selecta</h1>
            <div class="hero-rating">
                <div class="hero-badge">
                    <i class="fas fa-clock"></i>
                    08:00 - 17:00 WIB
                </div>
                <div class="hero-badge">Wisata Populer Batu Malang</div>
            </div>
            <div class="hero-location">
                <i class="fas fa-map-marker-alt"></i>
                Jl. Raya Selecta No. 1, Batu, Malang
            </div>
        </div>

        <form class="search-form" id="ticketForm">
            <div class="form-group">
                <label class="form-label">Jenis Tiket</label>
                <select class="form-select" id="ticketType">
                    @if($packages->isNotEmpty())
                        @foreach($packages as $package)
                            <option value="{{ strtolower(str_replace('Tiket ', '', $package->name)) }}">
                                {{ $package->name }} - Rp {{ number_format($package->price) }}
                            </option>
                        @endforeach
                    @else
                        <option value="reguler">Tiket Reguler - Rp 50.000</option>
                        <option value="terusan">Tiket Terusan - Rp 80.000</option>
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" class="form-input" id="visitDate" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Tiket</label>
                <div class="ticket-counter">
                    <span class="counter-display" id="ticketCountDisplay">1 tiket</span>
                    <div class="counter-controls">
                        <button type="button" class="counter-btn" id="decreaseBtn" onclick="decreaseTickets()">-</button>
                        <button type="button" class="counter-btn" onclick="increaseTickets()">+</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="search-btn" onclick="searchTickets()">Pesan Tiket</button>
            </div>
        </form>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Left Content -->
        <div class="left-content">
            <!-- Tentang Selecta -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Tentang Taman Selecta</h2>
                    <p class="section-description">Destinasi wisata terpopuler di Batu Malang</p>
                </div>
                
                <div class="about-content">
                    <p class="about-text">
                        Taman Selecta adalah destinasi wisata alam yang menawarkan keindahan panorama pegunungan dan udara sejuk khas Batu Malang. Dengan berbagai fasilitas menarik dan wahana seru, Selecta menjadi pilihan utama untuk liburan keluarga yang tak terlupakan.
                    </p>
                    
                    <p class="about-text">
                        Nikmati keindahan taman bunga yang colorful, kolam renang dengan pemandangan pegunungan, dan berbagai spot foto instagramable yang akan membuat kunjungan Anda semakin berkesan.
                    </p>

                    <!-- Ticket Information Section -->
                    <div class="ticket-info-section">
                        <h3 style="margin-bottom: 1.5rem; color: #26265A;">Jenis Tiket</h3>
                        
                        <div class="ticket-types-grid">
                            @if($packages->isNotEmpty())
                                @foreach($packages as $package)
                                    <div class="ticket-type-card-compact {{ $package->badge ? 'premium' : '' }}">
                                        <div class="ticket-header-compact">
                                            <h4 class="ticket-title-compact">{{ $package->name }}</h4>
                                            <div class="ticket-price-compact">Rp {{ number_format($package->price) }}</div>
                                            @if($package->badge)
                                                <span class="badge-compact">{{ $package->badge }}</span>
                                            @endif
                                        </div>
                                        <div class="ticket-brief">
                                            <p>{{ $package->description ?: 'Paket wisata dengan fasilitas lengkap' }}</p>
                                            <button class="detail-btn" data-ticket-type="{{ strtolower(str_replace('Tiket ', '', $package->name)) }}" onclick="openTicketModal(this.dataset.ticketType)">Detail</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="ticket-type-card-compact">
                                    <div class="ticket-header-compact">
                                        <h4 class="ticket-title-compact">Tiket Reguler</h4>
                                        <div class="ticket-price-compact">Rp 50.000</div>
                                    </div>
                                    <div class="ticket-brief">
                                        <p>Termasuk: Kolam renang, waterpark, taman bunga, dan fasilitas dasar lainnya</p>
                                        <button class="detail-btn" onclick="openTicketModal('reguler')">Detail</button>
                                    </div>
                                </div>
                                
                                <div class="ticket-type-card-compact premium">
                                    <div class="ticket-header-compact">
                                        <h4 class="ticket-title-compact">Tiket Terusan</h4>
                                        <div class="ticket-price-compact">Rp 80.000</div>
                                    </div>
                                    <div class="ticket-brief">
                                        <p>Akses ke semua wahana dan fasilitas Taman Rekreasi Selecta termasuk tiket masuk</p>
                                        <button class="detail-btn" onclick="openTicketModal('terusan')">Detail</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 style="margin: 2rem 0 1rem 0; color: #26265A;">Fasilitas</h3>
                    
                    <div class="facilities-simple">
                        <div class="facility-item-simple">
                            <i class="fas fa-swimming-pool facility-icon-simple"></i>
                            <span>Kolam renang</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-water facility-icon-simple"></i>
                            <span>Waterpark</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-seedling facility-icon-simple"></i>
                            <span>Taman bunga</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-fish facility-icon-simple"></i>
                            <span>Kolam ikan</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-dragon facility-icon-simple"></i>
                            <span>Dino Ranch</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-ship facility-icon-simple"></i>
                            <span>Perahu danau</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-mountain facility-icon-simple"></i>
                            <span>Flying fox</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-utensils facility-icon-simple"></i>
                            <span>Food court</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-car facility-icon-simple"></i>
                            <span>Area parkir</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-restroom facility-icon-simple"></i>
                            <span>Toilet & mushola</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-camera facility-icon-simple"></i>
                            <span>Spot foto</span>
                        </div>
                        <div class="facility-item-simple">
                            <i class="fas fa-gamepad facility-icon-simple"></i>
                            <span>Wahana permainan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Reviews & Gallery -->
        <div class="right-sidebar">
            <!-- Reviews Section -->
            <div class="content-section">
                <div class="section-header">
                    <div class="review-header">
                        <div>
                            <h3 class="section-title">Review Pengunjung</h3>
                            <div class="rating-summary">
                                <span class="rating-score" id="avgRating">
                                    @if($reviews->count() > 0)
                                        {{ number_format($reviews->avg('rating'), 1) }}
                                    @else
                                        0.0
                                    @endif
                                </span>
                                <div class="rating-stars" id="avgStars">
                                    @if($reviews->count() > 0)
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $reviews->avg('rating'))
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    @else
                                        ☆☆☆☆☆
                                    @endif
                                </div>
                                <span class="review-count" id="reviewCount">({{ $reviews->count() }} review)</span>
                            </div>
                        </div>
                        <div class="review-actions">
                            @auth
                                @if(!$userReview)
                                    <button class="btn-add-review" onclick="openAddReviewModal()">
                                        <i class="fas fa-plus"></i> Tulis Review
                                    </button>
                                @else
                                    <button class="btn-edit-review" onclick="openEditReviewModal()">
                                        <i class="fas fa-edit"></i> Edit Review
                                    </button>
                                @endif
                            @else
                                <button class="btn-login-review" onclick="showLoginAlert()">
                                    <i class="fas fa-sign-in-alt"></i> Login untuk Review
                                </button>
                            @endauth
                            <a href="#" class="view-all-reviews" onclick="openReviewModal()">Lihat Semua</a>
                        </div>
                    </div>
                </div>

                <div class="review-carousel">
                    <div class="review-container" id="reviewContainer">
                        @if($reviews->count() > 0)
                            @foreach($reviews->take(3) as $review)
                                <div class="review-card">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">{{ strtoupper(substr($review->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="reviewer-name">{{ $review->name }}</div>
                                            <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                        {{ $review->rating }}/5
                                    </div>
                                    <p class="review-text">{{ $review->comment }}</p>
                                    @if($review->image_url)
                                        <div class="review-media">
                                            <img src="{{ $review->image_url }}" alt="Review Photo" class="review-image">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="review-card">
                                <div class="no-reviews">
                                    <i class="fas fa-star" style="font-size: 2rem; color: #ddd; margin-bottom: 1rem;"></i>
                                    <p>Belum ada review. Jadilah yang pertama memberikan review!</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($reviews->count() > 1)
                        <div class="carousel-nav">
                            <button class="nav-btn" onclick="prevReview()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="nav-btn" onclick="nextReview()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Photo Gallery Section -->
            <div class="photo-gallery">
                <div class="gallery-header">
                    <div>
                        <h3 class="gallery-title">Foto-foto</h3>
                        <div class="gallery-count">36+ foto tersedia</div>
                    </div>
                 <a href="{{ route('gallery.index') }}">Lihat Semua</a>
                </div>
                <div class="gallery-grid">
                    <div class="gallery-item" onclick="openGallery(0)">
                        <img src="/images/galeri1.jpeg" alt="Taman Selecta" id="galleryImg1">
                    </div>
                    <div class="gallery-item" onclick="openGallery(1)">
                        <img src="/images/galeri2.jpeg" alt="Kolam Renang" id="galleryImg2">
                    </div>
                    <div class="gallery-item" onclick="openGallery(2)">
                        <img src="/images/galeri3.jpeg" alt="Wahana Permainan" id="galleryImg3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Modal -->
    <div class="ticket-modal" id="ticketModal">
        <div class="ticket-modal-content">
            <div class="ticket-modal-header">
                <h3 class="ticket-modal-title" id="ticketModalTitle">Detail Tiket</h3>
                <button class="ticket-modal-close" onclick="closeTicketModal()">&times;</button>
            </div>
            <div class="ticket-modal-body">
                <!-- Modal Navigation Tabs -->
                <div class="modal-tabs">
                    <button class="modal-tab active" onclick="scrollToSection('info')">Termasuk</button>
                    <button class="modal-tab" onclick="scrollToSection('voucher')">Cara Pakai E-voucher</button>
                    <button class="modal-tab" onclick="scrollToSection('terms')">Syarat & Ketentuan</button>
                    <button class="modal-tab" onclick="scrollToSection('additional')">Informasi Tambahan</button>
                </div>

                <!-- Single Modal Content Area -->
                <div class="modal-content-section" id="infoSection">
                    <h4>Termasuk:</h4>
                    <div class="ticket-features" id="ticketIncludes">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                    
                    <h4 style="margin-top: 1.5rem;">Harga Tidak Termasuk:</h4>
                    <div class="ticket-features" id="ticketNotIncludes">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>

                <div class="modal-content-section" id="voucherSection">
                    <h4>Cara Pakai E-voucher:</h4>
                    <ul id="voucherUsageList">
                        <!-- Content will be populated by JavaScript -->
                    </ul>
                </div>

                <div class="modal-content-section" id="termsSection">
                    <h4>Syarat & Ketentuan:</h4>
                    <ul id="termsConditionsList">
                        <!-- Content will be populated by JavaScript -->
                    </ul>
                </div>

                <div class="modal-content-section" id="additionalSection">
                    <h4>Informasi Tambahan:</h4>
                    <ul id="additionalInfoList">
                        <!-- Content will be populated by JavaScript -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="gallery-modal" id="galleryModal">
        <span class="gallery-close" onclick="closeGallery()">&times;</span>
        <button class="gallery-nav gallery-prev" onclick="prevImage()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="gallery-modal-content">
            <img id="galleryImage" src="" alt="">
        </div>
        <button class="gallery-nav gallery-next" onclick="nextImage()">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Review Modal -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeReviewModal()">
        <div class="review-modal" id="reviewModal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">Semua Review ({{ $reviews->count() }})</h3>
                <button class="close-btn" onclick="closeReviewModal()">&times;</button>
            </div>
            <div class="modal-content">
                <div class="all-reviews" id="allReviewsContainer">
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                            <div class="full-review-card">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">{{ strtoupper(substr($review->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="reviewer-name">{{ $review->name }}</div>
                                        <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    {{ $review->rating }}/5
                                </div>
                                <p class="review-text">{{ $review->comment }}</p>
                                @if($review->image_url)
                                    <div class="review-media">
                                        <img src="{{ $review->image_url }}" alt="Review Photo" class="review-image">
                                    </div>
                                @endif
                                @auth
                                    @if($review->user_id == auth()->id())
                                        <div class="review-actions-user">
                                            <button class="btn-edit-user-review" onclick="openEditReviewModal()">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn-delete-user-review" onclick="deleteUserReview()">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    @else
                        <div class="no-reviews-modal">
                            <i class="fas fa-star" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <h4>Belum ada review</h4>
                            <p>Jadilah yang pertama memberikan review untuk Taman Selecta!</p>
                            @auth
                                <button class="btn-add-review-modal" onclick="closeReviewModal(); openAddReviewModal();">
                                    <i class="fas fa-plus"></i> Tulis Review Pertama
                                </button>
                            @else
                                <button class="btn-login-review-modal" onclick="showLoginAlert()">
                                    <i class="fas fa-sign-in-alt"></i> Login untuk Review
                                </button>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Review Modal -->
    <div class="modal-overlay" id="reviewFormOverlay" onclick="closeReviewFormModal()">
        <div class="review-modal" id="reviewFormModal" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title" id="reviewFormTitle">Tulis Review</h3>
                <button class="close-btn" onclick="closeReviewFormModal()">&times;</button>
            </div>
            <div class="modal-content">
                <form id="reviewForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <div class="star-rating" id="starRating">
                            <span class="star" data-rating="1">★</span>
                            <span class="star" data-rating="2">★</span>
                            <span class="star" data-rating="3">★</span>
                            <span class="star" data-rating="4">★</span>
                            <span class="star" data-rating="5">★</span>
                        </div>
                        <input type="hidden" id="ratingInput" name="rating" value="5">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Komentar</label>
                        <textarea id="commentInput" name="comment" class="form-textarea" rows="4" 
                                placeholder="Ceritakan pengalaman Anda di Taman Selecta..." 
                                minlength="10" maxlength="1000" required></textarea>
                        <div class="char-count">
                            <span id="charCount">0</span>/1000 karakter
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Foto (Opsional)</label>
                        <div class="image-upload-area" id="imageUploadArea">
                            <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
                            <div class="upload-placeholder" onclick="document.getElementById('imageInput').click()">
                                <i class="fas fa-camera"></i>
                                <p>Klik untuk menambah foto</p>
                                <small>JPG, PNG, GIF (Max 2MB)</small>
                            </div>
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <button type="button" class="remove-image" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeReviewFormModal()">Batal</button>
                        <button type="submit" class="btn-submit" id="submitReviewBtn">
                            <i class="fas fa-paper-plane"></i> Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Data as JSON in hidden div -->
    <div id="server-data" style="display: none;">
        <script type="application/json" id="package-mapping-data">
            {!! json_encode($packageMapping ?? ['reguler' => 1, 'terusan' => 2]) !!}
        </script>
        <script type="application/json" id="ticket-data">
            @php
                $ticketDataArray = [];
                if($packages->isNotEmpty()) {
                    foreach($packages as $package) {
                        $key = strtolower(str_replace('Tiket ', '', $package->name));
                        $ticketDataArray[$key] = [
                            'title' => $package->name . ' - Rp ' . number_format($package->price),
                            'includes' => $package->features ?: ['Akses ke fasilitas utama', 'Fasilitas parkir', 'Area bermain'],
                            'notIncluded' => [
                                'Tiket wahana permainan diluar paket',
                                'Tiket parkir kendaraan'
                            ],
                            'voucherInfo' => [
                                'Berlaku selama periode yang tertera di voucher'
                            ],
                            'voucherUsage' => [
                                'Voucher tersedia di menu "Your Orders"',
                                'Tidak perlu dicetak',
                                'Tunjukkan kode QR pada petugas'
                            ],
                            'termsConditions' => [
                                'Tiket yang sudah dibeli tidak dapat dijadwalkan ulang',
                                'Tiket yang dibeli tidak dapat dikembalikan/non-refundable',
                                'Penjualan tiket dapat dihentikan kapan saja',
                                'E-tiket tidak dapat ditukar dengan uang'
                            ]
                        ];
                    }
                }
            @endphp
            {!! json_encode($ticketDataArray) !!}
        </script>
        <script type="application/json" id="user-review-data">
            {!! json_encode($userReview) !!}
        </script>
    </div>

    <script>
        let currentReview = 0;
        const totalReviews = document.querySelectorAll('.review-card').length;
        
        // Gallery images data - using images from gallery page
        const galleryImages = [
            { src: '/images/galeri1.jpeg', alt: 'Taman Selecta - Pemandangan Utama' },
            { src: '/images/galeri2.jpeg', alt: 'Kolam Renang Selecta' },
            { src: '/images/galeri3.jpeg', alt: 'Wahana Permainan' },
            { src: '/images/galeri4.jpeg', alt: 'Taman Bunga Colorful' },
            { src: '/images/galeri5.jpeg', alt: 'Area Bermain Anak' },
            { src: '/images/galeri6.jpeg', alt: 'Flying Fox Adventure' },
            { src: '/images/galeri7.jpeg', alt: 'Waterpark Area' },
            { src: '/images/galeri8.jpeg', alt: 'Dino Ranch' },
            { src: '/images/galeri9.jpeg', alt: 'Food Court' },
            { src: '/images/galeri10.jpeg', alt: 'Spot Foto Instagramable' },
            { src: '/images/galeri11.jpeg', alt: 'Perahu Danau' },
            { src: '/images/galeri12.jpeg', alt: 'Taman Lumut' }
        ];
        
        let currentImageIndex = 0;

        // Gallery auto rotation - using gallery images
        let currentGallerySet = 0;
        const galleryImageSets = [
            ['/images/galeri1.jpeg', '/images/galeri2.jpeg', '/images/galeri3.jpeg'],
            ['/images/galeri4.jpeg', '/images/galeri5.jpeg', '/images/galeri6.jpeg'],
            ['/images/galeri7.jpeg', '/images/galeri8.jpeg', '/images/galeri9.jpeg'],
            ['/images/galeri10.jpeg', '/images/galeri11.jpeg', '/images/galeri12.jpeg']
        ];

        // Ticket data - use data from window object or fallback
        const ticketData = window.ticketDataFromServer || {
            reguler: {
                title: 'Tiket Reguler - Rp 50.000',
                includes: [
                    'Tiket masuk',
                    'Akses kolam renang',
                    'Waterpark',
                    'Kolam Ikan',
                    'Akuarium',
                    'Taman Bunga',
                    'Dino Ranch',
                    'Asuransi kecelakaan'
                ],
                notIncluded: [
                    'Tiket wahana permainan diluar paket',
                    'Tiket parkir kendaraan'
                ],
                voucherInfo: [
                    'Berlaku selama periode yang tertera di voucher'
                ],
                voucherUsage: [
                    'Voucher tersedia di menu "Your Orders"',
                    'Tidak perlu dicetak',
                    'Tunjukkan kode QR pada petugas'
                ],
                termsConditions: [
                    'Tiket yang sudah dibeli tidak dapat dijadwalkan ulang',
                    'Tiket yang dibeli tidak dapat dikembalikan/non-refundable',
                    'Penjualan tiket dapat dihentikan kapan saja',
                    'E-tiket tidak dapat ditukar dengan uang'
                ]
            },
            terusan: {
                title: 'Tiket Terusan - Rp 80.000',
                includes: [
                    '1x Tiket Masuk ke Taman Rekreasi Selecta untuk 1 Pengunjung',
                    '1x Tiket Masuk ke Dino Ranch untuk 1 Pengunjung',
                    '1x Tiket Masuk ke Bioskop 4D untuk 1 Pengunjung',
                    '1x Tiket Masuk ke Mobil Ayun untuk 1 Pengunjung',
                    '1x Tiket Masuk ke Mini Bumper Car untuk 1 Pengunjung',
                    '1x Tiket Masuk ke Paddle Boat untuk 1 Pengunjung',
                    '1x Akses ke Bianglala untuk 1 Pengunjung',
                    '1x Akses ke Dino Ride untuk 1 Pengunjung',
                    '1x Akses ke Sky Bike untuk 1 Pengunjung',
                    '1x Akses ke Garden Tram untuk 1 Pengunjung',
                    '1x Akses ke Kolam Renang untuk 1 Pengunjung',
                    '1x Akses ke Waterpark untuk 1 Pengunjung',
                    '1x Akses ke Kolam Ikan untuk 1 Pengunjung',
                    '1x Akses ke Taman Lumut untuk 1 Pengunjung',
                    '1x Akses ke Taman Bunga untuk 1 Pengunjung',
                    '1x Akses ke Tagada Disco untuk 1 Pengunjung'
                ],
                notIncluded: [
                    'Tiket wahana permainan diluar paket',
                    'Tiket parkir kendaraan'
                ],
                voucherInfo: [
                    'Berlaku selama periode yang tertera di voucher'
                ],
                voucherUsage: [
                    'Voucher tersedia di menu "Your Orders"',
                    'Tidak perlu dicetak',
                    'Tunjukkan kode QR pada petugas'
                ],
                termsConditions: [
                    'Tiket yang sudah dibeli tidak dapat dijadwalkan ulang',
                    'Tiket yang dibeli tidak dapat dikembalikan/non-refundable',
                    'Penjualan tiket dapat dihentikan kapan saja',
                    'E-tiket tidak dapat ditukar dengan uang'
                ]
            }
        };

        // Function to redirect to gallery page
        function goToGalleryPage() {
            // Replace with your actual gallery page URL
            window.location.href = 'galeri.html'; // or wherever your gallery page is located
        }

        function rotateGalleryImages() {
            currentGallerySet = (currentGallerySet + 1) % galleryImageSets.length;
            const currentSet = galleryImageSets[currentGallerySet];
            
            document.getElementById('galleryImg1').src = currentSet[0];
            document.getElementById('galleryImg2').src = currentSet[1];
            document.getElementById('galleryImg3').src = currentSet[2];
        }


        function nextReview() {
            const container = document.getElementById('reviewContainer');
            currentReview = (currentReview + 1) % totalReviews;
            container.style.transform = `translateX(-${currentReview * 100}%)`;
        }

        function prevReview() {
            const container = document.getElementById('reviewContainer');
            currentReview = (currentReview - 1 + totalReviews) % totalReviews;
            container.style.transform = `translateX(-${currentReview * 100}%)`;
        }

        // Modal Tab Functions
        function scrollToSection(sectionName) {
            document.querySelectorAll('.modal-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            event.target.classList.add('active');
            
            const sectionMap = {
                'info': 'infoSection',
                'voucher': 'voucherSection', 
                'terms': 'termsSection',
                'additional': 'additionalSection'
            };
            
            const targetSection = document.getElementById(sectionMap[sectionName]);
            if (targetSection) {
                targetSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Ticket Modal Functions
        function openTicketModal(ticketType) {
            const modal = document.getElementById('ticketModal');
            const title = document.getElementById('ticketModalTitle');
            const includesList = document.getElementById('ticketIncludes');
            const notIncludesList = document.getElementById('ticketNotIncludes');
            const voucherUsage = document.getElementById('voucherUsageList');
            const termsList = document.getElementById('termsConditionsList');
            const additionalList = document.getElementById('additionalInfoList');
            
            const data = ticketData[ticketType];
            
            title.textContent = data.title;
            
            includesList.innerHTML = data.includes.map(item => `
                <div class="feature-item">
                    <i class="fas fa-check feature-check"></i>
                    <span>${item}</span>
                </div>
            `).join('');
            
            notIncludesList.innerHTML = data.notIncluded.map(item => `
                <div class="feature-item">
                    <i class="fas fa-times feature-cross"></i>
                    <span>${item}</span>
                </div>
            `).join('');
            
            voucherUsage.innerHTML = data.voucherUsage.map(item => `<li>${item}</li>`).join('');
            termsList.innerHTML = data.termsConditions.map(item => `<li>${item}</li>`).join('');
            additionalList.innerHTML = data.voucherInfo.map(item => `<li>${item}</li>`).join('');
            
            document.querySelectorAll('.modal-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector('.modal-tab').classList.add('active');
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeTicketModal() {
            document.getElementById('ticketModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('ticketModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTicketModal();
            }
        });

        function openReviewModal() {
            document.getElementById('modalOverlay').style.display = 'block';
            setTimeout(() => {
                document.getElementById('reviewModal').classList.add('active');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.remove('active');
            setTimeout(() => {
                document.getElementById('modalOverlay').style.display = 'none';
            }, 300);
            document.body.style.overflow = 'auto';
        }

        // Gallery Functions
        function openGallery(imageIndex = 0) {
            currentImageIndex = imageIndex;
            const modal = document.getElementById('galleryModal');
            const img = document.getElementById('galleryImage');
            
            img.src = galleryImages[currentImageIndex].src;
            img.alt = galleryImages[currentImageIndex].alt;
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            const modal = document.getElementById('galleryModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            const img = document.getElementById('galleryImage');
            img.src = galleryImages[currentImageIndex].src;
            img.alt = galleryImages[currentImageIndex].alt;
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            const img = document.getElementById('galleryImage');
            img.src = galleryImages[currentImageIndex].src;
            img.alt = galleryImages[currentImageIndex].alt;
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('galleryModal');
            if (modal.classList.contains('active')) {
                if (e.key === 'ArrowRight') {
                    nextImage();
                } else if (e.key === 'ArrowLeft') {
                    prevImage();
                } else if (e.key === 'Escape') {
                    closeGallery();
                }
            }
            
            const ticketModal = document.getElementById('ticketModal');
            if (ticketModal.classList.contains('active') && e.key === 'Escape') {
                closeTicketModal();
            }
        });

        // Ticket booking functionality
        let ticketCount = 1;
        
        // Load server data from JSON scripts
        function loadServerData() {
            try {
                const packageMappingElement = document.getElementById('package-mapping-data');
                const ticketDataElement = document.getElementById('ticket-data');
                const userReviewElement = document.getElementById('user-review-data');
                
                if (packageMappingElement) {
                    window.packageMappingData = JSON.parse(packageMappingElement.textContent);
                }
                
                if (ticketDataElement) {
                    window.ticketDataFromServer = JSON.parse(ticketDataElement.textContent);
                }
                
                if (userReviewElement) {
                    currentUserReview = JSON.parse(userReviewElement.textContent);
                }
                
                console.log('Server data loaded:', {
                    packageMapping: window.packageMappingData,
                    ticketData: window.ticketDataFromServer,
                    userReview: currentUserReview
                });
            } catch (error) {
                console.error('Error loading server data:', error);
                // Fallback data
                window.packageMappingData = { reguler: 1, terusan: 2 };
                window.ticketDataFromServer = {};
                currentUserReview = null;
            }
        }
        
        // Load data on DOM ready
        document.addEventListener('DOMContentLoaded', loadServerData);
        
        // Package mapping - populated from server data
        const packageMapping = window.packageMappingData || {'reguler': 1, 'terusan': 2};
        
        function getPackageIdByType(ticketType) {
            // Use dynamic data if available, otherwise fallback
            const mapping = window.packageMappingData || packageMapping;
            return mapping[ticketType] || null;
        }

        function updateTicketDisplay() {
            const display = document.getElementById('ticketCountDisplay');
            display.textContent = `${ticketCount} tiket`;
            
            // Update decrease button state
            const decreaseBtn = document.getElementById('decreaseBtn');
            decreaseBtn.disabled = ticketCount <= 1;
        }

        function increaseTickets() {
            if (ticketCount < 100) {
                ticketCount++;
                updateTicketDisplay();
            }
        }

        function decreaseTickets() {
            if (ticketCount > 1) {
                ticketCount--;
                updateTicketDisplay();
            }
        }

        function searchTickets() {
            const ticketType = document.getElementById('ticketType').value;
            const visitDate = document.getElementById('visitDate').value;
            
            if (!visitDate) {
                showNotification('Silakan pilih tanggal kunjungan', 'warning');
                return;
            }

            // Check if user is authenticated
            if (!isUserAuthenticated) {
                showNotification('Anda perlu login untuk melakukan pemesanan.', 'warning');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
                return;
            }

            // Process booking directly without confirmation
            processBooking(ticketType, visitDate, ticketCount);
        }

        function processBooking(ticketType, visitDate, ticketCount) {
            // Show loading
            const searchBtn = document.querySelector('.search-btn');
            const originalText = searchBtn.textContent;
            searchBtn.textContent = 'Memproses...';
            searchBtn.disabled = true;

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Get package_id based on ticket type
            const packageId = getPackageIdByType(ticketType);
            if (!packageId) {
                showNotification('Paket tiket tidak ditemukan', 'error');
                searchBtn.textContent = originalText;
                searchBtn.disabled = false;
                return;
            }

            // Make booking request
            fetch('/ticket/book', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    package_id: packageId,
                    visit_date: visitDate,
                    quantity: ticketCount,
                    booker_name: document.querySelector('meta[name="user-name"]').content,
                    booker_email: document.querySelector('meta[name="user-email"]').content,
                    booker_phone: document.querySelector('meta[name="user-phone"]').content || ''
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Proceed to payment
                    initiatePayment(data.booking_id);
                } else {
                    showNotification(data.message || 'Terjadi kesalahan saat membuat booking', 'error');
                }
            })
            .catch(error => {
                console.error('Booking error:', error);
                showNotification('Terjadi kesalahan saat membuat booking', 'error');
            })
            .finally(() => {
                // Reset button
                searchBtn.textContent = originalText;
                searchBtn.disabled = false;
            });
        }

        function initiatePayment(bookingId) {
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    booking_id: bookingId,
                    booking_type: 'ticket'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    // Open Midtrans Snap
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            showNotification('Pembayaran berhasil!', 'success');
                            setTimeout(() => {
                                window.location.href = `/payment/success/${bookingId}`;
                            }, 1500);
                        },
                        onPending: function(result) {
                            showNotification('Pembayaran pending. Silakan selesaikan pembayaran Anda.', 'warning');
                        },
                        onError: function(result) {
                            showNotification('Pembayaran gagal. Silakan coba lagi.', 'error');
                        },
                        onClose: function() {
                            showNotification('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran', 'warning');
                        }
                    });
                } else {
                    showNotification(data.message || 'Terjadi kesalahan saat memproses pembayaran', 'error');
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                showNotification('Terjadi kesalahan saat memproses pembayaran', 'error');
            });
        }

        // Review System Variables
        let isEditMode = false;
        let currentUserReview = null;

        // Notification System
        function showNotification(message, type = 'info', duration = 5000) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => {
                notification.remove();
            });

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <button class="close-notification" onclick="this.parentElement.remove()">&times;</button>
                <div>${message}</div>
            `;

            // Add to body
            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Auto remove after duration
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, duration);
        }

        // Review Modal Functions
        function openAddReviewModal() {
            isEditMode = false;
            document.getElementById('reviewFormTitle').textContent = 'Tulis Review';
            document.getElementById('submitReviewBtn').innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Review';
            
            // Reset form
            document.getElementById('reviewForm').reset();
            document.getElementById('ratingInput').value = '5';
            document.getElementById('charCount').textContent = '0';
            resetStarRating();
            setStarRating(5);
            hideImagePreview();
            
            document.getElementById('reviewFormOverlay').style.display = 'block';
            setTimeout(() => {
                document.getElementById('reviewFormModal').classList.add('active');
            }, 10);
        }

        function openEditReviewModal() {
            if (!currentUserReview) return;
            
            isEditMode = true;
            document.getElementById('reviewFormTitle').textContent = 'Edit Review';
            document.getElementById('submitReviewBtn').innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
            
            // Fill form with existing data
            document.getElementById('commentInput').value = currentUserReview.comment;
            document.getElementById('ratingInput').value = currentUserReview.rating;
            document.getElementById('charCount').textContent = currentUserReview.comment.length;
            setStarRating(currentUserReview.rating);
            
            // Handle existing image
            if (currentUserReview.image_url) {
                showImagePreview(currentUserReview.image_url);
            } else {
                hideImagePreview();
            }
            
            document.getElementById('reviewFormOverlay').style.display = 'block';
            setTimeout(() => {
                document.getElementById('reviewFormModal').classList.add('active');
            }, 10);
        }

        function closeReviewFormModal() {
            document.getElementById('reviewFormModal').classList.remove('active');
            setTimeout(() => {
                document.getElementById('reviewFormOverlay').style.display = 'none';
            }, 300);
        }

        function showLoginAlert() {
            showNotification('Silakan login terlebih dahulu untuk memberikan review.', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        }

        // Star Rating Functions
        function setStarRating(rating) {
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            document.getElementById('ratingInput').value = rating;
        }

        function resetStarRating() {
            document.querySelectorAll('.star').forEach(star => {
                star.classList.remove('active');
            });
        }

        // Image Upload Functions
        function showImagePreview(src) {
            document.querySelector('.upload-placeholder').style.display = 'none';
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('previewImg').src = src;
        }

        function hideImagePreview() {
            document.querySelector('.upload-placeholder').style.display = 'block';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('previewImg').src = '';
            document.getElementById('imageInput').value = '';
        }

        function removeImage() {
            hideImagePreview();
        }

        // Delete User Review
        function deleteUserReview() {
            const deleteBtn = document.querySelector('.btn-delete-user-review');
            
            // If already in confirm state, proceed with deletion
            if (deleteBtn.classList.contains('confirm-delete')) {
                // Proceed with actual deletion
                proceedWithDeletion();
                return;
            }
            
            // First click - show confirmation
            showNotification('Klik tombol hapus sekali lagi untuk konfirmasi', 'warning', 3000);
            deleteBtn.classList.add('confirm-delete');
            deleteBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus';
            
            setTimeout(() => {
                deleteBtn.classList.remove('confirm-delete');
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Hapus';
            }, 3000);
        }

        function proceedWithDeletion() {

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/ticket/reviews', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        location.reload(); // Reload to update UI
                    }, 1500);
                } else {
                    showNotification(data.message || 'Terjadi kesalahan saat menghapus review', 'error');
                }
            })
            .catch(error => {
                console.error('Delete review error:', error);
                showNotification('Terjadi kesalahan saat menghapus review', 'error');
            });
        }

        // Initialize the page when DOM loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load server data first
            loadServerData();
            
            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowString = tomorrow.toISOString().split('T')[0];
            document.getElementById('visitDate').min = tomorrowString;
            document.getElementById('visitDate').value = tomorrowString;
            
            // Initialize ticket counter display
            updateTicketDisplay();

            // Initialize star rating
            document.querySelectorAll('.star').forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    setStarRating(rating);
                });

                star.addEventListener('mouseover', function() {
                    const rating = parseInt(this.dataset.rating);
                    const stars = document.querySelectorAll('.star');
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.style.color = '#fbbf24';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });

            // Reset star colors on mouse leave
            document.getElementById('starRating').addEventListener('mouseleave', function() {
                const currentRating = parseInt(document.getElementById('ratingInput').value);
                setStarRating(currentRating);
            });

            // Character counter for comment
            document.getElementById('commentInput').addEventListener('input', function() {
                document.getElementById('charCount').textContent = this.value.length;
            });

            // Image upload preview
            document.getElementById('imageInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showNotification('Ukuran file terlalu besar. Maksimal 2MB.', 'warning');
                        this.value = '';
                        return;
                    }

                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        showNotification('File harus berupa gambar.', 'warning');
                        this.value = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        showImagePreview(e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Review form submission
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submitReviewBtn');
                const originalText = submitBtn.innerHTML;
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

                const formData = new FormData(this);
                const url = isEditMode ? '/ticket/reviews' : '/ticket/reviews';
                const method = isEditMode ? 'PUT' : 'POST';

                // Convert FormData to regular form data for PUT request
                if (isEditMode) {
                    const regularData = new FormData();
                    regularData.append('_token', formData.get('_token'));
                    regularData.append('_method', 'PUT');
                    regularData.append('rating', formData.get('rating'));
                    regularData.append('comment', formData.get('comment'));
                    if (formData.get('image')) {
                        regularData.append('image', formData.get('image'));
                    }
                    formData = regularData;
                }

                fetch(url, {
                    method: 'POST', // Always POST for FormData with _method
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        closeReviewFormModal();
                        setTimeout(() => {
                            location.reload(); // Reload to update UI
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Terjadi kesalahan saat menyimpan review', 'error');
                    }
                })
                .catch(error => {
                    console.error('Review submission error:', error);
                    showNotification('Terjadi kesalahan saat menyimpan review', 'error');
                })
                .finally(() => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        });
    </script>
</body>
</html>