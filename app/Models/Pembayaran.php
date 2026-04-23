<?php
/**
 * Purpose: Pembayaran (Payment) model
 * Used by: CheckoutController, AdminOrderController
 * Dependencies: pemesanans table
 * Main functions: pemesanan()
 * Side effects: None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan', 'metode_pembayaran', 'status_pembayaran', 'jumlah', 'bukti_bayar',
    ];

    protected $casts = ['jumlah' => 'decimal:2'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pesanan', 'id_pemesanan');
    }
}
