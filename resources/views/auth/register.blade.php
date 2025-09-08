<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Selecta Wisata</title>
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
    </style>
</head>
<body>
    <button class="back-home" onclick="goBack()">
        <span class="arrow-icon">←</span>
        Kembali
    </button>

    <div class="left-content">
        <div class="logo">
            <svg viewBox="0 0 200 60">
                <text x="10" y="35" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="white">SELECTA</text>
                <circle cx="180" cy="30" r="15" fill="none" stroke="white" stroke-width="2"/>
                <path d="M170 30 L175 25 L185 35 L190 20" stroke="white" stroke-width="2" fill="none"/>
            </svg>
        </div>
        <h1 class="main-heading">Bergabung<br>Bersama</h1>
        <p class="subtitle">Dimana Destinasi Impian Anda Menjadi Kenyataan.</p>
        <p class="description">
            Daftar sekarang dan nikmati pengalaman wisata terbaik di Selecta. 
            Dapatkan akses ke berbagai fasilitas eksklusif dan penawaran menarik yang tersedia untuk seluruh keluarga.
        </p>
    </div>

    <!-- REGISTER FORM -->
    <div class="auth-container">
        <form class="auth-form" method="POST" action="{{ route('register') }}" onsubmit="handleRegister(event)">
            @csrf
            <h2>Buat Akun Baru</h2>
            
            <div class="form-group">
                <label for="registerName">Nama Lengkap *</label>
                <input type="text" 
                       id="registerName" 
                       name="name" 
                       placeholder="Masukkan nama lengkap Anda" 
                       value="{{ old('name') }}"
                       required>
                <div class="error-message" id="registerNameError">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="registerEmail">Alamat Email *</label>
                <input type="email" 
                       id="registerEmail" 
                       name="email" 
                       placeholder="contoh@email.com" 
                       value="{{ old('email') }}"
                       required>
                <div class="error-message" id="registerEmailError">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 0 0 120px;">
                    <label for="phoneCode">Kode Negara</label>
                    <select id="phoneCode" name="phone_code">
                        <option value="+62" {{ old('phone_code') == '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                        <option value="+1" {{ old('phone_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                        <option value="+65" {{ old('phone_code') == '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                        <option value="+60" {{ old('phone_code') == '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="registerPhone">Nomor Telepon *</label>
                    <input type="tel" 
                           id="registerPhone" 
                           name="phone" 
                           placeholder="Masukkan nomor telepon" 
                           value="{{ old('phone') }}"
                           required>
                    <div class="error-message" id="registerPhoneError">
                        @error('phone')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="registerPassword">Kata Sandi *</label>
                <input type="password" 
                       id="registerPassword" 
                       name="password" 
                       placeholder="Minimal 8 karakter" 
                       required>
                <div class="error-message" id="registerPasswordError">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="registerPasswordConfirm">Konfirmasi Kata Sandi *</label>
                <input type="password" 
                       id="registerPasswordConfirm" 
                       name="password_confirmation" 
                       placeholder="Ulangi kata sandi Anda" 
                       required>
                <div class="error-message" id="registerPasswordConfirmError"></div>
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
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </form>
    </div>

<script>
    function handleRegister(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = document.getElementById('registerSubmitBtn');
        const formData = new FormData(form);
        
        // Check password confirmation
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== confirmPassword) {
            document.getElementById('registerPasswordConfirmError').textContent = 'Konfirmasi kata sandi tidak cocok';
            return;
        }
        
        submitBtn.textContent = 'Sedang Mendaftar...';
        submitBtn.disabled = true;
        
        // Clear previous errors
        clearErrors();
        
        fetch('/register', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to landing page
                window.location.href = data.redirect || '/';
            } else {
                // Show errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const errorElement = document.getElementById('register' + key.charAt(0).toUpperCase() + key.slice(1) + 'Error');
                        if (errorElement) {
                            errorElement.textContent = data.errors[key][0];
                        }
                    });
                }
                // Show general error message in name field if no specific errors
                if (!data.errors) {
                    const nameError = document.getElementById('registerNameError');
                    if (nameError) {
                        nameError.textContent = data.message || 'Registrasi gagal!';
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const nameError = document.getElementById('registerNameError');
            if (nameError) {
                nameError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            }
        })
        .finally(() => {
            submitBtn.textContent = 'Daftar';
            submitBtn.disabled = false;
        });
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => {
            element.textContent = '';
        });
    }

    function registerWithGoogle() {
        window.location.href = '{{ route("auth.google") }}';
    }

    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/';
        }
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
                const errorElement = this.parentElement.querySelector('.error-message');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            });
        });

        // Real-time password confirmation check
        const passwordConfirm = document.getElementById('registerPasswordConfirm');
        const password = document.getElementById('registerPassword');
        
        passwordConfirm.addEventListener('input', function() {
            const confirmError = document.getElementById('registerPasswordConfirmError');
            if (this.value && password.value && this.value !== password.value) {
                confirmError.textContent = 'Konfirmasi kata sandi tidak cocok';
            } else {
                confirmError.textContent = '';
            }
        });

        password.addEventListener('input', function() {
            const confirmError = document.getElementById('registerPasswordConfirmError');
            const confirmPassword = passwordConfirm.value;
            if (confirmPassword && this.value && confirmPassword !== this.value) {
                confirmError.textContent = 'Konfirmasi kata sandi tidak cocok';
            } else {
                confirmError.textContent = '';
            }
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            goBack();
        }
    });
</script>
</body>
</html>