<?php
/**
 * Purpose: Pemesanan (Order) model
 * Used by: PemesananController, AdminOrderController
 * Dependencies: users, pemesanan_items, pembayarans tables
 * Main functions: user(), items(), pembayaran(), menus()
 * Side effects: None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_pengguna', 'status_pemesanan', 'catatan', 'total_harga',
    ];

    protected $casts = ['total_harga' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function items()
    {
        return $this->hasMany(PemesananItem::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pemesanan');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'pemesanan_items', 'id_pemesanan', 'id_menu')
                    ->withPivot('jumlah', 'harga_satuan');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('id_pengguna', $userId);
    }
}
