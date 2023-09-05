<?php

use App\Enum\ContestantType;
use App\Enum\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contestants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->references('id')
                ->on('contestants')
                ->nullOnDelete();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->enum('country_code', Country::values())->nullable();
            $table->enum('contestant_type', ContestantType::values());
            $table->string('sport');
            $table->boolean('active')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->unique([
                'name',
                'contestant_type',
                'sport',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contestants');
    }
};
