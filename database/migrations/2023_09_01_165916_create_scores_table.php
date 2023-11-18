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
            $table->foreignId('mattch_id');
            $table->string('achievement');
            $table->decimal('home_score')->nullable();
            $table->decimal('home_points')->nullable();
            $table->decimal('away_score')->nullable();
            $table->decimal('away_points')->nullable();
            $table->jsonb('history')->nullable();
            $table->timestamps();

            $table->unique(['mattch_id', 'achievement']);
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
