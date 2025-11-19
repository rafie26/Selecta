<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Pastikan email sudah terverifikasi
            if (!$user->email_verified_at) {
                Auth::logout();

                $message = 'Email Anda belum terverifikasi. Silakan cek email Anda dan klik link verifikasi yang dikirimkan.';

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => [
                            'email' => [$message],
                        ],
                    ], 403);
                }

                return back()->withErrors([
                    'email' => $message,
                ])->withInput($request->except('password'));
            }

            $request->session()->regenerate();
            
            
            // Debug: Log user role for troubleshooting
            Log::info('User logged in', [
                'email' => $user->email,
                'role' => $user->role ?? 'NULL',
                'user_id' => $user->id
            ]);
            
            // Determine redirect URL based on role
            $redirectUrl = '/';
            $welcomeMessage = 'Login berhasil!';
            
            if ($user->role === User::ROLE_ADMIN) {
                $redirectUrl = '/admin/dashboard';
                $welcomeMessage = 'Selamat datang, Admin!';
            } elseif ($user->role === User::ROLE_PETUGAS_LOKET) {
                $redirectUrl = '/petugas-loket/dashboard';
                $welcomeMessage = 'Selamat datang, Petugas Loket!';
            } elseif ($user->role === User::ROLE_PETUGAS_HOTEL) {
                $redirectUrl = '/petugas-hotel/dashboard';
                $welcomeMessage = 'Selamat datang, Petugas Hotel!';
            }
            
            // Check if it's an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $welcomeMessage,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role ?? 'user',
                    ],
                    'redirect_url' => $redirectUrl
                ]);
            }
            
            // Regular form submission - redirect based on role
            return redirect($redirectUrl)->with('success', $welcomeMessage);
        }

        // Check if it's an AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
                'errors' => [
                    'email' => ['Email atau password salah.']
                ]
            ], 422);
        }
        
        // Regular form submission - redirect back with errors
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'phone_code' => 'nullable|string|max:5',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'phone_code' => $request->phone_code ?? '+62',
        ]);

        // Kirim email verifikasi (akun belum bisa digunakan sebelum email diverifikasi)
        $this->sendEmailVerificationLink($user);

        // Check if it's an AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'redirect' => route('login'),
            ]);
        }
        
        // Regular form submission - redirect ke halaman login
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun sebelum login.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $userRole = $user ? $user->role : null;
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Determine redirect URL based on previous role
        $redirectUrl = '/';
        $message = 'Logout berhasil!';
        
        if ($userRole === User::ROLE_ADMIN) {
            $redirectUrl = '/admin/login';
            $message = 'Logout berhasil! Silakan login kembali untuk mengakses admin panel.';
        } elseif ($userRole === User::ROLE_PETUGAS_LOKET || $userRole === User::ROLE_PETUGAS_HOTEL) {
            $redirectUrl = '/login';
            $message = 'Logout berhasil! Silakan login kembali.';
        }

        // Check if it's an AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => $redirectUrl
            ]);
        }
        
        // Redirect based on user role
        return redirect($redirectUrl)->with('success', $message);
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
        ]);

        $user = User::where('email', $request->email)->first();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        $user->password_reset_code = $code;
        $user->password_reset_expires_at = $expiresAt;
        $user->save();

        try {
            Mail::raw(
                "Kode reset kata sandi Anda adalah: {$code}. Kode ini berlaku selama 15 menit. Jangan berikan kode ini kepada siapa pun, termasuk pihak yang mengatasnamakan layanan kami.",
                function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('Kode Reset Kata Sandi - Selecta Wisata');
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send password reset code email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim kode ke email. Silakan coba lagi nanti.',
                ], 500);
            }

            return back()->withErrors([
                'email' => 'Gagal mengirim kode ke email. Silakan coba lagi nanti.',
            ])->withInput();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kode reset kata sandi telah dikirim ke email Anda.',
                'redirect_url' => route('password.reset.code', ['email' => $user->email]),
            ]);
        }

        return redirect()
            ->route('password.reset.code', ['email' => $user->email])
            ->with('success', 'Kode reset kata sandi telah dikirim ke email Anda.');
    }

    public function showResetWithCodeForm(Request $request)
    {
        $email = $request->query('email');

        return view('auth.reset-password-code', [
            'email' => $email,
        ]);
    }

    public function resetPasswordWithCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar.',
            'code.required' => 'Kode reset wajib diisi.',
            'code.size' => 'Kode reset harus 6 digit.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->password_reset_code || !$user->password_reset_expires_at) {
            return $this->resetErrorResponse($request, 'Kode reset tidak ditemukan. Silakan minta kode baru.');
        }

        if ($user->password_reset_code !== $request->code) {
            return $this->resetErrorResponse($request, 'Kode reset tidak valid.');
        }

        if ($user->password_reset_expires_at->isPast()) {
            return $this->resetErrorResponse($request, 'Kode reset sudah kadaluarsa. Silakan minta kode baru.');
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_code = null;
        $user->password_reset_expires_at = null;
        $user->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kata sandi berhasil direset. Silakan login dengan kata sandi baru Anda.',
                'redirect_url' => route('login'),
            ]);
        }

        return redirect()->route('login')->with('success', 'Kata sandi berhasil direset. Silakan login dengan kata sandi baru Anda.');
    }

    protected function resetErrorResponse(Request $request, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => [
                    'code' => [$message],
                ],
            ], 422);
        }

        return back()->withErrors([
            'code' => $message,
        ])->withInput($request->except('password', 'password_confirmation'));
    }

    public function verifyEmail(Request $request, $id, $token)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('success', 'Email Anda sudah terverifikasi. Silakan login.');
        }

        if (!$user->email_verification_token || !hash_equals($user->email_verification_token, (string) $token)) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau sudah digunakan.');
        }

        if ($user->email_verification_sent_at && $user->email_verification_sent_at->lt(now()->subHours(24))) {
            return redirect()->route('login')->with('error', 'Link verifikasi sudah kadaluarsa. Silakan daftar ulang atau minta link baru.');
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->email_verification_sent_at = null;
        $user->save();

        // Opsional: login user setelah verifikasi
        Auth::login($user);

        return redirect('/')->with('success', 'Email Anda berhasil diverifikasi. Selamat datang!');
    }

    protected function sendEmailVerificationLink(User $user): void
    {
        $token = Str::random(64);

        $user->email_verification_token = $token;
        $user->email_verification_sent_at = now();
        $user->save();

        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'token' => $token,
        ]);

        try {
            $body = "Halo {$user->name},\n\n" .
                "Terima kasih telah mendaftar di Selecta Wisata.\n" .
                "Silakan klik link berikut untuk verifikasi email Anda:\n" .
                "{$verificationUrl}\n\n" .
                "Jika Anda tidak merasa membuat akun ini, abaikan email ini.";

            Mail::raw($body, function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Verifikasi Email Akun - Selecta Wisata');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email verification link', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}