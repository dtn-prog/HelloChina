<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->foreignId('folder_id')->nullable()->constrained('media_folders')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index('disk');
            $table->index('mime_type');
            $table->index('folder_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
