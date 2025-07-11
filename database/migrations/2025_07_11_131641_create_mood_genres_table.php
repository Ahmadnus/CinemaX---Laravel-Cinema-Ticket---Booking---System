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
        Schema::create('mood_genres', function (Blueprint $table) {
            $table->id();
            $table->string('mood');
            $table->text('message_keywords'); // كلمات مفصولة بـ |
            $table->string('recommended_genre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_genres');
    }
};
