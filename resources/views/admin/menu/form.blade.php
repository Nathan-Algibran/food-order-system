{{--
 * Purpose: Shared create/edit form for menu items
 * Used by: GET /admin/menu/create  (Admin\MenuController@create) → $menu = new Menu
 *           GET /admin/menu/{menu}/edit (Admin\MenuController@edit)  → $menu = existing
 * Dependencies: layouts/app, $menu (Menu model)
 *               Menu fields: id_menu, nama_menu, harga_menu, stok_menu, deskripsi, tersedia, gambar
 * Main sections: Breadcrumb, image upload with live preview, fields, tersedia toggle, submit
 * Side effects: POST admin.menu.store | PUT admin.menu.update → writes/replaces image file
--}}
@extends('layouts.admin')
@section('title', $menu->exists ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6 animate-fade-in">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brand transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('admin.menu.index') }}" class="hover:text-brand transition-colors">Menu</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium">{{ $menu->exists ? 'Edit' : 'Tambah' }}</span>
    </nav>

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6 animate-fade-in">
        {{ $menu->exists ? '✏️ Edit Menu' : '➕ Tambah Menu Baru' }}
    </h1>

    @php $existingImg = $menu->gambar ? asset('storage/'.$menu->gambar) : ''; @endphp

    <form method="POST"
          action="{{ $menu->exists ? route('admin.menu.update', $menu) : route('admin.menu.store') }}"
          enctype="multipart/form-data"
          x-data="{ preview: '{{ $existingImg }}', handleFile(e){ const f=e.target.files[0]; if(f) this.preview=URL.createObjectURL(f); } }"
          class="space-y-5 animate-fade-in">
        @csrf
        @if($menu->exists) @method('PUT') @endif

        {{-- Image upload --}}
        <div class="card p-5">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Foto Menu</label>
            <div class="flex gap-4 items-start">
                <div class="w-28 h-28 rounded-2xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-200 shrink-0 flex items-center justify-center">
                    <template x-if="preview">
                        <img :src="preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!preview">
                        <span class="text-3xl">🍱</span>
                    </template>
                </div>
                <label class="flex-1 border-2 border-dashed border-gray-200 rounded-2xl p-5 text-center cursor-pointer hover:border-brand hover:bg-orange-50 transition-all duration-200 block">
                    <input type="file" name="gambar" accept="image/*" @change="handleFile($event)" class="sr-only">
                    <svg class="w-7 h-7 text-gray-300 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-sm text-gray-500 font-medium">{{ $menu->exists ? 'Ganti foto' : 'Upload foto' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">JPG, PNG — max 2MB</p>
                </label>
            </div>
            @error('gambar')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Fields --}}
        <div class="card p-5 space-y-4">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Menu <span class="text-red-400">*</span></label>
                <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" required
                       class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama_menu') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50' }} focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all text-sm"
                       placeholder="Contoh: Nasi Goreng Spesial">
                @error('nama_menu')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all text-sm resize-none"
                          placeholder="Deskripsi singkat menu...">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                @error('deskripsi')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="harga_menu" value="{{ old('harga_menu', $menu->harga_menu) }}" min="0" step="500" required
                               class="w-full pl-9 pr-4 py-3 rounded-xl border {{ $errors->has('harga_menu') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50' }} focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all text-sm"
                               placeholder="15000">
                    </div>
                    @error('harga_menu')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-400">*</span></label>
                    <input type="number" name="stok_menu" value="{{ old('stok_menu', $menu->stok_menu ?? 0) }}" min="0" required
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('stok_menu') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50' }} focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all text-sm"
                           placeholder="50">
                    @error('stok_menu')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Tersedia toggle --}}
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Tampilkan ke Publik</p>
                    <p class="text-xs text-gray-400 mt-0.5">Menu akan muncul di halaman pengunjung</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="tersedia" value="0">
                    <input type="checkbox" name="tersedia" value="1"
                           @checked(old('tersedia', $menu->exists ? $menu->tersedia : true))
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-checked:bg-brand rounded-full transition-colors duration-200
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5
                                after:bg-white after:rounded-full after:shadow after:transition-all
                                peer-checked:after:translate-x-5"></div>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 py-3.5 text-base flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $menu->exists ? 'Simpan Perubahan' : 'Tambah Menu' }}
            </button>
            <a href="{{ route('admin.menu.index') }}" class="btn-ghost py-3.5 px-6">Batal</a>
        </div>
    </form>
</div>
@endsection