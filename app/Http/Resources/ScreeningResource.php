<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreeningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movie' => new MovieResource($this->movie),
            //\'cinema' => new CinemaResource($this->cinema),
            'hall' => new HallResource($this->hall),

            'cinema' => $this->cinema,

            'screening_time' => $this->screening_time,
            'language' => $this->language,
        ];
    }

}
