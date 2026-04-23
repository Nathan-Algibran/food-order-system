@extends('layouts.app')

@section('content')

<style>
    :root {
        --amber:    #F59E0B;
        --amber-lt: #FEF3C7;
        --amber-dk: #D97706;
        --char:     #111827;
        --muted:    #6B7280;
        --border:   rgba(0,0,0,0.07);
        --red:      #EF4444;
        --green:    #10B981;
        --card-bg:  rgba(255, 255, 255, 0.85);
    }

    /* Modern Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-8px) rotate(-1deg); }
        75% { transform: translateY(4px) rotate(1deg); }
    }

    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.3), 0 10px 30px -10px rgba(0,0,0,0.1); }
        50% { box-shadow: 0 0 30px rgba(245,158,11,0.5), 0 15px 40px -10px rgba(245,158,11,0.2); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(60px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.85); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Page Header - Glassmorphism */
    .menu-header {
        margin-bottom: 2.5rem;
        animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .menu-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 900;
        background: linear-gradient(135deg, var(--char) 0%, var(--amber) 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }

    .menu-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--amber), transparent);
        border-radius: 4px;
    }

    .menu-subtitle {
        font-size: 1rem;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .stats-badge {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        padding: 0.6rem 1.5rem;
        border-radius: 60px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stats-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(245,158,11,0.1);
        border-color: var(--amber);
    }

    .stats-badge strong {
        color: var(--amber);
        font-size: 1.3rem;
        margin-right: 4px;
    }

    /* Search & Toolbar - Modern */
    .toolbar-wrapper {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        animation: slideUpFade 0.7s 0.1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .search-wrapper {
        flex: 1;
        min-width: 280px;
        position: relative;
    }

    .search-box {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 2px solid var(--border);
        border-radius: 20px;
        padding: 0.2rem 0.2rem 0.2rem 1.2rem;
        display: flex;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .search-box:focus-within {
        border-color: var(--amber);
        box-shadow: 0 8px 25px rgba(245,158,11,0.15);
        transform: translateY(-2px);
        background: white;
    }

    .search-icon {
        font-size: 1.3rem;
        color: var(--amber);
        transition: transform 0.3s ease;
    }

    .search-box:focus-within .search-icon {
        transform: scale(1.1) rotate(-5deg);
    }

    .search-box input {
        flex: 1;
        border: none;
        outline: none;
        padding: 0.9rem 1rem;
        font-size: 0.95rem;
        background: transparent;
        color: var(--char);
    }

    .search-box input::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    .search-clear {
        background: none;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 14px;
        cursor: pointer;
        color: #a0aec0;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        opacity: 0;
        pointer-events: none;
    }

    .search-clear.show {
        opacity: 1;
        pointer-events: auto;
    }

    .search-clear:hover {
        background: #fee2e2;
        color: var(--red);
    }

    .sort-select {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 2px solid var(--border);
        border-radius: 20px;
        padding: 0 2.5rem 0 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--char);
        cursor: pointer;
        transition: all 0.3s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23F59E0B' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .sort-select:hover {
        border-color: var(--amber);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .view-toggle {
        display: flex;
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 4px;
        gap: 4px;
    }

    .view-btn {
        background: none;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--muted);
        transition: all 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .view-btn:hover {
        background: rgba(245,158,11,0.1);
        color: var(--amber);
    }

    .view-btn.active {
        background: var(--amber);
        color: white;
        box-shadow: 0 4px 10px rgba(245,158,11,0.3);
    }

    /* Filter Chips - Interactive */
    .filter-section {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
        animation: slideUpFade 0.7s 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .filter-chip {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 2px solid var(--border);
        color: var(--muted);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.6rem 1.4rem;
        border-radius: 60px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        position: relative;
        overflow: hidden;
    }

    .filter-chip i {
        font-size: 1.1rem;
    }

    .filter-chip::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.8), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .filter-chip:hover::before {
        opacity: 1;
    }

    .filter-chip:hover {
        border-color: var(--amber);
        color: var(--amber-dk);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -8px rgba(245,158,11,0.2);
    }

    .filter-chip.active {
        background: var(--amber);
        border-color: var(--amber);
        color: white;
        box-shadow: 0 8px 20px rgba(245,158,11,0.4);
    }

    .filter-chip.active i {
        color: white;
    }

    /* Menu Grid - Responsive & Animated */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.8rem;
    }

    .menu-grid.list-view {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    /* Modern Card Design */
    .food-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        border-radius: 28px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 15px 30px -12px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .food-card:hover {
        transform: translateY(-12px) scale(1.01);
        box-shadow: 0 30px 45px -15px rgba(245,158,11,0.2);
        border-color: var(--amber);
        background: white;
    }

    .menu-grid.list-view .food-card {
        flex-direction: row;
        border-radius: 24px;
    }

    .menu-grid.list-view .food-card:hover {
        transform: translateY(-6px) scale(1.005);
    }

    /* Card Image */
    .card-img-container {
        position: relative;
        overflow: hidden;
    }

    .menu-grid:not(.list-view) .card-img-container img {
        width: 100%;
        height: 210px;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .food-card:hover .card-img-container img {
        transform: scale(1.12);
    }

    .menu-grid.list-view .card-img-container {
        width: 200px;
        flex-shrink: 0;
    }

    .menu-grid.list-view .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Badges */
    .card-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 5px 14px;
        border-radius: 60px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
        z-index: 2;
        animation: glowPulse 2.5s infinite;
    }

    .badge-hot { background: rgba(239,68,68,0.9); color: white; }
    .badge-new { background: rgba(16,185,129,0.9); color: white; }
    .badge-promo { background: rgba(245,158,11,0.95); color: white; }

    .favorite-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(8px);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 2;
        color: var(--char);
    }

    .favorite-btn:hover {
        transform: scale(1.15);
        background: white;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .favorite-btn.loved {
        color: var(--red);
        animation: float 0.5s ease;
    }

    /* Card Content */
    .card-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .menu-grid.list-view .card-content {
        flex-direction: row;
        align-items: center;
        gap: 2rem;
        padding: 1.5rem 2rem;
    }

    .card-category {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--amber);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.6rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--char);
        margin-bottom: 0.5rem;
        line-height: 1.3;
        transition: color 0.3s;
    }

    .food-card:hover .card-title {
        color: var(--amber-dk);
    }

    .card-description {
        font-size: 0.85rem;
        color: var(--muted);
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }

    .rating-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1.2rem;
    }

    .stars {
        color: var(--amber);
        letter-spacing: 2px;
    }

    .rating-value {
        font-weight: 700;
        color: var(--char);
    }

    .review-count {
        font-size: 0.8rem;
        color: var(--muted);
    }

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }

    .price-wrapper {
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .current-price {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 900;
        color: var(--char);
    }

    .old-price {
        font-size: 0.8rem;
        color: #cbd5e1;
        text-decoration: line-through;
    }

    .order-btn {
        background: linear-gradient(135deg, var(--char) 0%, #1f2937 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 0.7rem 1.4rem;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
    }

    .order-btn i {
        font-size: 1.1rem;
        transition: transform 0.3s;
    }

    .order-btn:hover {
        background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dk) 100%);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -5px rgba(245,158,11,0.5);
    }

    .order-btn:hover i {
        transform: translateX(4px) rotate(5deg);
    }

    /* Empty State */
    .empty-state-container {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 2rem;
        animation: scaleIn 0.5s;
    }

    .empty-illustration {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        display: inline-block;
        animation: float 4s ease-in-out infinite;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .menu-grid {
            grid-template-columns: 1fr;
        }
        
        .menu-grid.list-view .food-card {
            flex-direction: column;
        }
        
        .menu-grid.list-view .card-img-container {
            width: 100%;
            height: 180px;
        }
        
        .menu-grid.list-view .card-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<!-- Header Section -->
<div class="menu-header">
    <h1 class="menu-title">Menu Istimewa</h1>
    <div class="menu-subtitle">
        <span>Temukan hidangan yang menggugah selera ✨</span>
        <span class="stats-badge">
            <i class="ri-restaurant-line" style="color: var(--amber);"></i>
            <strong id="visibleCount">{{ $menus->count() }}</strong> pilihan menu
        </span>
    </div>
</div>

<!-- Toolbar Section -->
<div class="toolbar-wrapper">
    <div class="search-wrapper">
        <div class="search-box">
            <i class="ri-search-line search-icon"></i>
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Cari burger, mie, ayam..." 
                oninput="handleSearch()"
                autocomplete="off"
            >
            <button class="search-clear" id="clearBtn" onclick="clearSearch()" type="button">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>

    <select class="sort-select" id="sortSelect" onchange="handleSort()">
        <option value="default">✨ Rekomendasi</option>
        <option value="price-asc">💰 Harga Terendah</option>
        <option value="price-desc">💎 Harga Tertinggi</option>
        <option value="name-asc">📝 Nama A-Z</option>
    </select>

    <div class="view-toggle">
        <button class="view-btn active" id="gridBtn" onclick="setView('grid')">
            <i class="ri-layout-grid-line"></i>
        </button>
        <button class="view-btn" id="listBtn" onclick="setView('list')">
            <i class="ri-list-check"></i>
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <button class="filter-chip active" data-filter="semua" onclick="setFilter(this, 'semua')">
        <i class="ri-apps-line"></i> Semua Menu
    </button>
</div>

<!-- Menu Grid -->
<div id="menu-grid" class="menu-grid">
    @foreach($menus as $i => $menu)
        @php
            $cat     = $menu->kategori ?? 'Menu';
            $desc    = $menu->deskripsi ?? '';
            $rating  = round($menu->ulasans_avg_rating ?? 0, 1);
            $reviews = $menu->ulasans_count ?? 0;
        @endphp

        <div 
            class="food-card"
            data-category="{{ strtolower($menu->kategori ?? '') }}"
            data-name="{{ strtolower($menu->nama_menu) }}"
            data-price="{{ $menu->harga_menu }}"
            data-badge=""
            onclick="window.location='{{ route('menu.show', $menu) }}'"
            style="cursor: pointer;"
        >
            <div class="card-img-container">
                <img 
                    src="{{ $menu->gambar ? asset('storage/'.$menu->gambar) : 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&q=80' }}"
                    alt="{{ $menu->nama_menu }}"
                    loading="lazy"
                >
                @if($menu->stok_menu <= 5 && $menu->stok_menu > 0)
                    <span class="card-badge badge-hot">⚠️ Stok Terbatas</span>
                @endif
                <button class="favorite-btn" onclick="toggleFavorite(this)" type="button">
                    <i class="ri-heart-line"></i>
                </button>
            </div>

            <div class="card-content">
                <div>
                    <div class="card-category">{{ $cat }}</div>
                    <h3 class="card-title">{{ $menu->nama_menu }}</h3>
                    <p class="card-description">
                        {{ $desc ?: 'Nikmati kelezatan ' . $menu->nama_menu . ' yang dibuat dengan bahan pilihan.' }}
                    </p>
                    
                    <div class="rating-row">
                        @php $fullStars = round($rating); @endphp
                        <span class="stars">
                            @for($s = 1; $s <= 5; $s++){{ $s <= $fullStars ? '★' : '☆' }}@endfor
                        </span>
                        <span class="rating-value">{{ $rating > 0 ? number_format($rating, 1) : '-' }}</span>
                        <span class="review-count">({{ $reviews }} ulasan)</span>
                    </div>
                </div>{{-- tutup div dalam --}}

                <div class="card-footer">
                    <div class="price-wrapper">
                        <span class="current-price">Rp {{ number_format($menu->harga_menu, 0, ',', '.') }}</span>
                    </div>

                    @auth
                    <form action="{{ route('cart.add', $menu) }}" method="POST">
                        @csrf
                        <input type="hidden" name="jumlah" value="1">
                        <button type="submit" class="order-btn">
                            Pesan <i class="ri-arrow-right-line"></i>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="order-btn" style="text-decoration: none;">
                        Pesan <i class="ri-arrow-right-line"></i>
                    </a>
                    @endauth
                </div>
            </div>{{-- tutup card-content --}}
        </div>{{-- tutup food-card --}}
    @endforeach

    <div class="empty-state-container" id="emptyState" style="display: none;">
        <div class="empty-illustration">🍜🔍</div>
        <h3 style="margin-bottom: 0.5rem;">Menu Tidak Ditemukan</h3>
        <p style="color: var(--muted);">Coba kata kunci lain atau pilih kategori berbeda</p>
    </div>
</div>

<script>
    let currentFilter = 'semua';
    let currentView = 'grid';

    // Initialize animations
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.food-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
            }, index * 80);
        });
    });

    function handleSearch() {
        const input = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearBtn');
        clearBtn.classList.toggle('show', input.value.length > 0);
        applyFilters();
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('clearBtn').classList.remove('show');
        applyFilters();
    }

    function setFilter(el, filter) {
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.classList.remove('active');
        });
        el.classList.add('active');
        currentFilter = filter;
        
        // Add click animation
        el.style.transform = 'scale(0.95)';
        setTimeout(() => el.style.transform = '', 150);
        
        applyFilters();
    }

    function handleSort() {
        const grid = document.getElementById('menu-grid');
        const cards = [...grid.querySelectorAll('.food-card:not(#emptyState)')];
        const sortValue = document.getElementById('sortSelect').value;

        cards.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            const nameA = a.dataset.name;
            const nameB = b.dataset.name;

            switch(sortValue) {
                case 'price-asc': return priceA - priceB;
                case 'price-desc': return priceB - priceA;
                case 'name-asc': return nameA.localeCompare(nameB);
                default: return 0;
            }
        });

        cards.forEach(card => grid.appendChild(card));
        
        // Reapply animation
        cards.forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, i * 50);
        });
        
        applyFilters();
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.food-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const categoryMatch = currentFilter === 'semua' 
                ? true 
                : currentFilter === 'promo'
                    ? card.dataset.badge === 'promo'
                    : card.dataset.category === currentFilter;
                    
            const searchMatch = !searchTerm || card.dataset.name.includes(searchTerm);
            const shouldShow = categoryMatch && searchMatch;

            if (shouldShow) {
                card.style.display = '';
                visibleCount++;
                // Re-trigger animation
                card.style.opacity = '0';
                setTimeout(() => card.style.opacity = '1', 10);
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('visibleCount').textContent = visibleCount;
        document.getElementById('emptyState').style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function setView(mode) {
        currentView = mode;
        const grid = document.getElementById('menu-grid');
        
        if (mode === 'list') {
            grid.classList.add('list-view');
        } else {
            grid.classList.remove('list-view');
        }

        document.getElementById('gridBtn').classList.toggle('active', mode === 'grid');
        document.getElementById('listBtn').classList.toggle('active', mode === 'list');

        // Smooth transition
        const cards = document.querySelectorAll('.food-card');
        cards.forEach(card => {
            card.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
        });
    }

    function toggleFavorite(btn) {
        const icon = btn.querySelector('i');
        const isLoved = btn.classList.toggle('loved');
        
        if (isLoved) {
            icon.classList.remove('ri-heart-line');
            icon.classList.add('ri-heart-fill');
            btn.style.animation = 'float 0.5s ease';
        } else {
            icon.classList.remove('ri-heart-fill');
            icon.classList.add('ri-heart-line');
        }
        
        setTimeout(() => btn.style.animation = '', 500);
    }

    // Initialize on page load
    applyFilters();
</script>

@endsection