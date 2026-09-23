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
        // 1. users: Bổ sung phone_verified_at nếu chưa tồn tại
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }
        });

        // 2. user_settings
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('daily_goal')->default(10); // số phút mục tiêu học mỗi ngày
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('sound_enabled')->default(true);
            $table->boolean('music_enabled')->default(true);
            $table->string('timezone', 50)->default('Asia/Ho_Chi_Minh');
            $table->timestamps();
        });

        // 3. user_stats (Trạng thái điểm, level, gems, streak của người dùng)
        Schema::create('user_stats', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('current_xp')->default(0);
            $table->unsignedSmallInteger('current_level')->default(1);
            $table->unsignedInteger('gems')->default(0);
            $table->unsignedSmallInteger('streak')->default(0);
            $table->unsignedSmallInteger('longest_streak')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->timestamps();

            $table->index('current_xp');
        });

        // 4. user_items (Túi đồ / Vật phẩm: streak_freeze, double_xp, v.v.)
        Schema::create('user_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 50); // 'streak_freeze', 'double_xp', 'chest_key', v.v.
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'type']);
            $table->index(['user_id', 'type']);
        });

        // 5. levels (Cấu hình mốc XP cho từng level)
        Schema::create('levels', function (Blueprint $table) {
            $table->unsignedSmallInteger('level')->primary();
            $table->unsignedBigInteger('required_xp');
            $table->timestamps();
        });

        // 6. xp_transactions (Lịch sử biến động XP)
        Schema::create('xp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('amount'); // Dương: cộng, Âm: trừ
            $table->string('type', 50); // 'lesson_completed', 'daily_quest', 'streak_bonus'
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
        });

        // 7. gem_transactions (Lịch sử biến động Đá quý Gems)
        Schema::create('gem_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('amount'); // Dương: cộng, Âm: tiêu
            $table->string('type', 50); // 'lesson_reward', 'buy_item', 'quest_reward'
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
        });

        // 8. user_daily_activities (Ghi nhận hoạt động theo ngày)
        Schema::create('user_daily_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedSmallInteger('minutes')->default(0);
            $table->unsignedInteger('xp_earned')->default(0);
            $table->boolean('goal_completed')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->index(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_activities');
        Schema::dropIfExists('gem_transactions');
        Schema::dropIfExists('xp_transactions');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('user_items');
        Schema::dropIfExists('user_stats');
        Schema::dropIfExists('user_settings');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone_verified_at')) {
                $table->dropColumn('phone_verified_at');
            }
        });
    }
};
