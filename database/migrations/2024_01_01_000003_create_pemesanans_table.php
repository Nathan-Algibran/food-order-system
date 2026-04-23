<?php
/**
 * Purpose: Create pemesanans (orders) table
 * Used by: Pemesanan model, User checkout, Admin order management
 * Dependencies: users table
 * Main functions: up(), down()
 * Side effects: Creates pemesanans table with FK to users
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->foreignId('id_pengguna')->constrained('users', 'id_pengguna')->cascadeOnDelete();
            $table->enum('status_pemesanan', [
                'pending', 'paid', 'prepared', 'shipped', 'delivered', 'cancelled'
            ])->default('pending');
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['id_pengguna', 'status_pemesanan']);
            $table->index('status_pemesanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
