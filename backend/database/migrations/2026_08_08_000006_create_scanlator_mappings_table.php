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
        Schema::create('scanlator_mappings', function (Blueprint $table) {
            $table->id();
            $table->char('manga_id', 36);
            $table->char('scanlation_group_id', 36);
            $table->enum('group_type', ['project', 'mirror']);
            $table->unsignedSmallInteger('priority')->default(0);
            $table->timestamps();

            $table->unique(['manga_id', 'scanlation_group_id']);
            $table->index(['manga_id', 'group_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanlator_mappings');
    }
};
