<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_exercise_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->string('status')->default('not_started');
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedInteger('best_score')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'exercise_id']);
            $table->index('status');
        });

        Schema::create('user_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('status')->default('not_started');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->unsignedTinyInteger('stars')->default(0);
            $table->unsignedInteger('score')->nullable();
            $table->unsignedInteger('xp_earned')->default(0);
            $table->unsignedInteger('gem_earned')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_lesson_progress');
        Schema::dropIfExists('user_exercise_progress');
    }
};
