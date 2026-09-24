<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multiple_choice_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->text('question');
            $table->timestamps();
        });

        Schema::create('multiple_choice_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['exercise_id', 'sort_order']);
        });

        Schema::create('connect_word_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('connect_word_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->string('left_content');
            $table->string('right_content');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['exercise_id', 'sort_order']);
        });

        Schema::create('rearrange_sentence_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->text('sentence');
            $table->timestamps();
        });

        Schema::create('rearrange_sentence_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->string('content');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['exercise_id', 'sort_order']);
        });

        Schema::create('pronunciation_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->text('target_text');
            $table->string('target_pinyin')->nullable();
            $table->string('audio')->nullable();
            $table->timestamps();
        });

        Schema::create('writing_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->string('character');
            $table->timestamps();
        });

        Schema::create('listening_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->string('audio');
            $table->text('question')->nullable();
            $table->text('transcript')->nullable();
            $table->timestamps();
        });

        Schema::create('translation_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->text('source_text');
            $table->text('expected_answer');
            $table->timestamps();
        });

        Schema::create('tone_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->string('hanzi');
            $table->string('pinyin')->nullable();
            $table->unsignedTinyInteger('tone');
            $table->string('audio')->nullable();
            $table->timestamps();
        });

        Schema::create('grammar_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->text('question');
            $table->text('explanation')->nullable();
            $table->text('expected_answer')->nullable();
            $table->timestamps();
        });

        Schema::create('conversation_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')->primary()->constrained('exercises')->cascadeOnDelete();
            $table->string('scenario');
            $table->text('prompt')->nullable();
            $table->string('ai_role')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_exercises');
        Schema::dropIfExists('grammar_exercises');
        Schema::dropIfExists('tone_exercises');
        Schema::dropIfExists('translation_exercises');
        Schema::dropIfExists('listening_exercises');
        Schema::dropIfExists('writing_exercises');
        Schema::dropIfExists('pronunciation_exercises');
        Schema::dropIfExists('rearrange_sentence_items');
        Schema::dropIfExists('rearrange_sentence_exercises');
        Schema::dropIfExists('connect_word_pairs');
        Schema::dropIfExists('connect_word_exercises');
        Schema::dropIfExists('multiple_choice_options');
        Schema::dropIfExists('multiple_choice_exercises');
    }
};
