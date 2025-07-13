<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'rating'    => $this->rating,
            'comment'   => $this->comment,
        //    'user'      => new UserResource($this->whenLoaded('user')),
            'movie'     => new MovieResource($this->whenLoaded('movie')),
            'created_at'=> $this->created_at->toDateTimeString(),
        ];
    }}
