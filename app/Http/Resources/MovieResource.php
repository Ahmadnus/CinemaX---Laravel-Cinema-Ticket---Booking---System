<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'genre'         => $this->genre,
            'language'      => $this->language,
            'duration_min'  => $this->duration_min,
            'rating'        => $this->rating,
            'release_date'  => $this->release_date,
            'poster_url'    => $this->getFirstMediaUrl('posters'),  // من Spatie
            'trailer_url'   => $this->getFirstMediaUrl('trailers'), // من Spatie
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
