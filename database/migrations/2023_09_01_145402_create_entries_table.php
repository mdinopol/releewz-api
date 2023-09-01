<?php

use App\Enum\Currency;
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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('game_id')->unique();
            $table->string('name', 50)->unique();
            $table->float('total_points')->nullable();
            $table->jsonb('points_history')->nullable();
            $table->jsonb('contestants');
            $table->string('license_at_creation', 50);
            $table->enum('currency_at_creation', Currency::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
