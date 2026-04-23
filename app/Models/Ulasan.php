<?php
/**
 * Purpose: Ulasan (Review/Rating) model
 * Used by: UlasanController, MenuController
 * Dependencies: users, menus, pemesanans tables
 * Main functions: user(), menu(), pemesanan()
 * Side effects: None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $primaryKey = 'id_ulasan';

    protected $fillable = [
        'id_pengguna', 'id_menu', 'id_pemesanan', 'rating', 'komentar',
    ];

    protected $casts = ['rating' => 'integer'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
