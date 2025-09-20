<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'max_occupancy',
        'max_adults',
        'max_children',
        'total_rooms',
        'available_rooms',
        'amenities',
        'images',
        'is_active'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'amenities' => 'array',
        'images' => 'array',
        'is_active' => 'boolean'
    ];

    public function roomBookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(HotelPhoto::class);
    }

    public function activePhotos(): HasMany
    {
        return $this->hasMany(HotelPhoto::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function featuredPhoto()
    {
        return $this->hasOne(HotelPhoto::class)->where('is_featured', true)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if room type is available for given dates and number of rooms
     */
    public function isAvailable($checkIn, $checkOut, $numberOfRooms = 1)
    {
        // Use the available_rooms column for real-time availability
        return $this->available_rooms >= $numberOfRooms;
    }

    /**
     * Get available rooms count for given dates
     */
    public function getAvailableRoomsCount($checkIn, $checkOut)
    {
        // Use the available_rooms column for real-time availability
        return $this->available_rooms;
    }

    /**
     * Reserve rooms - decrease available count
     */
    public function reserveRooms($numberOfRooms)
    {
        if ($this->available_rooms >= $numberOfRooms) {
            $this->decrement('available_rooms', $numberOfRooms);
            return true;
        }
        return false;
    }

    /**
     * Release rooms - increase available count
     */
    public function releaseRooms($numberOfRooms)
    {
        $newAvailable = $this->available_rooms + $numberOfRooms;
        if ($newAvailable <= $this->total_rooms) {
            $this->increment('available_rooms', $numberOfRooms);
            return true;
        }
        return false;
    }

    /**
     * Get current available rooms (real-time)
     */
    public function getCurrentAvailableRooms()
    {
        return $this->available_rooms;
    }
}
