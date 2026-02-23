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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('admin_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['qris', 'cod'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->enum('delivery_method', ['dropoff', 'cod'])->nullable();
            $table->string('dropoff_point')->nullable();
            $table->string('qris_code')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('transaction_code');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index(['seller_id', 'payment_status']);
            $table->index(['buyer_id', 'payment_status']);
            $table->index('created_at');
            $table->index(['item_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
