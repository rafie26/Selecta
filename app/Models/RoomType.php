<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'description', 
        'price_per_night',
        'max_occupancy',
        'max_adults',
        'max_children',
        'amenities',
        'total_rooms',
        'available_rooms',
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
        // Use dynamic calculation based on paid room bookings and date range
        return $this->getAvailableRoomsCount($checkIn, $checkOut) >= $numberOfRooms;
    }

    /**
     * Get available rooms count for given dates
     */
    public function getAvailableRoomsCount($checkIn, $checkOut)
    {
        // Normalize dates to Carbon instances
        if (!$checkIn instanceof Carbon) {
            $checkIn = Carbon::parse($checkIn);
        }

        if (!$checkOut instanceof Carbon) {
            $checkOut = Carbon::parse($checkOut);
        }

        // Total physical rooms for this type
        $totalRooms = $this->total_rooms ?? 0;

        if ($totalRooms <= 0) {
            return 0;
        }

        // Hitung jumlah kamar yang sudah terisi oleh booking hotel yang SUDAH DIBAYAR
        // Rentang tanggal yang dianggap terisi adalah [check_in_date, check_out_date)
        // Dua interval overlap jika: booking.check_in_date < requested_check_out
        // DAN booking.check_out_date > requested_check_in
        $bookedRooms = $this->roomBookings()
            ->whereHas('booking', function ($query) {
                $query->where('booking_type', 'hotel')
                      ->where('payment_status', 'paid');
            })
            ->where(function ($query) use ($checkIn, $checkOut) {
                // Interval overlap detection:
                // Booking overlap jika: booking_check_in < requested_check_out AND booking_check_out > requested_check_in
                $query->whereDate('check_in_date', '<', $checkOut->toDateString())
                      ->whereDate('check_out_date', '>', $checkIn->toDateString());
            })
            ->sum('number_of_rooms');

        $available = $totalRooms - $bookedRooms;

        return $available > 0 ? $available : 0;
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
