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
        // Ubah kolom status dari enum menjadi string
        Schema::table('items', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum seperti semula (sesuaikan dengan nilai default di migration awal)
        Schema::table('items', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending')->change();
        });
    }
};
