<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register - Selecta Wisata</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .register-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
        }

        .register-visual {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .register-visual::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .visual-content {
            position: relative;
            z-index: 2;
        }

        .visual-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .visual-icon::before {
            content: '👑';
            font-size: 48px;
        }

        .visual-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .visual-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            max-width: 300px;
        }

        .register-form-container {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            background: #f8fafc;
            color: #1e293b;
        }

        .form-group input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input:hover:not(:focus) {
            border-color: #cbd5e1;
            background: white;
        }

        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            background: #f8fafc;
            color: #1e293b;
            cursor: pointer;
        }

        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group select:hover:not(:focus) {
            border-color: #cbd5e1;
            background: white;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 8px;
            margin-left: 4px;
            opacity: 0;
            animation: fadeInError 0.3s ease forwards;
        }

        .error-message:not(:empty) {
            opacity: 1;
        }

        @keyframes fadeInError {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .submit-btn {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 24px;
            position: relative;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .form-links {
            text-align: center;
            margin-top: 24px;
        }

        .form-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .form-links a:hover {
            color: #764ba2;
        }

        .divider {
            margin: 24px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 16px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .info-card {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-top: 24px;
        }

        .info-card p {
            color: #475569;
            font-size: 0.85rem;
            line-height: 1.5;
            margin: 0;
        }

        .info-card strong {
            color: #1e293b;
        }

        /* Loading state */
        .submit-btn.loading {
            position: relative;
            color: transparent;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-left: -9px;
            margin-top: -9px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .register-wrapper {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
            
            .register-visual {
                padding: 40px 30px;
            }
            
            .visual-title {
                font-size: 2rem;
            }
            
            .register-form-container {
                padding: 40px 30px;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .register-visual {
                padding: 30px 20px;
            }
            
            .register-form-container {
                padding: 30px 20px;
            }
        }

        /* Enhanced focus styles for accessibility */
        .submit-btn:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }

        .form-group input:focus-visible {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <!-- Visual Side -->
        <div class="register-visual">
            <div class="visual-content">
                <div class="visual-icon"></div>
                <h1 class="visual-title">Admin Portal</h1>
                <p class="visual-subtitle">Bergabunglah dengan tim administrator Selecta dan kelola sistem wisata terbaik</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="register-form-container">
            <div class="form-header">
                <h2 class="form-title">Daftar Staff</h2>
                <p class="form-subtitle">Buat akun staff baru (Admin / Petugas)</p>
            </div>

            <form method="POST" action="{{ route('admin.register') }}" onsubmit="handleAdminRegister(event)">
                @csrf
                
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <input type="text" 
                               id="name" 
                               name="name" 
                               placeholder="Masukkan nama lengkap" 
                               value="{{ old('name') }}"
                               required>
                    </div>
                    <div class="error-message" id="nameError">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="Masukkan email admin" 
                               value="{{ old('email') }}"
                               required>
                    </div>
                    <div class="error-message" id="emailError">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon (Opsional)</label>
                    <div class="input-wrapper">
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               placeholder="Masukkan nomor telepon" 
                               value="{{ old('phone') }}">
                    </div>
                    <div class="error-message" id="phoneError">
                        @error('phone')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Role / Jabatan</label>
                    <div class="input-wrapper">
                        <select id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Admin - Akses Penuh Sistem</option>
                            <option value="petugas_loket" {{ old('role') == 'petugas_loket' ? 'selected' : '' }}>🎫 Petugas Loket - Kelola Tiket</option>
                            <option value="petugas_hotel" {{ old('role') == 'petugas_hotel' ? 'selected' : '' }}>🏨 Petugas Hotel - Kelola Hotel</option>
                        </select>
                    </div>
                    <div class="error-message" id="roleError">
                        @error('role')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan kata sandi (min. 8 karakter)" 
                               required>
                    </div>
                    <div class="error-message" id="passwordError">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Ulangi kata sandi" 
                               required>
                    </div>
                    <div class="error-message" id="password_confirmationError">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    Daftar Akun
                </button>
            </form>

            <div class="info-card">
                <p style="margin-bottom: 12px;"><strong>Perbedaan Role:</strong></p>
                <p style="margin-bottom: 8px; font-size: 0.8rem;">👑 <strong>Admin:</strong> Akses penuh ke sistem (Users, Restaurants, Hotel Photos)</p>
                <p style="margin-bottom: 8px; font-size: 0.8rem;">🎫 <strong>Petugas Loket:</strong> Kelola paket tiket dan booking tiket</p>
                <p style="margin-bottom: 8px; font-size: 0.8rem;">🏨 <strong>Petugas Hotel:</strong> Kelola tipe kamar dan booking hotel</p>
                <p style="margin-top: 12px; font-size: 0.8rem;"><strong>Keamanan:</strong> Gunakan kata sandi yang kuat dan simpan kredensial dengan aman.</p>
            </div>

            <div class="divider">
                <span>atau</span>
            </div>

            <div class="form-links">
                <a href="{{ route('admin.login') }}">Sudah punya akun admin? Masuk di sini</a>
            </div>

            <div class="form-links" style="margin-top: 16px;">
                <a href="{{ route('home') }}">← Kembali ke Situs Utama</a>
            </div>
        </div>
    </div>

    <script>
        function handleAdminRegister(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(form);
            
            submitBtn.textContent = 'Mendaftarkan...';
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            
            // Clear previous errors
            clearErrors();
            
            fetch(form.action, {
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
                    window.location.href = data.redirect_url || '/admin/dashboard';
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(key + 'Error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                            }
                        });
                    }
                    if (!data.errors) {
                        const emailError = document.getElementById('emailError');
                        if (emailError) {
                            emailError.textContent = data.message || 'Registrasi gagal!';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const emailError = document.getElementById('emailError');
                if (emailError) {
                    emailError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                }
            })
            .finally(() => {
                submitBtn.textContent = 'Daftar Akun';
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
            });
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.textContent = '';
            });
        }

        // Enhanced input interactions
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.01)';
                    this.parentElement.style.transition = 'transform 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });

                input.addEventListener('input', function() {
                    const errorElement = this.closest('.form-group').querySelector('.error-message');
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                });
            });

            // Password confirmation validation
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            
            function validatePasswordMatch() {
                if (password.value && passwordConfirmation.value) {
                    if (password.value !== passwordConfirmation.value) {
                        document.getElementById('password_confirmationError').textContent = 'Konfirmasi kata sandi tidak cocok';
                    } else {
                        document.getElementById('password_confirmationError').textContent = '';
                    }
                }
            }
            
            password.addEventListener('input', validatePasswordMatch);
            passwordConfirmation.addEventListener('input', validatePasswordMatch);

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });
    </script>
</body>
</html>
