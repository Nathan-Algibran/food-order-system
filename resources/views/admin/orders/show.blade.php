{{--
 * Purpose: Admin order detail — full info + konfirmasi pembayaran QRIS/Bank + status advancement
 * Used by: GET /admin/orders/{pemesanan} (Admin\OrderController@show)
 * Dependencies: layouts/app, $pemesanan (Pemesanan with user, items.menu, pembayaran)
 *               OrderController::FLOW = ['paid'=>'prepared', 'prepared'=>'shipped']
 * Main sections: Breadcrumb, customer info, items table, payment card (+ confirm btn),
 *                status timeline, advance-status form (if eligible)
 * Side effects:
 *   PATCH admin.orders.confirm-payment → updates status_pembayaran + status_pemesanan
 *   PATCH admin.orders.status          → updates status_pemesanan
--}}
@extends('layouts.admin')
@section('title', 'Detail Pesanan #'.str_pad($pemesanan->id_pemesanan,5,'0',STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6 animate-fade-in">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.orders.index') }}" class="hover:text-brand transition-colors">Pesanan</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium">#{{ str_pad($pemesanan->id_pemesanan,5,'0',STR_PAD_LEFT) }}</span>
    </nav>

    @php
        $statusSteps  = ['pending','paid','prepared','shipped','delivered'];
        $currentIdx   = array_search($pemesanan->status_pemesanan, $statusSteps);
        $statusLabels = [
            'pending'   => ['Menunggu',  '🕐'],
            'paid'      => ['Dibayar',   '💳'],
            'prepared'  => ['Disiapkan', '👨‍🍳'],
            'shipped'   => ['Dikirim',   '🚚'],
            'delivered' => ['Diterima',  '✅'],
        ];
        $nextMap     = ['paid' => 'prepared', 'prepared' => 'shipped'];
        $nextStatus  = $nextMap[$pemesanan->status_pemesanan] ?? null;
        $nextLabels  = ['prepared' => 'Tandai Disiapkan', 'shipped' => 'Tandai Dikirim'];

        // Apakah pembayaran perlu dikonfirmasi oleh admin?
        $needsPaymentConfirm = $pemesanan->pembayaran
            && in_array($pemesanan->pembayaran->metode_pembayaran, ['qris', 'bank_transfer'])
            && $pemesanan->pembayaran->status_pembayaran === 'unpaid'
            && $pemesanan->status_pemesanan === 'pending';
    @endphp

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2 animate-fade-in">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2 animate-fade-in">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ⚠️ Banner konfirmasi pembayaran (QRIS / Bank Transfer) --}}
    @if($needsPaymentConfirm)
    <div class="mb-6 rounded-2xl border-2 border-yellow-300 bg-yellow-50 p-5 animate-fade-in" x-data="{ open: false }">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-yellow-200 flex items-center justify-center text-xl shrink-0">⏳</div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-yellow-800 text-sm">Menunggu Konfirmasi Pembayaran</p>
                <p class="text-yellow-700 text-xs mt-0.5">
                    Pelanggan memilih
                    <span class="font-semibold uppercase">{{ str_replace('_', ' ', $pemesanan->pembayaran->metode_pembayaran) }}</span>.
                    @if($pemesanan->pembayaran->bukti_bayar)
                        Bukti transfer sudah diupload — periksa sebelum konfirmasi.
                    @else
                        Bukti transfer belum diupload.
                    @endif
                </p>

                {{-- Thumbnail bukti bayar (jika ada) --}}
                @if($pemesanan->pembayaran->bukti_bayar)
                <div class="mt-4">
                    <p class="text-xs text-gray-400 mb-2 font-semibold">📎 Bukti Transfer</p>
                    <a href="{{ asset('storage/'.$pemesanan->pembayaran->bukti_bayar) }}" target="_blank"
                    class="block group relative overflow-hidden rounded-xl border border-gray-200 hover:border-brand transition-colors">
                        <img src="{{ asset('storage/'.$pemesanan->pembayaran->bukti_bayar) }}"
                            class="w-full max-h-64 object-contain bg-gray-50 hover:opacity-90 transition-opacity">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/10">
                            <span class="bg-white text-xs font-semibold px-3 py-1.5 rounded-full shadow">🔍 Klik untuk buka penuh</span>
                        </div>
                    </a>
                </div>
                @else
                <div class="mt-4">
                    <p class="text-xs text-gray-400 italic">Tidak ada bukti transfer diunggah</p>
                </div>
                @endif
            </div>

            {{-- Tombol Konfirmasi + Tolak --}}
            <div class="flex flex-col sm:flex-row gap-2 shrink-0">
                {{-- Konfirmasi --}}
                <form method="POST"
                      action="{{ route('admin.orders.confirm-payment', $pemesanan) }}"
                      x-data="{ loading: false }" @submit="loading = true">
                    @csrf @method('PATCH')
                    <button type="submit" :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-colors disabled:opacity-70 whitespace-nowrap">
                        <svg x-show="loading" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        <svg x-show="!loading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Konfirmasi Lunas</span>
                    </button>
                </form>

                {{-- Tolak --}}
                <form method="POST"
                      action="{{ route('admin.orders.reject-payment', $pemesanan) }}"
                      x-data="{ loading: false }" @submit="loading = true"
                      onsubmit="return confirm('Tolak pembayaran ini? Status pesanan akan dikembalikan ke pending.')">
                    @csrf @method('PATCH')
                    <button type="submit" :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-red-300 hover:bg-red-50 text-red-600 text-xs font-bold rounded-xl transition-colors disabled:opacity-70 whitespace-nowrap">
                        <svg x-show="loading" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        <svg x-show="!loading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Tolak</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">
                Pesanan #{{ str_pad($pemesanan->id_pemesanan,5,'0',STR_PAD_LEFT) }}
            </h1>
            <p class="text-gray-400 text-sm mt-0.5">{{ $pemesanan->created_at->format('d M Y, H:i') }}</p>
        </div>

        {{-- Advance status CTA (hanya muncul setelah paid) --}}
        @if($nextStatus)
        <form method="POST" action="{{ route('admin.orders.status', $pemesanan) }}" x-data="{ loading: false }" @submit="loading = true">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $nextStatus }}">
            <button type="submit" :disabled="loading"
                    class="btn-primary flex items-center gap-2 text-sm disabled:opacity-70">
                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                <span>{{ $nextLabels[$nextStatus] ?? 'Update Status' }}</span>
            </button>
        </form>
        @endif
    </div>

    {{-- Status timeline --}}
    <div class="card p-6 mb-5 animate-fade-in">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 right-0 h-0.5 bg-gray-100 top-5 mx-8 z-0"></div>
            @if($currentIdx > 0)
            <div class="absolute left-8 h-0.5 bg-brand top-5 z-0 transition-all duration-700"
                 style="width:calc({{ min($currentIdx,4) }}/4*(100% - 4rem))"></div>
            @endif
            @foreach($statusSteps as $i => $step)
            @php $done = $i <= $currentIdx; $active = $i === $currentIdx; @endphp
            <div class="flex flex-col items-center z-10 flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-base transition-all duration-300 border-2
                    {{ $done ? 'bg-brand border-brand text-white shadow-md' : 'bg-white border-gray-200 text-gray-300' }}
                    {{ $active ? 'ring-4 ring-brand/20 scale-110' : '' }}">
                    {{ $statusLabels[$step][1] }}
                </div>
                <p class="text-[10px] font-semibold mt-2 {{ $done ? 'text-brand' : 'text-gray-300' }} text-center">
                    {{ $statusLabels[$step][0] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5 mb-5">

        {{-- Customer info --}}
        <div class="card p-5 animate-fade-in">
            <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-7 h-7 bg-orange-100 rounded-full flex items-center justify-center text-brand text-sm font-bold">
                    {{ strtoupper(substr($pemesanan->user->nama ?? '?', 0, 1)) }}
                </span>
                Informasi Pelanggan
            </h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Nama</span>
                    <span class="font-semibold text-gray-800">{{ $pemesanan->user->nama ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Email</span>
                    <span class="font-medium text-gray-700 text-xs">{{ $pemesanan->user->email ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Alamat</span>
                    <span class="font-medium text-gray-700 text-xs text-right max-w-[180px]">{{ $pemesanan->user->alamat ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Payment info --}}
        <div class="card p-5 animate-fade-in">
            <h2 class="font-bold text-gray-900 mb-4">💳 Pembayaran</h2>
            @if($pemesanan->pembayaran)
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Metode</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-gray-800 capitalize">
                        @if($pemesanan->pembayaran->metode_pembayaran === 'qris')
                            📱
                        @elseif($pemesanan->pembayaran->metode_pembayaran === 'bank_transfer')
                            🏦
                        @else
                            💵
                        @endif
                        {{ str_replace('_', ' ', $pemesanan->pembayaran->metode_pembayaran) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Status</span>
                    @php $paid = $pemesanan->pembayaran->status_pembayaran === 'paid'; @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $paid ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                        @if($paid)
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Lunas
                        @else
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Menunggu Konfirmasi
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Jumlah</span>
                    <span class="font-bold text-brand">Rp{{ number_format($pemesanan->pembayaran->jumlah,0,',','.') }}</span>
                </div>
            </div>

            {{-- Bukti bayar di card (tampil jika tidak dalam mode "pending confirm") --}}
            @if($pemesanan->pembayaran->bukti_bayar && !$needsPaymentConfirm)
            <div class="mt-4" x-data="{ open: false }">
                <button type="button" @click="open = !open"
                        class="text-xs text-gray-400 hover:text-brand transition-colors underline">
                    <span x-text="open ? 'Sembunyikan bukti' : 'Lihat bukti transfer'">Lihat bukti transfer</span>
                </button>
                <div x-show="open" x-transition class="mt-2">
                    <a href="{{ asset('storage/'.$pemesanan->pembayaran->bukti_bayar) }}" target="_blank" class="block">
                        <img src="{{ asset('storage/'.$pemesanan->pembayaran->bukti_bayar) }}"
                             class="max-h-32 rounded-xl border border-gray-100 hover:opacity-90 transition-opacity object-contain cursor-zoom-in">
                    </a>
                </div>
            </div>
            @endif

            @else
            <p class="text-sm text-gray-400">Data pembayaran tidak tersedia</p>
            @endif
        </div>
    </div>

    {{-- Order items --}}
    <div class="card p-5 mb-5 animate-fade-in">
        <h2 class="font-bold text-gray-900 mb-4">🍱 Item Pesanan</h2>
        <div class="space-y-3">
            @foreach($pemesanan->items as $item)
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                    @if($item->menu?->gambar)
                    <img src="{{ asset('storage/'.$item->menu->gambar) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-xl">🍱</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-gray-900">{{ $item->menu?->nama_menu ?? 'Menu dihapus' }}</p>
                    <p class="text-xs text-gray-400">{{ $item->jumlah }} × Rp{{ number_format($item->harga_satuan,0,',','.') }}</p>
                </div>
                <span class="font-bold text-sm text-gray-800 shrink-0">
                    Rp{{ number_format($item->jumlah * $item->harga_satuan,0,',','.') }}
                </span>
            </div>
            @endforeach
        </div>
        <div class="border-t border-dashed border-gray-200 mt-4 pt-4 flex justify-between font-bold text-gray-900">
            <span>Total</span>
            <span class="text-brand text-lg">Rp{{ number_format($pemesanan->total_harga,0,',','.') }}</span>
        </div>
        @if($pemesanan->catatan)
        <div class="mt-3 bg-yellow-50 rounded-xl p-3 text-xs text-yellow-700 flex gap-2">
            <span>📝</span> {{ $pemesanan->catatan }}
        </div>
        @endif
    </div>

    {{-- Back --}}
    <a href="{{ route('admin.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-brand transition-colors animate-fade-in">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Pesanan
    </a>

</div>
@endsection