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
        Schema::create('manga_views', function (Blueprint $table) {
            $table->id();
            $table->char('manga_id', 36);
            $table->timestamp('viewed_at')->useCurrent();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->index(['manga_id', 'viewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manga_views');
    }
};
