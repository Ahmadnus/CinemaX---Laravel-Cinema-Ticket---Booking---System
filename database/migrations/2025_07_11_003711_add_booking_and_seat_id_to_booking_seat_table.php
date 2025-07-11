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
        Schema::table('booking_seat', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade')->after('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_seat', function (Blueprint $table) {
            //
        });
    }
};
