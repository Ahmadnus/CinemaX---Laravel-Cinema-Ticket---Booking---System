<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HallResource extends JsonResource
{
        public function toArray($request)
        {
            return [
                'id'              => $this->id,
                'cinema_id'       => $this->cinema_id,
                'name'            => $this->name,
                'seat_map_json'   => json_decode($this->seat_map_json),
                'created_at'      => $this->created_at->toDateTimeString(),
            ];
        }
}
