<?php
/**
 * Purpose: User order list, order detail, confirm delivery
 * Used by: User routes
 * Dependencies: Pemesanan, Pembayaran models
 * Main functions: index(), show(), confirmDelivered()
 * Side effects: Updates status_pemesanan to 'delivered'
 */

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanans = Pemesanan::forUser(auth()->id())
            ->with('pembayaran')
            ->latest()
            ->paginate(10);

        return view('pemesanan.index', compact('pemesanans'));
    }

    public function show(Pemesanan $pemesanan)
    {
        $this->authorizeOrder($pemesanan);

        $pemesanan->load([
            'items.menu:id_menu,nama_menu,gambar',
            'pembayaran',
            'ulasans.menu:id_menu,nama_menu',
        ]);

        return view('pemesanan.show', compact('pemesanan'));
    }

    public function confirmDelivered(Pemesanan $pemesanan)
    {
        $this->authorizeOrder($pemesanan);

        if ($pemesanan->status_pemesanan !== 'shipped') {
            return back()->with('error', 'Pesanan belum dalam status dikirim.');
        }

        $pemesanan->update(['status_pemesanan' => 'delivered']);
        return back()->with('success', 'Pesanan dikonfirmasi diterima!');
    }

    private function authorizeOrder(Pemesanan $pemesanan): void
    {
        if ($pemesanan->id_pengguna !== auth()->id()) abort(403);
    }
}
