<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Nunito+Sans:wght@400;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Selecta - Wisata Keluarga Terbaik</title>
    <style>
        /* Reset dan Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    scroll-behavior: smooth;
}

body {
    background-color: #ffffff;
    font-family: 'Poppins', sans-serif;
}

/* HERO SECTION */
.hero {
    height: 100vh;
    position: relative;
    overflow: hidden;
}

.hero img {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

/* Cloud floating animations */
.cloud1-img {
    animation: cloudWind1 60s infinite ease-in-out;
    z-index: 1;
}

.cloud2-img {
    animation: cloudWind2 70s infinite ease-in-out;
    z-index: 1;
}

.cloud3-img {
    animation: cloudWind3 80s infinite ease-in-out;
    z-index: 1;
}

@keyframes cloudWind1 {
    0% { transform: translateX(-120px) translateY(0px); }
    50% { transform: translateX(120px) translateY(-8px); }
    100% { transform: translateX(-120px) translateY(0px); }
}

@keyframes cloudWind2 {
    0% { transform: translateX(-100px) translateY(0px); }
    50% { transform: translateX(140px) translateY(12px); }
    100% { transform: translateX(-100px) translateY(0px); }
}

@keyframes cloudWind3 {
    0% { transform: translateX(-80px) translateY(0px); }
    50% { transform: translateX(100px) translateY(-5px); }
    100% { transform: translateX(-80px) translateY(0px); }
}

#landing {
    height: 100vh;
}

/* AIR TEXT EFFECT */
.air {
    color: #FFF;
    position: absolute;
    z-index: 3;
    top: 36%;
    left: 50%;
    transform: translateX(-50%);
    font-size: 8vw;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    font-family: 'Montserrat', cursive;
}

.air h1 {
    position: absolute;
    font-size: 8vw;
    left: 50%;
    transform: translateX(-50%);
}

.air h1:nth-child(1) {
    color: #fff;
    text-shadow: 2px 2px 0px #183954, -4px 4px 0px #183954, -6px 6px 0px #183954, -8px 8px 0px #183954, -18px 18px 50px rgba(0,0,0,0.5);
}

.air h1:nth-child(2) {
    color: #2196f3;
    opacity: 0.3;
    animation: animate 3s ease-in-out infinite;
}

.air h1:nth-child(3) {
    color: #2196f3;
    opacity: 0.5;
    animation: animate 6s ease-in-out infinite;
}

.air h1:nth-child(4) {
    color: #2196f3;
    animation: animate 4s ease-in-out infinite;
    clip-path: polygon(1% 63%, 10% 70%, 14% 70%, 22% 71%, 35% 69%, 49% 68%, 64% 66%, 79% 60%, 88% 57%, 97% 53%, 100% 51%, 100% 73%, 99% 99%, 26% 100%, 1% 100%);
}

@keyframes animate {
    0%,100% {
        clip-path: polygon(1% 63%, 10% 70%, 14% 70%, 22% 71%, 35% 69%, 49% 68%, 64% 66%, 79% 60%, 88% 57%, 97% 53%, 100% 51%, 100% 73%, 99% 99%, 26% 100%, 1% 100%);
    }
    50% {
        clip-path: polygon(0 50%, 9% 48%, 21% 51%, 31% 55%, 40% 60%, 52% 68%, 66% 71%, 76% 71%, 85% 66%, 93% 61%, 100% 56%, 100% 73%, 99% 99%, 26% 100%, 1% 100%);
    }
}

.mid-img { z-index: 2; }
.mid-img2 { z-index: 2; }
.focus-img { z-index: 4; }

.button-container {
    position: absolute;
    top: 55%;
    z-index: 9;
    left: 50%;
    transform: translate(-50%, -50%);
}

.search-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.search-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border-radius: 25px;
    padding: 12px 20px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    min-width: 320px;
}

.search-icon {
    color: #999;
    font-size: 16px;
    margin-right: 12px;
}

.search-input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 16px;
    color: #333;
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
}

.search-input::placeholder {
    color: #999;
    font-weight: 400;
}

.explore-btn {
    display: inline-block;
    padding: 14px 90px;
    background: rgba(255, 255, 255, 0.95);
    color: #666;
    text-decoration: none;
    border-radius: 30px;
    font-size: clamp(14px, 3.5vw, 16px);
    font-weight: 400;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    text-align: center;
    min-width: 200px;
}

.explore-btn:hover {
    background: rgba(255, 255, 255, 1);
    color: #333;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

/* LANDING OVERLAY & WAVES */
#landing::before {
    content: '';
    position: absolute;
    bottom: 0;
    width: 100%;
    height: min(220px, 25vh);
    background: linear-gradient(to top, #26265A 0%, rgba(31, 31, 70, 0.8) 30%, rgba(31, 31, 70, 0.4) 60%, transparent 100%);
    z-index: 6;
}

.wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: min(100px, 12vh);
    background: url(/images/wave.png);
    background-size: 1000px min(100px, 12vh);
    background-repeat: repeat-x;
    animation: animae 4s ease-in-out infinite;
    animation-delay: calc(var(--i) * 0.25s);
}

.wave#wave1{ z-index: 1000; opacity: 1; background-position-x: 400px; }
.wave#wave2{ z-index: 999; opacity: 0.5; background-position-x: 300px; }
.wave#wave3{ z-index: 998; opacity: 0.2; background-position-x: 200px; }
.wave#wave4{ z-index: 999; opacity: 0.7; background-position-x: 100px; }

@keyframes animae {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(10px); }
}

/* ABOUT SECTION */
#about {
    width: 100%;
    padding: clamp(4rem, 10vh, 8rem) 0;
    background-color: #26265A;
    position: relative;
}

.about-container {
    width: min(1000px, 90vw);
    margin: 0 auto;
    margin-top: clamp(5rem, 12vh, 10rem);
    margin-bottom: clamp(7rem, 17vh, 10rem);
}

.about-info {
    text-align: center;
    font-size: clamp(1rem, 2.8vw, 1.2rem);
    color: #ffffff;
    line-height: clamp(1.5rem, 4vw, 1.8rem);
    margin-bottom: clamp(50px, 10vh, 100px);
    padding: 0 clamp(20px, 5vw, 40px);
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.about-info h2 {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    margin-bottom: clamp(2.5rem, 3vh, 2rem);
    color: #ffffff;
}

/* LAYANAN SECTION - Minimal Animation */
#layanan-section {
    width: 100%;
    background: #ffffff;
    padding: clamp(4rem, 10vh, 8rem) 0;
    position: relative;
    margin-top: 50px;
}

.layanan-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 clamp(20px, 5vw, 40px);
    position: relative;
}

.layanan-container h2 {
    text-align: center;
    font-size: clamp(2.2rem, 5vw, 3rem);
    color: #26265A;
    margin-bottom: clamp(3rem, 6vh, 5rem);
    font-weight: 700;
    position: relative;
    font-family: 'Montserrat', sans-serif;
}

.layanan-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.layanan-card {
    background: #26265A;
    border-radius: 15px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
    cursor: pointer;
}

.layanan-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
}

.layanan-icon {
    width: 80px;
    height: 80px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    transition: transform 0.2s ease;
}

.layanan-card:hover .layanan-icon {
    transform: scale(1.03);
}

.layanan-icon i {
    font-size: 35px;
    color: #26265A;
}

.layanan-card h3 {
    font-size: clamp(1.3rem, 3vw, 1.5rem);
    color: #ffffff;
    margin-bottom: 20px;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
}

.layanan-card p {
    font-size: clamp(0.9rem, 2.2vw, 1rem);
    color: #e0e0e0;
    line-height: 1.6;
    margin-bottom: 30px;
}

.layanan-btn {
    display: inline-block;
    background: #ffffff;
    color: #26265A !important;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none !important;
    font-weight: 600;
    font-size: clamp(0.9rem, 2vw, 1rem);
    transition: all 0.2s ease;
    font-family: 'Montserrat', sans-serif;
    border: none; /* Hilangkan border default link */
    outline: none; /* Hilangkan outline */
}

.layanan-btn:hover {
    background: #f0f0f0;
    color: #26265A !important; /* Force warna hover tetap */
    text-decoration: none !important;
    transform: translateY(-1px);
}

.layanan-btn:visited {
    color: #26265A !important; /* Warna ketika sudah dikunjungi */
}

.layanan-btn:focus {
    color: #26265A !important;
    text-decoration: none !important;
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 38, 90, 0.2); /* Focus indicator yang bagus */
}

#attractions {
    width: 100%;
    padding: clamp(4rem, 10vh, 8rem) 0;
    background: #ffffff; 
    position: relative;
    overflow: hidden;
    margin-bottom: 100px;
    margin-top: 50px;
}

#attractions::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.02)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

#attractions .container {
    max-width: 1400px; /* Bigger container */
    margin: 0 auto;
    padding: 0 clamp(20px, 5vw, 40px);
    position: relative;
    z-index: 2;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 40px;
}

.section-info {
    text-align: left;
}

.section-subheading {
    color: #26265A;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 10px;
    font-family: 'Montserrat', sans-serif;
}

.section-heading {
    font-size: clamp(2rem, 4vw, 2.8rem);
    color: #26265A;
    margin: 0;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    line-height: 1.1;
}

.section-right {
    display: flex;
    align-items: flex-end;
    gap: 30px;
}

.nav-controls {
    display: flex;
    gap: 10px;
}

.nav-btn {
    width: 50px;
    height: 50px;
    border: none;
    background: #26265A;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.nav-btn:hover {
    background: #1f1f46;
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.page-counter {
    font-size: 3rem;
    font-weight: 800;
    font-family: 'Montserrat', sans-serif;
    color: #26265a;
    line-height: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.page-counter .numbers {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.page-counter .current {
    font-size: 3rem;
}

.page-counter .divider {
    color: #8b92b5;
    font-weight: 400;
    margin: 0 8px;
    font-size: 2.5rem;
}

.page-counter .total {
    color: #8b92b5;
    font-size: 2.5rem;
    font-weight: 600;
}

.progress-container {
    width: 120px;
    height: 4px;
    background: rgba(139, 146, 181, 0.3);
    border-radius: 2px;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #26265A, #8b92b5);
    border-radius: 2px;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(-90%);
}

/* ATTRACTIONS SLIDER - Bigger & Smoother */
.attractions-slider-container {
    width: 100%;
    height: 380px; /* Bigger height */
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 0;
}

.attractions-slider {
    display: flex;
    height: 100%;
    gap: 20px; /* Bigger gap */
    cursor: pointer;
    transition: transform 0.8s cubic-bezier(0.25, 0.1, 0.25, 1); /* Smoother transition */
    will-change: transform;
    width: max-content;
    padding: 0;
}

.attraction-card {
    width: 300px; /* Bigger width */
    min-width: 300px;
     background: #000;
    height: 100%;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    border-radius: 15px;
    flex-shrink: 0;
    transition: width 0.8s cubic-bezier(0.25, 0.1, 0.25, 1), box-shadow 0.4s ease; /* Smoother */
    will-change: width, box-shadow;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    flex-shrink: 0; /* Prevent shrinking */
    overflow: hidden; /* Hide any overflow */
}

.attraction-card.active {
    width: 700px; /* Bigger active width */
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.attraction-image {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: 15px;
}

.attraction-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.8s ease, filter 0.8s ease; /* Smoother image transition */
    will-change: transform, filter;
    display: block; /* Tambah ini */
    border-radius: 15px; /* Tambah ini */
}

.attraction-card .attraction-image::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60%;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
    pointer-events: none;
    z-index: 2;
    border-radius: 0 0 15px 15px; 
}

.attraction-card.active .attraction-image img {
    transform: scale(1.02); /* Less aggressive scale */
    filter: brightness(0.95);
}

.attraction-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 35px; /* Bigger padding */
    color: white;
    z-index: 3;
    opacity: 0;
    transition: opacity 0.8s ease, padding 0.8s ease; /* Smoother content transition */
    will-change: opacity, padding;
}

.attraction-card.active .attraction-content {
    opacity: 1;
}

.attraction-card:not(.active) .attraction-content {
    opacity: 1;
    padding: 20px 15px; /* Bigger padding for non-active */
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    bottom: auto;
    top: 0;
    text-align: center;
}

.attraction-card:not(.active) .attraction-content h3 {
    font-size: clamp(1.2rem, 2.2vw, 1.4rem); /* Slightly bigger */
    margin: 0;
    font-weight: 700;
    color: white; 
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
}

.attraction-content .location {
    font-size: clamp(1rem, 1.8vw, 1.1rem); /* Bigger location text */
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    margin-bottom: 18px; /* More space */
    display: flex;
    align-items: center;
    gap: 10px; /* Bigger gap */
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.attraction-content .location i {
    font-size: 1em;
}

.attraction-content .description {
    font-size: clamp(1rem, 2vw, 1.2rem); /* Bigger description */
    color: rgba(255, 255, 255, 0.95);
    font-weight: 400;
    line-height: 1.7; /* More line height */
    margin-top: 25px; /* More space */
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    max-width: 600px; /* Bigger max width */
}

.attraction-card:not(.active) .attraction-content .location,
.attraction-card:not(.active) .attraction-content .description {
    display: none;
}

/* BOTTOM SLIDER STYLES */
main { 
    width: min(1200px, 90vw); 
    margin: auto; 
    padding: 40px 0;
}

.slider { 
    width: 100%; 
    height: var(--height); 
    overflow: hidden; 
    mask-image: linear-gradient(to right, transparent, #000 10% 90%, transparent); 
}

.slider .list { 
    display: flex; 
    width: 100%; 
    min-width: calc(var(--width) * var(--quantity)); 
    position: relative; 
}

.slider .list .item { 
    width: var(--width); 
    height: var(--height); 
    position: absolute; 
    left: 100%; 
    animation: autoRun 10s linear infinite; 
    transition: filter 0.5s; 
    animation-delay: calc( (10s / var(--quantity)) * (var(--position) - 1) - 10s)!important; 
}

.slider .list .item img { 
    width: 100%; 
    border-radius: 8px;
}

@keyframes autoRun { 
    from { left: 100%; }
    to { left: calc(var(--width) * -1); } 
}

.slider:hover .item { 
    animation-play-state: paused!important; 
    filter: grayscale(1); 
}

.slider .item:hover { 
    filter: grayscale(0); 
}

.slider[reverse="true"] .item { 
    animation: reversePlay 10s linear infinite; 
}

@keyframes reversePlay { 
    from { left: calc(var(--width) * -1); }
    to { left: 100%; } 
}

/* RESPONSIVE DESIGN */
@media (max-width: 1024px) {
    .attractions-slider-container {
        max-width: 800px;
        height: 320px;
    }
    
    .attraction-card {
        width: 250px;
        min-width: 250px;
    }
}

@media (max-width: 768px) {
    .air { 
        top: 32%; 
        font-size: 100px; 
    }
    
    .air h1 { 
        font-size: 100px; 
    }
    
.button-container { 
    top: 55%; 
}

.search-box {
    min-width: 280px;
    padding: 8px 16px;
}

.search-input {
    font-size: 14px;
}

.suggestion-tag {
    font-size: 12px;
    padding: 5px 12px;
}

.explore-btn { 
    padding: 12px 32px; 
    font-size: 14px;
}
    
    .about-info { 
        margin-bottom: clamp(30px, 8vh, 60px); 
        font-size: clamp(1.1rem, 3vw, 1.2rem); 
        line-height: clamp(1.6rem, 4.5vw, 1.9rem); 
    }
    
    .about-info h2 { 
        font-size: clamp(2rem, 5vw, 2.8rem); 
        margin-bottom: clamp(1.5rem, 3vh, 2.5rem); 
    }
    
    .layanan-cards { 
        grid-template-columns: 1fr; 
        gap: 25px; 
    }
    
    .layanan-card { 
        padding: 35px 25px; 
    }
    
    .layanan-icon { 
        width: 70px; 
        height: 70px; 
        margin-bottom: 20px; 
    }
    
    .layanan-icon i { 
        font-size: 30px; 
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

    .section-right {
        align-self: center;
        gap: 20px;
    }

    .page-counter {
        font-size: 2.5rem;
    }

    .progress-container {
        width: 100px;
    }

    .attractions-slider-container {
        max-width: 600px;
        height: 280px;
    }
    
    .nav-btn {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }

    .attraction-card {
        width: 220px;
        min-width: 220px;
    }
}

@media (max-width: 480px) {
    .air { 
        top: 30%; 
        font-size: 80px; 
    }
    
    .air h1 { 
        font-size: 80px; 
    }
    
.button-container { 
    top: 52%; 
}

.search-box {
    min-width: 250px;
    padding: 6px 12px;
}

.search-input {
    font-size: 13px;
}

.suggestion-tag {
    font-size: 11px;
    padding: 4px 10px;
}

.explore-btn { 
    padding: 10px 28px; 
    font-size: 12px;
}

    .page-counter {
        font-size: 2rem;
    }

    .progress-container {
        width: 80px;
    }

    .attractions-slider-container {
        max-width: 320px;
        height: 240px;
        border-radius: 15px;
    }
    
    .nav-btn {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }

    .attraction-card {
        width: 200px;
        min-width: 200px;
    }

    .attraction-content {
        padding: 18px;
    }

    .attraction-content h3 {
        font-size: 1.2rem;
    }

    .attraction-content .location {
        font-size: 0.8rem;
    }

    .attraction-content .description {
        font-size: 0.75rem;
    }
}
        </style>
</head>
<body>
        @include('components.navbar')
    <section id="landing">
        <div class="hero">
            <img src="/images/11.png" alt="" class="last-img">
            <img src="/images/12.png" alt="" class="mid-img">
            <img src="/images/13.png" alt="" class="focus-img">
            <img src="/images/bird.png" alt="" class="bird-img">
            <img src="/images/16.png" alt="" class="cloud1-img">
            <img src="/images/17.png" alt="" class="cloud2-img">
            <img src="/images/18.png" alt="" class="cloud3-img">
        </div>

        <div class="button-container">
            <div class="search-container">
                <form class="search-form" action="{{ route('search.index') }}" method="GET">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input
                            type="text"
                            name="q"
                            placeholder="Search"
                            class="search-input"
                            value="{{ request('q') }}"
                        >
                    </div>
                </form>
            </div>
        </div>
        
        <div class="air">
            <h1>SELECTA</h1>
            <h1>SELECTA</h1>
            <h1>SELECTA</h1>
            <h1>SELECTA</h1>
        </div>
    </section>

    <section id="about">
        <div class="about-container">
            <div class="about-info">
                <h2>TENTANG SELECTA</h2>
                Selecta Malang adalah destinasi wisata keluarga yang telah menjadi ikon pariwisata Jawa Timur selama hampir satu abad. Terletak di kaki Gunung Arjuno dengan ketinggian 1.150 meter di atas permukaan laut, Selecta menawarkan udara sejuk pegunungan dan pemandangan alam yang memukau. Dengan luas area lebih dari 18 hektar, Selecta menghadirkan berbagai wahana permainan, kolam renang, taman bunga yang indah, dan fasilitas lengkap untuk memberikan pengalaman liburan yang tak terlupakan bagi seluruh anggota keluarga.
            </div>
        </div>
        
        <section>
            <div class="wave" id="wave1" style="--i:1;"></div>
            <div class="wave" id="wave2" style="--i:2;"></div>
            <div class="wave" id="wave3" style="--i:3;"></div>
            <div class="wave" id="wave4" style="--i:4;"></div>
        </section>
    </section>

    <section id="layanan-section">
        <div class="layanan-container">
            <h2>LAYANAN KAMI</h2>
            
            <div class="layanan-cards">
                <div class="layanan-card">
                    <div class="layanan-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>Tiket Masuk</h3>
                    <p>Dapatkan tiket masuk Selecta dengan harga terjangkau. Nikmati berbagai wahana dan atraksi menarik yang telah menjadi legendaris sejak 1930.</p>
                    <a href="{{ route('tickets.index') }}" class="layanan-btn">Pesan Tiket</a>
                </div>
                
                <div class="layanan-card">
                    <div class="layanan-icon">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <h3>Hotel & Penginapan</h3>
                    <p>Menginap di sekitar area Selecta dengan pilihan hotel dan penginapan yang nyaman. Rasakan pengalaman bermalam di kawasan wisata yang sejuk.</p>
                    <a href="{{ route('hotels.index') }}" class="layanan-btn">Pesan Hotel</a>
                </div>
                
                <div class="layanan-card">
                    <div class="layanan-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Kuliner & Restoran</h3>
                    <p>Cicipi kelezatan kuliner khas Malang dan berbagai hidangan lezat di restoran-restoran pilihan di area Selecta dan sekitarnya dengan harga yang terjangkau.</p>
                    <a href="{{ route('restaurants.index') }}" class="layanan-btn">Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>

    <section id="attractions">
        <div class="container">
            <div class="section-header">
                <div class="section-info">
                    <div class="section-subheading">Wahana</div>
                    <h1 class="section-heading">Top Wahana Selecta</h1>
                </div>
                <div class="section-right">
                    <div class="nav-controls">
                        <button class="nav-btn prev"><i class="fas fa-chevron-left"></i></button>
                        <button class="nav-btn next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="page-counter">
                        <div class="numbers">
                            <span class="current">01</span>
                            <span class="divider">/</span>
                            <span class="total">15</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="attractions-slider-container">
                <div class="attractions-slider">
                    @if(isset($topAttractions) && $topAttractions->count())
                        @foreach($topAttractions as $index => $attraction)
                            <div class="attraction-card {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                <div class="attraction-image">
                                    @php
                                        $imageUrl = $attraction->image_url ?? '/images/familycoaster.png';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $attraction->title }}">
                                </div>
                                <div class="attraction-content">
                                    <h3>{{ $attraction->title }}</h3>
                                    <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $attraction->location ?? 'Selecta Malang' }}</p>
                                    @if($attraction->description)
                                        <p class="description">{{ $attraction->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="attraction-card active" data-index="0">
                            <div class="attraction-image">
                                <img src="/images/familycoaster.png" alt="Family Coaster">
                            </div>
                            <div class="attraction-content">
                                <h3>Family Coaster</h3>
                                <p class="location"><i class="fas fa-map-marker-alt"></i> Selecta Malang</p>
                                <p class="description">Family Coaster menyajikan petualangan yang tak terlupakan bagi anda, anda akan dibawa melalui rute yang rindang dan pemandangan yang segar khas Selecta.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script>
        // Compact and efficient version
function debugElements() {
    console.log("=== DEBUG ===");
    console.log("All .air:", document.querySelectorAll(".air"));
    console.log("All .air h1:", document.querySelectorAll(".air h1"));
}

// Get elements efficiently
const elements = {
    lastImg: document.getElementsByClassName("last-img")[0],
    midImg: document.getElementsByClassName("mid-img")[0],
    birdImg: document.getElementsByClassName("bird-img")[0],
    focusImg: document.getElementsByClassName("focus-img")[0],
    btn: document.querySelector(".btn"),
    wave1: document.getElementById('wave1'),
    wave2: document.getElementById('wave2'),
    wave3: document.getElementById('wave3'),
    wave4: document.getElementById('wave4')
};

let Selecta = null;

// DOM Content Loaded - Initialize everything
document.addEventListener('DOMContentLoaded', function() {
    // Find text elements with fallback
    const selectors = [".air h1", "#landing .air h1", "#landing h1"];
    for (let selector of selectors) {
        const found = document.querySelectorAll(selector);
        if (found.length > 0) {
            Selecta = found;
            console.log(`Using ${selector} - found ${found.length} elements`);
            break;
        }
    }
    if (!Selecta) {
        console.log("NO H1 ELEMENTS FOUND!");
        debugElements();
    }

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('animate-in');
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.layanan-card, .about-info, .section-heading')
        .forEach(el => observer.observe(el));

    // Initialize new horizontal attractions slider
    initHorizontalAttractionsSlider();

    initSearchFunctionality();
});

// Single scroll handler for performance
window.addEventListener('scroll', function() {
    const value = window.scrollY;
    const windowHeight = window.innerHeight;
    
    // Wave animations
    if (elements.wave1) elements.wave1.style.backgroundPositionX = (400 + value * 4) + 'px';
    if (elements.wave2) elements.wave2.style.backgroundPositionX = (300 + value * -4) + 'px';
    if (elements.wave3) elements.wave3.style.backgroundPositionX = (200 + value * 2) + 'px';
    if (elements.wave4) elements.wave4.style.backgroundPositionX = (100 + value * -2) + 'px';
    
    // Parallax effects
    if (elements.birdImg) {
        elements.birdImg.style.left = (value * 0.9) + 'px';
        elements.birdImg.style.top = (value * 0.7) + 'px';
    }
    if (elements.lastImg) elements.lastImg.style.top = (value * 0.7) + 'px';
    if (elements.midImg) elements.midImg.style.top = (value * 0.3) + 'px';
    if (elements.focusImg) elements.focusImg.style.top = (value * 0) + 'px';
    
    // Text elements animation
    if (Selecta?.length > 0) {
        const translateY = value * 0.5;
        const scale = Math.max(0.3, 1 - (value * 0.001));
        const transform = `translateX(-50%) translateY(${translateY}px) scale(${scale})`;
        
        Selecta.forEach(element => {
            if (element) {
                element.style.transform = transform;
                element.style.transition = 'none';
            }
        });
    }
    
    // Button opacity
    if (elements.btn) {
        elements.btn.style.opacity = Math.max(0, 1 - (value / (windowHeight * 0.5)));
    }
    
    // Reset when at top
    if (value === 0) {
        if (Selecta?.length > 0) {
            Selecta.forEach(element => {
                if (element) element.style.transform = 'translateX(-50%) translateY(0px) scale(1)';
            });
        }
        if (elements.btn) elements.btn.style.opacity = '1';
    }
});

function initHorizontalAttractionsSlider() {
    console.log('Initializing infinite slider...');

    const slider = document.querySelector('.attractions-slider');
    let originalCards = document.querySelectorAll('.attraction-card');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');
    const currentSpan = document.querySelector('.page-counter .current');
    const totalSpan = document.querySelector('.page-counter .total');
    const progressBar = document.querySelector('.progress-bar');

    if (!slider || !originalCards.length) {
        console.log('Slider elements not found');
        return;
    }

    const totalOriginal = originalCards.length; // 15 cards
    const cardWidth = 320; // width + gap
    let currentIndex = 0; // start at 0 (first original card)

    if (totalSpan) {
        totalSpan.textContent = String(totalOriginal).padStart(2, '0');
    }
    let isTransitioning = false;

    // Clone semua cards untuk infinite effect
    originalCards.forEach(card => {
        const clone = card.cloneNode(true);
        slider.appendChild(clone);
    });

    // Refresh untuk ambil semua cards (original + clones)
    const allCards = document.querySelectorAll('.attraction-card');
    console.log(`Total cards: ${allCards.length} (${totalOriginal} originals + ${totalOriginal} clones)`);

    // Set initial position
    slider.style.transform = `translateX(0px)`;
    
    function updateDisplay() {
        // Remove all active classes
        allCards.forEach(card => card.classList.remove('active'));
        
        // Add active to current card
        const activeIndex = currentIndex % totalOriginal;
        if (allCards[activeIndex]) {
            allCards[activeIndex].classList.add('active');
        }
        
        // Update counter (always 1-15)
        const displayIndex = activeIndex + 1;
        if (currentSpan) {
            currentSpan.textContent = String(displayIndex).padStart(2, '0');
        }
        
        // Update progress bar
        if (progressBar) {
            const progress = (displayIndex / totalOriginal) * 100;
            progressBar.style.transform = `translateX(${-100 + progress}%)`;
        }
    }

 // GANTI fungsi slideToPosition() dengan ini:
function slideToPosition() {
    if (isTransitioning) return;
    isTransitioning = true;
    
    // Calculate offset dengan spacing yang tepat
    const offset = -currentIndex * cardWidth;
    slider.style.transition = 'transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
    slider.style.transform = `translateX(${offset}px)`;
    
    updateDisplay();
    
    setTimeout(() => {
        if (currentIndex >= totalOriginal) {
            slider.style.transition = 'none';
            currentIndex = 0;
            slider.style.transform = `translateX(0px)`; // Reset ke posisi 0
        } else if (currentIndex < 0) {
            slider.style.transition = 'none';
            currentIndex = totalOriginal - 1;
            const offset = -currentIndex * cardWidth;
            slider.style.transform = `translateX(${offset}px)`;
        }
        
        isTransitioning = false;
        updateDisplay();
    }, 600);
}

    function nextSlide() {
        if (isTransitioning) return;
        currentIndex++;
        slideToPosition();
    }

    function prevSlide() {
        if (isTransitioning) return;
        currentIndex--;
        slideToPosition();
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoSlide();
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoSlide();
        });
    }

    // Click on cards
    allCards.forEach((card, index) => {
        card.addEventListener('click', () => {
            if (isTransitioning) return;
            const realIndex = index % totalOriginal;
            currentIndex = realIndex;
            slideToPosition();
            resetAutoSlide();
        });
    });

    // Auto slide
    let autoSlideInterval = setInterval(nextSlide, 7000);
    
    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(nextSlide, 7000);
    }

    // API for search
    window.sliderAPI = {
        cards: allCards,
        setIndex: (index) => {
            if (!isTransitioning) {
                currentIndex = index % totalOriginal;
                slideToPosition();
            }
        }
    };

    // Initialize
    updateDisplay();
    console.log('Infinite slider ready!');
}

function initSearchFunctionality() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    const suggestionTags = document.querySelectorAll('.suggestion-tag');
    
    // Form sekarang submit normal ke backend (route search.index)
    // jadi tidak perlu e.preventDefault() di sini.

    // Handle suggestion tag clicks (isi input lalu submit form)
    suggestionTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const query = this.textContent;
            if (searchInput) {
                searchInput.value = query;
                if (searchForm) {
                    searchForm.submit();
                }
            }
        });
    });
    
    // Auto-focus search input on page load
    if (searchInput) {
        setTimeout(() => {
            searchInput.focus();
        }, 1000);
    }
}

function handleSearch(query) {
    console.log('Searching for:', query);

    const attractionsSection = document.getElementById('attractions');
    if (attractionsSection) {
        attractionsSection.scrollIntoView({ behavior: 'smooth' });
    }

    // Cari matching card
    const { cards, setIndex } = window.sliderAPI || {};
    if (!cards) return;

    cards.forEach((card, index) => {
        const title = card.querySelector('h3')?.textContent.toLowerCase();
        if (title && title.includes(query.toLowerCase())) {
            setTimeout(() => setIndex(index), 1000);
            return;
        }
    });
}

// Debug on load
window.addEventListener('load', function() {
    console.log("Page loaded - debugging elements:");
    debugElements();
});
    </script>
         <x-footer />
</body>
</html>