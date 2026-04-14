<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('genres');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $movies = $query->paginate(10);

        return MovieResource::collection($movies);
    }

    public function store(MovieRequest $request)
    {
        $movie = Movie::create($request->validated());

        return new MovieResource($movie);
    }

    public function show(Movie $movie)
    {
        $movie->load(['genres', 'showtimes.room.cinema']);

        return new MovieResource($movie);
    }

    public function update(MovieRequest $request, Movie $movie)
    {
        $movie->update($request->validated());

        return new MovieResource($movie);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json(['message' => 'Movie deleted successfully']);
    }
}