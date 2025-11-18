<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
            $request->session()->regenerate();
            
            $user = Auth::user();
            
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

        Auth::login($user);

        // Check if it's an AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }
        
        // Regular form submission - redirect to home
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
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
}