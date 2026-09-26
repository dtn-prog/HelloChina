<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');

        Schema::create('dictionary_entries', function (Blueprint $table) {
            $table->id();

            // Chinese
            $table->string('traditional', 100);
            $table->string('simplified', 100);

            // Pinyin
            $table->string('pinyin_accented', 255); // "lǎo shī"
            $table->string('pinyin', 255);          // "lao3 shi1"
            $table->string('pinyin_clean', 255);    // "laoshi"

            // Vietnamese
            $table->string('vietnamese');             // "thầy giáo"
            $table->string('vietnamese_unaccented');  // "thay giao"

            // Audio
            $table->string('audio_url', 255)->nullable();

            $table->timestamps();
        });

        // Chinese search
        DB::statement(
            'CREATE INDEX idx_gin_simplified
             ON dictionary_entries USING gin(simplified gin_trgm_ops);'
        );

        DB::statement(
            'CREATE INDEX idx_gin_traditional
             ON dictionary_entries USING gin(traditional gin_trgm_ops);'
        );

        // Pinyin search
        DB::statement(
            'CREATE INDEX idx_gin_pinyin_accented
             ON dictionary_entries USING gin(pinyin_accented gin_trgm_ops);'
        );

        DB::statement(
            'CREATE INDEX idx_gin_pinyin
             ON dictionary_entries USING gin(pinyin gin_trgm_ops);'
        );

        DB::statement(
            'CREATE INDEX idx_gin_pinyin_clean
             ON dictionary_entries USING gin(pinyin_clean gin_trgm_ops);'
        );

        // Vietnamese search
        DB::statement(
            'CREATE INDEX idx_gin_vietnamese
             ON dictionary_entries USING gin(vietnamese gin_trgm_ops);'
        );

        DB::statement(
            'CREATE INDEX idx_gin_vietnamese_unaccented
             ON dictionary_entries USING gin(vietnamese_unaccented gin_trgm_ops);'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('dictionary_entries');
    }
};
