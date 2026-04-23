<?php
/**
 * Purpose: Session-based cart (no DB writes until checkout)
 * Used by: User routes
 * Dependencies: Menu model, Session
 * Main functions: index(), add(), update(), remove(), clear()
 * Side effects: Modifies session cart data
 */

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private const SESSION_KEY = 'cart';

    private function getCart(): array
    {
        return session(self::SESSION_KEY, []);
    }

    private function saveCart(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }

    public function index()
    {
        $cart  = $this->getCart();
        $total = collect($cart)->sum(fn($i) => $i['harga'] * $i['jumlah']);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Menu $menu)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);

        if (!$menu->tersedia || $menu->stok_menu < 1) {
            return back()->with('error', 'Menu tidak tersedia.');
        }

        $cart = $this->getCart();
        $key  = $menu->id_menu;

        $jumlah = ($cart[$key]['jumlah'] ?? 0) + $request->jumlah;
        $jumlah = min($jumlah, $menu->stok_menu);

        $cart[$key] = [
            'id_menu'   => $menu->id_menu,
            'nama_menu' => $menu->nama_menu,
            'harga'     => $menu->harga_menu,
            'gambar'    => $menu->gambar,
            'jumlah'    => $jumlah,
        ];

        $this->saveCart($cart);
        return back()->with('success', 'Ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $menu = Menu::find($id);
            $cart[$id]['jumlah'] = min($request->jumlah, $menu->stok_menu ?? $request->jumlah);
            $this->saveCart($cart);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
        return back()->with('success', 'Item dihapus.');
    }

    public function clear()
    {
        $this->saveCart([]);
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
