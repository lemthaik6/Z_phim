<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = DB::table('users')->first();
        $showtime = DB::table('showtimes')->first();

        DB::table('bookings')->insert([
            [
                'user_id' => $user->id,
                'showtime_id' => $showtime->id,
                'total_amount' => 15.00,
                'status' => 'confirmed',
            ],
        ]);
    }
}
