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
        Schema::create('reading_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->char('manga_id', 36);
            $table->string('manga_title', 255);
            $table->string('manga_cover_url', 500);
            $table->char('chapter_id', 36);
            $table->string('chapter_number', 20);
            $table->unsignedSmallInteger('last_page_read')->nullable();
            $table->timestamp('read_at')->useCurrent();

            $table->unique(['user_id', 'manga_id', 'chapter_id']);
            $table->index(['user_id', 'manga_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_history');
    }
};
