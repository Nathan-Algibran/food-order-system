<?php
/**
 * Purpose: Create users table
 * Used by: Auth, Pengguna model
 * Dependencies: Laravel Schema
 * Main functions: up(), down()
 * Side effects: Creates users table
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('alamat')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
