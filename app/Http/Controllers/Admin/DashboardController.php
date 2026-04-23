<?php
/**
 * Purpose: Admin dashboard summary stats
 * Used by: Admin routes
 * Dependencies: Pemesanan, Menu, User models
 * Main functions: index()
 * Side effects: None (read-only)
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'    => Pemesanan::count(),
            'pending_orders'  => Pemesanan::where('status_pemesanan', 'paid')->count(),
            'total_revenue'   => Pemesanan::whereIn('status_pemesanan', ['paid','prepared','shipped','delivered'])->sum('total_harga'),
            'total_users'     => User::where('role', 'user')->count(),
            'low_stock_menus' => Menu::where('stok_menu', '<=', 5)->where('tersedia', true)->count(),
        ];

        $recentOrders = Pemesanan::with('user:id_pengguna,nama')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
