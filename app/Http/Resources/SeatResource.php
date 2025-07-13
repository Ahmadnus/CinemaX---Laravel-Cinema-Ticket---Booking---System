<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     public function toArray(Request $request): array
     {
         return [
             'id'          => $this->id,
             'screening_id'=>      ScreeningResource::collection($this->whenLoaded('screening')),
             'row'         => $this->row,
             'number'      => $this->number,
             'is_reserved' => $this->is_reserved,
             'price'       => $this->price,
         ];
     }
}
