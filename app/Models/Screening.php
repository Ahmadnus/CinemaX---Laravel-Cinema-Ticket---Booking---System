<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $fillable = [
        'language', 'hall_id', 'movie_id', 'screening_time'

    ];
    public function cinema()
{
    return $this->belongsTo(Cinema::class);
}

public function hall()
{
    return $this->belongsTo(Hall::class);
}

public function movie()
{
    return $this->belongsTo(Movie::class);
}
}
