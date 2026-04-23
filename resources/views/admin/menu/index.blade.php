{{--
 * Purpose: Admin menu list — paginated table with search, stock indicator, delete
 * Used by: GET /admin/menu (Admin\MenuController@index)
 * Dependencies: layouts/app, $menus (LengthAwarePaginator<Menu> with ulasans_count, ulasans_avg_rating)
 *               Menu fields: id_menu, nama_menu, harga_menu, stok_menu, gambar, tersedia
 * Main sections: Header + add button, search bar, responsive table, pagination
 * Side effects: DELETE admin.menu.destroy
--}}
@extends('layouts.admin')
@section('title', 'Kelola Menu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">🍽️ Kelola Menu</h1>
            <p class="text-gray-400 text-sm mt-0.5">{{ $menus->total() }} item terdaftar</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="btn-primary text-sm self-start sm:self-auto flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Menu
        </a>
    </div>

    {{-- Search --}}
    <div class="card p-4 mb-5 animate-fade-in">
        <form method="GET" action="{{ route('admin.menu.index') }}" class="flex gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all text-sm">
            </div>
            <button type="submit" class="btn-primary text-sm px-4">Cari</button>
            @if(request('search'))<a href="{{ route('admin.menu.index') }}" class="btn-ghost text-sm px-4">Reset</a>@endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden animate-fade-in">
        @if($menus->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <div class="text-5xl mb-3">🍽️</div>
            <p>{{ request('search') ? 'Menu tidak ditemukan' : 'Belum ada menu' }}</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs text-gray-400 font-semibold uppercase tracking-wide">
                        <th class="text-left px-5 py-3.5">Menu</th>
                        <th class="text-left px-5 py-3.5">Harga</th>
                        <th class="text-left px-5 py-3.5">Stok</th>
                        <th class="text-left px-5 py-3.5">Rating</th>
                        <th class="text-left px-5 py-3.5">Status</th>
                        <th class="text-right px-5 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($menus as $menu)
                    <tr class="hover:bg-gray-50/60 transition-colors group animate-fade-in"
                        style="animation-delay:{{ $loop->index*40 }}ms">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                    @if($menu->gambar)
                                    <img src="{{ asset('storage/'.$menu->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-xl bg-orange-50">🍱</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate max-w-[160px]">{{ $menu->nama_menu }}</p>
                                    <p class="text-xs text-gray-400">{{ $menu->ulasans_count }} ulasan</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-bold text-brand">Rp{{ number_format($menu->harga_menu,0,',','.') }}</td>
                        <td class="px-5 py-4">
                            <span class="font-semibold {{ $menu->stok_menu <= 5 ? 'text-red-500' : 'text-gray-700' }}">{{ $menu->stok_menu }}</span>
                            @if($menu->stok_menu <= 5 && $menu->stok_menu > 0)<span class="ml-1 text-[10px] bg-red-100 text-red-500 px-1.5 py-0.5 rounded-full font-semibold">Menipis</span>@elseif($menu->stok_menu === 0)<span class="ml-1 text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full font-semibold">Habis</span>@endif
                        </td>
                        <td class="px-5 py-4">
                            @php $avg = round($menu->ulasans_avg_rating ?? 0, 1); @endphp
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-semibold text-gray-700">{{ number_format($avg,1) }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $menu->tersedia ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $menu->tersedia ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                {{ $menu->tersedia ? 'Tersedia' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.menu.edit', $menu) }}"
                                   class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-all active:scale-90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}"
                                      x-data @submit.prevent="if(confirm('Hapus \'{{ addslashes($menu->nama_menu) }}\'?')) $el.submit()">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($menus->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
            <span>{{ $menus->firstItem() }}–{{ $menus->lastItem() }} dari {{ $menus->total() }}</span>
            {{ $menus->links('vendor.pagination.simple-tailwind') }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection