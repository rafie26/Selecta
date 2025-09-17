<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Selecta - Wisata Keluarga Terbaik</title>
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
    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" placeholder="Search" class="search-input">
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
         <h2>Tentang Selecta</h2>
        Selecta Malang adalah destinasi wisata keluarga yang telah menjadi ikon pariwisata Jawa Timur selama hampir satu abad. Terletak di kaki Gunung Arjuno dengan ketinggian 1.150 meter di atas permukaan laut, Selecta menawarkan udara sejuk pegunungan dan   pemandangan alam yang memukau. Dengan luas area lebih dari 18 hektar, Selecta menghadirkan berbagai wahana permainan, kolam renang, taman bunga yang indah, dan fasilitas lengkap untuk memberikan pengalaman liburan yang tak terlupakan bagi seluruh anggota keluarga.
    </div>
    </div>
    
    <section>
        <div class="wave" id="wave1" style="--i:1;"></div>
        <div class="wave" id="wave2" style="--i:2;"></div>
        <div class="wave" id="wave3" style="--i:3;"></div>
        <div class="wave" id="wave4" style="--i:4;"></div>
    </section>
</section>

<section id="wahana-section">
    <div class="wahana-container">
        <h2>TOP WAHANA</h2>
        <p class="wahana-subtitle">Jelajahi destinasi wahana terpopuler yang wajib dikunjungi</p>
        
        <div class="slider-container">
            <div class="slider-wrapper">
                <div class="wahana-card">
                    <img src="/images/sepedaudara.png" alt="Sepeda Udara">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Sepeda Udara</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/bahteraayun.png" alt="Ontang-Anting">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Bahtera Ayun</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/bianglala.png" alt="Mobil-mobilan">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Bianglala</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/familycoaster.png" alt="Roller Coaster">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Family Coaster</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/flyingfox.png" alt="Flying Fox">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Flying Fox</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/waterpark.png" alt="Water Park">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Water Park</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/cinema4d.png" alt="Water Park">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Cinema 4D</h3>
                    </div>
                </div>
                <div class="wahana-card">
                    <img src="/images/sepedaair.png" alt="Water Park">
                    <div class="wahana-card-content">
                        <h3 class="wahana-card-title">Sepeda Air</h3>
                    </div>
                </div>
            </div>
            <div class="slider-nav">
                <button class="nav-btn prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="nav-btn next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

    <script src="js/script.js"></script>
</body>
</html>