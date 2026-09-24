<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('type');
            $table->unsignedTinyInteger('difficulty');
            $table->text('instruction')->nullable();
            $table->unsignedInteger('xp_reward')->default(0);
            $table->unsignedInteger('gem_reward')->default(0);
            $table->unsignedInteger('energy_cost')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['lesson_id', 'sort_order']);
            $table->index('type');
            $table->index('difficulty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
