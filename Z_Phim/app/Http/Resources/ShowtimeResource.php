<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MovieResource;
use App\Http\Resources\RoomResource;

class ShowtimeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'room_id' => $this->room_id,
            'movie' => new MovieResource($this->whenLoaded('movie')),
            'room' => new RoomResource($this->whenLoaded('room')),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}