<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom payment_method dari enum ke string dulu
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->change();
        });

        // Update data yang ada (kalau ada yang pakai 'qris' ganti jadi 'bank_transfer')
        DB::table('transactions')->where('payment_method', 'qris')->update(['payment_method' => 'bank_transfer']);

        // Tambahkan kolom baru untuk transfer bank
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('payment_method');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_name')->nullable()->after('account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'account_name']);
            // Kembalikan ke enum awal
            $table->enum('payment_method', ['qris', 'cod'])->change();
        });
    }
};
