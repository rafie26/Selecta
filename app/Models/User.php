<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_PETUGAS_LOKET = 'petugas_loket';
    const ROLE_PETUGAS_HOTEL = 'petugas_hotel';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_code',
        'avatar',
        'google_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's avatar URL or generate initial avatar
     */
    public function getAvatarAttribute($value)
    {
        // If user has Google avatar, return it
        if ($value && filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // Generate initial avatar for manual registration
        return null;
    }

    /**
     * Get the first letter of user's name for avatar
     */
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Check if user is petugas loket
     */
    public function isPetugasLoket()
    {
        return $this->role === self::ROLE_PETUGAS_LOKET;
    }

    /**
     * Check if user is petugas hotel
     */
    public function isPetugasHotel()
    {
        return $this->role === self::ROLE_PETUGAS_HOTEL;
    }

    /**
     * Check if user has any staff role (admin, petugas loket, or petugas hotel)
     */
    public function isStaff()
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_PETUGAS_LOKET,
            self::ROLE_PETUGAS_HOTEL
        ]);
    }
}
