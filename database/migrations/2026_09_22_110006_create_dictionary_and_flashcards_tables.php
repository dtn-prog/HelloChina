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
        // 1. dictionary_words (Kho từ điển tiếng Trung)
        Schema::create('dictionary_words', function (Blueprint $table) {
            $table->id();
            $table->string('simplified', 50); // Chữ Hán giản thể: 学习
            $table->string('traditional', 50)->nullable(); // Phồn thể: 學習
            $table->string('pinyin', 100); // xuéxí
            $table->string('pinyin_clean', 100); // xuexi (Tối ưu tìm kiếm)
            $table->text('meaning'); // Nghĩa tiếng Việt
            $table->unsignedTinyInteger('hsk_level')->nullable(); // 1 - 6
            $table->string('audio', 255)->nullable();
            $table->timestamps();

            $table->index('simplified');
            $table->index('pinyin_clean');
            $table->index('hsk_level');
        });

        // 2. user_flashcards (Thẻ từ vựng SRS của từng người dùng)
        Schema::create('user_flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dictionary_word_id')->constrained('dictionary_words')->cascadeOnDelete();

            $table->unsignedSmallInteger('interval')->default(0); // Khoảng cách ngày đến lần ôn tiếp theo
            $table->decimal('ease', 4, 2)->default(2.50); // Hệ số độ dễ (mặc định 2.50)
            $table->unsignedSmallInteger('repetitions')->default(0); // Số lần ôn thành công liên tục

            $table->enum('state', ['new', 'learning', 'relearning', 'known'])->default('new');
            $table->unsignedSmallInteger('learning_step')->default(0);
            $table->timestamp('due_at')->useCurrent(); // Mốc thời gian đến hạn ôn tập

            $table->timestamps();

            $table->unique(['user_id', 'dictionary_word_id']);
            $table->index(['user_id', 'due_at']); // Index tối ưu cho truy vấn thẻ đến hạn
        });

        // 3. flashcard_reviews (Lịch sử từng lượt đánh giá thẻ SRS)
        Schema::create('flashcard_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_flashcard_id')->constrained('user_flashcards')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1: Again, 2: Hard, 3: Good, 4: Easy
            $table->string('previous_state', 30);
            $table->string('new_state', 30);
            $table->unsignedSmallInteger('previous_interval');
            $table->unsignedSmallInteger('new_interval');
            $table->decimal('previous_ease', 4, 2);
            $table->decimal('new_ease', 4, 2);
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_flashcard_id', 'reviewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_reviews');
        Schema::dropIfExists('user_flashcards');
        Schema::dropIfExists('dictionary_words');
    }
};
