<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deal_change_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('deal_id')
                ->constrained('deals')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')
                ->nullOnDelete();

            $table->foreignId('discount_code_id')
                ->nullable()
                ->constrained('discount_codes')
                ->nullOnDelete();

            $table->enum('payment_method', [
                'full',
                'installment',
            ]);

            $table->unsignedSmallInteger('installment_months')
                ->nullable();

            $table->decimal('base_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2);

            $table->text('user_note')->nullable();
            $table->text('admin_note')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled',
            ])->default('pending');

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['deal_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_change_requests');
    }
};
