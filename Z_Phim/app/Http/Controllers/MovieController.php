<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return view('movies.index');
    }

    public function show(Movie $movie)
    {
        $movie->load(['genres', 'reviews.user']);

        return view('movies.show', compact('movie'));
    }

    public function seats(Movie $movie, Showtime $showtime)
    {
        return view('movies.seats', compact('movie', 'showtime'));
    }
}