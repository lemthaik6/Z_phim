<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

try {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
} catch (\Exception $e) {
    die("Error: " . $e->getMessage());
}

$output = '';

try {
    // Get movies
    $movies = \App\Models\Movie::select('id', 'title', 'poster')->get();
    
    $output = "Movies in Database:\n";
    $output .= str_repeat("=", 60) . "\n\n";
    
    foreach ($movies as $movie) {
        $poster = $movie->poster ?? 'NULL';
        $output .= "ID: {$movie->id} | Title: {$movie->title} | Poster: {$poster}\n";
    }
    
    // Check files
    $dir = storage_path('app/public/posters');
    $output .= "\n\nFiles in Storage:\n";
    $output .= str_repeat("=", 60) . "\n\n";
    
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $size = filesize($dir . '/' . $file);
            $output .= "$file (" . number_format($size / 1024, 2) . " KB)\n";
        }
    }
    
    echo $output;
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
