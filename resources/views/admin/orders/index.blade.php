{{--
 * Purpose: Admin order management — filter by status, list all orders, update status
 * Used by: GET /admin/orders (Admin\OrderController@index)
 * Dependencies: layouts/app, $orders (LengthAwarePaginator<Pemesanan with user, pembayaran>)
 *               $status (string current filter), $counts (Collection status=>total)
 * Main sections: Status tab filters, orders table (ID/customer/total/payment/status/actions)
 * Side effects: None (read-only list; status update via order show page)
--}}
@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">📋 Kelola Pesanan</h1>
            <p class="text-gray-400 text-sm mt-0.5">{{ $orders->total() }} pesanan ditemukan</p>
        </div>
    </div>

    {{-- Status filter tabs --}}
    @php
    $tabs = [
        ['key'=>'all',       'label'=>'Semua',     'icon'=>'🗂️'],
        ['key'=>'paid',      'label'=>'Dibayar',   'icon'=>'💳'],
        ['key'=>'prepared',  'label'=>'Disiapkan', 'icon'=>'👨‍🍳'],
        ['key'=>'shipped',   'label'=>'Dikirim',   'icon'=>'🚚'],
        ['key'=>'delivered', 'label'=>'Diterima',  'icon'=>'✅'],
    ];
    @endphp
    <div class="flex gap-2 overflow-x-auto pb-1 mb-5 animate-fade-in">
        @foreach($tabs as $tab)
        @php $count = $tab['key'] === 'all' ? $counts->sum() : ($counts[$tab['key']] ?? 0); @endphp
        <a href="{{ route('admin.orders.index', ['status'=>$tab['key']]) }}"
           class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200
                  {{ $status === $tab['key'] ? 'bg-brand text-white shadow-sm' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-200' }}">
            {{ $tab['icon'] }} {{ $tab['label'] }}
            @if($count > 0)
            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold
                {{ $status === $tab['key'] ? 'bg-white/30 text-white' : 'bg-gray-100 text-gray-500' }}">
                {{ $count }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden animate-fade-in">
        @if($orders->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-3">📭</div>
            <p>Tidak ada pesanan untuk filter ini</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs text-gray-400 font-semibold uppercase tracking-wide">
                        <th class="text-left px-5 py-3.5">ID</th>
                        <th class="text-left px-5 py-3.5">Pelanggan</th>
                        <th class="text-left px-5 py-3.5">Total</th>
                        <th class="text-left px-5 py-3.5">Pembayaran</th>
                        <th class="text-left px-5 py-3.5">Status</th>
                        <th class="text-left px-5 py-3.5">Waktu</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
                    @php
                        $stColor = ['pending'=>'bg-yellow-100 text-yellow-700','paid'=>'bg-blue-100 text-blue-700','prepared'=>'bg-purple-100 text-purple-700','shipped'=>'bg-indigo-100 text-indigo-700','delivered'=>'bg-emerald-100 text-emerald-700','cancelled'=>'bg-red-100 text-red-500'][$order->status_pemesanan] ?? 'bg-gray-100 text-gray-500';
                        $stLabel = ['pending'=>'Menunggu','paid'=>'Dibayar','prepared'=>'Disiapkan','shipped'=>'Dikirim','delivered'=>'Diterima','cancelled'=>'Batal'][$order->status_pemesanan] ?? $order->status_pemesanan;
                        $payColor = ($order->pembayaran?->status_pembayaran === 'paid') ? 'text-emerald-600' : 'text-yellow-600';
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors group animate-fade-in"
                        style="animation-delay:{{ $loop->index*30 }}ms">
                        <td class="px-5 py-4 font-mono text-xs text-gray-400">
                            #{{ str_pad($order->id_pemesanan,5,'0',STR_PAD_LEFT) }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-orange-100 rounded-full flex items-center justify-center text-brand text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($order->user->nama ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 truncate max-w-[120px]">{{ $order->user->nama ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-gray-400 truncate max-w-[120px]">{{ $order->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-bold text-brand">Rp{{ number_format($order->total_harga,0,',','.') }}</td>
                        <td class="px-5 py-4">
                            @if($order->pembayaran)
                            <p class="text-xs capitalize text-gray-600">{{ str_replace('_',' ',$order->pembayaran->metode_pembayaran) }}</p>
                            <p class="text-[10px] font-semibold {{ $payColor }}">{{ $order->pembayaran->status_pembayaran === 'paid' ? 'Lunas' : 'Belum Lunas' }}</p>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColor }}">{{ $stLabel }}</span>
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-400">{{ $order->created_at->format('d/m/y H:i') }}</td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-xs font-semibold text-gray-300 hover:text-brand transition-colors group-hover:text-gray-500 whitespace-nowrap">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
            <span>{{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }}</span>
            {{ $orders->appends(['status'=>$status])->links('vendor.pagination.simple-tailwind') }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection