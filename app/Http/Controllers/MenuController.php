<?php
/**
 * Purpose: User-facing menu browsing with reviews
 * Used by: User routes
 * Dependencies: Menu, Ulasan models
 * Main functions: index(), show()
 * Side effects: None (read-only)
 */

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::available()
            ->withCount('ulasans')
            ->withAvg('ulasans', 'rating');

        if ($search = $request->get('search')) {
            $query->where('nama_menu', 'like', "%{$search}%");
        }

        $menus = $query->latest()->paginate(12)->withQueryString();

        return view('menu.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        $menu->loadCount('ulasans')->loadAvg('ulasans', 'rating');

        $ulasans = $menu->ulasans()
            ->with('user:id_pengguna,nama')
            ->latest()
            ->paginate(10);

        return view('menu.show', compact('menu', 'ulasans'));
    }
}
