<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Selecta Wisata</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            height: 100vh;
            background: #0f0f23;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.1) 0%, transparent 50%);
            animation: floating 20s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(2deg); }
            66% { transform: translate(-20px, 20px) rotate(-2deg); }
        }

        .admin-login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 440px;
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.12),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .admin-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .admin-logo::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 22px;
            z-index: -1;
            opacity: 0.7;
            filter: blur(8px);
        }

        .admin-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .admin-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 12px;
            color: #374151;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: -0.01em;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            background: #fafafa;
            color: #1f2937;
        }

        .form-group input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.08),
                0 1px 3px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .form-group input:hover:not(:focus) {
            border-color: #d1d5db;
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

        .admin-btn {
            width: 100%;
            padding: 18px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            margin-bottom: 24px;
            position: relative;
            letter-spacing: -0.01em;
        }

        .admin-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .admin-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 
                0 20px 40px rgba(102, 126, 234, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .admin-btn:hover::before {
            opacity: 1;
        }

        .admin-btn:active {
            transform: translateY(0);
        }

        .admin-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-to-site {
            text-align: center;
            margin-top: 32px;
        }

        .back-to-site a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 12px;
        }

        .back-to-site a:hover {
            color: #764ba2;
            background: rgba(102, 126, 234, 0.05);
            transform: translateX(-4px);
        }

        .security-notice {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(76, 99, 210, 0.08) 100%);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-top: 24px;
            border-radius: 12px;
            position: relative;
            display: none;
        }

        .security-notice::before {
            content: '🛡️';
            position: absolute;
            top: 16px;
            left: 16px;
            font-size: 1.2rem;
        }

        .security-notice p {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
            padding-left: 32px;
            font-weight: 500;
        }

        .security-notice strong {
            color: #334155;
        }

        /* Loading state */
        .admin-btn.loading {
            position: relative;
            color: transparent;
        }

        .admin-btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
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
            body {
                padding: 16px;
            }
            
            .admin-login-container {
                padding: 32px 24px;
                max-width: 100%;
                border-radius: 20px;
            }
            
            .admin-title {
                font-size: 1.8rem;
            }

            .admin-logo {
                width: 64px;
                height: 64px;
                font-size: 24px;
            }

            .form-group input {
                padding: 14px 16px;
            }

            .admin-btn {
                padding: 16px 20px;
            }
        }

        @media (max-width: 480px) {
            .admin-login-container {
                padding: 24px 20px;
            }

            .admin-title {
                font-size: 1.6rem;
            }
        }

        /* Enhanced focus styles for accessibility */
        .admin-btn:focus-visible {
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
    <div class="admin-login-container">
        <div class="admin-header">
            
            <h1 class="admin-title">Admin Panel</h1>
            <p class="admin-subtitle">Masuk ke Dashboard Administrator</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" onsubmit="handleAdminLogin(event)">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           placeholder="Masukan Email" 
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
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan kata sandi admin" 
                           required>
                </div>
                <div class="error-message" id="passwordError">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <button type="submit" class="admin-btn" id="submitBtn">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="security-notice">
            <p><strong>Keamanan:</strong> Halaman ini khusus untuk administrator. Semua aktivitas login akan dicatat untuk keamanan sistem.</p>
        </div>

        <div class="back-to-site">
            <a href="{{ route('home') }}">← Kembali ke Situs Utama</a>
        </div>
    </div>

    <script>
        function handleAdminLogin(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(form);
            
            submitBtn.textContent = 'Memverifikasi...';
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
                            emailError.textContent = data.message || 'Login gagal!';
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
                submitBtn.textContent = 'Masuk ke Dashboard';
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

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });
    </script>
</body>
</html>