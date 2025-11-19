<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Selecta - Kuliner Terbaik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('/images/heroresto.png');
            background-size: cover;
            background-position: center 70%;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
            padding: 2rem;
            text-align: center;
            z-index: 2;
            position: relative;
        }

        .hero-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 0.8rem;
        }

        .hero-badge {
            background: #26265A;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .hero-hours {
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

        /* Main Content */
        .main-content {
            max-width: 1350px;
            margin: -2rem auto 0;
            padding: 0.5rem 1.5rem 1.5rem;
            position: relative;
            z-index: 3;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 0.2rem;
        }

        .section-subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        /* Restaurant Cards */
        .restaurants-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 4rem;
        }

        .restaurant-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.2s ease;
            cursor: pointer;
        }

        .restaurant-card:hover {
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        .restaurant-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .restaurant-content {
            padding: 1.5rem;
        }

        .restaurant-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .restaurant-description {
            color: #6b7280;
            margin-bottom: 1rem;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .restaurant-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-tag {
            background: #f3f4f6;
            color: #374151;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .view-menu-btn {
            width: 100%;
            background: #26265A;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .view-menu-btn:hover {
            background: #1d1d47;
        }

        /* Menu Modal */
        .menu-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .menu-modal.active {
            display: flex;
        }

        .menu-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            overflow: hidden;
            position: relative;
        }

        .menu-header {
            background: #f8fafc;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .close-modal:hover {
            background: #e5e7eb;
        }

        .menu-body {
            padding: 2rem;
            overflow-y: auto;
            max-height: 70vh;
        }

        .menu-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .menu-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .menu-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .menu-item-image {
            height: 150px;
            background-size: cover;
            background-position: center;
        }

        .menu-item-content {
            padding: 1rem;
        }

        .menu-item-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .menu-item-description {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .menu-item-price {
            color: #26265A;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .menu-item-category {
            background: #f3f4f6;
            color: #059669;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        /* Icon styles */
        .icon {
            color: inherit;
            font-size: inherit;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                height: auto;
                min-height: 500px;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .main-content {
                padding: 1rem;
                max-width: 100%;
            }

            .restaurants-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .section-title {
                font-size: 1.4rem;
            }

            .section-subtitle {
                font-size: 0.9rem;
            }

            .menu-modal {
                padding: 1rem;
            }

            .menu-header {
                padding: 1rem;
            }

            .menu-body {
                padding: 1rem;
            }

            .menu-items {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-header">
            <h1 class="hero-title">Restoran Selecta Batu Malang</h1>
            <div class="hero-rating">
                <div class="hero-hours">
                    <i class="fas fa-clock"></i>
                    08:00 - 21:00 WIB
                </div>
                <div class="hero-badge">Destinasi Kuliner Lezat</div>
            </div>
            <div class="hero-location">
                <i class="fas fa-map-marker-alt"></i>
                Jl. Raya Selecta No. 1, Batu, Malang
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="section-title">Pilihan Restoran Kami</h2>
        <p class="section-subtitle">Tiga restoran pilihan dengan cita rasa dan suasana yang berbeda</p>

        <div class="restaurants-grid">
            @php
                $featureIcons = ['fa-utensils', 'fa-users', 'fa-leaf', 'fa-globe', 'fa-heart', 'fa-clock', 'fa-fire', 'fa-tree', 'fa-smile'];
            @endphp
            @forelse($restaurants as $restaurant)
                @php
                    $imageUrl = $restaurant->image_url ?? '/images/heroresto.png';
                    $features = is_array($restaurant->features) ? $restaurant->features : [];
                @endphp
                <div class="restaurant-card" onclick="openMenu('{{ $restaurant->slug }}')">
                    <div class="restaurant-image" style="background-image: url('{{ $imageUrl }}')">
                    </div>
                    <div class="restaurant-content">
                        <h3 class="restaurant-name">{{ $restaurant->name }}</h3>
                        @if($restaurant->description)
                            <p class="restaurant-description">
                                {{ $restaurant->description }}
                            </p>
                        @endif
                        @if(!empty($features))
                            <div class="restaurant-features">
                                @foreach($features as $index => $feature)
                                    <span class="feature-tag">
                                        <i class="fas {{ $featureIcons[$index % count($featureIcons)] }} icon"></i>
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        <button class="view-menu-btn">
                            <i class="fas fa-book icon"></i>
                            Lihat Menu
                        </button>
                    </div>
                </div>
            @empty
                <p class="section-subtitle" style="grid-column: 1 / -1;">Belum ada restoran yang tersedia.</p>
            @endforelse
        </div>
    </div>

    <!-- Menu Modal -->
    <div class="menu-modal" id="menuModal">
        <div class="menu-content">
            <div class="menu-header">
                <h3 class="menu-title" id="modalTitle">Menu Restoran</h3>
                <button class="close-modal" onclick="closeMenu()">✕</button>
            </div>
            <div class="menu-body">
                <div class="menu-items" id="menuItems">
                </div>
            </div>
        </div>
    </div>

    <div id="restaurant-menu-data" data-config="{{ base64_encode(json_encode($menuData ?? [])) }}" style="display: none;"></div>

    <script>
        const menuConfigElement = document.getElementById('restaurant-menu-data');
        let menuData = {};
        if (menuConfigElement && menuConfigElement.dataset && menuConfigElement.dataset.config) {
            try {
                menuData = JSON.parse(atob(menuConfigElement.dataset.config));
            } catch (e) {
                menuData = {};
            }
        }

        let currentRestaurant = '';

        // Modal functionality
        function openMenu(restaurant) {
            if (!menuData[restaurant] || !Array.isArray(menuData[restaurant].items) || menuData[restaurant].items.length === 0) {
                return;
            }

            currentRestaurant = restaurant;
            
            const modal = document.getElementById('menuModal');
            const modalTitle = document.getElementById('modalTitle');

            const data = menuData[restaurant];
            modalTitle.textContent = `Menu ${data.name || 'Restoran'}`;
            
            displayMenuItems();
            modal.classList.add('active');
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            const modal = document.getElementById('menuModal');
            modal.classList.remove('active');
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        function displayMenuItems() {
            const menuContainer = document.getElementById('menuItems');
            const data = menuData[currentRestaurant];
            const items = data && Array.isArray(data.items) ? data.items : [];

            if (!items.length) {
                menuContainer.innerHTML = '<p class="section-subtitle">Belum ada menu untuk restoran ini.</p>';
                return;
            }

            menuContainer.innerHTML = items.map(item => `
                <div class="menu-item">
                    <div class="menu-item-image" style="background-image: url('${item.image}')"></div>
                    <div class="menu-item-content">
                        <div class="menu-item-category">${getCategoryName(item.category)}</div>
                        <h4 class="menu-item-name">${item.name}</h4>
                        <p class="menu-item-description">${item.description}</p>
                        <div class="menu-item-price">${item.price}</div>
                    </div>
                </div>
            `).join('');
        }

        function getCategoryName(category) {
            const categories = {
                'makanan': 'Makanan',
                'minuman': 'Minuman',
                'appetizer': 'Appetizer',
                'main': 'Main Course',
                'dessert': 'Dessert',
                'beverage': 'Minuman'
            };
            return categories[category] || category;
        }

        // Close modal when clicking outside
        document.getElementById('menuModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMenu();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenu();
            }
        });
    </script>
        <x-footer />
</body>
</html>