<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'room_type_id',
        'check_in_date',
        'check_out_date',
        'number_of_rooms',
        'number_of_guests',
        'room_rate_per_night',
        'total_room_amount',
        'room_status',
        'actual_check_in',
        'actual_check_out'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'room_rate_per_night' => 'decimal:2',
        'total_room_amount' => 'decimal:2',
        'actual_check_in' => 'datetime',
        'actual_check_out' => 'datetime'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Calculate number of nights
     */
    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Check in the room booking
     */
    public function checkIn()
    {
        $this->update([
            'room_status' => 'occupied',
            'actual_check_in' => now()
        ]);
        
        // Update booking check-in status
        $this->booking->update(['check_in_status' => 'checked_in']);
    }

    /**
     * Check out the room booking
     */
    public function checkOut()
    {
        $this->update([
            'room_status' => 'checked_out',
            'actual_check_out' => now()
        ]);
        
        // Release rooms back to available pool
        $this->roomType->releaseRooms($this->number_of_rooms);
    }

    /**
     * Cancel the room booking
     */
    public function cancel()
    {
        // Release rooms back to available pool
        $this->roomType->releaseRooms($this->number_of_rooms);
        
        $this->update(['room_status' => 'checked_out']);
    }
}
