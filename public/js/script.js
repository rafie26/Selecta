// Debug function - panggil di console untuk lihat semua elemen
function debugElements() {
    console.log("=== DEBUG ELEMENTS ===");
    console.log("All .air:", document.querySelectorAll(".air"));
    console.log("All .air h1:", document.querySelectorAll(".air h1"));
    console.log("All #landing h1:", document.querySelectorAll("#landing h1"));
    console.log("All h1:", document.querySelectorAll("h1"));
    console.log("======================");
}

let LastImage = document.getElementsByClassName("last-img")[0];
let MidImage = document.getElementsByClassName("mid-img")[0];
let BirdImage = document.getElementsByClassName("bird-img")[0];
let Cloud1Image = document.getElementsByClassName("cloud1-img")[0];
let Cloud2Image = document.getElementsByClassName("cloud2-img")[0];
let Cloud3Image = document.getElementsByClassName("cloud3-img")[0];
let FocusImage = document.getElementsByClassName("focus-img")[0];
let Button = document.querySelector(".btn");
let wave1 = document.getElementById('wave1');
let wave2 = document.getElementById('wave2');
let wave3 = document.getElementById('wave3');
let wave4 = document.getElementById('wave4');

// Wave animation
window.addEventListener('scroll', function(){
    let value = window.scrollY;

    if(wave1) wave1.style.backgroundPositionX = 400 + value * 4 + 'px';
    if(wave2) wave2.style.backgroundPositionX = 300 + value * -4 + 'px';
    if(wave3) wave3.style.backgroundPositionX = 200 + value * 2 + 'px';
    if(wave4) wave4.style.backgroundPositionX = 100 + value * -2 + 'px';
});

// Try SEMUA kemungkinan selector text
let Selecta = null;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Coba berbagai selector sampai ketemu
    if (document.querySelectorAll(".air h1").length > 0) {
        Selecta = document.querySelectorAll(".air h1");
        console.log("Using .air h1 - found", Selecta.length, "elements");
    } else if (document.querySelectorAll("#landing .air h1").length > 0) {
        Selecta = document.querySelectorAll("#landing .air h1");
        console.log("Using #landing .air h1 - found", Selecta.length, "elements");
    } else if (document.querySelectorAll("#landing h1").length > 0) {
        Selecta = document.querySelectorAll("#landing h1");
        console.log("Using #landing h1 - found", Selecta.length, "elements");
    } else if (document.querySelectorAll("h1").length > 0) {
        Selecta = document.querySelectorAll("h1");
        console.log("Using all h1 - found", Selecta.length, "elements");
    } else {
        console.log("NO H1 ELEMENTS FOUND AT ALL!");
        debugElements();
    }
});

// Parallax effects
window.addEventListener('scroll', function() {
    let value = window.scrollY;
    let windowHeight = window.innerHeight;
    
    // Bird parallax effect
    if (BirdImage) {
        BirdImage.style.left = value * 0.9 + 'px';
        BirdImage.style.top = value * 0.7 + 'px';
    }
    
    // Background layers parallax
    if (LastImage) {
        LastImage.style.top = value * 0.7 + 'px';
    }
    
    if (MidImage) {
        MidImage.style.top = value * 0.3 + 'px';
    }
    
    // Focus image stays fixed
    if (FocusImage) {
        FocusImage.style.top = value * 0 + 'px';
    }
    
    // Text scaling and movement effect
    if (Selecta && Selecta.length > 0) {
        let translateY = value * 0.5;
        let scale = Math.max(0.3, 1 - (value * 0.001));
        
        // Apply transform to all text elements
        Selecta.forEach(function(element, index) {
            if (element) {
                element.style.transform = `translateX(-50%) translateY(${translateY}px) scale(${scale})`;
                element.style.transition = 'none';
            }
        });
    }
    
    // Button fade out effect
    if (Button) {
        let buttonOpacity = Math.max(0, 1 - (value / (windowHeight * 0.5)));
        Button.style.opacity = buttonOpacity;
    }
});

// Reset when scrolled back to top
window.addEventListener('scroll', function() {
    if (window.scrollY === 0) {
        if (Selecta && Selecta.length > 0) {
            Selecta.forEach(function(element) {
                if (element) {
                    element.style.transform = 'translateX(-50%) translateY(0px) scale(1)';
                }
            });
        }
        if (Button) {
            Button.style.opacity = '1';
        }
    }
});

// GALLERY CAROUSEL FUNCTIONALITY (if exists)
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.indicator');
    const nextBtnGallery = document.getElementById('nextBtn');
    const prevBtnGallery = document.getElementById('prevBtn');
    const wrapper = document.querySelector('.carousel-wrapper');

    if (slides.length > 0) {
        function updateCarousel() {
            if (wrapper) {
                wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
            }
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                if (indicator) {
                    indicator.classList.toggle('active', index === currentSlide);
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateCarousel();
        }

        // Event listeners for gallery carousel
        if (nextBtnGallery) {
            nextBtnGallery.addEventListener('click', nextSlide);
        }

        if (prevBtnGallery) {
            prevBtnGallery.addEventListener('click', prevSlide);
        }

        // Indicator clicks
        indicators.forEach((indicator, index) => {
            if (indicator) {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    updateCarousel();
                });
            }
        });

        // Auto play gallery carousel
        if (slides.length > 1) {
            setInterval(nextSlide, 5000);
        }
    }
});

// WAHANA CARD CAROUSEL FUNCTIONALITY - FIXED
document.addEventListener('DOMContentLoaded', function() {
    let currentWahanaIndex = 0;
    const wahanaCards = document.querySelectorAll('.wahana-card');
    const wahanaWrapper = document.querySelector('.slider-wrapper');
    const wahanaNextBtn = document.querySelector('#wahana-section .nav-btn.next-btn');
    const wahanaPrevBtn = document.querySelector('#wahana-section .nav-btn.prev-btn');
    const wahanaSliderContainer = document.querySelector('#wahana-section .slider-container');
    
    if (wahanaCards.length > 0) {
        function getCardsToShow() {
            const screenWidth = window.innerWidth;
            if (screenWidth <= 480) return 1;
            if (screenWidth <= 768) return 2;
            return 3;
        }

        function updateWahanaCarousel() {
            if (wahanaWrapper) {
                const cardsToShow = getCardsToShow();
                const cardWidth = wahanaCards[0].offsetWidth || 300;
                const gap = 20;
                const totalCardWidth = cardWidth + gap;
                const maxIndex = Math.max(0, wahanaCards.length - cardsToShow);
                
                // Ensure currentIndex doesn't exceed bounds
                if (currentWahanaIndex > maxIndex) {
                    currentWahanaIndex = 0;
                }
                if (currentWahanaIndex < 0) {
                    currentWahanaIndex = maxIndex;
                }
                
                const translateX = -currentWahanaIndex * totalCardWidth;
                wahanaWrapper.style.transform = `translateX(${translateX}px)`;
            }
        }

        function nextWahanaCard() {
            const cardsToShow = getCardsToShow();
            const maxIndex = Math.max(0, wahanaCards.length - cardsToShow);
            currentWahanaIndex = currentWahanaIndex >= maxIndex ? 0 : currentWahanaIndex + 1;
            updateWahanaCarousel();
        }

        function prevWahanaCard() {
            const cardsToShow = getCardsToShow();
            const maxIndex = Math.max(0, wahanaCards.length - cardsToShow);
            currentWahanaIndex = currentWahanaIndex <= 0 ? maxIndex : currentWahanaIndex - 1;
            updateWahanaCarousel();
        }

        // Event listeners for wahana carousel
        if (wahanaNextBtn) {
            wahanaNextBtn.addEventListener('click', nextWahanaCard);
        }

        if (wahanaPrevBtn) {
            wahanaPrevBtn.addEventListener('click', prevWahanaCard);
        }

        // Auto-play wahana carousel
        let wahanaAutoPlay = setInterval(nextWahanaCard, 4000);

        // Pause auto-play on hover
        if (wahanaSliderContainer) {
            wahanaSliderContainer.addEventListener('mouseenter', () => {
                clearInterval(wahanaAutoPlay);
            });
            
            wahanaSliderContainer.addEventListener('mouseleave', () => {
                wahanaAutoPlay = setInterval(nextWahanaCard, 4000);
            });
        }

        // Touch/swipe functionality for wahana carousel
        let startX = 0;
        let endX = 0;

        if (wahanaSliderContainer) {
            wahanaSliderContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            wahanaSliderContainer.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                handleWahanaSwipe();
            });
            
            function handleWahanaSwipe() {
                const swipeThreshold = 50;
                const swipeDistance = startX - endX;
                
                if (Math.abs(swipeDistance) > swipeThreshold) {
                    if (swipeDistance > 0) {
                        nextWahanaCard();
                    } else {
                        prevWahanaCard();
                    }
                }
            }
        }

        // Update carousel on window resize
        window.addEventListener('resize', () => {
            updateWahanaCarousel();
        });

        // Initialize carousel
        updateWahanaCarousel();
    }
});

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    console.log('Searching for:', searchTerm);
                    const aboutSection = document.querySelector('#about');
                    if (aboutSection) {
                        aboutSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            }
        });
    }
});

// Call debug on load
window.addEventListener('load', function() {
    console.log("Page loaded - debugging elements:");
    debugElements();
});