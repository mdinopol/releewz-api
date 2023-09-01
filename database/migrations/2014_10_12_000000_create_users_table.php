<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 50)->unique();
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->smallInteger('role');
            $table->string('phone', 50)->nullable();
            $table->dateTimeTz('date_of_birth')->nullable();
            $table->enum('country_code', Country::values());
            $table->string('adress_city')->nullable();
            $table->string('adress_postal')->nullable();
            $table->string('adress_line_one')->nullable();
            $table->string('adress_line_two')->nullable();
            $table->string('image_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
