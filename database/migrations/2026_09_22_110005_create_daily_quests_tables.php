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
        // 1. daily_quest_definitions (Định nghĩa các nhiệm vụ hằng ngày)
        Schema::create('daily_quest_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // 'complete_lessons', 'learn_words', 'practice_speaking'
            $table->unsignedSmallInteger('target'); // 2, 15, 3
            $table->unsignedSmallInteger('xp_reward'); // 30, 20, 30
            $table->unsignedSmallInteger('gem_reward')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. user_daily_quests (Tiến độ hoàn thành nhiệm vụ theo ngày của từng học viên)
        Schema::create('user_daily_quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('daily_quest_definition_id')->constrained('daily_quest_definitions')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedSmallInteger('progress')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'daily_quest_definition_id', 'date'], 'uq_user_quest_date');
            $table->index(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_quests');
        Schema::dropIfExists('daily_quest_definitions');
    }
};
