<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movies')->insert([
            [
                'title' => 'Dune: Part Two',
                'description' => 'Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family.',
                'release_date' => '2024-03-01',
                'duration' => 166,
                'poster' => 'https://image.tmdb.org/t/p/original/68ueXL1S5NIDz9A8fUo4fF0Yf65.jpg',
                'trailer' => 'https://www.youtube.com/embed/Way9Dexny3w',
            ],
            [
                'title' => 'Oppenheimer',
                'description' => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.',
                'release_date' => '2023-07-21',
                'duration' => 180,
                'poster' => 'https://image.tmdb.org/t/p/original/8GxvA9zDZ9G0P38obwrM2YOVtS1.jpg',
                'trailer' => 'https://www.youtube.com/embed/uYPbbksJxIg',
            ],
            [
                'title' => 'Interstellar',
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'release_date' => '2014-11-07',
                'duration' => 169,
                'poster' => 'https://image.tmdb.org/t/p/original/gEU2QniE6EwfVnzC9vBaseTTUr5.jpg',
                'trailer' => 'https://www.youtube.com/embed/zSWdZVtXT7E',
            ],
            [
                'title' => 'The Dark Knight',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
                'release_date' => '2008-07-18',
                'duration' => 152,
                'poster' => 'https://image.tmdb.org/t/p/original/qJ2tW6WMUDp9EXm7FbmZkG9vWFp.jpg',
                'trailer' => 'https://www.youtube.com/embed/EXeTwQWrcwY',
            ],
            [
                'title' => 'Spider-Man: Across the Spider-Verse',
                'description' => 'Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence.',
                'release_date' => '2023-06-02',
                'duration' => 140,
                'poster' => 'https://image.tmdb.org/t/p/original/8VtbbwsbiubeGvK6mSTqTMpWocg.jpg',
                'trailer' => 'https://www.youtube.com/embed/shW9i6k8cB0',
            ],
            [
                'title' => 'Avatar: The Way of Water',
                'description' => 'Jake Sully leads the Omaticaya clan against the destructive colonization of Pandora.',
                'release_date' => '2022-12-16',
                'duration' => 192,
                'poster' => 'https://image.tmdb.org/t/p/original/t6HIqrRAclMPA5IvVQnd3will4i.jpg',
                'trailer' => 'https://www.youtube.com/embed/d9MyW72EsYw',
            ],
            [
                'title' => 'Inception',
                'description' => 'A skilled thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                'release_date' => '2010-07-16',
                'duration' => 148,
                'poster' => 'https://image.tmdb.org/t/p/original/oYuLEyjQo82jstxc6zo2JotNkD1.jpg',
                'trailer' => 'https://www.youtube.com/embed/YoHD_XwrzKc',
            ],
            [
                'title' => 'The Shawshank Redemption',
                'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'release_date' => '1994-10-14',
                'duration' => 142,
                'poster' => 'https://image.tmdb.org/t/p/original/q6725aR8Zs4IwplRlQuExe2Hn4V.jpg',
                'trailer' => 'https://www.youtube.com/embed/6hB3S9bIaco',
            ],
            [
                'title' => 'Ready Player One',
                'description' => 'When the creator of a virtual reality world called the OASIS dies, he releases a video in which he talks about hiding his wealth inside the OASIS and leaving it to the winner of a contest.',
                'release_date' => '2018-03-29',
                'duration' => 140,
                'poster' => 'https://image.tmdb.org/t/p/original/wSG4PIGHmDSLn5FH2afnoGHlthC.jpg',
                'trailer' => 'https://www.youtube.com/embed/cSp1dM2Vj48',
            ],
        ]);

        // Attach genres
        $movies = [
            'Dune: Part Two' => ['Action', 'Sci-Fi'],
            'Oppenheimer' => ['Drama'],
            'Interstellar' => ['Sci-Fi', 'Drama'],
            'The Dark Knight' => ['Action', 'Drama'],
            'Spider-Man: Across the Spider-Verse' => ['Action', 'Sci-Fi'],
            'Avatar: The Way of Water' => ['Action', 'Sci-Fi'],
            'Inception' => ['Action', 'Sci-Fi', 'Drama'],
            'The Shawshank Redemption' => ['Drama'],
            'Ready Player One' => ['Action', 'Sci-Fi'],
        ];

        foreach ($movies as $title => $genres) {
            $movie = DB::table('movies')->where('title', $title)->first();
            foreach ($genres as $genreName) {
                $genre = DB::table('genres')->where('name', $genreName)->first();
                if ($movie && $genre) {
                    DB::table('movie_genre')->insert([
                        'movie_id' => $movie->id,
                        'genre_id' => $genre->id
                    ]);
                }
            }
        }
    }
}
