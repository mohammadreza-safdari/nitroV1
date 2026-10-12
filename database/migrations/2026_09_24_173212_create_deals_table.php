<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->restrictOnDelete();

            $table->string('type');
            $table->string('status')->default('pending');

            $table->unsignedTinyInteger('quantity')->default(1);

            $table->decimal('base_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2);

            $table->foreignId('discount_code_id')
                ->nullable()
                ->constrained('discount_codes')
                ->nullOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')
                ->nullOnDelete();

            $table->text('admin_note')->nullable();
            $table->text('user_note')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
