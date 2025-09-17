<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'qr_code',
        'booker_name',
        'booker_email',
        'booker_phone',
        'visit_date',
        'total_amount',
        'payment_status',
        'check_in_status',
        'payment_method',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_response',
        'paid_at',
        'checked_in_at',
        'user_id'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'visit_date' => 'date',
        'paid_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }

    public function roomBookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function roomBooking()
    {
        return $this->hasOne(RoomBooking::class);
    }

    public function generateBookingCode(): string
    {
        return 'BK-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function generateQRCode(): string
    {
        return 'QR-' . $this->booking_code . '-' . md5($this->id . $this->created_at);
    }
}
