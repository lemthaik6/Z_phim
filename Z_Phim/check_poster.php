<?php

use App\Models\Movie;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

// Handle a dummy request to bootstrap the app
$request = Request::create('/');
try {
    $kernel->handle($request);
} catch (\Throwable $e) {
    // Ignore
}

$movies = Movie::all();

foreach ($movies as $movie) {
    echo "ID: {$movie->id} | Title: {$movie->title} | Poster: {$movie->poster} | URL: {$movie->poster_url}\n";
}
