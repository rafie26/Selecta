<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Selecta Wisata</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.4)), 
                        url('https://s-light.tiket.photos/t/01E25EBZS3W0FY9GTG6C42E1SE/rsfit1440960gsm/events/2024/08/22/1b772b63-4795-4e3d-a099-462b3332d925-1724309286961-69b32002690362d59642546692bed3d6.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 40px;
            color: white;
            position: relative;
        }

        .left-content {
            flex: 1;
            max-width: 450px;
            animation: slideInLeft 1s ease-out;
            margin-right: 60px;
            margin-left: 60px;
        }

        .logo {
            font-size: 24px;
            font-weight: 300;
            letter-spacing: 3px;
            margin-bottom: 60px;
            text-transform: uppercase;
        }

        .logo svg {
            height: 50px;
            width: auto;
            fill: white;
        }

        .main-heading {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 30px;
            color: rgba(255,255,255,0.9);
        }

        .description {
            font-size: 1rem;
            line-height: 1.6;
            color: rgba(255,255,255,0.8);
            max-width: 400px;
        }

        .auth-container {
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            width: 380px;
            height: fit-content;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            animation: slideInRight 1s ease-out;
            margin-left: 60px;
        }

        .auth-form h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
            transform: translateY(-1px);
        }

        .form-group input::placeholder {
            color: #aaa;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 25px;
        }

        .forgot-password a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .primary-btn {
            width: 100%;
            padding: 14px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .primary-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,123,255,0.3);
        }

        .primary-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: white;
            position: relative;
        }

        .divider::before {
            display: none;
        }

        .divider span {
            background: transparent;
            padding: 0 20px;
            position: relative;
            z-index: 2;
            font-size: 0.9rem;
            color: white;
        }

        .google-btn {
            width: 100%;
            padding: 12px;
            background: white;
            color: #333;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .google-btn:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .google-icon {
            width: 20px;
            height: 20px;
            background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="%234285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="%2334A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="%23FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="%23EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        .auth-switch {
            text-align: center;
            color: white;
            font-size: 0.95rem;
            margin-top: 10px;
        }

        .auth-switch a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }

        .back-home {
            position: absolute;
            top: 30px;
            left: 30px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 12px 18px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
        }

        .back-home:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .arrow-icon {
            font-size: 16px;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 1024px) {
            body {
                flex-direction: column;
                padding: 20px;
                justify-content: center;
            }

            .left-content {
                text-align: center;
                margin-bottom: 30px;
                margin-right: 0;
                margin-left: 0;
            }

            .main-heading {
                font-size: 2.8rem;
            }

            .auth-container {
                width: 100%;
                max-width: 480px;
                margin-left: 0;
            }

            .back-home {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
                display: inline-flex;
            }
        }

        @media (max-width: 768px) {
            .main-heading {
                font-size: 2.2rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .auth-container {
                padding: 30px 20px;
            }
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-element {
            position: absolute;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 140px;
            height: 140px;
            top: 65%;
            right: 12%;
            animation-delay: 3s;
        }

        .floating-element:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 35%;
            right: 25%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.6;
            }
            50% {
                transform: translateY(-25px) rotate(180deg);
                opacity: 0.8;
            }
        }

        .hidden {
            display: none;
        }

        .show {
            display: block;
        }

        /* Profile styles */
        .profile-container {
            position: absolute;
            top: 30px;
            right: 30px;
            display: none;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.15);
            padding: 10px 15px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            animation: slideInProfile 0.5s ease-out;
        }

        .profile-container:hover {
            background: rgba(255,255,255,0.25);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            background: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        .profile-info {
            color: white;
            cursor: pointer;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .profile-email {
            font-size: 12px;
            opacity: 0.8;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            min-width: 200px;
            overflow: hidden;
            display: none;
            z-index: 1000;
        }

        .profile-dropdown.show {
            display: block;
            animation: dropdownSlide 0.3s ease-out;
        }

        .dropdown-item {
            padding: 12px 15px;
            color: #333;
            cursor: pointer;
            transition: background 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item:last-child {
            border-bottom: none;
            color: #dc3545;
        }

        .dropdown-item:last-child:hover {
            background: #fee;
        }

        @keyframes slideInProfile {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .profile-container {
                position: relative;
                top: auto;
                right: auto;
                margin-bottom: 20px;
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="back-home" onclick="goBack()">
        <span class="arrow-icon">←</span>
        Kembali
    </button>

    <!-- Profile Container (hidden by default) -->
    <div class="profile-container" id="profileContainer">
        <div class="profile-avatar" id="profileAvatar" onclick="toggleProfileDropdown()">
            U
        </div>
        <div class="profile-info" onclick="toggleProfileDropdown()">
            <div class="profile-name" id="profileName">User Name</div>
            <div class="profile-email" id="profileEmail">user@email.com</div>
        </div>
        <div class="profile-dropdown" id="profileDropdown">
            <div class="dropdown-item" onclick="editProfile()">
                👤 Edit Profil
            </div>
            <div class="dropdown-item" onclick="viewBookings()">
                🎫 Pemesanan Saya
            </div>
            <div class="dropdown-item" onclick="viewSettings()">
                ⚙️ Pengaturan
            </div>
            <div class="dropdown-item" onclick="logout()">
                🚪 Keluar
            </div>
        </div>
    </div>

    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <div class="left-content">
        <div class="logo">
            <svg viewBox="0 0 200 60" xmlns="https://selectawisata.id/wp-content/uploads/2020/10/logo-selecta-line-putih-png-1.png">
                <text x="10" y="35" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="white">SELECTA</text>
                <circle cx="180" cy="30" r="15" fill="none" stroke="white" stroke-width="2"/>
                <path d="M170 30 L175 25 L185 35 L190 20" stroke="white" stroke-width="2" fill="none"/>
            </svg>
        </div>
        <h1 class="main-heading">Explore<br>Selecta</h1>
        <p class="subtitle">Dimana Destinasi Impian Anda Menjadi Kenyataan.</p>
        <p class="description">

            .profile-name {
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 2px;
            }

            .profile-email {
                font-size: 12px;
                opacity: 0.8;
            }

            .profile-dropdown {
                position: absolute;
                top: 100%;
                right: 0;
                margin-top: 10px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                min-width: 200px;
                overflow: hidden;
                display: none;
                z-index: 1000;
            }

            .profile-dropdown.show {
                display: block;
                animation: dropdownSlide 0.3s ease-out;
            }

            .dropdown-item {
                padding: 12px 15px;
                color: #333;
                cursor: pointer;
                transition: background 0.2s ease;
                border-bottom: 1px solid #f0f0f0;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .dropdown-item:hover {
                background: #f8f9fa;
            }

            .dropdown-item:last-child {
                border-bottom: none;
                color: #dc3545;
            }

            .dropdown-item:last-child:hover {
                background: #fee;
            }
            <h2>Buat Akun Baru</h2>
            
            <div class="form-group">
                <label for="registerName">Nama Lengkap *</label>
                <input type="text" 
                       id="registerName" 
                       name="name" 
                       placeholder="Masukkan nama lengkap Anda" 
                       required>
                <div class="error-message" id="registerNameError"></div>
            </div>

            <div class="form-group">
                <label for="registerEmail">Alamat Email *</label>
                <input type="email" 
                       id="registerEmail" 
                       name="email" 
                       placeholder="contoh@email.com" 
                       required>
                <div class="error-message" id="registerEmailError"></div>
            </div>

            <div class="form-group">
                <label for="registerPassword">Kata Sandi *</label>
                <input type="password" 
                       id="registerPassword" 
                       name="password" 
                       placeholder="Minimal 8 karakter" 
                       required>
                <div class="error-message" id="registerPasswordError"></div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 0 0 120px;">
                    <label for="phoneCode">Kode Negara</label>
                    <select id="phoneCode" name="phone_code">
                        <option value="+62">🇮🇩 +62</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+65">🇸🇬 +65</option>
                        <option value="+60">🇲🇾 +60</option>
                        <option value="+84">🇻🇳 +84</option>
                        <option value="+66">🇹🇭 +66</option>
                        <option value="+63">🇵🇭 +63</option>
                        <option value="+86">🇨🇳 +86</option>
                        <option value="+81">🇯🇵 +81</option>
                        <option value="+82">🇰🇷 +82</option>
                        <option value="+91">🇮🇳 +91</option>
                        <option value="+44">🇬🇧 +44</option>
                        <option value="+33">🇫🇷 +33</option>
                        <option value="+49">🇩🇪 +49</option>
                        <option value="+61">🇦🇺 +61</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="registerPhone">Nomor Telepon *</label>
                    <input type="tel" 
                           id="registerPhone" 
                           name="phone" 
                           placeholder="Masukkan nomor telepon" 
                           required>
                    <div class="error-message" id="registerPhoneError"></div>
                </div>
            </div>

            <button type="submit" class="primary-btn" id="registerSubmitBtn">
                Daftar
            </button>

            <div class="divider">
                <span>atau</span>
            </div>

            <button type="button" class="google-btn" onclick="registerWithGoogle()">
                <div class="google-icon"></div>
                Daftar dengan Google
            </button>

            <div class="auth-switch">
                Sudah punya akun?
                <a href="#" onclick="showLogin()">Masuk di sini</a>
            </div>
        </form>
    </div>

<script>
    function showLogin() {
        document.getElementById('loginForm').classList.remove('hidden');
        document.getElementById('registerForm').classList.add('hidden');
    }

    function showRegister() {
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('registerForm').classList.remove('hidden');
    }

    function handleLogin(event) {
        event.preventDefault();
        const submitBtn = document.getElementById('loginSubmitBtn');
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        submitBtn.textContent = 'Sedang Masuk...';
        submitBtn.disabled = true;
        
        // Simulate login process
        setTimeout(() => {
            // Simulasi data user (nanti diganti dengan response dari backend)
            const userData = {
                name: 'Ahmad Rizki',
                email: email,
                avatar: null // bisa diisi dengan URL foto nanti
            };
            
            // Tampilkan profil
            showProfile(userData);
            
            submitBtn.textContent = 'Masuk';
            submitBtn.disabled = false;
        }, 2000);
    }

    function handleRegister(event) {
        event.preventDefault();
        const submitBtn = document.getElementById('registerSubmitBtn');
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        
        submitBtn.textContent = 'Sedang Mendaftar...';
        submitBtn.disabled = true;
        
        // Simulate register process
        setTimeout(() => {
            // Simulasi data user baru
            const userData = {
                name: name,
                email: email,
                avatar: null
            };
            
            // Langsung login setelah register berhasil
            showProfile(userData);
            
            submitBtn.textContent = 'Daftar';
            submitBtn.disabled = false;
        }, 2000);
    }

    function signInWithGoogle() {
        alert('Login dengan Google akan segera tersedia!');
    }

    function registerWithGoogle() {
        alert('Daftar dengan Google akan segera tersedia!');
    }

    // Fungsi tombol kembali yang dinamis
    function goBack() {
        // Cek apakah ada history sebelumnya
        if (window.history.length > 1) {
            // Kembali ke halaman sebelumnya
            window.history.back();
        } else {
            // Jika tidak ada history, arahkan ke halaman utama
            // Ganti dengan URL homepage yang sesuai
            window.location.href = '/';
            // Atau jika ingin menampilkan pesan
            // alert('Tidak ada halaman sebelumnya. Mengarahkan ke halaman utama...');
        }
    }

    // State management untuk login
    let isLoggedIn = false;
    let currentUser = null;

    // Fungsi untuk menampilkan profil di navbar setelah login
    function showProfile(userData) {
        isLoggedIn = true;
        currentUser = userData;
        
        // Update profil data
        document.getElementById('profileName').textContent = userData.name;
        document.getElementById('profileEmail').textContent = userData.email;
        
        // Set avatar dengan inisial nama
        const initials = userData.name.split(' ').map(n => n[0]).join('').toUpperCase();
        document.getElementById('profileAvatar').textContent = initials;
        
        // Sembunyikan tombol kembali dan tampilkan profil
        document.querySelector('.back-home').style.display = 'none';
        document.getElementById('profileContainer').style.display = 'flex';
        
        // HALAMAN TETAP SAMA - tidak mengubah konten utama
        // Form tetap ada, user bisa logout dan login lagi kalau mau
    }

    // Fungsi dropdown profil
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('show');
    }

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', function(event) {
        const profileContainer = document.getElementById('profileContainer');
        const dropdown = document.getElementById('profileDropdown');
        
        if (!profileContainer.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });

    // Fungsi menu profil
    function editProfile() {
        alert('Fitur edit profil akan segera tersedia!');
        document.getElementById('profileDropdown').classList.remove('show');
    }

    function viewBookings() {
        alert('Mengarahkan ke halaman pemesanan...');
        document.getElementById('profileDropdown').classList.remove('show');
    }

    function viewSettings() {
        alert('Membuka pengaturan akun...');
        document.getElementById('profileDropdown').classList.remove('show');
    }

    function logout() {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
            // Reset state
            isLoggedIn = false;
            currentUser = null;
            
            // Tampilkan kembali tombol kembali, sembunyikan profil
            document.querySelector('.back-home').style.display = 'flex';
            document.getElementById('profileContainer').style.display = 'none';
            
            // Clear form inputs
            document.querySelectorAll('input').forEach(input => input.value = '');
            
            alert('Anda telah berhasil keluar.');
        }
        document.getElementById('profileDropdown').classList.remove('show');
    }

    // Enhanced input interactions
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Real-time validation
            input.addEventListener('input', function() {
                clearErrorMessage(this);
            });
        });
    });

    function clearErrorMessage(input) {
        const errorElement = input.parentElement.querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = '';
        }
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const registerForm = document.getElementById('registerForm');
            if (!registerForm.classList.contains('hidden')) {
                showLogin();
            }
        }
    });
</script>
</body>
</html>