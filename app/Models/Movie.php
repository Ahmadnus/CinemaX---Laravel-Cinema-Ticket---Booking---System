<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;


class Movie extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'title', 'description', 'genre', 'language', 'duration_min',
        'rating', 'release_date'
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posters')->singleFile();
        $this->addMediaCollection('trailers')->singleFile();
    }

    public function screenings() { return $this->hasMany(Screening::class); }
    public function reviews() { return $this->hasMany(MovieReview::class); }
}
