<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contestant_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contestant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->boolean('abandoned')->default(false);
            $table->timestamps();

            $table->unique(['contestant_id', 'tournament_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contestant_tournament');
    }
};
