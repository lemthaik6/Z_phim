<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeatResource;
use App\Models\Showtime;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function getSeatsByShowtime(Showtime $showtime)
    {
        $seats = $showtime->room->seats;

        return SeatResource::collection($seats->map(function ($seat) use ($showtime) {
            return new SeatResource($seat, $showtime->id);
        }));
    }
}