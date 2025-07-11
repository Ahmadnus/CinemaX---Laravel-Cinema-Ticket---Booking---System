<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $fillable = [
        'cinema_id',
        'name',
        'seat_map_json',
    ];

    protected $casts = [
        'seat_map_json' => 'array',
    ];

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
}