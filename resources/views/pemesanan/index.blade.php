{{--
 * Purpose: User's order history — list with status badges and quick links
 * Used by: GET /pesanan (PemesananController@index)
--}}
@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')

<style>
    /* Modern Order History Styles */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .orders-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Header */
    .orders-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        animation: slideInLeft 0.6s ease;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .orders-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .orders-title h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--char);
        margin: 0;
    }

    .orders-icon {
        font-size: 2.5rem;
        animation: float 3s ease-in-out infinite;
    }

    .order-count-badge {
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        padding: 0.3rem 1.2rem;
        border-radius: 60px;
        font-size: 0.9rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(245,158,11,0.3);
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        animation: scaleIn 0.5s ease;
    }

    .filter-tab {
        padding: 0.6rem 1.5rem;
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(8px);
        border: 2px solid var(--border);
        border-radius: 60px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .filter-tab i {
        font-size: 1.1rem;
    }

    .filter-tab:hover {
        border-color: var(--amber);
        color: var(--amber-dk);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(245,158,11,0.2);
    }

    .filter-tab.active {
        background: var(--amber);
        border-color: var(--amber);
        color: white;
        box-shadow: 0 8px 20px -5px rgba(245,158,11,0.4);
    }

    .filter-tab .count {
        background: rgba(0,0,0,0.1);
        padding: 0.1rem 0.5rem;
        border-radius: 30px;
        font-size: 0.8rem;
        margin-left: 0.3rem;
    }

    .filter-tab.active .count {
        background: rgba(255,255,255,0.2);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        animation: scaleIn 0.6s ease;
    }

    .empty-illustration {
        font-size: 6rem;
        margin-bottom: 1.5rem;
        animation: float 4s ease-in-out infinite;
    }

    .empty-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--char);
        margin-bottom: 0.5rem;
    }

    .empty-desc {
        color: var(--muted);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .browse-menu-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        text-decoration: none;
        padding: 1rem 2.5rem;
        border-radius: 60px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 10px 30px -5px rgba(245,158,11,0.4);
    }

    .browse-menu-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -8px rgba(245,158,11,0.6);
    }

    .browse-menu-btn i {
        transition: transform 0.3s;
    }

    .browse-menu-btn:hover i {
        transform: translateX(5px);
    }

    /* Order Cards */
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .order-card {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
        border-radius: 24px;
        padding: 1.5rem;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        animation: fadeInUp 0.5s ease backwards;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .order-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .order-card:hover {
        transform: translateX(8px) scale(1.01);
        box-shadow: 0 15px 35px -10px rgba(245,158,11,0.2);
        border-color: var(--amber);
        background: white;
    }

    .order-card:hover::before {
        opacity: 1;
    }

    .order-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-info {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .order-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--muted);
        font-size: 0.85rem;
    }

    .order-date i {
        color: var(--amber);
        font-size: 1rem;
    }

    .order-number {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--char);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-number i {
        color: var(--amber);
        font-size: 1.2rem;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1.2rem;
        border-radius: 60px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        backdrop-filter: blur(8px);
        transition: all 0.3s;
    }

    .status-badge i {
        font-size: 1rem;
    }

    .status-pending {
        background: rgba(251, 191, 36, 0.15);
        color: #d97706;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .status-paid {
        background: rgba(59, 130, 246, 0.15);
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-prepared {
        background: rgba(168, 85, 247, 0.15);
        color: #9333ea;
        border: 1px solid rgba(168, 85, 247, 0.3);
    }

    .status-shipped {
        background: rgba(99, 102, 241, 0.15);
        color: #4f46e5;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .status-delivered {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-cancelled {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.2); }
    }

    .status-pending .status-dot { background: #fbbf24; }
    .status-paid .status-dot { background: #3b82f6; }
    .status-prepared .status-dot { background: #a855f7; }
    .status-shipped .status-dot { background: #6366f1; }
    .status-delivered .status-dot { background: #10b981; }
    .status-cancelled .status-dot { background: #ef4444; }

    /* Order Footer */
    .order-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-details {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item i {
        color: var(--amber);
        font-size: 1.1rem;
    }

    .detail-label {
        color: var(--muted);
        font-size: 0.85rem;
    }

    .detail-value {
        font-weight: 700;
        color: var(--char);
        font-size: 0.95rem;
    }

    .total-amount {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--amber-dk);
    }

    .view-detail-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    }

    .view-detail-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245,158,11,0.5);
    }

    .view-detail-btn i {
        transition: transform 0.3s;
    }

    .view-detail-btn:hover i {
        transform: translateX(3px);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 3rem;
        color: var(--muted);
        display: none;
    }

    .no-results.show {
        display: block;
        animation: scaleIn 0.4s ease;
    }

    .no-results i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .orders-container {
            padding: 1rem 1rem 3rem;
        }

        .orders-title h1 {
            font-size: 1.8rem;
        }

        .order-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-details {
            gap: 1rem;
            width: 100%;
        }

        .view-detail-btn {
            width: 100%;
            justify-content: center;
        }

        .filter-tabs {
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .filter-tab {
            white-space: nowrap;
        }
    }
</style>

<div class="orders-container">
    @if($pemesanans->isEmpty())
        {{-- Empty State --}}
        <div class="empty-state">
            <div class="empty-illustration">
                📦✨
            </div>
            <h2 class="empty-title">Belum Ada Pesanan</h2>
            <p class="empty-desc">Yuk, jelajahi menu lezat kami dan buat pesanan pertamamu!</p>
            <a href="{{ route('menu.index') }}" class="browse-menu-btn">
                <i class="ri-restaurant-line"></i>
                Lihat Menu
                <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    @else
        {{-- Header --}}
        <div class="orders-header">
            <div class="orders-title">
                <span class="orders-icon">📦</span>
                <h1>Pesanan Saya</h1>
                <span class="order-count-badge">{{ $pemesanans->total() }} Pesanan</span>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">
                <i class="ri-list-check"></i>
                Semua
                <span class="count">{{ $pemesanans->total() }}</span>
            </div>
            <div class="filter-tab" data-filter="pending">
                <i class="ri-time-line"></i>
                Menunggu
            </div>
            <div class="filter-tab" data-filter="paid">
                <i class="ri-bank-card-line"></i>
                Dibayar
            </div>
            <div class="filter-tab" data-filter="prepared">
                <i class="ri-restaurant-line"></i>
                Disiapkan
            </div>
            <div class="filter-tab" data-filter="shipped">
                <i class="ri-truck-line"></i>
                Dikirim
            </div>
            <div class="filter-tab" data-filter="delivered">
                <i class="ri-checkbox-circle-line"></i>
                Selesai
            </div>
            <div class="filter-tab" data-filter="cancelled">
                <i class="ri-close-circle-line"></i>
                Dibatalkan
            </div>
        </div>

        {{-- Orders List --}}
        <div class="orders-list" id="ordersList">
            @foreach($pemesanans as $index => $p)
            @php
                $statusConfig = [
                    'pending' => [
                        'class' => 'status-pending',
                        'icon' => 'ri-time-line',
                        'label' => 'Menunggu Pembayaran'
                    ],
                    'paid' => [
                        'class' => 'status-paid',
                        'icon' => 'ri-bank-card-line',
                        'label' => 'Sudah Dibayar'
                    ],
                    'prepared' => [
                        'class' => 'status-prepared',
                        'icon' => 'ri-restaurant-line',
                        'label' => 'Sedang Disiapkan'
                    ],
                    'shipped' => [
                        'class' => 'status-shipped',
                        'icon' => 'ri-truck-line',
                        'label' => 'Dalam Pengiriman'
                    ],
                    'delivered' => [
                        'class' => 'status-delivered',
                        'icon' => 'ri-checkbox-circle-line',
                        'label' => 'Pesanan Selesai'
                    ],
                    'cancelled' => [
                        'class' => 'status-cancelled',
                        'icon' => 'ri-close-circle-line',
                        'label' => 'Dibatalkan'
                    ],
                ];
                $status = $statusConfig[$p->status_pemesanan] ?? [
                    'class' => 'status-pending',
                    'icon' => 'ri-information-line',
                    'label' => ucfirst($p->status_pemesanan)
                ];
            @endphp

            <div class="order-card" 
                 data-status="{{ $p->status_pemesanan }}"
                 data-order-id="{{ $p->id_pemesanan }}"
                 onclick="window.location='{{ route('pemesanan.show', $p) }}'"
                 style="animation-delay: {{ $index * 0.05 }}s;">
                
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-date">
                            <i class="ri-calendar-line"></i>
                            {{ $p->created_at->isoFormat('dddd, D MMMM Y') }}
                            <span style="margin: 0 0.5rem;">•</span>
                            <i class="ri-time-line"></i>
                            {{ $p->created_at->format('H:i') }} WIB
                        </div>
                        <div class="order-number">
                            <i class="ri-hashtag"></i>
                            #{{ str_pad($p->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <span class="status-badge {{ $status['class'] }}">
                        <span class="status-dot"></span>
                        <i class="{{ $status['icon'] }}"></i>
                        {{ $status['label'] }}
                    </span>
                </div>

                <div class="order-footer">
                    <div class="order-details">
                        <div class="detail-item">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span class="detail-label">Total</span>
                            <span class="total-amount">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($p->pembayaran)
                        <div class="detail-item">
                            <i class="ri-bank-line"></i>
                            <span class="detail-label">Metode</span>
                            <span class="detail-value">
                                {{ ucwords(str_replace('_', ' ', $p->pembayaran->metode_pembayaran)) }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <div onclick="event.stopPropagation(); window.location='{{ route('pemesanan.show', $p) }}'">
                        <span class="view-detail-btn">
                            Lihat Detail
                            <i class="ri-arrow-right-line"></i>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- No Results Message --}}
        <div class="no-results" id="noResults">
            <i class="ri-inbox-line"></i>
            <h3>Tidak ada pesanan</h3>
            <p>Tidak ada pesanan dengan status yang dipilih</p>
        </div>

        {{-- Pagination --}}
        @if($pemesanans->hasPages())
        <div class="pagination-wrapper">
            {{ $pemesanans->links('vendor.pagination.simple-tailwind') }}
        </div>
        @endif
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const orderCards = document.querySelectorAll('.order-card');
        const noResults = document.getElementById('noResults');
        const ordersList = document.getElementById('ordersList');

        // Filter functionality
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Update active state
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                let visibleCount = 0;

                // Filter cards
                orderCards.forEach(card => {
                    const status = card.dataset.status;
                    
                    if (filter === 'all' || status === filter) {
                        card.style.display = '';
                        visibleCount++;
                        // Re-trigger animation
                        card.style.animation = 'none';
                        setTimeout(() => {
                            card.style.animation = 'fadeInUp 0.5s ease backwards';
                        }, 10);
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResults.classList.add('show');
                    ordersList.style.display = 'none';
                } else {
                    noResults.classList.remove('show');
                    ordersList.style.display = 'flex';
                }

                // Update count badges
                updateFilterCounts();
            });
        });

        // Update filter counts
        function updateFilterCounts() {
            const counts = {
                all: 0,
                pending: 0,
                paid: 0,
                prepared: 0,
                shipped: 0,
                delivered: 0,
                cancelled: 0
            };

            orderCards.forEach(card => {
                const status = card.dataset.status;
                counts.all++;
                if (counts[status] !== undefined) {
                    counts[status]++;
                }
            });

            filterTabs.forEach(tab => {
                const filter = tab.dataset.filter;
                const countSpan = tab.querySelector('.count');
                if (countSpan) {
                    countSpan.textContent = counts[filter] || 0;
                }
            });
        }

        // Initialize counts
        updateFilterCounts();

        // Card hover effects
        orderCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)';
            });
        });
    });
</script>

@endsection