<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BookingComboItemResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'showtime' => new ShowtimeResource($this->whenLoaded('showtime')),
            'seats' => SeatResource::collection($this->whenLoaded('seats')),
            'combo_items' => BookingComboItemResource::collection($this->whenLoaded('comboItems')),
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}