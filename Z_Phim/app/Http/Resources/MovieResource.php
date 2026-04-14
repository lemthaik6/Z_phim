<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShowtimeResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'release_date' => $this->release_date,
            'duration' => $this->duration,
            'poster' => $this->poster,
            'poster_url' => $this->poster_url,
            'trailer' => $this->trailer,
            'genres' => $this->whenLoaded('genres', function () {
                return $this->genres->pluck('name');
            }),
            'showtimes' => ShowtimeResource::collection($this->whenLoaded('showtimes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}