<?php
/**
 * Purpose: Create menus table
 * Used by: Menu model, Admin menu management
 * Dependencies: Laravel Schema
 * Main functions: up(), down()
 * Side effects: Creates menus table
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu');
            $table->decimal('harga_menu', 12, 2);
            $table->integer('stok_menu')->default(0);
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('tersedia')->default(true);
            $table->timestamps();

            $table->index('tersedia');
            $table->index('stok_menu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
