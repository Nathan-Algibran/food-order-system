{{--
 * Purpose: Admin dashboard — KPI stat cards + recent orders table
 * Used by: GET /admin/dashboard (Admin\DashboardController@index)
 * Dependencies: layouts/app, $stats (array), $recentOrders (Collection<Pemesanan with user>)
 *               $stats keys: total_orders, pending_orders, total_revenue, total_users, low_stock_menus
 * Main sections: Greeting, 5 stat cards, recent orders table, quick nav links
 * Side effects: None (read-only)
--}}
@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ── HEADER ───────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">
                Selamat datang, <span class="text-brand">{{ auth()->user()->nama }}</span> 👋
            </h1>
            <p class="text-gray-400 text-sm mt-0.5">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.menu.create') }}" class="btn-ghost text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Menu
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn-primary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Kelola Pesanan
            </a>
        </div>
    </div>

    {{-- ── STAT CARDS ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @php
        $cards = [
            ['label'=>'Total Pesanan',   'value'=>number_format($stats['total_orders']),                               'icon'=>'📦','bg'=>'bg-blue-50',   'text'=>'text-blue-600',   'ring'=>'ring-blue-100'],
            ['label'=>'Perlu Diproses',  'value'=>number_format($stats['pending_orders']),                             'icon'=>'⏳','bg'=>'bg-yellow-50', 'text'=>'text-yellow-600', 'ring'=>'ring-yellow-100'],
            ['label'=>'Total Pendapatan','value'=>'Rp'.number_format($stats['total_revenue'],0,',','.'),               'icon'=>'💰','bg'=>'bg-emerald-50','text'=>'text-emerald-600','ring'=>'ring-emerald-100'],
            ['label'=>'Total Pengguna',  'value'=>number_format($stats['total_users']),                                'icon'=>'👥','bg'=>'bg-purple-50', 'text'=>'text-purple-600', 'ring'=>'ring-purple-100'],
            ['label'=>'Stok Menipis',    'value'=>number_format($stats['low_stock_menus']),                            'icon'=>'⚠️','bg'=>'bg-red-50',    'text'=>'text-red-600',    'ring'=>'ring-red-100'],
        ];
        @endphp
        @foreach($cards as $i => $c)
        <div class="card p-5 hover:scale-[1.02] transition-transform duration-200 animate-fade-in"
             style="animation-delay:{{ $i*70 }}ms">
            <div class="w-10 h-10 {{ $c['bg'] }} ring-1 {{ $c['ring'] }} rounded-xl flex items-center justify-center text-xl mb-3">
                {{ $c['icon'] }}
            </div>
            <p class="text-2xl font-extrabold {{ $c['text'] }} mb-0.5 truncate">{{ $c['value'] }}</p>
            <p class="text-xs text-gray-400 font-medium">{{ $c['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── RECENT ORDERS TABLE ──────────────────────────────────────── --}}
    <div class="card mb-6 animate-fade-in" style="animation-delay:350ms">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}"
               class="text-sm text-brand hover:text-brand-dark font-semibold transition-colors flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($recentOrders->isEmpty())
        <div class="text-center py-12 text-gray-400 text-sm">Belum ada pesanan</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 font-semibold uppercase tracking-wide border-b border-gray-50">
                        <th class="text-left px-6 py-3">ID</th>
                        <th class="text-left px-6 py-3">Pelanggan</th>
                        <th class="text-left px-6 py-3">Total</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-left px-6 py-3">Waktu</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentOrders as $order)
                    @php
                        $stColor = ['pending'=>'bg-yellow-100 text-yellow-700','paid'=>'bg-blue-100 text-blue-700','prepared'=>'bg-purple-100 text-purple-700','shipped'=>'bg-indigo-100 text-indigo-700','delivered'=>'bg-emerald-100 text-emerald-700','cancelled'=>'bg-red-100 text-red-500'][$order->status_pemesanan] ?? 'bg-gray-100 text-gray-500';
                        $stLabel = ['pending'=>'Menunggu','paid'=>'Dibayar','prepared'=>'Disiapkan','shipped'=>'Dikirim','delivered'=>'Diterima','cancelled'=>'Batal'][$order->status_pemesanan] ?? $order->status_pemesanan;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ str_pad($order->id_pemesanan,5,'0',STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-orange-100 rounded-full flex items-center justify-center text-brand text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($order->user->nama ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800 truncate max-w-[120px]">{{ $order->user->nama ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-brand">Rp{{ number_format($order->total_harga,0,',','.') }}</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColor }}">{{ $stLabel }}</span></td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $order->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-xs font-semibold text-gray-300 hover:text-brand transition-colors group-hover:text-gray-500">Detail →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection