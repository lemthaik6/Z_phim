<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booking = DB::table('bookings')->first();

        DB::table('payments')->insert([
            [
                'booking_id' => $booking->id,
                'amount' => 15.00,
                'payment_method' => 'cash',
                'status' => 'completed',
            ],
        ]);
    }
}
