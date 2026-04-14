<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cinemas')->insert([
            ['name' => 'Cinema City', 'address' => '123 Main St, City', 'phone' => '0123456789'],
            ['name' => 'Mega Cinema', 'address' => '456 Elm St, Town', 'phone' => '0987654321'],
        ]);
    }
}
