<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hanzis', function (Blueprint $table) {
            $table->id();

            // Core Identity
            $table->string('character', 10)->unique()->index();
            $table->text('definition')->nullable();
            $table->json('pinyin')->nullable();
            $table->string('decomposition')->nullable();
            $table->string('radical', 10)->nullable();

            // Detailed Structural & Linguistic Data
            $table->json('etymology')->nullable();
            $table->json('matches')->nullable();

            // Graphics Vector Data (from graphics.txt)
            $table->json('strokes')->nullable();
            $table->json('medians')->nullable();

            // Asset Storage Paths
            $table->string('animated_svg_path', 255)->nullable();
            $table->string('still_svg_path', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hanzis');
    }
};
