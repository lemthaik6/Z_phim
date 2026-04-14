<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
            ['cinema_id' => 1, 'name' => 'Room 1', 'capacity' => 100],
            ['cinema_id' => 1, 'name' => 'Room 2', 'capacity' => 80],
            ['cinema_id' => 2, 'name' => 'Room A', 'capacity' => 120],
        ]);
    }
}
