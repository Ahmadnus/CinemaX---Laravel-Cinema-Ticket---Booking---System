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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('genre', ['action', 'drama', 'horror', 'comedy', 'romance', 'sci-fi', 'other']);
            $table->string('language');
            $table->integer('duration_min');
            $table->float('rating')->default(0);
            $table->date('release_date');
            $table->string('poster_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
