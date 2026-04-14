<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

use App\Models\Movie;
use Illuminate\Support\Facades\File;

echo "=== DATABASE POSTERS ===\n";
$movies = Movie::select('id', 'title', 'poster')->get();
foreach ($movies as $m) {
    echo "ID {$m->id}: {$m->title} | Poster: " . ($m->poster ?? 'NULL') . "\n";
}

echo "\n=== FILE SYSTEM CHECK ===\n";
$dir = storage_path('app/public/posters');
if (is_dir($dir)) {
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $size = filesize($dir . '/' . $file);
        echo "✓ $file (" . round($size/1024, 1) . "KB)\n";
    }
} else {
    echo "✗ Directory not found\n";
}

echo "\n=== ROUTES URL ===\n";
$m1 = Movie::find(1);
$m6 = Movie::find(6);
echo "Movie 1 poster_url: " . ($m1->poster_url ?? 'NULL') . "\n";
echo "Movie 6 poster_url: " . ($m6->poster_url ?? 'NULL') . "\n";
