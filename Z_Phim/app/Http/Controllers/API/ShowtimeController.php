<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowtimeRequest;
use App\Http\Resources\ShowtimeResource;
use App\Models\Showtime;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'room.cinema']);

        if ($request->has('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->has('date')) {
            $query->whereDate('start_time', $request->date);
        }

        $showtimes = $query->paginate(10);

        return ShowtimeResource::collection($showtimes);
    }

    public function store(ShowtimeRequest $request)
    {
        $showtime = Showtime::create($request->validated());

        return new ShowtimeResource($showtime->load(['movie', 'room.cinema']));
    }

    public function show(Showtime $showtime)
    {
        $showtime->load(['movie', 'room.cinema']);

        return new ShowtimeResource($showtime);
    }

    public function update(ShowtimeRequest $request, Showtime $showtime)
    {
        $showtime->update($request->validated());

        return new ShowtimeResource($showtime->load(['movie', 'room.cinema']));
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return response()->json(['message' => 'Showtime deleted successfully']);
    }
}