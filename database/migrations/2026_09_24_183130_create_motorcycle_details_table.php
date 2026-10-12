<?php

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
        Schema::create('motorcycle_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')
                ->unique()
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->string('engine');
            $table->unsignedInteger('horsepower')->nullable();
            $table->unsignedInteger('torque')->nullable();
            $table->string('transmission')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('cooling_system')->nullable();
            $table->unsignedInteger('seat_height')->nullable();
            $table->unsignedInteger('wheelbase')->nullable();
            $table->decimal('fuel_capacity', 5, 2)->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('top_speed')->nullable();

            $table->json('additional_specs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_details');
    }
};
