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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->enum('category', ['buku', 'seragam', 'alat_praktikum', 'lainnya']);
            $table->enum('type', ['gift', 'sale']);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('condition', ['baru', 'sangat_baik', 'baik', 'cukup']);
            $table->json('images');
            $table->text('legacy_message');
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('category');
            $table->index('type');
            $table->index('condition');
            $table->index(['user_id', 'status']);
            $table->index(['created_at', 'status']);
            $table->fullText(['name', 'description', 'legacy_message']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
