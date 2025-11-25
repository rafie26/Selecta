<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user sudah ada berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update Google ID, tapi hanya update avatar jika user belum punya avatar custom
                $updateData = ['google_id' => $googleUser->getId()];
                
                // Hanya update avatar jika user belum punya avatar atau avatar adalah URL Google
                if (!$user->avatar || filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                    $updateData['avatar'] = $googleUser->getAvatar();
                }
                
                $user->update($updateData);
            } else {
                // Buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => null, // Password null untuk Google OAuth
                ]);
            }
            
            // Login user
            Auth::login($user);
            
            return redirect()->intended('/')->with('success', 'Login berhasil!');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }
}
