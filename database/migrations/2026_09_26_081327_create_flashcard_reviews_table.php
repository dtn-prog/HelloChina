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
        Schema::create('flashcard_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_flashcard_id')
                ->constrained('user_flashcards')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');

            $table->string('previous_state');
            $table->string('new_state');

            $table->unsignedInteger('previous_interval')->default(0);
            $table->unsignedInteger('new_interval')->default(0);

            $table->decimal('previous_ease', 4, 2);
            $table->decimal('new_ease', 4, 2);

            $table->unsignedSmallInteger('previous_learning_step')->default(0);
            $table->unsignedSmallInteger('new_learning_step')->default(0);

            $table->timestamp('reviewed_at');

            $table->timestamps();

            $table->index([
                'user_flashcard_id',
                'reviewed_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_reviews');
    }
};
