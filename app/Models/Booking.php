<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [

    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function screening()
{
    return $this->belongsTo(Screening::class);
}

public function seats()
{
    return $this->belongsToMany(Seat::class, 'booking_seat');
}

public function snacks()
{
    return $this->belongsToMany(Snack::class, 'booking_snack')->withPivot('quantity');
}
}
