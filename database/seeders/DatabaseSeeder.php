<?php
/**
 * Purpose: Seed admin user + sample menu items
 * Used by: php artisan db:seed
 * Dependencies: User, Menu models
 * Main functions: run()
 * Side effects: Creates DB records
 */

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama'     => 'Administrator',
            'email'    => 'admin@food.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'alamat'   => 'Kantor Pusat',
        ]);

        $menus = [
            ['nama_menu' => 'Nasi Goreng Spesial', 'harga_menu' => 25000, 'stok_menu' => 50, 'deskripsi' => 'Nasi goreng dengan telur, ayam, dan sayuran segar.'],
            ['nama_menu' => 'Ayam Bakar',          'harga_menu' => 35000, 'stok_menu' => 30, 'deskripsi' => 'Ayam bakar bumbu kecap dengan nasi putih.'],
            ['nama_menu' => 'Mie Ayam',             'harga_menu' => 20000, 'stok_menu' => 40, 'deskripsi' => 'Mie ayam dengan pangsit dan kuah kaldu.'],
            ['nama_menu' => 'Es Teh Manis',         'harga_menu' => 8000,  'stok_menu' => 100,'deskripsi' => 'Teh manis dingin segar.'],
            ['nama_menu' => 'Sate Ayam',            'harga_menu' => 28000, 'stok_menu' => 25, 'deskripsi' => '10 tusuk sate ayam dengan bumbu kacang.'],
            ['nama_menu' => 'Gado-Gado',            'harga_menu' => 22000, 'stok_menu' => 20, 'deskripsi' => 'Sayuran rebus dengan saus kacang.'],
        ];

        foreach ($menus as $m) {
            Menu::create(array_merge($m, ['tersedia' => true]));
        }
    }
}
