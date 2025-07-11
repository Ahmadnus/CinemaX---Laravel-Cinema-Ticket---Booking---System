<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'screening_id',
        'row',
        'number',
        'is_reserved',
        'price',
    ];

    protected $casts = [
        'is_reserved' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * العلاقة مع العرض (Screening)
     */
    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    /**
     * العلاقة مع الحجز (Many-to-Many)
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seat');
    }
}