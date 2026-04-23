<?php
/**
 * Purpose: Order line items (Menu <-> Pemesanan pivot)
 * Used by: Pemesanan, Menu models
 * Dependencies: pemesanans, menus tables
 * Main functions: up(), down()
 * Side effects: Creates pemesanan_items table
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemesanan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemesanan')->constrained('pemesanans', 'id_pemesanan')->cascadeOnDelete();
            $table->foreignId('id_menu')->constrained('menus', 'id_menu')->restrictOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->timestamps();

            $table->index('id_pemesanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan_items');
    }
};
