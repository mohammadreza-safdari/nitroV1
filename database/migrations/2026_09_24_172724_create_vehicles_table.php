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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_model_id')
                ->constrained('vehicle_models')
                ->cascadeOnDelete();

            $table->string('stock_code')->unique();
            $table->unsignedSmallInteger('year');
            $table->string('color');
            $table->unsignedInteger('mileage')->default(0);
            $table->decimal('price', 15, 2);
            $table->string('status');
            $table->string('condition')->default('new');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
