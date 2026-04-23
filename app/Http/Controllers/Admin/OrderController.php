<?php
/**
 * Purpose: Admin order management — list paid orders, update status
 * Used by: Admin routes
 * Dependencies: Pemesanan, Pembayaran models
 * Main functions: index(), show(), updateStatus()
 * Side effects: Updates status_pemesanan
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private const FLOW = ['paid' => 'prepared', 'prepared' => 'shipped'];

    public function index(Request $request)
    {
        $status = $request->get('status', 'paid');

        $orders = Pemesanan::with(['user:id_pengguna,nama,email', 'pembayaran'])
            ->when($status !== 'all', fn($q) => $q->where('status_pemesanan', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = Pemesanan::selectRaw('status_pemesanan, count(*) as total')
            ->groupBy('status_pemesanan')
            ->pluck('total', 'status_pemesanan');

        return view('admin.orders.index', compact('orders', 'status', 'counts'));
    }

    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load([
            'user:id_pengguna,nama,email,alamat',
            'items.menu:id_menu,nama_menu,gambar',
            'pembayaran',
        ]);

        return view('admin.orders.show', compact('pemesanan'));
    }

    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $request->validate(['status' => 'required|in:prepared,shipped']);

        $allowed = self::FLOW[$pemesanan->status_pemesanan] ?? null;

        if ($allowed !== $request->status) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        $pemesanan->update(['status_pemesanan' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function confirmPayment(Pemesanan $pemesanan)
    {
        $pemesanan->pembayaran->update(['status_pembayaran' => 'paid']);
        $pemesanan->update(['status_pemesanan' => 'paid']);

        return back()->with('success', 'Pembayaran dikonfirmasi!');
    }
}
