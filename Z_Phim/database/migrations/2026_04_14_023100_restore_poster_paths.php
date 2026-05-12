<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Restore valid poster paths for movies that have files
        DB::table('movies')->where('id', 1)->update(['poster' => 'posters/4KfDpwjrrzkuNjgZf4lrW92ReY4tAXFrReylVIX3.jpg']);
        DB::table('movies')->where('id', 6)->update(['poster' => 'posters/eFNmZD5qplXaOYWOvinxMsqSZGYvA2aZsiJwx9Lx.png']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert if needed
        DB::table('movies')->whereIn('id', [1, 6])->update(['poster' => null]);
    }
};
