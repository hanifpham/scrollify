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
        Schema::create('release_schedules', function (Blueprint $table) {
            $table->id();
            $table->char('manga_id', 36);
            $table->string('manga_title', 255);
            $table->string('manga_cover_url', 500);
            $table->enum('release_day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('release_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['release_day', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_schedules');
    }
};
