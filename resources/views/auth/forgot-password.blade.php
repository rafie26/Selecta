<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Selecta Wisata</title>
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

        .auth-container {
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 25px;
            width: 380px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }

        .auth-form h2 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .subtitle {
            font-size: 0.9rem;
            color: #555;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
            transform: translateY(-1px);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .primary-btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
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

        .back-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
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
    </style>
</head>
<body>
    <button class="back-home" onclick="goBack()">
        <span class="arrow-icon">←</span>
        Kembali
    </button>

    <div class="auth-container">
        <form class="auth-form" method="POST" action="{{ route('password.forgot.send') }}" onsubmit="handleForgot(event)">
            @csrf
            <h2>Lupa Kata Sandi</h2>
            <p class="subtitle">Masukkan alamat email akun Anda. Kami akan mengirim kode OTP untuk mengatur ulang kata sandi.</p>

            @if(session('success'))
                <div class="error-message" style="color: #155724; background: #d4edda; border-radius: 8px; padding: 8px 10px; margin-bottom: 10px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       placeholder="Masukkan alamat email Anda" 
                       value="{{ old('email') }}"
                       required>
                <div class="error-message" id="emailError">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <button type="submit" class="primary-btn" id="submitBtn">
                Kirim Kode OTP
            </button>

            <div class="back-link">
                <a href="{{ route('login') }}">Kembali ke halaman login</a>
            </div>
        </form>
    </div>

    <script>
        function handleForgot(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(form);

            submitBtn.textContent = 'Mengirim Kode...';
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
                    window.location.href = data.redirect_url || '{{ route('password.reset.code') }}';
                } else {
                    if (data.errors && data.errors.email) {
                        document.getElementById('emailError').textContent = data.errors.email[0];
                    } else if (data.message) {
                        document.getElementById('emailError').textContent = data.message;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('emailError').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            })
            .finally(() => {
                submitBtn.textContent = 'Kirim Kode OTP';
                submitBtn.disabled = false;
            });
        }

        function clearErrors() {
            const emailError = document.getElementById('emailError');
            if (emailError) {
                emailError.textContent = '';
            }
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/';
            }
        }
    </script>
</body>
</html>
