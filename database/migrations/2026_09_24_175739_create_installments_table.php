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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')
                ->constrained('deals')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('installment_number');
            $table->decimal('amount', 15, 2);
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();

            $table->string('status')->default('pending');

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
