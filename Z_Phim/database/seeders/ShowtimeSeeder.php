<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movieTitles = [
            'Dune: Part Two',
            'Oppenheimer',
            'Interstellar',
            'The Dark Knight',
            'Spider-Man: Across the Spider-Verse',
            'Avatar: The Way of Water',
            'Inception',
            'The Shawshank Redemption',
            'Ready Player One'
        ];
        $room = DB::table('rooms')->where('name', 'Room 1')->first();

        foreach ($movieTitles as $title) {
            $movie = DB::table('movies')->where('title', $title)->first();
            if ($movie && $room) {
                // Today
                DB::table('showtimes')->insert([
                    'movie_id' => $movie->id,
                    'room_id' => $room->id,
                    'start_time' => now()->setTime(14, 0),
                    'end_time' => now()->setTime(17, 0),
                    'price' => 12.00,
                ]);
                
                // Tomorrow
                DB::table('showtimes')->insert([
                    'movie_id' => $movie->id,
                    'room_id' => $room->id,
                    'start_time' => now()->addDay()->setTime(10, 0),
                    'end_time' => now()->addDay()->setTime(13, 0),
                    'price' => 10.00,
                ]);

                // Day after tomorrow
                DB::table('showtimes')->insert([
                    'movie_id' => $movie->id,
                    'room_id' => $room->id,
                    'start_time' => now()->addDays(2)->setTime(19, 0),
                    'end_time' => now()->addDays(2)->setTime(22, 0),
                    'price' => 15.00,
                ]);
            }
        }
    }
}
