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
        // 1. courses (Khoá học)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        // 2. course_units (Chương / Đơn vị bài học trong khoá)
        Schema::create('course_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 150);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });

        // 3. lessons (Bài học)
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_unit_id')->constrained('course_units')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 150);
            $table->text('description')->nullable();
            $table->string('type', 50)->default('standard'); // 'standard', 'review', 'legendary'
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->unsignedSmallInteger('xp_reward')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['course_unit_id', 'sort_order']);
        });

        // 4. user_lesson_progress (Tiến độ học của từng học viên cho từng bài học)
        Schema::create('user_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('status', 30)->default('locked'); // 'locked', 'unlocked', 'in_progress', 'completed'
            $table->unsignedTinyInteger('progress')->default(0); // 0 - 100%
            $table->unsignedSmallInteger('score')->default(0);
            $table->unsignedSmallInteger('xp_earned')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_lesson_progress');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('course_units');
        Schema::dropIfExists('courses');
    }
};
