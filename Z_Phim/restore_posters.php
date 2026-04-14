<?php
// Quick fix script to ensure movies have posters restored

// Since we can't easily use Eloquent in CLI script, let's restore via SQL
// But first let's verify current state by checking which movies have NULL posters

// The files we know exist:
$files_exist = [
    'posters/4KfDpwjrrzkuNjgZf4lrW92ReY4tAXFrReylVIX3.jpg',
    'posters/eFNmZD5qplXaOYWOvinxMsqSZGYvA2aZsiJwx9Lx.png',
    'posters/8UfS6kD3mP1AWfupuOLCYnfruX1I72Prh6k8vWsh.jpg',
    'posters/sv0Vk47tnNsqfOe4grnDwPxNHbB2GdbUqVDZ5Mpy.png',
];

// This is just for reference - actual restoration happens in Laravel

echo "Files that should be active:\n";
foreach ($files_exist as $file) {
    echo "- $file\n";
}
