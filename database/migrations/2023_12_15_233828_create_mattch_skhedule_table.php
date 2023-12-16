<?php

use App\Models\Mattch;
use App\Models\Skhedule;
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
        Schema::create('mattch_skhedule', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mattch::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Skhedule::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mattch_skhedule');
    }
};
