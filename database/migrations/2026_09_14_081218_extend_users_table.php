<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->timestamp('phone_verified_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('avatar');
            $table->text('two_factor_secret')->nullable()->after('status');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_secret');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_enabled');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'avatar', 'status',
                'two_factor_secret', 'two_factor_enabled',
                'last_login_at', 'last_login_ip',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
