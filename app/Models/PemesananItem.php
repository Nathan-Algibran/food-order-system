<?php
/**
 * Purpose: Order line item model
 * Used by: Pemesanan, Menu models
 * Dependencies: pemesanans, menus tables
 * Main functions: pemesanan(), menu()
 * Side effects: None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananItem extends Model
{
    protected $fillable = [
        'id_pemesanan', 'id_menu', 'jumlah', 'harga_satuan',
    ];

    protected $casts = ['harga_satuan' => 'decimal:2'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function subtotal(): float
    {
        return (float) ($this->harga_satuan * $this->jumlah);
    }
}
