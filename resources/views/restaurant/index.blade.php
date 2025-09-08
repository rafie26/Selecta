<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Selecta - Kuliner Terbaik</title>
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
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                              url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(2) {
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                              url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(3) {
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                              url('https://images.unsplash.com/photo-1552566090-ba4061f84a8e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(4) {
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                              url('https://images.unsplash.com/photo-1559339352-11d035aa65de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 5;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
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

        /* Restaurant Info Section */
        .restaurant-info {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .restaurant-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .restaurant-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .restaurant-rating {
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

        .restaurant-location {
            color: #6b7280;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }

        /* Restaurant Cards */
        .restaurants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .restaurant-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .restaurant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .restaurant-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .restaurant-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: #059669;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
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
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .view-menu-btn:hover {
            background: #1d4ed8;
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

        .menu-categories {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 0.8rem 1.5rem;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .category-btn.active {
            border-color: #2563eb;
            background: #2563eb;
            color: white;
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
            color: #2563eb;
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
                height: 50vh;
                min-height: 300px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
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
                padding: 1rem;
            }

            .restaurants-grid {
                grid-template-columns: 1fr;
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

            .menu-categories {
                justify-content: center;
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
        
        <div class="hero-content">
            <h1 class="hero-title">Restoran Selecta</h1>
            <p class="hero-subtitle">Nikmati Kuliner Terbaik dengan Pemandangan Menakjubkan</p>
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

    <!-- Restaurant Info Section -->
    <section class="restaurant-info">
        <div class="restaurant-container">
            <h1 class="restaurant-title">Restoran Selecta Batu Malang</h1>
            <div class="restaurant-rating">
                <div class="stars">★★★★</div>
                <div class="award-badge">Kuliner Terbaik - Batu Malang</div>
            </div>
            <div class="restaurant-location">
                <i class="fas fa-map-marker-alt icon"></i>
                Jl. Raya Selecta No. 1 desa Tulungrejo, Kec. Bumiaji - 65336 Kota Batu, Indonesia
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="section-title">Pilihan Restoran Kami</h2>
        <p class="section-subtitle">Tiga restoran pilihan dengan cita rasa dan suasana yang berbeda</p>

        <div class="restaurants-grid">
            <!-- Restoran Bahagia -->
            <div class="restaurant-card" onclick="openMenu('bahagia')">
                <div class="restaurant-image" style="background-image: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')">
                    <div class="restaurant-badge">
                        <i class="fas fa-star icon"></i>
                        Popular
                    </div>
                </div>
                <div class="restaurant-content">
                    <h3 class="restaurant-name">Restoran Bahagia</h3>
                    <p class="restaurant-description">
                        Restoran keluarga dengan suasana hangat dan menu tradisional Indonesia. Menyajikan berbagai hidangan khas Jawa Timur dengan cita rasa autentik yang tak terlupakan.
                    </p>
                    <div class="restaurant-features">
                        <span class="feature-tag">
                            <i class="fas fa-utensils icon"></i>
                            Masakan Tradisional
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-users icon"></i>
                            Family Friendly
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-leaf icon"></i>
                            Halal
                        </span>
                    </div>
                    <button class="view-menu-btn">
                        <i class="fas fa-book icon"></i>
                        Lihat Menu
                    </button>
                </div>
            </div>

            <!-- Restoran Asri -->
            <div class="restaurant-card" onclick="openMenu('asri')">
                <div class="restaurant-image" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')">
                    <div class="restaurant-badge">
                        <i class="fas fa-mountain icon"></i>
                        Garden View
                    </div>
                </div>
                <div class="restaurant-content">
                    <h3 class="restaurant-name">Restoran Asri</h3>
                    <p class="restaurant-description">
                        Nikmati pengalaman bersantap di tengah taman yang asri dengan menu fusion Asia-Western. Suasana outdoor yang sejuk dengan pemandangan pegunungan.
                    </p>
                    <div class="restaurant-features">
                        <span class="feature-tag">
                            <i class="fas fa-globe icon"></i>
                            Asian Fusion
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-tree icon"></i>
                            Outdoor Dining
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-camera icon"></i>
                            Instagram-able
                        </span>
                    </div>
                    <button class="view-menu-btn">
                        <i class="fas fa-book icon"></i>
                        Lihat Menu
                    </button>
                </div>
            </div>

            <!-- Restoran Cantik -->
            <div class="restaurant-card" onclick="openMenu('cantik')">
                <div class="restaurant-image" style="background-image: url('https://images.unsplash.com/photo-1552566090-ba4061f84a8e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80')">
                    <div class="restaurant-badge">
                        <i class="fas fa-wine-glass icon"></i>
                        Fine Dining
                    </div>
                </div>
                <div class="restaurant-content">
                    <h3 class="restaurant-name">Restoran Cantik</h3>
                    <p class="restaurant-description">
                        Fine dining experience dengan menu internasional dan wine selection terbaik. Suasana elegan dengan interior mewah untuk acara spesial Anda.
                    </p>
                    <div class="restaurant-features">
                        <span class="feature-tag">
                            <i class="fas fa-gem icon"></i>
                            Fine Dining
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-wine-glass icon"></i>
                            Wine Bar
                        </span>
                        <span class="feature-tag">
                            <i class="fas fa-heart icon"></i>
                            Romantic
                        </span>
                    </div>
                    <button class="view-menu-btn">
                        <i class="fas fa-book icon"></i>
                        Lihat Menu
                    </button>
                </div>
            </div>
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
                <div class="menu-categories">
                    <button class="category-btn active" onclick="filterMenu('all')">Semua Menu</button>
                    <button class="category-btn" onclick="filterMenu('appetizer')">Appetizer</button>
                    <button class="category-btn" onclick="filterMenu('main')">Main Course</button>
                    <button class="category-btn" onclick="filterMenu('dessert')">Dessert</button>
                    <button class="category-btn" onclick="filterMenu('beverage')">Minuman</button>
                </div>
                <div class="menu-items" id="menuItems">
                    <!-- Menu items will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script>
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

        // Menu data for each restaurant
        const menuData = {
            bahagia: {
                name: "Restoran Bahagia",
                items: [
                    {
                        name: "Gudeg Jogja",
                        description: "Gudeg khas Jogja dengan nangka muda, telur, dan opor ayam kampung",
                        price: "Rp 45.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Rawon Setan",
                        description: "Rawon hitam khas Jawa Timur dengan daging sapi empuk",
                        price: "Rp 50.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1603133872878-684f208fb84b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Soto Ayam Lamongan",
                        description: "Soto ayam dengan kuah bening, dilengkapi kerupuk dan sambal",
                        price: "Rp 35.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1623341214825-9f4f963727da?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Kerak Telor",
                        description: "Kerak telor tradisional dengan telur bebek dan beras ketan",
                        price: "Rp 25.000",
                        category: "appetizer",
                        image: "https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Es Dawet Ayu",
                        description: "Minuman tradisional dengan santan dan gula jawa",
                        price: "Rp 15.000",
                        category: "beverage",
                        image: "https://images.unsplash.com/photo-1544145945-f90425340c7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Klepon",
                        description: "Kue tradisional dengan isi gula jawa dan kelapa parut",
                        price: "Rp 20.000",
                        category: "dessert",
                        image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    }
                ]
            },
            asri: {
                name: "Restoran Asri",
                items: [
                    {
                        name: "Nasi Goreng Seafood",
                        description: "Nasi goreng dengan udang, cumi, dan ikan segar dari laut selatan",
                        price: "Rp 55.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Ayam Bakar Madu",
                        description: "Ayam bakar dengan marinasi madu dan rempah pilihan",
                        price: "Rp 48.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1598103442097-8b74394b95c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Gado-gado Betawi",
                        description: "Salad sayuran dengan bumbu kacang khas Betawi",
                        price: "Rp 32.000",
                        category: "appetizer",
                        image: "https://images.unsplash.com/photo-1565299507177-b0ac66763828?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Thai Green Curry",
                        description: "Kari hijau Thailand dengan ayam dan sayuran segar",
                        price: "Rp 58.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Es Teh Tawar",
                        description: "Es teh tawar segar dengan daun teh pilihan",
                        price: "Rp 12.000",
                        category: "beverage",
                        image: "https://images.unsplash.com/photo-1556679343-c7306c1976bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Puding Coklat",
                        description: "Puding coklat lembut dengan saus karamel dan buah segar",
                        price: "Rp 28.000",
                        category: "dessert",
                        image: "https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    }
                ]
            },
            cantik: {
                name: "Restoran Cantik",
                items: [
                    {
                        name: "Beef Wellington",
                        description: "Daging sapi tenderloin dengan mushroom duxelles dalam puff pastry",
                        price: "Rp 185.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1546833999-b9f581a1996d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Lobster Thermidor",
                        description: "Lobster segar dengan saus thermidor dan keju parmesan",
                        price: "Rp 220.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1559737558-2f5a35ab53ad?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Foie Gras Terrine",
                        description: "Foie gras premium dengan brioche toast dan fig compote",
                        price: "Rp 165.000",
                        category: "appetizer",
                        image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Truffle Risotto",
                        description: "Risotto arborio dengan black truffle dan parmesan aged",
                        price: "Rp 145.000",
                        category: "main",
                        image: "https://images.unsplash.com/photo-1563379091339-03246963d958?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Dom Pérignon 2012",
                        description: "Champagne premium dari Perancis",
                        price: "Rp 3.200.000",
                        category: "beverage",
                        image: "https://images.unsplash.com/photo-1547595628-c61a29f496f0?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Crème Brûlée",
                        description: "Dessert klasik Perancis dengan vanilla bean dan caramelized sugar",
                        price: "Rp 65.000",
                        category: "dessert",
                        image: "https://images.unsplash.com/photo-1470324161839-ce2bb6fa6bc3?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Oysters Rockefeller",
                        description: "Tiram segar dengan spinach, herbs, dan hollandaise sauce",
                        price: "Rp 125.000",
                        category: "appetizer",
                        image: "https://images.unsplash.com/photo-1606756790138-261d2b21cd75?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    },
                    {
                        name: "Château Margaux 2015",
                        description: "Red wine premium dari Bordeaux, Perancis",
                        price: "Rp 4.500.000",
                        category: "beverage",
                        image: "https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80"
                    }
                ]
            }
        };

        let currentRestaurant = '';
        let currentCategory = 'all';

        // Modal functionality
        function openMenu(restaurant) {
            currentRestaurant = restaurant;
            currentCategory = 'all';
            
            const modal = document.getElementById('menuModal');
            const modalTitle = document.getElementById('modalTitle');
            
            modalTitle.textContent = `Menu ${menuData[restaurant].name}`;
            
            // Reset category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector('.category-btn').classList.add('active');
            
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

        function filterMenu(category) {
            currentCategory = category;
            
            // Update active button
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            displayMenuItems();
        }

        function displayMenuItems() {
            const menuContainer = document.getElementById('menuItems');
            const items = menuData[currentRestaurant].items;
            
            let filteredItems = items;
            if (currentCategory !== 'all') {
                filteredItems = items.filter(item => item.category === currentCategory);
            }
            
            menuContainer.innerHTML = filteredItems.map(item => `
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

    {{-- Footer bisa ditambahkan di sini --}}
    {{-- @include('components.footer') --}}
</body>
</html>