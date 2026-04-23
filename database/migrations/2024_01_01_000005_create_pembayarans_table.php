<?php
/**
 * Purpose: Create pembayarans (payments) table
 * Used by: Pembayaran model, checkout flow
 * Dependencies: pemesanans table
 * Main functions: up(), down()
 * Side effects: Creates pembayarans table with FK to pemesanans
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->constrained('pemesanans', 'id_pemesanan')->cascadeOnDelete();
            $table->enum('metode_pembayaran', ['qris', 'cod', 'bank_transfer']);
            $table->enum('status_pembayaran', ['unpaid', 'paid', 'failed'])->default('unpaid');
            $table->decimal('jumlah', 14, 2);
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();

            $table->index(['id_pesanan', 'status_pembayaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
