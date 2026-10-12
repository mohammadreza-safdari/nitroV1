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
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('vehicle_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('deposit', 15, 2);

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
                'fulfilled',
            ])->default('pending');

            $table->text('user_note')
                ->nullable();

            $table->text('admin_note')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};
