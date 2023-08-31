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
        Schema::create('matchups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bout_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('home_id');
            $table->unsignedBigInteger('away_id');
            $table->dateTimeTz('start_date');
            $table->dateTimeTz('end_date');
            $table->timestamps();

            $table->unique([
                'bout_id',
                'home_id',
                'away_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchups');
    }
};
