<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'user_id'        => $this->user_id,
            'seats' => ScreeningResource::collection($this->whenLoaded('screening')),
            'total_price'    => $this->total_price,
            'status'         => $this->status,
            'payment_method' => $this->payment_method,
            'created_at'     => $this->created_at?->toDateTimeString(),

            'seats' => SeatResource::collection($this->whenLoaded('seats')),
            'snacks' => SnackResource::collection($this->whenLoaded('snacks')),
        ];
    }
}
