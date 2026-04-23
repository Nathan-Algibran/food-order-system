<?php
/**
 * Purpose: Admin CRUD for menu items
 * Used by: Admin routes
 * Dependencies: Menu model, Storage
 * Main functions: index(), create(), store(), edit(), update(), destroy()
 * Side effects: Writes/deletes menu records and image files
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::withCount('ulasans')
            ->withAvg('ulasans', 'rating')
            ->when($request->search, fn($q) => $q->where('nama_menu', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.form', ['menu' => new Menu]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }
        Menu::create($data);
        return redirect()->route('admin.menu.index')->with('success', 'Menu ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.form', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validated($request, $menu);
        if ($request->hasFile('gambar')) {
            if ($menu->gambar) Storage::disk('public')->delete($menu->gambar);
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }
        $menu->update($data);
        return redirect()->route('admin.menu.index')->with('success', 'Menu diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar) Storage::disk('public')->delete($menu->gambar);
        $menu->delete();
        return back()->with('success', 'Menu dihapus.');
    }

    private function validated(Request $request, ?Menu $menu = null): array
    {
        return $request->validate([
            'nama_menu'  => 'required|string|max:150',
            'harga_menu' => 'required|numeric|min:0',
            'stok_menu'  => 'required|integer|min:0',
            'deskripsi'  => 'nullable|string',
            'tersedia'   => 'boolean',
            'gambar'     => 'nullable|image|max:2048',
        ]);
    }
}