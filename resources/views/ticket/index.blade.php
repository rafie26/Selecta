<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecta - Tiket Wisata</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Midtrans Snap -->
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Authentication Status -->
    <meta name="auth-status" content="{{ auth()->check() ? '1' : '0' }}">
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
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            margin: 0;
        }

        .main-container {
            width: 100%;
            min-height: calc(100vh - 120px);
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            position: relative;
        }

        .hero-section {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.9) 0%, rgba(30, 64, 175, 0.9) 100%), url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 24px;
            color: white;
        }

        .hero-content {
            text-align: center;
            z-index: 10;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 900;
            margin-bottom: 16px;
            color: white;
            letter-spacing: -2px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 20px;
            font-weight: 400;
            margin-bottom: 24px;
            color: rgba(255,255,255,0.9);
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .hero-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            font-size: 16px;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }

        .hero-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .star {
            color: #fbbf24;
        }

        .content {
            padding: 60px 40px;
            background: white;
            max-width: 1400px;
            margin: -50px auto 0;
            border-radius: 30px 30px 0 0;
            position: relative;
            z-index: 5;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #0f172a;
            text-align: center;
        }

        .date-section {
            margin-bottom: 40px;
        }

        .date-navigation {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 24px;
            margin-bottom: 32px;
        }

        .nav-button {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 22px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .nav-button:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .nav-button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .month-year-display {
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
            min-width: 180px;
            text-align: center;
        }

        .date-picker-container {
            position: relative;
            overflow: hidden;
            padding: 0 20px;
        }

        .date-picker {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .date-picker::-webkit-scrollbar {
            display: none;
        }

        .date-item {
            min-width: 90px;
            padding: 20px 16px;
            background: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
            position: relative;
        }

        .date-item:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .date-item.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }

        .date-item.past {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .date-item.past:hover {
            background: #f1f5f9;
            transform: none;
        }

        .date-day {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .date-item.active .date-day {
            color: rgba(255,255,255,0.8);
        }

        .date-item.past .date-day {
            color: #cbd5e1;
        }

        .date-number {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .date-item.active .date-number {
            color: white;
        }

        .date-item.past .date-number {
            color: #cbd5e1;
        }

        .date-month {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 500;
        }

        .date-item.active .date-month {
            color: rgba(255,255,255,0.7);
        }

        .date-item.past .date-month {
            color: #cbd5e1;
        }

        .packages-section {
            margin-bottom: 40px;
        }

        .package-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .package-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            padding: 32px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .package-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #3b82f6, #1e40af);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .package-card:hover {
            border-color: #3b82f6;
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(59, 130, 246, 0.2);
        }

        .package-card:hover::before {
            transform: scaleX(1);
        }

        .package-card.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(30, 64, 175, 0.1) 100%);
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.25);
        }

        .package-card.selected::before {
            transform: scaleX(1);
        }

        .package-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .package-badge {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .package-badge.popular {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .package-badge.best {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .package-badge.exclusive {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .package-name {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .package-desc {
            font-size: 16px;
            color: #475569;
            margin-bottom: 24px;
            line-height: 1.6;
            background: #f8fafc;
            padding: 16px 20px;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
        }

        .package-features {
            margin-bottom: 24px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 15px;
            color: #374151;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .feature-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .feature-icon {
            color: #3b82f6;
            background: #eff6ff;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .package-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .package-price {
            font-size: 26px;
            font-weight: 800;
            color: #3b82f6;
        }

        .price-per {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f1f5f9;
            border-radius: 12px;
            padding: 8px;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: #3b82f6;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            border-color: #3b82f6;
        }

        .qty-display {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            min-width: 30px;
            text-align: center;
        }

        .summary-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 32px;
            margin: 40px auto;
            display: none;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .summary-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0f172a;
            text-align: center;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 16px;
            padding: 8px 0;
        }

        .summary-total {
            border-top: 2px solid #3b82f6;
            padding-top: 20px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: 700;
            color: #3b82f6;
        }

        .continue-button {
            display: block;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 22px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .continue-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 45px rgba(59, 130, 246, 0.5);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .continue-button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Form Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(10px);
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .form-container {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }

        .form-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            padding: 32px;
            color: white;
            border-radius: 24px 24px 0 0;
            text-align: center;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            background: rgba(255,255,255,0.3);
        }

        .form-body {
            padding: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        .form-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
        }

        .form-section h4 {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .visitor-section {
            grid-column: 1 / -1;
        }

        .visitor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .visitor-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
        }

        .visitor-header {
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            text-align: center;
            font-size: 16px;
        }

        .visitor-inputs {
            display: grid;
            gap: 12px;
        }

        .visitor-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 12px;
        }

        .payment-section {
            grid-column: 1 / -1;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .payment-method {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .payment-method:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .payment-method.selected {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
        }

        .payment-icon {
            width: 60px;
            height: 40px;
            background: #3b82f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .submit-button {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 24px;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        /* Ticket Modal */
        .ticket-modal {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            position: relative;
        }

        .ticket-card {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
            opacity: 0.4;
        }

        .ticket-header {
            position: relative;
            z-index: 10;
            padding: 40px 32px;
            color: white;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .ticket-venue {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .ticket-date {
            font-size: 18px;
            opacity: 0.95;
            font-weight: 500;
        }

        .ticket-body {
            background: white;
            padding: 40px 32px;
            position: relative;
        }

        .ticket-body::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 30px;
            right: 30px;
            height: 30px;
            background: white;
            border-radius: 0 0 25px 25px;
        }

        .ticket-body::after {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            height: 40px;
            background: radial-gradient(circle at 20px 20px, transparent 20px, white 20px),
                        radial-gradient(circle at calc(100% - 20px) 20px, transparent 20px, white 20px);
        }

        .barcode-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .barcode {
            width: 100%;
            max-width: 350px;
            height: 90px;
            background: repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 3px,
                transparent 3px,
                transparent 6px,
                #000 6px,
                #000 9px,
                transparent 9px,
                transparent 12px,
                #000 12px,
                #000 15px,
                transparent 15px,
                transparent 18px
            );
            border-radius: 10px;
            margin: 0 auto 20px;
            border: 2px solid #e5e7eb;
        }

        .ticket-code {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            font-weight: bold;
            color: #374151;
            letter-spacing: 2px;
            background: #f3f4f6;
            padding: 12px 20px;
            border-radius: 10px;
            display: inline-block;
        }

        .ticket-details {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }

        .detail-grid {
            display: grid;
            gap: 16px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: 600;
            color: #3b82f6;
            font-size: 18px;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
            font-size: 15px;
        }

        .detail-value {
            color: #0f172a;
            font-weight: 600;
            font-size: 15px;
        }

        .download-section {
            text-align: center;
            padding-top: 24px;
        }

        .download-btn {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }
            
            .content {
                padding: 40px 20px;
                margin: -30px auto 0;
                border-radius: 20px 20px 0 0;
            }

            .hero-title {
                font-size: 42px;
            }

            .hero-subtitle {
                font-size: 18px;
            }

            .hero-info {
                flex-direction: column;
                gap: 16px;
            }

            .hero-section {
                height: 350px;
                padding: 40px 20px;
            }

            .section-title {
                font-size: 24px;
            }

            .package-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-body {
                padding: 24px;
            }

            .payment-grid {
                grid-template-columns: 1fr;
            }

            .visitor-grid {
                grid-template-columns: 1fr;
            }

            .date-picker {
                justify-content: flex-start;
                padding: 0;
            }

            .date-item {
                min-width: 80px;
                padding: 16px 12px;
            }

            .date-navigation {
                gap: 16px;
            }

            .nav-button {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }

            .month-year-display {
                font-size: 18px;
                min-width: 150px;
            }

            .ticket-modal {
                max-width: 420px;
                margin: 20px;
            }

            .ticket-venue {
                font-size: 28px;
            }

            .ticket-header {
                padding: 32px 24px;
            }

            .ticket-body {
                padding: 32px 24px;
            }
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .package-card {
            animation: slideUp 0.6s ease forwards;
        }

        .package-card:nth-child(2) { animation-delay: 0.1s; }
        .package-card:nth-child(3) { animation-delay: 0.2s; }
        .package-card:nth-child(4) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <!-- Include Navbar Component -->
    @include('components.navbar')

    <div class="main-container">
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Selecta</h1>
                <p class="hero-subtitle">Nikmati Keindahan Alam Batu, Malang</p>
                <div class="hero-info">
                    <div class="hero-info-item">
                        <span class="star">⭐</span>
                        <span>4.8 Rating</span>
                    </div>
                    <div class="hero-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Batu, Malang</span>
                    </div>
                    <div class="hero-info-item">
                        <i class="fas fa-clock"></i>
                        <span>08:00 - 17:00 WIB</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="date-section">
                <h2 class="section-title">Pilih Tanggal Kunjungan</h2>
                
                <div class="date-navigation">
                    <button class="nav-button" id="prevMonth" onclick="changeMonth(-1)">‹</button>
                    <div class="month-year-display" id="monthYearDisplay">Agustus 2024</div>
                    <button class="nav-button" id="nextMonth" onclick="changeMonth(1)">›</button>
                </div>
                
                <div class="date-picker-container">
                    <div class="date-picker" id="datePicker">
                        <!-- Dates will be generated dynamically -->
                    </div>
                </div>
            </div>

            <div class="packages-section">
                <h2 class="section-title">Pilih Paket Wisata</h2>
                
                <div class="package-grid">
                    @if(isset($packages) && $packages->count() > 0)
                        @foreach($packages as $package)
                        <div class="package-card" data-package-id="{{ $package->id }}">
                            <div class="package-header">
                                <h3>{{ $package->name }}</h3>
                                @if($package->badge)
                                    <span class="package-badge">{{ $package->badge }}</span>
                                @endif
                            </div>
                            <div class="package-body">
                                <div class="package-desc">{{ $package->description }}</div>
                                @if($package->features)
                                <div class="package-features">
                                    @foreach($package->features as $feature)
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>{{ $feature }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="package-footer">
                                <div>
                                    <div class="package-price">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                    <div class="price-per">per orang</div>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn qty-minus" data-package-id="{{ $package->id }}">-</button>
                                    <span class="qty-display" id="qty-{{ $package->id }}">0</span>
                                    <button class="qty-btn qty-plus" data-package-id="{{ $package->id }}">+</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Fallback static packages if database is empty -->
                        <div class="package-card" data-package-id="1">
                            <div class="package-header">
                                <h3>Paket Reguler</h3>
                            </div>
                            <div class="package-body">
                                <div class="package-desc">Paket tiket masuk reguler untuk menikmati semua wahana dan fasilitas</div>
                                <div class="package-features">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Akses ke semua wahana</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Fasilitas parkir</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Area bermain anak</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Spot foto menarik</span>
                                    </div>
                                </div>
                            </div>
                            <div class="package-footer">
                                <div>
                                    <div class="package-price">Rp 50.000</div>
                                    <div class="price-per">per orang</div>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn qty-minus" data-package-id="1">-</button>
                                    <span class="qty-display" id="qty-1">0</span>
                                    <button class="qty-btn qty-plus" data-package-id="1">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="package-card" data-package-id="2">
                            <div class="package-header">
                                <h3>Paket Premium</h3>
                                <span class="package-badge">Popular</span>
                            </div>
                            <div class="package-body">
                                <div class="package-desc">Paket premium dengan fasilitas tambahan dan prioritas akses</div>
                                <div class="package-features">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Akses ke semua wahana</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Fasilitas parkir VIP</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Area bermain anak</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Spot foto menarik</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Prioritas akses wahana</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Welcome drink</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Souvenir eksklusif</span>
                                    </div>
                                </div>
                            </div>
                            <div class="package-footer">
                                <div>
                                    <div class="package-price">Rp 75.000</div>
                                    <div class="price-per">per orang</div>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn qty-minus" data-package-id="2">-</button>
                                    <span class="qty-display" id="qty-2">0</span>
                                    <button class="qty-btn qty-plus" data-package-id="2">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="package-card" data-package-id="3">
                            <div class="package-header">
                                <h3>Paket Family</h3>
                                <span class="package-badge">Best Value</span>
                            </div>
                            <div class="package-body">
                                <div class="package-desc">Paket khusus untuk keluarga dengan harga spesial</div>
                                <div class="package-features">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Akses untuk 4 orang</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Fasilitas parkir</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Area bermain anak</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Spot foto menarik</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Makan siang keluarga</span>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span>Foto keluarga gratis</span>
                                    </div>
                                </div>
                            </div>
                            <div class="package-footer">
                                <div>
                                    <div class="package-price">Rp 180.000</div>
                                    <div class="price-per">4 orang</div>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn qty-minus" data-package-id="3">-</button>
                                    <span class="qty-display" id="qty-3">0</span>
                                    <button class="qty-btn qty-plus" data-package-id="3">+</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="summary-section" id="summarySection">
                <div class="summary-title">Ringkasan Pesanan</div>
                <div id="summaryItems"></div>
                <div class="summary-total">
                    <span>Total Pembayaran:</span>
                    <span id="totalAmount">Rp 0</span>
                </div>
            </div>

            <button class="continue-button" id="continueButton" onclick="openBookingForm()" disabled>
                Pilih Paket Dulu
            </button>
        </div>
    </div>

    <!-- Booking Form Modal -->
    <div class="modal" id="bookingModal">
        <div class="form-container">
            <div class="form-header">
                <button class="close-modal" onclick="closeBookingForm()">&times;</button>
                <h3 style="font-size: 24px; margin-bottom: 8px;">Data Pemesanan</h3>
                <p style="opacity: 0.9;">Lengkapi data untuk melanjutkan pemesanan</p>
            </div>
            
            <div class="form-body">
                <div class="form-grid">
                    <div class="form-section">
                        <h4>Data Pemesan</h4>
                        @auth
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-input" id="bookerName" value="{{ auth()->user()->name }}" placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" id="bookerEmail" value="{{ auth()->user()->email }}" placeholder="Masukkan email">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-input" id="bookerPhone" value="{{ auth()->user()->phone ?? '' }}" placeholder="Masukkan nomor telepon">
                            </div>
                        @else
                            <div style="text-align: center; padding: 20px; background: #fef3c7; border-radius: 12px; border: 1px solid #f59e0b;">
                                <div style="font-size: 18px; font-weight: 600; color: #92400e; margin-bottom: 8px;">
                                    🔐 Login Diperlukan
                                </div>
                                <div style="color: #b45309; margin-bottom: 16px;">
                                    Silakan login terlebih dahulu untuk melakukan pemesanan
                                </div>
                                <a href="/login" style="background: #f59e0b; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                    Login Sekarang
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="form-section">
                        <h4>Ringkasan Pesanan</h4>
                        <div id="modalSummaryItems" style="margin-bottom: 20px;"></div>
                        <div style="border-top: 2px solid #3b82f6; padding-top: 16px;">
                            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: #3b82f6;">
                                <span>Total:</span>
                                <span id="modalTotalAmount">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section visitor-section" id="visitorSection">
                    <h4>Data Pengunjung</h4>
                    <div class="visitor-grid" id="visitorList">
                        <!-- Visitor forms will be generated here -->
                    </div>
                </div>

                <div class="form-section payment-section">
                    <h4>Metode Pembayaran</h4>
                    <div style="text-align: center; padding: 20px;">
                        <div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 20px; border-radius: 16px; margin-bottom: 16px;">
                            <div style="font-size: 24px; font-weight: bold; margin-bottom: 8px;">💳 Midtrans Payment</div>
                            <div style="font-size: 16px; opacity: 0.9;">Pembayaran aman dengan berbagai metode</div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px; margin-top: 16px;">
                            <div style="background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                                <div style="font-size: 20px; margin-bottom: 4px;">💳</div>
                                <div style="font-size: 12px; color: #64748b;">Credit Card</div>
                            </div>
                            <div style="background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                                <div style="font-size: 20px; margin-bottom: 4px;">🏦</div>
                                <div style="font-size: 12px; color: #64748b;">Bank Transfer</div>
                            </div>
                            <div style="background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                                <div style="font-size: 20px; margin-bottom: 4px;">📱</div>
                                <div style="font-size: 12px; color: #64748b;">E-Wallet</div>
                            </div>
                            <div style="background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                                <div style="font-size: 20px; margin-bottom: 4px;">🏪</div>
                                <div style="font-size: 12px; color: #64748b;">Convenience Store</div>
                            </div>
                        </div>
                    </div>
                </div>

                @auth
                    <button class="submit-button" onclick="processPayment()">
                        🎫 Bayar Sekarang
                    </button>
                @else
                    <a href="/login" class="submit-button" style="text-decoration: none; display: block; text-align: center;">
                        🔐 Login untuk Memesan
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Ticket Modal -->
    <div class="modal" id="ticketModal">
        <div class="ticket-modal">
            <div class="ticket-card">
                <div class="ticket-header">
                    <button class="close-modal" onclick="closeTicketModal()">&times;</button>
                    <div class="ticket-venue">Selecta</div>
                    <div class="ticket-date" id="ticketModalDate">19 Agustus 2024</div>
                </div>
            </div>
            
            <div class="ticket-body">
                <div class="barcode-section">
                    <div class="barcode"></div>
                    <div class="ticket-code" id="ticketCode">SEL240819001234</div>
                </div>

                <div class="ticket-details">
                    <div class="detail-grid" id="ticketDetailsContainer">
                        <!-- Details will be populated dynamically -->
                    </div>
                </div>

                <p style="font-size: 14px; color: #6b7280; margin: 20px 0; line-height: 1.6; text-align: center;">
                    📱 Tunjukkan barcode ini di pintu masuk Selecta<br>
                    💾 Screenshot atau simpan tiket untuk akses mudah
                </p>

                <div class="download-section">
                    <button class="download-btn" onclick="downloadTicket()">
                        <span>📱</span>
                        <span>Simpan ke Galeri</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Data for JavaScript -->
    <script type="application/json" id="package-data">
        @if(isset($packages) && $packages->count() > 0)
        {
            "prices": @json($packages->pluck('price', 'id')),
            "names": @json($packages->pluck('name', 'id'))
        }
        @else
        {
            "prices": {"1": 50000, "2": 75000, "3": 180000},
            "names": {"1": "Paket Reguler", "2": "Paket Premium", "3": "Paket Family"}
        }
        @endif
    </script>

    <script>
        let selectedPackages = {};
        let packagePrices = {};
        let packageNames = {};
        
        // Load package data from JSON script
        try {
            const packageData = JSON.parse(document.getElementById('package-data').textContent);
            packagePrices = packageData.prices;
            packageNames = packageData.names;
        } catch (e) {
            console.error('Failed to load package data:', e);
            // Fallback data
            packagePrices = {1: 50000, 2: 75000, 3: 180000};
            packageNames = {1: 'Paket Reguler', 2: 'Paket Premium', 3: 'Paket Family'};
        }
        let selectedDate = null;
        let selectedPayment = 'gopay';
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        function initializeDatePicker() {
            // Set default to current month
            currentMonth = new Date().getMonth();
            currentYear = new Date().getFullYear();
            
            // Auto-select tomorrow if today is available, otherwise select first available date
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            generateDatePicker();
        }

        function generateDatePicker() {
            const datePicker = document.getElementById('datePicker');
            const monthYearDisplay = document.getElementById('monthYearDisplay');
            
            // Update month/year display
            monthYearDisplay.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            // Calculate dates to show (30 days from current month)
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            let datesHTML = '';
            
            // Generate dates for current month (starting from tomorrow)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            
            for (let day = 1; day <= lastDay.getDate(); day++) {
                const currentDate = new Date(currentYear, currentMonth, day);
                const dayName = dayNames[currentDate.getDay()];
                
                // Skip dates before tomorrow (today and past dates)
                if (currentDate < tomorrow) {
                    continue;
                }
                
                const dateString = `${day} ${monthNames[currentMonth]} ${currentYear}`;
                
                datesHTML += `
                    <div class="date-item" 
                         onclick="selectDate('${dateString}', this)"
                         data-date="${dateString}">
                        <div class="date-day">${dayName}</div>
                        <div class="date-number">${day}</div>
                        <div class="date-month">${monthNames[currentMonth].slice(0, 3)}</div>
                    </div>
                `;
            }
            
            datePicker.innerHTML = datesHTML;
            
            // Auto-select first available date (tomorrow)
            if (tomorrow.getMonth() === currentMonth && tomorrow.getFullYear() === currentYear) {
                const tomorrowString = `${tomorrow.getDate()} ${monthNames[currentMonth]} ${currentYear}`;
                const tomorrowElement = document.querySelector(`[data-date="${tomorrowString}"]`);
                if (tomorrowElement) {
                    selectDate(tomorrowString, tomorrowElement);
                }
            } else {
                // Select first available date if tomorrow is not in current month
                const firstAvailable = document.querySelector('.date-item');
                if (firstAvailable) {
                    const dateString = firstAvailable.getAttribute('data-date');
                    selectDate(dateString, firstAvailable);
                }
            }
            
            updateNavigationButtons();
        }

        function selectDate(dateString, element) {
            // Remove active class from all dates
            document.querySelectorAll('.date-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to selected date
            element.classList.add('active');
            selectedDate = dateString;
            
            // Scroll selected date into view
            element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }

        function changeMonth(direction) {
            currentMonth += direction;
            
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            } else if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            
            generateDatePicker();
        }

        function updateNavigationButtons() {
            const prevButton = document.getElementById('prevMonth');
            const today = new Date();
            
            // Disable previous button if we're at current month/year or earlier
            if (currentYear < today.getFullYear() || 
                (currentYear === today.getFullYear() && currentMonth <= today.getMonth())) {
                prevButton.disabled = true;
            } else {
                prevButton.disabled = false;
            }
            
            // Limit to 12 months in the future
            const nextButton = document.getElementById('nextMonth');
            const maxMonth = today.getMonth();
            const maxYear = today.getFullYear() + 1;
            
            if (currentYear > maxYear || 
                (currentYear === maxYear && currentMonth >= maxMonth)) {
                nextButton.disabled = true;
            } else {
                nextButton.disabled = false;
            }
        }

        // Initialize calendar (auto-selects next day)
        initializeDatePicker();

        // Event delegation for package cards and quantity buttons
        document.addEventListener('click', function(e) {
            const plusBtn = e.target.closest('.qty-plus');
            const minusBtn = e.target.closest('.qty-minus');
            const card = e.target.closest('.package-card');

            if (plusBtn) {
                e.stopPropagation();
                const packageId = plusBtn.dataset.packageId;
                changeQty(packageId, 1);
                return;
            }

            if (minusBtn) {
                e.stopPropagation();
                const packageId = minusBtn.dataset.packageId;
                changeQty(packageId, -1);
                return;
            }

            if (card && !e.target.closest('.quantity-controls')) {
                const packageId = card.dataset.packageId;
                changeQty(packageId, 1);
            }
        });
        
        // Package selection handled through quantity controls
        function selectPackage(packageId) {
            // Package selection handled through quantity controls
        }

        function changeQty(packageId, change) {
            const qtyElement = document.getElementById(`qty-${packageId}`);
            let currentQty = parseInt(qtyElement.textContent);
            let newQty = Math.max(0, currentQty + change);
            
            qtyElement.textContent = newQty;
            
            if (newQty > 0) {
                selectedPackages[packageId] = {
                    quantity: newQty,
                    price: packagePrices[packageId],
                    name: packageNames[packageId]
                };
                document.querySelector(`[data-package-id="${packageId}"]`).classList.add('selected');
            } else {
                delete selectedPackages[packageId];
                document.querySelector(`[data-package-id="${packageId}"]`).classList.remove('selected');
            }
            
            updateSummary();
        }

        function updateSummary() {
            let subtotal = 0;
            let totalQty = 0;
            let summaryHTML = '';
            
            Object.entries(selectedPackages).forEach(([id, pkg]) => {
                subtotal += pkg.price * pkg.quantity;
                totalQty += pkg.quantity;
                summaryHTML += `
                    <div class="summary-item">
                        <span>${pkg.quantity}x ${pkg.name}</span>
                        <span>Rp ${(pkg.price * pkg.quantity).toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
            
            const summarySection = document.getElementById('summarySection');
            const continueButton = document.getElementById('continueButton');
            
            if (totalQty > 0) {
                summarySection.style.display = 'block';
                document.getElementById('summaryItems').innerHTML = summaryHTML;
                document.getElementById('totalAmount').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
                
                continueButton.disabled = false;
                continueButton.textContent = `🎫 Lanjut Pemesanan (${totalQty} tiket)`;
            } else {
                summarySection.style.display = 'none';
                continueButton.disabled = true;
                continueButton.textContent = 'Pilih Paket Dulu';
            }
        }

        function openBookingForm() {
            if (Object.keys(selectedPackages).length === 0) {
                alert('Pilih paket dulu ya!');
                return;
            }
            
            if (!selectedDate) {
                alert('Pilih tanggal kunjungan dulu ya!');
                return;
            }

            generateVisitorForm();
            updateModalSummary();
            
            const modal = document.getElementById('bookingModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingForm() {
            const modal = document.getElementById('bookingModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function generateVisitorForm() {
            let totalVisitors = 0;
            Object.values(selectedPackages).forEach(pkg => {
                if (pkg.name === 'Family Bundle') {
                    totalVisitors += pkg.quantity * 4; // 4 people per bundle
                } else {
                    totalVisitors += pkg.quantity;
                }
            });

            let visitorHTML = '';
            for (let i = 1; i <= totalVisitors; i++) {
                visitorHTML += `
                    <div class="visitor-item">
                        <div class="visitor-header">Pengunjung ${i}</div>
                        <div class="visitor-inputs">
                            <input type="text" class="form-input" placeholder="Nama lengkap" id="visitor_${i}_name" required>
                            <div class="visitor-row">
                                <input type="number" class="form-input" placeholder="Umur" id="visitor_${i}_age" required>
                                <select class="form-input" id="visitor_${i}_gender" required>
                                    <option value="">Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
            }

            document.getElementById('visitorList').innerHTML = visitorHTML;
        }

        function updateModalSummary() {
            let subtotal = 0;
            let summaryHTML = '';
            
            Object.entries(selectedPackages).forEach(([id, pkg]) => {
                subtotal += pkg.price * pkg.quantity;
                summaryHTML += `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-size: 14px;">${pkg.quantity}x ${pkg.name}</span>
                        <span style="font-weight: 600;">Rp ${(pkg.price * pkg.quantity).toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
            
            document.getElementById('modalSummaryItems').innerHTML = summaryHTML;
            document.getElementById('modalTotalAmount').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        }

        function selectPayment(method) {
            selectedPayment = method;
            document.querySelectorAll('.payment-method').forEach(pm => pm.classList.remove('selected'));
            document.querySelector(`[data-payment="${method}"]`).classList.add('selected');
        }

        function processPayment() {
            // Check if user is authenticated
            if (!isUserAuthenticated) {
                alert('Silakan login terlebih dahulu untuk melakukan pemesanan');
                window.location.href = '/login';
                return;
            }

            // Validate form
            const bookerName = document.getElementById('bookerName').value;
            const bookerEmail = document.getElementById('bookerEmail').value;
            const bookerPhone = document.getElementById('bookerPhone').value;

            if (!bookerName || !bookerEmail || !bookerPhone) {
                alert('Lengkapi data pemesan terlebih dahulu!');
                return;
            }

            // Validate visitors
            let totalVisitors = 0;
            Object.values(selectedPackages).forEach(pkg => {
                if (pkg.name === 'Family Bundle') {
                    totalVisitors += pkg.quantity * 4;
                } else {
                    totalVisitors += pkg.quantity;
                }
            });

            for (let i = 1; i <= totalVisitors; i++) {
                const name = document.getElementById(`visitor_${i}_name`).value;
                const age = document.getElementById(`visitor_${i}_age`).value;

                if (!name || !age) {
                    alert(`Lengkapi data pengunjung ${i}!`);
                    return;
                }
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('visit_date', selectedDate);
            
            // Add packages
            Object.entries(selectedPackages).forEach(([id, pkg]) => {
                formData.append(`packages[${id}]`, pkg.quantity);
            });

            // Add visitors
            for (let i = 1; i <= totalVisitors; i++) {
                const name = document.getElementById(`visitor_${i}_name`).value;
                const age = document.getElementById(`visitor_${i}_age`).value;
                
                formData.append(`visitors[${i-1}][name]`, name);
                formData.append(`visitors[${i-1}][age_category]`, age);
            }

            // Show loading
            const submitButton = document.querySelector('.submit-button');
            const originalText = submitButton.textContent;
            
            submitButton.textContent = '⏳ Memproses Pembayaran...';
            submitButton.disabled = true;

            // Send to backend
            fetch('/payment', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                // Check for error response
                if (data.error) {
                    throw new Error(data.message || 'Terjadi kesalahan pada server');
                }
                
                if (data.snap_token) {
                    console.log('Snap token received:', data.snap_token);
                    
                    // Check if Snap is available
                    if (typeof window.snap === 'undefined') {
                        throw new Error('Midtrans Snap tidak tersedia. Silakan refresh halaman.');
                    }
                    
                    // Use Midtrans Snap
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            alert('Pembayaran berhasil! Anda akan diarahkan ke halaman tiket.');
                            window.location.href = `/payment/success/${data.booking_id}`;
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            alert('Pembayaran sedang diproses. Silakan cek status pembayaran Anda.');
                            window.location.href = `/payment/success/${data.booking_id}`;
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            alert('Pembayaran gagal! Silakan coba lagi.');
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            // Don't show alert on close, user might want to try again
                        }
                    });
                } else {
                    throw new Error('Token pembayaran tidak ditemukan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Payment Error:', error);
                let errorMessage = 'Terjadi kesalahan saat memproses pembayaran.';
                
                if (error.message.includes('401')) {
                    errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
                } else if (error.message.includes('500')) {
                    errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi.';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                alert(errorMessage);
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        }

        function generateTicketCode() {
            const today = new Date();
            const dateStr = today.getFullYear().toString().slice(-2) + 
                          (today.getMonth() + 1).toString().padStart(2, '0') + 
                          today.getDate().toString().padStart(2, '0');
            const randomNum = Math.floor(Math.random() * 999999).toString().padStart(6, '0');
            return `SEL${dateStr}${randomNum}`;
        }

        function showTicketModal(ticketCode) {
            document.getElementById('ticketCode').textContent = ticketCode;
            document.getElementById('ticketModalDate').textContent = selectedDate;
            
            // Generate ticket details
            let detailsHTML = `
                <div class="detail-row">
                    <span class="detail-label">Kode Tiket:</span>
                    <span class="detail-value">${ticketCode}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value">${selectedDate}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu:</span>
                    <span class="detail-value">08:00 - 17:00 WIB</span>
                </div>
            `;
            
            Object.entries(selectedPackages).forEach(([id, pkg]) => {
                detailsHTML += `
                    <div class="detail-row">
                        <span class="detail-label">${pkg.name}:</span>
                        <span class="detail-value">${pkg.quantity}x</span>
                    </div>
                `;
            });
            
            let totalAmount = 0;
            Object.values(selectedPackages).forEach(pkg => {
                totalAmount += pkg.price * pkg.quantity;
            });
            
            detailsHTML += `
                <div class="detail-row">
                    <span class="detail-label">Total Bayar:</span>
                    <span class="detail-value">Rp ${totalAmount.toLocaleString('id-ID')}</span>
                </div>
            `;
            
            document.getElementById('ticketDetailsContainer').innerHTML = detailsHTML;
            
            const modal = document.getElementById('ticketModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeTicketModal() {
            const modal = document.getElementById('ticketModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function downloadTicket() {
            // Simulate download/save functionality
            alert('🎉 Tiket berhasil disimpan! Check galeri kamu atau folder Downloads.');
            closeTicketModal();
        }

        function resetForm() {
            selectedPackages = {};
            document.querySelectorAll('.qty-display').forEach(el => el.textContent = '0');
            document.querySelectorAll('.package-card').forEach(el => el.classList.remove('selected'));
            document.getElementById('summarySection').style.display = 'none';
            document.getElementById('continueButton').disabled = true;
            document.getElementById('continueButton').textContent = 'Pilih Paket Dulu';
            
            document.getElementById('bookerName').value = '';
            document.getElementById('bookerEmail').value = '';
            document.getElementById('bookerPhone').value = '';
        }

        document.getElementById('bookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingForm();
            }
        });

        document.getElementById('ticketModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTicketModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            initializeDatePicker();
            
            const cards = document.querySelectorAll('.package-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });

        console.log('🎫 Selecta Full Screen Booking System with Enhanced Date Picker loaded!');
    </script>
</body>
</html>