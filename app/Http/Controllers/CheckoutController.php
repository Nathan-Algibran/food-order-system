<?php
/**
 * Purpose: Handle checkout — create Pemesanan + Pembayaran atomically
 * Used by: User routes
 * Dependencies: Pemesanan, PemesananItem, Pembayaran, Menu models, DB transaction
 * Main functions: show(), process()
 * Side effects: Writes pemesanans, pemesanan_items, pembayarans; decrements stok_menu
 */

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');

        $total = collect($cart)->sum(fn($i) => $i['harga'] * $i['jumlah']);
        return view('checkout.show', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:qris,cod,bank_transfer',
            'catatan'           => 'nullable|string|max:500',
            'bukti_bayar'       => 'nullable|file|image|max:2048',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        // Validate stock and fetch menus in single query
        $menuIds   = array_keys($cart);
        $menus     = Menu::whereIn('id_menu', $menuIds)->lockForUpdate()->get()->keyBy('id_menu');

        foreach ($cart as $id => $item) {
            $menu = $menus->get($id);
            if (!$menu || $menu->stok_menu < $item['jumlah']) {
                return back()->with('error', "Stok {$item['nama_menu']} tidak cukup.");
            }
        }

        $total = collect($cart)->sum(fn($i) => $i['harga'] * $i['jumlah']);

        DB::transaction(function () use ($cart, $menus, $total, $request) {
            $order = Pemesanan::create([
                'id_pengguna'      => auth()->id(),
                'status_pemesanan' => 'pending',
                'catatan'          => $request->catatan,
                'total_harga'      => $total,
            ]);

            foreach ($cart as $id => $item) {
                PemesananItem::create([
                    'id_pemesanan' => $order->id_pemesanan,
                    'id_menu'      => $id,
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                ]);
                $menus[$id]->decrement('stok_menu', $item['jumlah']);
            }

            $bukti = null;
            if ($request->hasFile('bukti_bayar')) {
                $bukti = $request->file('bukti_bayar')->store('bukti', 'public');
            }

            $isPaid = $request->metode_pembayaran === 'cod';

            Pembayaran::create([
                'id_pesanan'         => $order->id_pemesanan,
                'metode_pembayaran'  => $request->metode_pembayaran,
                'status_pembayaran'  => $isPaid ? 'paid' : 'unpaid',
                'jumlah'             => $total,
                'bukti_bayar'        => $bukti,
            ]);

            if ($isPaid) {
                $order->update(['status_pemesanan' => 'paid']);
            }

            session()->forget('cart');
            session(['last_order_id' => $order->id_pemesanan]);
        });

        return redirect()->route('pemesanan.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}
