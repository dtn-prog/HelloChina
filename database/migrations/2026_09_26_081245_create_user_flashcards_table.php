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
        Schema::create('user_flashcards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('dictionary_entry_id')
                ->constrained('dictionary_entries')
                ->cascadeOnDelete();

            $table->string('state')->default('new');

            $table->unsignedSmallInteger('learning_step')->default(0);
            $table->unsignedInteger('repetitions')->default(0);

            $table->unsignedInteger('interval')->default(0);
            $table->decimal('ease', 4, 2)->default(2.50);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamp('due_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'dictionary_entry_id']);

            $table->index(['user_id', 'due_at']);
            $table->index(['user_id', 'state']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_flashcards');
    }
};
