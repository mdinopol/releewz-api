<?php

use App\Enum\GameDuration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->string('name', 255)->unique();
            $table->string('short', 50)->nullable();
            $table->string('slug')->unique();
            $table->string('description', 255)->nullable();
            $table->string('sport', 100);
            $table->smallInteger('game_state');
            $table->enum('duration_type', GameDuration::values());
            $table->string('game_type', 100);
            $table->smallInteger('min_entry')->unsigned();
            $table->smallInteger('max_entry')->unsigned();
            $table->tinyInteger('entry_contestants')->unsigned();
            $table->decimal('max_entry_value', 19);
            $table->decimal('entry_price', 19);
            $table->decimal('initial_prize_pool', 19)->nullable();
            $table->decimal('current_prize_pool', 19)->nullable();
            $table->dateTimeTz('start_date');
            $table->dateTimeTz('end_date');
            $table->jsonb('point_template')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
