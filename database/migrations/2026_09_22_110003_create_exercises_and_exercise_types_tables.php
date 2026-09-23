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
        // 1. exercises (Bảng câu hỏi / bài tập chung)
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('type', 50); // 'multiple_choice', 'listen_and_choose', 'word_matching', 'sentence_ordering', 'translation', 'pinyin_choice', 'tone_recognition', 'pronunciation', 'writing'
            $table->text('question')->nullable();
            $table->text('instruction')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->unsignedSmallInteger('xp_reward')->default(5);
            $table->timestamps();

            $table->index(['lesson_id', 'sort_order']);
        });

        // 2. multiple_choice_exercises
        Schema::create('multiple_choice_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->text('question')->nullable();
            $table->json('choices'); // [{ "id": 1, "text": "...", "is_correct": true }]
            $table->timestamps();
        });

        // 3. listen_and_choose_options
        Schema::create('listen_and_choose_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->text('content');
            $table->string('audio', 255)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->json('choices')->nullable();
            $table->timestamps();

            $table->index(['exercise_id', 'sort_order']);
        });

        // 4. word_matching_exercises & word_matching_pairs
        Schema::create('word_matching_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->json('pairs')->nullable(); // Có thể lưu pairs dưới dạng json
            $table->timestamps();
        });

        Schema::create('word_matching_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->string('left_content', 255);
            $table->string('right_content', 255);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['exercise_id', 'sort_order']);
        });

        // 5. sentence_ordering_exercises & sentence_ordering_items
        Schema::create('sentence_ordering_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->text('sentence');
            $table->json('items')->nullable(); // Có thể lưu danh sách từ dưới dạng json
            $table->timestamps();
        });

        Schema::create('sentence_ordering_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->string('content', 100);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['exercise_id', 'sort_order']);
        });

        // 6. translation_exercises
        Schema::create('translation_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->text('source_text');
            $table->text('expected_answer');
            $table->json('acceptable_answers')->nullable();
            $table->timestamps();
        });

        // 7. pinyin_choice_exercises
        Schema::create('pinyin_choice_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->string('hanzi', 100);
            $table->json('choices'); // ['mā', 'má', 'mǎ', 'mà']
            $table->timestamps();
        });

        // 8. tone_recognition_exercises
        Schema::create('tone_recognition_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->string('hanzi', 50)->nullable();
            $table->string('pinyin', 100);
            $table->unsignedTinyInteger('tone'); // 1, 2, 3, 4, 5
            $table->string('audio', 255)->nullable();
            $table->timestamps();
        });

        // 9. pronunciation_exercises
        Schema::create('pronunciation_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->text('target_text');
            $table->text('target_pinyin');
            $table->string('audio', 255)->nullable();
            $table->timestamps();
        });

        // 10. writing_exercises
        Schema::create('writing_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->unique()->constrained('exercises')->cascadeOnDelete();
            $table->string('character', 10);
            $table->unsignedTinyInteger('stroke_count')->nullable();
            $table->json('stroke_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writing_exercises');
        Schema::dropIfExists('pronunciation_exercises');
        Schema::dropIfExists('tone_recognition_exercises');
        Schema::dropIfExists('pinyin_choice_exercises');
        Schema::dropIfExists('translation_exercises');
        Schema::dropIfExists('sentence_ordering_items');
        Schema::dropIfExists('sentence_ordering_exercises');
        Schema::dropIfExists('word_matching_pairs');
        Schema::dropIfExists('word_matching_exercises');
        Schema::dropIfExists('listen_and_choose_options');
        Schema::dropIfExists('multiple_choice_exercises');
        Schema::dropIfExists('exercises');
    }
};
