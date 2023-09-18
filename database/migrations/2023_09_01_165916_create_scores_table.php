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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matchup_id');
            $table->string('achievement');
            $table->decimal('home_score');
            $table->decimal('home_points');
            $table->decimal('away_score');
            $table->decimal('away_points');
            $table->jsonb('history')->nullable();
            $table->timestamps();

            $table->unique(['matchup_id', 'achievement']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
