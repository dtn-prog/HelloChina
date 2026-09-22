<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // error, warning, info, critical
            $table->string('message');
            $table->json('context')->nullable();
            $table->text('stack_trace')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('request_id', 36)->nullable();
            $table->timestamps();

            $table->index('level');
            $table->index('created_at');
            $table->index('request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
