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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            height: 100vh;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 40px 32px;
            background: #fafafa;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #111;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #666;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 6px;
            display: none;
        }

        .error-message:not(:empty) {
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 20px;
        }

        .submit-btn:hover:not(:disabled) {
            background: #333;
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-link a:hover {
            color: #111;
        }

        .info-box {
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 16px;
            margin-top: 20px;
            display: none;
        }

        .info-box p {
            color: #555;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        @media (max-width: 480px) {
            .title {
                font-size: 1.5rem;
            }

            .form-group input,
            .submit-btn {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1 class="title">Staff Portal</h1>
            <p class="subtitle">Login untuk Admin & Petugas</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" onsubmit="handleLogin(event)">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       placeholder="Masukan Email" 
                       value="{{ old('email') }}"
                       required>
                <div class="error-message" id="emailError">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       placeholder="Masukkan kata sandi" 
                       required>
                <div class="error-message" id="passwordError">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="info-box">
            <p>Halaman ini untuk staff (Admin, Petugas Loket, Petugas Hotel). Setelah login, Anda akan diarahkan ke dashboard sesuai role Anda.</p>
        </div>

        <div class="back-link">
            <a href="{{ route('home') }}">← Kembali ke Situs Utama</a>
        </div>
    </div>

    <script>
        function handleLogin(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(form);
            
            submitBtn.textContent = 'Memverifikasi...';
            submitBtn.disabled = true;
            
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
            });
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.textContent = '';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const errorElement = this.closest('.form-group').querySelector('.error-message');
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                });
            });
        });
    </script>
</body>
</html>