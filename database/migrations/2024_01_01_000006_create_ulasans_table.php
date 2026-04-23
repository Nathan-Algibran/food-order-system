<?php
/**
 * Purpose: Create ulasans (reviews) table
 * Used by: Ulasan model, User review flow
 * Dependencies: users, menus tables
 * Main functions: up(), down()
 * Side effects: Creates ulasans table
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->foreignId('id_pengguna')->constrained('users', 'id_pengguna')->cascadeOnDelete();
            $table->foreignId('id_menu')->constrained('menus', 'id_menu')->cascadeOnDelete();
            $table->foreignId('id_pemesanan')->constrained('pemesanans', 'id_pemesanan')->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->unique(['id_pengguna', 'id_menu', 'id_pemesanan']);
            $table->index('id_menu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasans');
    }
};
