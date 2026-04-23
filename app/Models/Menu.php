<?php
/**
 * Purpose: Menu model — food items managed by Admin
 * Used by: AdminMenuController, MenuController, CartController
 * Dependencies: pemesanan_items, ulasans tables
 * Main functions: pemesananItems(), ulasans(), avgRating()
 * Side effects: None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu', 'harga_menu', 'stok_menu', 'gambar', 'deskripsi', 'tersedia',
    ];

    protected $casts = [
        'tersedia' => 'boolean',
        'harga_menu' => 'decimal:2',
    ];

    public function pemesananItems()
    {
        return $this->hasMany(PemesananItem::class, 'id_menu', 'id_menu');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_menu', 'id_menu');
    }

    public function avgRating(): float
    {
        return (float) $this->ulasans()->avg('rating') ?? 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where('tersedia', true)->where('stok_menu', '>', 0);
    }
}
