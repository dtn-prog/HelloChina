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
        // 1. exercise_attempts (Lịch sử làm bài tập chung)
        Schema::create('exercise_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->unsignedSmallInteger('score')->default(0);
            $table->json('answer')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'exercise_id', 'created_at']);
        });

        // 2. pronunciation_attempts (Lịch sử làm bài phát âm AI)
        Schema::create('pronunciation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->string('audio', 255);
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('pinyin_score');
            $table->unsignedTinyInteger('tone_score');
            $table->unsignedTinyInteger('pronunciation_score');
            $table->unsignedTinyInteger('intonation_score');
            $table->unsignedTinyInteger('fluency_score');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'exercise_id']);
        });

        // 3. writing_attempts (Lịch sử làm bài tập viết chữ Hán)
        Schema::create('writing_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->json('input_data')->nullable(); // Dữ liệu nét vẽ vector của người dùng
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('stroke_order_score');
            $table->unsignedTinyInteger('shape_score');
            $table->unsignedTinyInteger('proportion_score');
            $table->unsignedTinyInteger('position_score');
            $table->unsignedTinyInteger('accuracy_score');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'exercise_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writing_attempts');
        Schema::dropIfExists('pronunciation_attempts');
        Schema::dropIfExists('exercise_attempts');
    }
};
