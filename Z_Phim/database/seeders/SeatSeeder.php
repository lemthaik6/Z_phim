<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seats = [];
        $rooms = DB::table('rooms')->get();
        foreach ($rooms as $room) {
            for ($row = 'A'; $row <= 'D'; $row++) {
                for ($num = 1; $num <= 10; $num++) {
                    $seats[] = [
                        'room_id' => $room->id,
                        'row' => $row,
                        'number' => $num,
                        'type' => rand(0, 1) ? 'regular' : 'vip',
                    ];
                }
            }
        }
        DB::table('seats')->insert($seats);
    }
}
