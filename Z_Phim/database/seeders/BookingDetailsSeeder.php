<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booking = DB::table('bookings')->first();
        $seat = DB::table('seats')->first();

        DB::table('booking_details')->insert([
            [
                'booking_id' => $booking->id,
                'seat_id' => $seat->id,
                'price' => 15.00,
            ],
        ]);
    }
}
