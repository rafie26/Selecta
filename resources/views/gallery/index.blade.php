<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Wisata Selecta</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            line-height: 1.6;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 60vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('/images/herogaleri.jpeg');
            background-size: cover;
            background-position: center 50%;
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

        /* Control Container - Same as ticket page */
        .control-container {
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

        .form-select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            background: white;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #26265A;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 20px 20px;
            position: relative;
            z-index: 3;
            margin-top: -30px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            margin-bottom: 40px;
        }

        .gallery-item {
            height: 200px;
        }

        .gallery.show-all {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            grid-template-rows: auto;
            max-height: none;
        }

        .gallery.show-all .gallery-item {
            height: 200px;
        }

        .gallery.show-all .gallery-item.tall,
        .gallery.show-all .gallery-item.wide,
        .gallery.show-all .gallery-item.large {
            grid-column: span 1;
            grid-row: span 1;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .gallery-item.wide {
            grid-column: span 2;
        }

        .gallery-item.tall {
            grid-row: span 2;
        }

        .gallery-item.large {
            grid-column: span 2;
            grid-row: span 2;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
            filter: brightness(0.6);
        }

        /* Date badge */
        .date-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            position: relative;
            margin: auto;
            padding: 0;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .modal-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .modal-info {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .modal-date {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            transition: background 0.3s ease;
        }

        .close:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* Modal Navigation Arrows */
        .modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            font-size: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .modal-nav:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }

        .modal-prev {
            left: 20px;
        }

        .modal-next {
            right: 20px;
        }

        .modal-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .modal-nav:disabled:hover {
            background: rgba(0, 0, 0, 0.5);
            transform: translateY(-50%) scale(1);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-header {
                padding: 2rem;
            }

            .container {
                padding: 5px 15px 20px;
            }

            .control-container {
                grid-template-columns: 1fr;
                margin: -3rem 1rem 1rem;
                padding: 1.2rem;
                gap: 1.2rem;
            }

            .filter-section {
                justify-content: center;
                flex-wrap: wrap;
            }

            .filter-label {
                width: 100%;
                text-align: center;
                margin-bottom: 0.5rem;
                margin-right: 0;
            }

            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .pagination button {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
                min-width: 40px;
            }

            .gallery {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 150px);
                gap: 10px;
            }

            .close {
                top: 10px;
                right: 15px;
                font-size: 30px;
                width: 40px;
                height: 40px;
            }

            .modal-nav {
                width: 50px;
                height: 50px;
                font-size: 25px;
            }

            .modal-prev {
                left: 10px;
            }

            .modal-next {
                right: 10px;
            }

            .modal-info {
                bottom: 10px;
                padding: 10px 15px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .control-container {
                margin: -2rem 1rem 1rem;
                padding: 1rem;
            }

            .filter-btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                flex: 1;
            }

            .pagination button {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
                min-width: 35px;
            }

            .gallery {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(12, 120px);
            }
            
            .gallery-item.tall,
            .gallery-item.wide,
            .gallery-item.large {
                grid-column: span 1;
                grid-row: span 1;
            }

            .modal-content {
                max-width: 95vw;
                max-height: 70vh;
            }

            .close {
                top: -45px;
                font-size: 20px;
                width: 35px;
                height: 35px;
            }

            .modal-nav {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .modal-prev {
                left: 5px;
            }

            .modal-next {
                right: 5px;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-header">
            <h1 class="hero-title">Galeri Wisata Selecta</h1>
            <div class="hero-rating">
                <div class="hero-badge">Lihat keseruan pengunjung di berbagai wahana</div>
            </div>
        </div>

        <!-- Clean Control Container - Same style as ticket page -->
        <div class="control-container">
            <div class="form-group">
                <label class="form-label">Urutkan Foto</label>
                <select class="form-select" id="sortFilter" onchange="applyFilter()">
                    <option value="default">Default</option>
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Halaman</label>
                <div class="ticket-counter">
                    <span class="counter-display" id="pageDisplay">Halaman 1 dari 3</span>
                    <div class="counter-controls">
                        <button type="button" class="counter-btn" id="prevBtn" onclick="previousPage()">‹</button>
                        <button type="button" class="counter-btn" id="nextBtn" onclick="nextPage()">›</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="button" class="search-btn" onclick="showAllPhotos()" id="showAllBtn">Lihat Semua Foto</button>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="gallery" id="gallery">
            <!-- Gallery items will be populated by JavaScript -->
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <button class="modal-nav modal-prev" onclick="previousImage()">&#8249;</button>
        <button class="modal-nav modal-next" onclick="nextImage()">&#8250;</button>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <div class="modal-info">
                <div class="modal-date" id="modalDate"></div>
            </div>
        </div>
    </div>

    <script>
        const originalGalleryData = [
            // Page 1
            [
                { src: "/images/galeri1.jpeg", type: "normal", date: "2024-01-15", displayDate: "15 Jan 2024" },
                { src: "/images/galeri2.jpeg", type: "normal", date: "2024-01-20", displayDate: "20 Jan 2024" },
                { src: "/images/galeri3.jpeg", type: "normal", date: "2024-02-05", displayDate: "5 Feb 2024" },
                { src: "/images/galeri4.jpeg", type: "tall", date: "2024-02-12", displayDate: "12 Feb 2024" },
                { src: "/images/galeri5.jpeg", type: "wide", date: "2024-02-25", displayDate: "25 Feb 2024" },
                { src: "/images/galeri6.jpeg", type: "normal", date: "2024-03-08", displayDate: "8 Mar 2024" },
                { src: "/images/galeri7.jpeg", type: "tall", date: "2024-03-15", displayDate: "15 Mar 2024" },
                { src: "/images/galeri8.jpeg", type: "normal", date: "2024-03-22", displayDate: "22 Mar 2024" },
                { src: "/images/galeri9.jpeg", type: "normal", date: "2024-04-02", displayDate: "2 Apr 2024" },
                { src: "/images/galeri10.jpeg", type: "normal", date: "2024-04-10", displayDate: "10 Apr 2024" },
                { src: "/images/galeri11.jpeg", type: "wide", date: "2024-04-18", displayDate: "18 Apr 2024" },
                { src: "/images/galeri12.jpeg", type: "normal", date: "2024-04-25", displayDate: "25 Apr 2024" }
            ],
            // Page 2
            [
                { src: "/images/galeri12.jpeg", type: "normal", date: "2024-05-05", displayDate: "5 Mei 2024" },
                { src: "/images/galeri11.jpeg", type: "normal", date: "2024-05-12", displayDate: "12 Mei 2024" },
                { src: "/images/galeri10.jpeg", type: "normal", date: "2024-05-20", displayDate: "20 Mei 2024" },
                { src: "/images/galeri9.jpeg", type: "tall", date: "2024-05-28", displayDate: "28 Mei 2024" },
                { src: "/images/galeri8.jpeg", type: "wide", date: "2024-06-05", displayDate: "5 Jun 2024" },
                { src: "/images/galeri1.jpeg", type: "normal", date: "2024-06-12", displayDate: "12 Jun 2024" },
                { src: "/images/galeri7.jpeg", type: "tall", date: "2024-06-20", displayDate: "20 Jun 2024" },
                { src: "/images/galeri6.jpeg", type: "normal", date: "2024-06-28", displayDate: "28 Jun 2024" },
                { src: "/images/galeri5.jpeg", type: "normal", date: "2024-07-08", displayDate: "8 Jul 2024" },
                { src: "/images/galeri4.jpeg", type: "normal", date: "2024-07-15", displayDate: "15 Jul 2024" },
                { src: "/images/galeri3.jpeg", type: "wide", date: "2024-07-22", displayDate: "22 Jul 2024" },
                { src: "/images/galeri2.jpeg", type: "normal", date: "2024-07-30", displayDate: "30 Jul 2024" }
            ],
            // Page 3
            [
                { src: "/images/galeri11.jpeg", type: "normal", date: "2024-08-05", displayDate: "5 Agu 2024" },
                { src: "/images/galeri6.jpeg", type: "normal", date: "2024-08-12", displayDate: "12 Agu 2024" },
                { src: "/images/galeri7.jpeg", type: "normal", date: "2024-08-20", displayDate: "20 Agu 2024" },
                { src: "/images/galeri8.jpeg", type: "tall", date: "2024-08-28", displayDate: "28 Agu 2024" },
                { src: "/images/galeri9.jpeg", type: "wide", date: "2024-09-05", displayDate: "5 Sep 2024" },
                { src: "/images/galeri10.jpeg", type: "normal", date: "2024-09-12", displayDate: "12 Sep 2024" },
                { src: "/images/galeri1.jpeg", type: "tall", date: "2024-09-18", displayDate: "18 Sep 2024" },
                { src: "/images/galeri12.jpeg", type: "normal", date: "2024-09-19", displayDate: "19 Sep 2024" },
                { src: "/images/galeri3.jpeg", type: "normal", date: "2023-12-10", displayDate: "10 Des 2023" },
                { src: "/images/galeri4.jpeg", type: "normal", date: "2023-12-15", displayDate: "15 Des 2023" },
                { src: "/images/galeri5.jpeg", type: "wide", date: "2023-12-20", displayDate: "20 Des 2023" },
                { src: "/images/galeri6.jpeg", type: "normal", date: "2023-12-25", displayDate: "25 Des 2023" }
            ]
        ];

        let currentPage = 1;
        let totalPages = originalGalleryData.length;
        let currentImageIndex = 0;
        let currentPageImages = [];
        let currentSortedData = [];

        function deepCopyGalleryData() {
            return originalGalleryData.map(page => 
                page.map(item => ({ ...item }))
            );
        }

        function applyFilter() {
            const sortFilter = document.getElementById('sortFilter').value;
            
            // Create fresh copy of original data
            currentSortedData = deepCopyGalleryData();
            
            if (sortFilter === 'newest' || sortFilter === 'oldest') {
                // Gabungkan semua foto dari semua halaman
                const allPhotos = [];
                currentSortedData.forEach(page => {
                    allPhotos.push(...page);
                });
                
                // Sort semua foto
                if (sortFilter === 'newest') {
                    allPhotos.sort((a, b) => new Date(b.date) - new Date(a.date));
                } else {
                    allPhotos.sort((a, b) => new Date(a.date) - new Date(b.date));
                }
                
                // Bagi kembali ke halaman-halaman (12 foto per halaman)
                const photosPerPage = 12;
                currentSortedData = [];
                for (let i = 0; i < allPhotos.length; i += photosPerPage) {
                    currentSortedData.push(allPhotos.slice(i, i + photosPerPage));
                }
                
                // Update total pages jika berubah
                totalPages = currentSortedData.length;
                
                // Reset ke halaman 1
                currentPage = 1;
            }
            
            renderGallery();
            updatePageDisplay();
        }

        function updatePageDisplay() {
            const pageDisplay = document.getElementById('pageDisplay');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            pageDisplay.textContent = `Halaman ${currentPage} dari ${totalPages}`;
            
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
        }

        let isShowingAll = false;

        function showAllPhotos() {
            const gallery = document.getElementById('gallery');
            const btn = document.getElementById('showAllBtn');
            const pageControls = document.querySelector('.ticket-counter');
            
            if (!isShowingAll) {
                // Show all photos
                gallery.innerHTML = '';
                gallery.classList.add('show-all');
                
                // Combine all photos from all pages
                const allPhotos = [];
                currentSortedData.forEach(page => {
                    allPhotos.push(...page);
                });
                
                // Render all photos
                allPhotos.forEach((item, index) => {
                    const galleryItem = document.createElement('div');
                    galleryItem.className = 'gallery-item';
                    
                    galleryItem.innerHTML = `
                        <img src="${item.src}" alt="Gallery Image ${index + 1}">
                        <div class="date-badge">${item.displayDate}</div>
                    `;
                    
                    galleryItem.addEventListener('click', () => {
                        currentPageImages = allPhotos;
                        openModal(index);
                    });
                    
                    gallery.appendChild(galleryItem);
                });
                
                btn.textContent = 'Mode Halaman';
                pageControls.style.opacity = '0.5';
                pageControls.style.pointerEvents = 'none';
                isShowingAll = true;
                
            } else {
                // Back to page mode
                gallery.classList.remove('show-all');
                renderGallery();
                btn.textContent = 'Lihat Semua Foto';
                pageControls.style.opacity = '1';
                pageControls.style.pointerEvents = 'auto';
                isShowingAll = false;
            }
        }

        function renderGallery() {
            const gallery = document.getElementById('gallery');
            const currentData = currentSortedData[currentPage - 1];
            currentPageImages = currentData;
            
            gallery.innerHTML = '';
            
            currentData.forEach((item, index) => {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'gallery-item';
                
                galleryItem.innerHTML = `
                    <img src="${item.src}" alt="Gallery Image ${index + 1}">
                    <div class="date-badge">${item.displayDate}</div>
                `;
                
                galleryItem.addEventListener('click', () => openModal(index));
                
                gallery.appendChild(galleryItem);
            });
        }

        function openModal(imageIndex) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalDate = document.getElementById('modalDate');
            
            currentImageIndex = imageIndex;
            modalImage.src = currentPageImages[currentImageIndex].src;
            modalDate.textContent = `Diunggah pada: ${currentPageImages[currentImageIndex].displayDate}`;
            modal.style.display = 'block';
            
            updateModalNavigation();
            
            // Add keyboard navigation
            document.addEventListener('keydown', handleKeyNavigation);
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            
            // Remove keyboard navigation
            document.removeEventListener('keydown', handleKeyNavigation);
        }

        function previousImage() {
            if (currentImageIndex > 0) {
                currentImageIndex--;
                const modalImage = document.getElementById('modalImage');
                const modalDate = document.getElementById('modalDate');
                modalImage.src = currentPageImages[currentImageIndex].src;
                modalDate.textContent = `Diambil pada: ${currentPageImages[currentImageIndex].displayDate}`;
                updateModalNavigation();
            }
        }

        function nextImage() {
            if (currentImageIndex < currentPageImages.length - 1) {
                currentImageIndex++;
                const modalImage = document.getElementById('modalImage');
                const modalDate = document.getElementById('modalDate');
                modalImage.src = currentPageImages[currentImageIndex].src;
                modalDate.textContent = `Diambil pada: ${currentPageImages[currentImageIndex].displayDate}`;
                updateModalNavigation();
            }
        }

        function updateModalNavigation() {
            const prevBtn = document.querySelector('.modal-prev');
            const nextBtn = document.querySelector('.modal-next');
            
            prevBtn.disabled = currentImageIndex === 0;
            nextBtn.disabled = currentImageIndex === currentPageImages.length - 1;
        }

        function handleKeyNavigation(event) {
            switch(event.key) {
                case 'Escape':
                    closeModal();
                    break;
                case 'ArrowLeft':
                    previousImage();
                    break;
                case 'ArrowRight':
                    nextImage();
                    break;
            }
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });

        function goToPage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderGallery();
                updatePageDisplay();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function previousPage() {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        }

        // Initialize gallery on page load
        function initializeGallery() {
            currentSortedData = deepCopyGalleryData();
            renderGallery();
            updatePageDisplay();
        }

        // Initialize when page loads
        initializeGallery();
    </script>
     <x-footer />
</body>
</html>