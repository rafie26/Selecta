@props([
    'description' => 'Destinasi wisata keluarga terbaik di Malang dengan berbagai wahana seru dan pemandangan yang menakjubkan.',
    'companyTitle' => 'Selecta Malang',
    'companyDescription' => 'Destinasi wisata keluarga terbaik di Malang dengan berbagai wahana seru dan pemandangan yang menakjubkan.',
    'address' => 'Jl. Raya Selecta No.1, Tulungrejo, Bumiaji, Malang',
    'phone' => '+62 341 591 025',
    'email' => 'itselecta1950@gmail.com',
    'quickLinks' => [
        ['name' => 'Beranda', 'route' => 'home', 'url' => route('home')],
        ['name' => 'Tiket', 'route' => 'tickets.*', 'url' => route('tickets.index')],
        ['name' => 'Hotel', 'route' => 'hotels.*', 'url' => route('hotels.index')],
        ['name' => 'Restoran', 'route' => 'restaurants.*', 'url' => route('restaurants.index')],
        ['name' => 'Galeri', 'route' => 'gallery.*', 'url' => route('gallery.index')]
    ],
    'copyrightYear' => null,
    'copyrightText' => 'Selecta Malang'
])

<style>
/* RESET & SCOPING KETAT - Isolasi total footer */
.selecta-footer-wrapper {
    all: initial;
    display: block;
    width: 100%;
    clear: both;
    position: relative;
    z-index: 1;
    font-family: initial;
    color: initial;
    background: initial;
    margin: 0;
    padding: 0;
}

.selecta-footer-wrapper,
.selecta-footer-wrapper *,
.selecta-footer-wrapper *::before,
.selecta-footer-wrapper *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    border: 0;
    font: inherit;
    vertical-align: baseline;
    text-decoration: none;
    list-style: none;
}

.selecta-footer-section {
    background-color: #26265A !important;
    color: white !important;
    padding: 48px 0 24px 0 !important;
    margin: 0 !important;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    line-height: 1.5 !important;
    font-size: 14px !important;
    width: 100% !important;
    overflow: hidden;
    position: relative;
    display: block;
}

.selecta-footer-container {
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
    width: 100% !important;
    display: block;
    position: relative;
}

.selecta-footer-grid {
    display: grid !important;
    grid-template-columns: 1fr !important;
    gap: 24px !important;
    margin-bottom: 32px !important;
    width: 100% !important;
    align-items: start;
}

@media (min-width: 768px) {
    .selecta-footer-grid {
        grid-template-columns: 200px 1fr 1fr 1fr !important;
        gap: 24px !important;
    }
}

@media (min-width: 1024px) {
    .selecta-footer-grid {
        grid-template-columns: 220px 1fr 1fr 1fr !important;
        gap: 32px !important;
    }
}

.selecta-footer-col {
    grid-column: span 1 !important;
    min-width: 0 !important;
    display: block;
}

/* Maps Section - CROP KANAN KIRI */
.selecta-footer-map-col {
    display: flex !important;
    justify-content: center !important;
    align-items: flex-start !important;
    overflow: hidden !important;
    width: 100% !important;
}

.selecta-footer-map-col img {
    width: 350px !important;
    height: 220px !important;
    object-fit: contain !important;
    cursor: pointer !important;
    border-radius: 8px !important;
    display: block;
}

/* Footer Titles */
.selecta-footer-title {
    font-size: 18px !important;
    font-weight: 600 !important;
    margin: 0 0 20px 0 !important;
    color: white !important;
    line-height: 1.3 !important;
    display: block;
}

/* Company Description */
.selecta-footer-description {
    font-weight: 400 !important;
    line-height: 1.7 !important;
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 14px !important;
    margin: 0 !important;
    display: block;
}

/* Contact Section */
.selecta-contact-section {
    margin: 0 !important;
    display: block;
}

.selecta-contact-item {
    display: flex !important;
    align-items: flex-start !important;
    margin-bottom: 16px !important;
    line-height: 1.5 !important;
}

.selecta-contact-item:last-child {
    margin-bottom: 0 !important;
}

.selecta-contact-item.center {
    align-items: center !important;
}

.selecta-contact-icon {
    width: 18px !important;
    height: 18px !important;
    color: #ef4444 !important;
    margin-right: 14px !important;
    flex-shrink: 0 !important;
    fill: currentColor !important;
    margin-top: 2px !important;
    display: block;
}

.selecta-contact-item.center .selecta-contact-icon {
    margin-top: 0 !important;
}

.selecta-contact-text {
    font-weight: 400 !important;
    line-height: 1.6 !important;
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 14px !important;
    margin: 0 !important;

    white-space: normal !important;
    word-break: keep-all !important;   /* jangan pecah di tengah kata */
    overflow-wrap: break-word !important; /* pecah hanya di spasi */
    hyphens: none !important;          /* jangan tambahin strip otomatis */
    display: block;
}


.selecta-contact-text.email {
    font-size: 13px !important;
    word-break: break-all !important;
}

/* Quick Links */
.selecta-quick-links {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: block;
}

.selecta-quick-links li {
    margin-bottom: 12px !important;
    display: block;
}

.selecta-quick-links li:last-child {
    margin-bottom: 0 !important;
}

.selecta-footer-link {
    font-weight: 500 !important;
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 14px !important;
    text-decoration: none !important;
    display: block !important;
    padding: 4px 0 !important;
}

.selecta-footer-link:hover {
    color: white !important;
}

/* Copyright Section */
.selecta-copyright-section {
    border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
    margin-top: 32px !important;
    padding-top: 24px !important;
    display: block;
}

.selecta-copyright-text {
    font-weight: 400 !important;
    color: rgba(255, 255, 255, 0.6) !important;
    font-size: 14px !important;
    text-align: center !important;
    margin: 0 !important;
    line-height: 1.4 !important;
    display: block;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .selecta-footer-section {
        padding: 32px 0 20px 0 !important;
    }
    
    .selecta-footer-container {
        padding: 0 16px !important;
    }
    
    .selecta-footer-grid {
        gap: 28px !important;
        margin-bottom: 28px !important;
    }
    
    .selecta-footer-map-col img {
        max-width: 300px !important;
        height: 200px !important;
    }
    
    .selecta-footer-title {
        font-size: 16px !important;
        margin-bottom: 16px !important;
    }
    
    .selecta-footer-description,
    .selecta-contact-text,
    .selecta-footer-link {
        font-size: 13px !important;
    }
    
    .selecta-contact-text.email {
        font-size: 12px !important;
    }
    
    .selecta-contact-item {
        margin-bottom: 14px !important;
    }
    
    .selecta-quick-links li {
        margin-bottom: 10px !important;
    }
    
    .selecta-copyright-text {
        font-size: 13px !important;
    }
}

@media (max-width: 480px) {
    .selecta-footer-section {
        padding: 24px 0 16px 0 !important;
    }
    
    .selecta-footer-container {
        padding: 0 12px !important;
    }
    
    .selecta-footer-grid {
        gap: 24px !important;
        margin-bottom: 24px !important;
    }
    
    .selecta-footer-map-col img {
        max-width: 320px !important;
        height: 220px !important;
    }
    
    .selecta-footer-title {
        font-size: 15px !important;
        margin-bottom: 14px !important;
    }
    
    .selecta-contact-icon {
        width: 16px !important;
        height: 16px !important;
        margin-right: 12px !important;
    }
    
    .selecta-contact-text.email {
        font-size: 11px !important;
    }
    
    .selecta-copyright-section {
        margin-top: 24px !important;
        padding-top: 20px !important;
    }
}
</style>

<div class="selecta-footer-wrapper">
    <footer class="selecta-footer-section">
        <div class="selecta-footer-container">
            <div class="selecta-footer-grid">
                <!-- Maps Section -->
                <div class="selecta-footer-col selecta-footer-map-col">
                    <img src="/images/maps.png" alt="Peta Lokasi Selecta Malang" onclick="window.open('https://maps.google.com/maps?q=Selecta+Recreation+Park+Malang&t=&z=15&ie=UTF8&iwloc=&output=embed', '_blank')">
                </div>

                <!-- Company Info Section -->
                <div class="selecta-footer-col">
                    <h4 class="selecta-footer-title">{{ $companyTitle }}</h4>
                    <p class="selecta-footer-description">
                        {{ $companyDescription }}
                    </p>
                </div>

                <!-- Contact Section -->
                <div class="selecta-footer-col">
                    <h4 class="selecta-footer-title">Kontak</h4>
                    <div class="selecta-contact-section">
                        <div class="selecta-contact-item">
                            <svg class="selecta-contact-icon" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <p class="selecta-contact-text">{{ $address }}</p>
                        </div>
                        
                        <div class="selecta-contact-item center">
                            <svg class="selecta-contact-icon" viewBox="0 0 24 24">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            <p class="selecta-contact-text">{{ $phone }}</p>
                        </div>
                        
                        <div class="selecta-contact-item center">
                            <svg class="selecta-contact-icon" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            <p class="selecta-contact-text email">{{ $email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="selecta-footer-col">
                    <h4 class="selecta-footer-title">Quick Link</h4>
                    <ul class="selecta-quick-links">
                        @foreach($quickLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}" class="selecta-footer-link">
                                {{ $link['name'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Copyright Section -->
            <div class="selecta-copyright-section">
                <p class="selecta-copyright-text">
                    © {{ $copyrightYear ?? date('Y') }} {{ $copyrightText }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</div>