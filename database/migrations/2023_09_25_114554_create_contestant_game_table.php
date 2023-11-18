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
        Schema::create('contestant_game', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contestant_id');
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->boolean('abandoned')->default(false);
            $table->decimal('value', 19)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contestant_game');
    }
};
