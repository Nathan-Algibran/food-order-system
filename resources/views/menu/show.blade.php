{{--
 * Purpose: Single menu detail page — image, description, rating, reviews, add to cart
 * Used by: GET /menu/{menu} (MenuController@show)
 * Dependencies: layouts/app, $menu (Menu), $ulasans (Paginator<Ulasan with user>)
--}}
@extends('layouts.app')
@section('title', $menu->nama_menu . ' - FoodApp')

@section('content')

<style>
    /* Modern Detail Page Styles */
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        font-size: 0.9rem;
        animation: slideInLeft 0.6s ease;
    }

    .breadcrumb a {
        color: var(--muted);
        text-decoration: none;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb a:hover {
        color: var(--amber);
    }

    .breadcrumb i {
        font-size: 1rem;
    }

    .breadcrumb .separator {
        color: #cbd5e1;
        font-size: 0.8rem;
    }

    .breadcrumb .current {
        color: var(--char);
        font-weight: 600;
    }

    /* Main Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    /* Image Gallery */
    .image-gallery {
        animation: scaleIn 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .main-image {
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #fef3c7 0%, #fff 100%);
        position: relative;
    }

    .main-image:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 50px -15px rgba(245,158,11,0.3);
    }

    .main-image img {
        width: 100%;
        height: 380px;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .main-image:hover img {
        transform: scale(1.08);
    }

    .image-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 2;
    }

    .favorite-btn-large {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(8px);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 2;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .favorite-btn-large:hover {
        transform: scale(1.15);
        background: white;
        box-shadow: 0 8px 20px rgba(245,158,11,0.3);
    }

    .favorite-btn-large.loved {
        color: var(--red);
        animation: pulse 0.5s ease;
    }

    /* Info Section */
    .info-section {
        animation: slideInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1.2rem;
        border-radius: 60px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(8px);
    }

    .status-badge.available {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-badge.unavailable {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .status-badge i {
        font-size: 1rem;
    }

    .menu-name {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 900;
        color: var(--char);
        line-height: 1.2;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }

    @media (max-width: 768px) {
        .menu-name {
            font-size: 2.2rem;
        }
    }

    /* Rating Summary */
    .rating-summary {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .rating-stars {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stars-container {
        display: flex;
        gap: 2px;
    }

    .star {
        width: 20px;
        height: 20px;
        color: #e2e8f0;
    }

    .star.filled {
        color: #fbbf24;
    }

    .rating-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--char);
    }

    .rating-count {
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* Price Display */
    .price-display {
        display: flex;
        align-items: baseline;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem 0;
        border-top: 2px solid var(--border);
        border-bottom: 2px solid var(--border);
    }

    .current-price {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--amber);
    }

    .stock-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--muted);
        font-size: 0.9rem;
    }

    .stock-info i {
        color: var(--amber);
    }

    /* Description */
    .description {
        color: var(--muted);
        line-height: 1.7;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    /* Add to Cart Section */
    .cart-section {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(254,243,199,0.3) 100%);
        backdrop-filter: blur(12px);
        border-radius: 24px;
        padding: 1.8rem;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .quantity-label {
        font-weight: 600;
        color: var(--char);
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid var(--border);
    }

    .qty-btn {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--muted);
        transition: all 0.2s;
        font-size: 1.2rem;
    }

    .qty-btn:hover:not(:disabled) {
        background: var(--amber-lt);
        color: var(--amber);
    }

    .qty-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .qty-display {
        width: 50px;
        text-align: center;
        font-weight: 700;
        color: var(--char);
    }

    .subtotal-display {
        margin-left: auto;
        font-size: 0.9rem;
        color: var(--muted);
    }

    .subtotal-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--amber);
        margin-left: 0.5rem;
    }

    .add-to-cart-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dk) 100%);
        color: white;
        border: none;
        border-radius: 18px;
        padding: 1rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px -6px rgba(245,158,11,0.4);
    }

    .add-to-cart-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .add-to-cart-btn:hover::before {
        width: 400px;
        height: 400px;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -8px rgba(245,158,11,0.6);
    }

    .add-to-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #cbd5e1;
        box-shadow: none;
    }

    .add-to-cart-btn i {
        font-size: 1.3rem;
        transition: transform 0.3s;
    }

    .add-to-cart-btn:hover i {
        transform: rotate(5deg) scale(1.1);
    }

    .login-prompt {
        text-align: center;
        padding: 1rem;
    }

    .login-link {
        display: inline-block;
        background: var(--char);
        color: white;
        text-decoration: none;
        padding: 1rem 2rem;
        border-radius: 18px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .login-link:hover {
        background: var(--amber);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(245,158,11,0.4);
    }

    /* Reviews Section */
    .reviews-section {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .section-title i {
        color: var(--amber);
        font-size: 2rem;
    }

    .review-count-badge {
        background: var(--amber-lt);
        color: var(--amber-dk);
        padding: 0.3rem 1rem;
        border-radius: 60px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: 1rem;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .review-card {
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        animation: slideInLeft 0.5s ease backwards;
    }

    .review-card:hover {
        transform: translateX(8px);
        box-shadow: 0 10px 30px -10px rgba(245,158,11,0.15);
        border-color: var(--amber);
        background: white;
    }

    .review-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .reviewer-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--amber), #ef4444);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    }

    .reviewer-details h4 {
        font-weight: 700;
        color: var(--char);
        margin-bottom: 0.2rem;
    }

    .review-date {
        font-size: 0.8rem;
        color: var(--muted);
    }

    .review-rating {
        display: flex;
        gap: 2px;
    }

    .review-content {
        color: var(--char);
        line-height: 1.7;
        padding-left: 4rem;
    }

    @media (max-width: 640px) {
        .review-content {
            padding-left: 0;
            margin-top: 1rem;
        }
    }

    .empty-reviews {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255,255,255,0.5);
        backdrop-filter: blur(8px);
        border-radius: 24px;
    }

    .empty-reviews i {
        font-size: 4rem;
        color: var(--amber);
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    .empty-reviews p {
        color: var(--muted);
        font-size: 1.1rem;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="detail-container">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('menu.index') }}">
            <i class="ri-arrow-left-line"></i> Menu
        </a>
        <span class="separator">/</span>
        <span class="current">{{ $menu->nama_menu }}</span>
    </nav>

    {{-- Main Detail --}}
    <div class="detail-grid">
        {{-- Image Gallery --}}
        <div class="image-gallery">
            <div class="main-image">
                @if($menu->gambar)
                <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}">
                @else
                <div style="height: 380px; display: flex; align-items: center; justify-content: center; font-size: 6rem; background: linear-gradient(135deg, #fef3c7, #fff);">
                    🍱
                </div>
                @endif
                
                <button class="favorite-btn-large" onclick="toggleFavorite(this)">
                    <i class="ri-heart-line"></i>
                </button>
            </div>
        </div>

        {{-- Info Section --}}
        <div class="info-section">
            {{-- Status Badge --}}
            <span class="status-badge {{ $menu->tersedia && $menu->stok_menu > 0 ? 'available' : 'unavailable' }}">
                <i class="{{ $menu->tersedia && $menu->stok_menu > 0 ? 'ri-checkbox-circle-line' : 'ri-close-circle-line' }}"></i>
                {{ $menu->tersedia && $menu->stok_menu > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
            </span>

            <h1 class="menu-name">{{ $menu->nama_menu }}</h1>

            {{-- Rating Summary --}}
            @php $avg = round($menu->ulasans_avg_rating ?? 0, 1); @endphp
            <div class="rating-summary">
                <div class="rating-stars">
                    <div class="stars-container">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="star {{ $i <= $avg ? 'filled' : '' }} ri-star-fill"></i>
                        @endfor
                    </div>
                    <span class="rating-number">{{ number_format($avg, 1) }}</span>
                </div>
                <span class="rating-count">({{ $menu->ulasans_count }} ulasan)</span>
            </div>

            {{-- Price Display --}}
            <div class="price-display">
                <span class="current-price">Rp {{ number_format($menu->harga_menu, 0, ',', '.') }}</span>
                <span class="stock-info">
                    <i class="ri-stack-line"></i>
                    Stok: {{ $menu->stok_menu }}
                </span>
            </div>

            {{-- Description --}}
            @if($menu->deskripsi)
            <p class="description">{{ $menu->deskripsi }}</p>
            @else
            <p class="description">Nikmati kelezatan {{ $menu->nama_menu }} yang dibuat dengan bahan-bahan pilihan dan resep istimewa. Cocok untuk segala suasana!</p>
            @endif

            {{-- Add to Cart --}}
            @auth
            <div class="cart-section" x-data="{ 
                qty: 1, 
                loading: false,
                get subtotal() { return this.qty * {{ $menu->harga_menu }} }
            }">
                @if($menu->tersedia && $menu->stok_menu > 0)
                <form method="POST" action="{{ route('cart.add', $menu) }}" @submit="loading = true">
                    @csrf
                    <input type="hidden" name="jumlah" :value="qty">

                    <div class="quantity-selector">
                        <span class="quantity-label">Jumlah:</span>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" @click="if(qty > 1) qty--" :disabled="qty <= 1">
                                <i class="ri-subtract-line"></i>
                            </button>
                            <span class="qty-display" x-text="qty"></span>
                            <button type="button" class="qty-btn" @click="if(qty < {{ $menu->stok_menu }}) qty++" :disabled="qty >= {{ $menu->stok_menu }}">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                        <div class="subtotal-display">
                            Subtotal:
                            <span class="subtotal-amount" x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <button type="submit" class="add-to-cart-btn" :disabled="loading">
                        <i class="ri-shopping-cart-2-line" x-show="!loading"></i>
                        <i class="ri-loader-4-line animate-spin" x-show="loading" style="display: none;"></i>
                        <span x-text="loading ? 'Menambahkan...' : 'Tambah ke Keranjang'"></span>
                    </button>
                </form>
                @else
                <button class="add-to-cart-btn" disabled style="background: #cbd5e1; cursor: not-allowed;">
                    <i class="ri-forbid-line"></i>
                    Menu Tidak Tersedia
                </button>
                @endif
            </div>
            @else
            <div class="cart-section login-prompt">
                <p style="margin-bottom: 1rem; color: var(--muted);">Silakan masuk untuk memesan menu ini</p>
                <a href="{{ route('login') }}" class="login-link">
                    <i class="ri-login-box-line"></i> Masuk Sekarang
                </a>
            </div>
            @endauth
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="reviews-section">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
            <h3 style="font-size:1.2rem; font-weight:700;">Ulasan Pelanggan</h3>
            <span class="review-count-badge">{{ $menu->ulasans_count }} Ulasan</span>
        </div>

        @if($ulasans->isEmpty())
        <div class="empty-reviews">
            <i class="ri-chat-smile-3-line"></i>
            <p>Belum ada ulasan untuk menu ini</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Jadilah yang pertama memberikan ulasan!</p>
        </div>
        @else
        <div class="reviews-list">
            @foreach($ulasans as $index => $ulasan)
            <div class="review-card" style="animation-delay: {{ $index * 0.1 }}s;">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar"
                            style="{{ auth()->check() && $ulasan->id_pengguna === auth()->id() ? 'background: linear-gradient(135deg, var(--amber), #f97316);' : '' }}">
                            {{ strtoupper(substr($ulasan->user->nama ?? 'U', 0, 1)) }}
                        </div>
                        <div class="reviewer-details">
                            <h4>
                                {{ $ulasan->user->nama ?? 'Pengguna' }}
                                @if(auth()->check() && $ulasan->id_pengguna === auth()->id())
                                <span style="font-size:0.75rem; color:var(--amber); font-weight:600; margin-left:6px;">Ulasan Anda</span>
                                @endif
                            </h4>
                            <span class="review-date">{{ $ulasan->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="star {{ $i <= $ulasan->rating ? 'filled' : '' }} ri-star-fill" style="width:16px;height:16px;"></i>
                        @endfor
                    </div>
                </div>
                @if($ulasan->komentar)
                <div class="review-content">{{ $ulasan->komentar }}</div>
                @else
                <div class="review-content" style="color:var(--muted);font-style:italic;">Tidak ada komentar</div>
                @endif
            </div>
            @endforeach
        </div>

        @if($ulasans->hasPages())
        <div class="pagination-container">
            {{ $ulasans->links('vendor.pagination.simple-tailwind') }}
        </div>
        @endif
        @endif
    </div>
</div>

<script>
    function toggleFavorite(btn) {
        const icon = btn.querySelector('i');
        const isLoved = btn.classList.toggle('loved');
        
        if (isLoved) {
            icon.classList.remove('ri-heart-line');
            icon.classList.add('ri-heart-fill');
            // Optional: Show toast notification
            console.log('Added to favorites!');
        } else {
            icon.classList.remove('ri-heart-fill');
            icon.classList.add('ri-heart-line');
        }
        
        btn.style.transform = 'scale(1.2)';
        setTimeout(() => btn.style.transform = '', 200);
    }

    // Add animation on scroll
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.review-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            observer.observe(card);
        });
    });
</script>

@endsection