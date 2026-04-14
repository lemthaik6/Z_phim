<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sessions table already exists in database, skip creation
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sessions table already exists, skip drop
    }
};
