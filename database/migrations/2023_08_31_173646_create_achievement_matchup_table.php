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
        Schema::create('achievement_matchup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id');
            $table->foreignId('matchup_id');
            $table->float('home_score');
            $table->float('home_points');
            $table->float('away_score');
            $table->float('away_points');
            $table->jsonb('history')->nullable();
            $table->timestamps();

            $table->unique(['achievement_id', 'matchup_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_matchup');
    }
};
