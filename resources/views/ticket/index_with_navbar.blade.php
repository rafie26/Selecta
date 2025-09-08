<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecta - Book Your Experience</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            padding-top: 80px; /* Space for fixed navbar */
        }

        .main-container {
            width: 100%;
            min-height: calc(100vh - 80px);
            background: white;
        }

        .header {
            position: relative;
            height: 320px;
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 24px;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
            opacity: 0.3;
        }

        .title-section {
            position: relative;
            z-index: 10;
            text-align: center;
        }

        .venue-name {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 12px;
            color: white;
            letter-spacing: -1px;
        }

        .venue-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            font-size: 16px;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }

        .star {
            color: #fbbf24;
        }

        .content {
            padding: 40px;
            background: white;
            max-width: 1400px;
            margin: 0 auto;
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
            gap: 20px;
            margin-bottom: 24px;
        }

        .nav-button {
            width: 50px;
            height: 50px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-button:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        .nav-button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
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
            border-radius: 20px;
            padding: 28px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .package-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #0ea5e9);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .package-card:hover {
            border-color: #3b82f6;
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        }

        .package-card:hover::before {
            transform: scaleX(1);
        }

        .package-card.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.2);
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
            background: #3b82f6;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .package-features {
            margin-bottom: 24px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 15px;
            color: #475569;
        }

        .feature-icon {
            color: #22c55e;
            font-weight: bold;
            font-size: 16px;
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
            background: #3b82f6;
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
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(59, 130, 246, 0.3);
        }

        .continue-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }

        .continue-button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .content {
                padding: 24px 20px;
            }

            .venue-name {
                font-size: 36px;
            }

            .venue-info {
                flex-direction: column;
                gap: 12px;
            }

            .section-title {
                font-size: 24px;
            }

            .package-grid {
                grid-template-columns: 1fr;
                gap: 20px;
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
        <div class="header">
            <div class="title-section">
                <h1 class="venue-name">Selecta</h1>
                <div class="venue-info">
                    <span><span class="star">⭐</span>4.8</span>
                    <span>Batu, Malang</span>
                    <span>08:00 - 17:00</span>
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
                    <div class="package-card" onclick="selectPackage(1)" data-package-id="1">
                        <div class="package-header">
                            <div>
                                <div class="package-name">Reguler Pass</div>
                            </div>
                            <div class="package-badge">Basic</div>
                        </div>
                        <div class="package-desc">Perfect untuk kunjungan santai bersama keluarga</div>
                        <div class="package-features">
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Akses semua wahana</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Area bermain anak</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Taman bunga</span>
                            </div>
                        </div>
                        <div class="package-footer">
                            <div>
                                <div class="package-price">Rp 25.000</div>
                                <div class="price-per">per orang</div>
                            </div>
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="event.stopPropagation(); changeQty(1, -1)">-</button>
                                <span class="qty-display" id="qty-1">0</span>
                                <button class="qty-btn" onclick="event.stopPropagation(); changeQty(1, 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="package-card" onclick="selectPackage(2)" data-package-id="2">
                        <div class="package-header">
                            <div>
                                <div class="package-name">Premium Package</div>
                            </div>
                            <div class="package-badge popular">Popular</div>
                        </div>
                        <div class="package-desc">Paket lengkap dengan makan siang dan fasilitas premium</div>
                        <div class="package-features">
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Semua fasilitas Reguler</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Makan siang set menu</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Welcome drink</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Foto digital gratis</span>
                            </div>
                        </div>
                        <div class="package-footer">
                            <div>
                                <div class="package-price">Rp 45.000</div>
                                <div class="price-per">per orang</div>
                            </div>
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="event.stopPropagation(); changeQty(2, -1)">-</button>
                                <span class="qty-display" id="qty-2">0</span>
                                <button class="qty-btn" onclick="event.stopPropagation(); changeQty(2, 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="package-card" onclick="selectPackage(3)" data-package-id="3">
                        <div class="package-header">
                            <div>
                                <div class="package-name">VIP Experience</div>
                            </div>
                            <div class="package-badge exclusive">Exclusive</div>
                        </div>
                        <div class="package-desc">Pengalaman eksklusif dengan layanan personal guide</div>
                        <div class="package-features">
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Semua fasilitas Premium</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Personal guide</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Priority access</span>
                            </div>
                            <div class="feature-item">
                                <span class="feature-icon">✓</span>
                                <span>Souvenir eksklusif</span>
                            </div>
                        </div>
                        <div class="package-footer">
                            <div>
                                <div class="package-price">Rp 75.000</div>
                                <div class="price-per">per orang</div>
                            </div>
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="event.stopPropagation(); changeQty(3, -1)">-</button>
                                <span class="qty-display" id="qty-3">0</span>
                                <button class="qty-btn" onclick="event.stopPropagation(); changeQty(3, 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-section" id="summarySection">
                <h3 class="summary-title">Ringkasan Pemesanan</h3>
                <div id="summaryContent">
                    <!-- Summary items will be added dynamically -->
                </div>
                <div class="summary-total">
                    <span>Total Pembayaran:</span>
                    <span id="totalPrice">Rp 0</span>
                </div>
                <button class="continue-button" onclick="proceedToBooking()">
                    Lanjutkan Pemesanan
                </button>
            </div>
        </div>
    </div>

    <script>
        // Date picker functionality
        let currentDate = new Date();
        let selectedDate = null;
        let selectedPackages = {};
        let totalAmount = 0;

        const packages = {
            1: { name: 'Reguler Pass', price: 25000 },
            2: { name: 'Premium Package', price: 45000 },
            3: { name: 'VIP Experience', price: 75000 }
        };

        function initializeDatePicker() {
            generateDates();
            updateMonthYearDisplay();
        }

        function generateDates() {
            const datePicker = document.getElementById('datePicker');
            datePicker.innerHTML = '';

            const startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const endDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const today = new Date();

            for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                const dateItem = document.createElement('div');
                dateItem.className = 'date-item';
                
                if (d < today) {
                    dateItem.classList.add('past');
                }

                const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                dateItem.innerHTML = `
                    <div class="date-day">${dayNames[d.getDay()]}</div>
                    <div class="date-number">${d.getDate()}</div>
                    <div class="date-month">${monthNames[d.getMonth()]}</div>
                `;

                if (d >= today) {
                    dateItem.onclick = () => selectDate(new Date(d), dateItem);
                }

                datePicker.appendChild(dateItem);
            }
        }

        function selectDate(date, element) {
            document.querySelectorAll('.date-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
            selectedDate = date;
            updateSummary();
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateDates();
            updateMonthYearDisplay();
        }

        function updateMonthYearDisplay() {
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                              'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            document.getElementById('monthYearDisplay').textContent = 
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }

        function selectPackage(packageId) {
            document.querySelectorAll('.package-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.querySelector(`[data-package-id="${packageId}"]`).classList.add('selected');
        }

        function changeQty(packageId, change) {
            const currentQty = parseInt(document.getElementById(`qty-${packageId}`).textContent);
            const newQty = Math.max(0, currentQty + change);
            
            document.getElementById(`qty-${packageId}`).textContent = newQty;
            
            if (newQty > 0) {
                selectedPackages[packageId] = newQty;
            } else {
                delete selectedPackages[packageId];
            }
            
            updateSummary();
        }

        function updateSummary() {
            const summarySection = document.getElementById('summarySection');
            const summaryContent = document.getElementById('summaryContent');
            
            if (Object.keys(selectedPackages).length === 0) {
                summarySection.style.display = 'none';
                return;
            }

            summarySection.style.display = 'block';
            summaryContent.innerHTML = '';
            totalAmount = 0;

            Object.keys(selectedPackages).forEach(packageId => {
                const qty = selectedPackages[packageId];
                const package = packages[packageId];
                const subtotal = package.price * qty;
                totalAmount += subtotal;

                const summaryItem = document.createElement('div');
                summaryItem.className = 'summary-item';
                summaryItem.innerHTML = `
                    <span>${package.name} (${qty}x)</span>
                    <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                `;
                summaryContent.appendChild(summaryItem);
            });

            if (selectedDate) {
                const dateItem = document.createElement('div');
                dateItem.className = 'summary-item';
                dateItem.innerHTML = `
                    <span>Tanggal Kunjungan</span>
                    <span>${selectedDate.toLocaleDateString('id-ID')}</span>
                `;
                summaryContent.appendChild(dateItem);
            }

            document.getElementById('totalPrice').textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
        }

        function proceedToBooking() {
            if (!selectedDate || Object.keys(selectedPackages).length === 0) {
                alert('Silakan pilih tanggal dan paket terlebih dahulu!');
                return;
            }
            
            // Here you would typically redirect to booking form or open modal
            alert('Fitur pemesanan akan segera tersedia!');
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeDatePicker();
        });
    </script>
</body>
</html>
