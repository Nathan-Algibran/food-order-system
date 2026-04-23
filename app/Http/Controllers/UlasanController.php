<?php
/**
 * Purpose: Submit rating & review for delivered orders
 * Used by: User routes
 * Dependencies: Ulasan, Pemesanan, PemesananItem models
 * Main functions: store()
 * Side effects: Creates ulasan record
 */

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request, Pemesanan $pemesanan)
    {
        if ($pemesanan->id_pengguna !== auth()->id()) abort(403);
        if ($pemesanan->status_pemesanan !== 'delivered') {
            return back()->with('error', 'Hanya pesanan yang telah diterima yang bisa diulas.');
        }

        $request->validate([
            'ulasans'              => 'required|array',
            'ulasans.*.id_menu'    => 'required|integer|exists:menus,id_menu',
            'ulasans.*.rating'     => 'required|integer|min:1|max:5',
            'ulasans.*.komentar'   => 'nullable|string|max:500',
        ]);

        $orderMenuIds = $pemesanan->items()->pluck('id_menu')->toArray();

        foreach ($request->ulasans as $u) {
            if (!in_array($u['id_menu'], $orderMenuIds)) continue;

            Ulasan::updateOrCreate(
                [
                    'id_pengguna'  => auth()->id(),
                    'id_menu'      => $u['id_menu'],
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                ],
                [
                    'rating'   => $u['rating'],
                    'komentar' => $u['komentar'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
