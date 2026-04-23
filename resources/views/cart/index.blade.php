{{--
 * Purpose: Shopping cart — view/update/remove items, proceed to checkout
 * Used by: GET /cart (CartController@index)
--}}
@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')

<style>
    /* Modern Cart Page Styles */
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Header */
    .cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        animation: slideInLeft 0.6s ease;
    }

    .cart-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cart-title h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--char);
        margin: 0;
    }

    .cart-icon {
        font-size: 2.5rem;
        animation: pulse 2s infinite;
    }

    .item-count-badge {
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        padding: 0.3rem 1.2rem;
        border-radius: 60px;
        font-size: 0.9rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(245,158,11,0.3);
    }

    .clear-all-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        background: rgba(239,68,68,0.1);
        color: #dc2626;
        border: 1px solid rgba(239,68,68,0.2);
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        backdrop-filter: blur(8px);
    }

    .clear-all-btn:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(239,68,68,0.4);
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

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
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

    /* Cart Grid */
    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    @media (max-width: 968px) {
        .cart-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Cart Items */
    .cart-items {
        animation: slideInLeft 0.7s ease;
    }

    .cart-item {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        gap: 1.2rem;
        animation: fadeInUp 0.5s ease backwards;
    }

    .cart-item:hover {
        transform: translateX(8px);
        box-shadow: 0 10px 30px -10px rgba(245,158,11,0.15);
        border-color: var(--amber);
        background: white;
    }

    .item-image {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        overflow: hidden;
        background: linear-gradient(135deg, #fef3c7, #fff);
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cart-item:hover .item-image img {
        transform: scale(1.15);
    }

    .item-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        background: linear-gradient(135deg, #fef3c7, #fff);
    }

    .item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .item-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .item-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--char);
        margin-bottom: 0.25rem;
    }

    .item-price {
        font-size: 1.2rem;
        font-weight: 900;
        color: var(--amber-dk);
        font-family: 'Playfair Display', serif;
    }

    .remove-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 10px;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .remove-btn:hover {
        background: #fee2e2;
        border-color: #fecaca;
        color: #dc2626;
        transform: scale(1.1) rotate(90deg);
    }

    .item-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .qty-wrapper {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--border);
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.1rem;
    }

    .qty-btn:hover {
        background: var(--amber-lt);
        color: var(--amber);
    }

    .qty-input {
        width: 45px;
        text-align: center;
        border: none;
        font-weight: 600;
        color: var(--char);
        font-size: 0.95rem;
    }

    /* Hilangkan spinner di Chrome, Safari, Edge */
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Hilangkan spinner di Firefox */
    .qty-input {
        -moz-appearance: textfield;
    }

    .qty-input:focus {
        outline: none;
        background: var(--amber-lt);
    }

    .item-subtotal {
        text-align: right;
    }

    .subtotal-label {
        font-size: 0.8rem;
        color: var(--muted);
        margin-bottom: 0.2rem;
    }

    .subtotal-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--char);
    }

    /* Order Summary */
    .order-summary {
        animation: slideInRight 0.7s ease;
    }

    .summary-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(254,243,199,0.3) 100%);
        backdrop-filter: blur(16px);
        border-radius: 28px;
        padding: 2rem;
        border: 1px solid rgba(255,255,255,0.6);
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1);
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-title i {
        color: var(--amber);
    }

    .summary-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 1.5rem;
        padding-right: 0.5rem;
    }

    .summary-items::-webkit-scrollbar {
        width: 4px;
    }

    .summary-items::-webkit-scrollbar-thumb {
        background: var(--amber);
        border-radius: 4px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px dashed var(--border);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-item-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-item-name {
        font-weight: 500;
        color: var(--char);
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .summary-item-qty {
        color: var(--muted);
        font-size: 0.85rem;
    }

    .summary-item-price {
        font-weight: 700;
        color: var(--char);
    }

    .summary-total {
        padding-top: 1.5rem;
        border-top: 3px solid var(--amber);
        margin-top: 1rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .total-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--char);
    }

    .total-value {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--amber-dk);
    }

    .checkout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        width: 100%;
        background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dk) 100%);
        color: white;
        text-decoration: none;
        padding: 1.2rem;
        border-radius: 18px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 1.5rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 30px -5px rgba(245,158,11,0.4);
        position: relative;
        overflow: hidden;
    }

    .checkout-btn::before {
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

    .checkout-btn:hover::before {
        width: 400px;
        height: 400px;
    }

    .checkout-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -8px rgba(245,158,11,0.6);
    }

    .checkout-btn i {
        transition: transform 0.3s;
    }

    .checkout-btn:hover i {
        transform: translateX(5px);
    }

    .add-more-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: var(--muted);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s;
    }

    .add-more-link:hover {
        color: var(--amber);
    }

    .add-more-link i {
        margin-right: 0.3rem;
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .cart-container {
            padding: 1rem 1rem 3rem;
        }

        .cart-title h1 {
            font-size: 1.8rem;
        }

        .cart-item {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 180px;
        }

        .item-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .item-subtotal {
            text-align: left;
            width: 100%;
        }

        .summary-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="cart-container">
    @if(empty($cart))
        {{-- Empty State --}}
        <div class="empty-state">
            <div class="empty-illustration">
                🛒✨
            </div>
            <h2 class="empty-title">Keranjangmu Kosong</h2>
            <p class="empty-desc">Yuk, jelajahi menu lezat kami dan mulai pesan sekarang!</p>
            <a href="{{ route('menu.index') }}" class="browse-menu-btn">
                <i class="ri-restaurant-line"></i>
                Lihat Menu
                <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    @else
        {{-- Cart Header --}}
        <div class="cart-header">
            <div class="cart-title">
                <span class="cart-icon">🛒</span>
                <h1>Keranjang Belanja</h1>
                <span class="item-count-badge">{{ count($cart) }} item</span>
            </div>
            
            <form method="POST" action="{{ route('cart.clear') }}"
                  x-data
                  @submit.prevent="if(confirm('Kosongkan semua item dari keranjang?')) $el.submit()">
                @csrf @method('DELETE')
                <button type="submit" class="clear-all-btn">
                    <i class="ri-delete-bin-line"></i>
                    Kosongkan Semua
                </button>
            </form>
        </div>

        {{-- Cart Grid --}}
        <div class="cart-grid">
            {{-- Cart Items --}}
            <div class="cart-items">
                @foreach($cart as $id => $item)
                <div class="cart-item" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="item-image">
                        @if($item['gambar'])
                        <img src="{{ asset('storage/' . $item['gambar']) }}" 
                             alt="{{ $item['nama_menu'] }}"
                             loading="lazy">
                        @else
                        <div class="item-placeholder">🍱</div>
                        @endif
                    </div>

                    <div class="item-details">
                        <div>
                            <div class="item-header">
                                <div>
                                    <h3 class="item-name">{{ $item['nama_menu'] }}</h3>
                                    <div class="item-price">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                                </div>
                                
                                <form method="POST" action="{{ route('cart.remove', $id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="remove-btn" title="Hapus item">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="item-footer">
                            <div class="quantity-control">
                                <span style="font-size: 0.85rem; color: var(--muted);">Jumlah:</span>
                                    <form method="POST" action="{{ route('cart.update', $id) }}"
                                        x-data="{ qty: {{ $item['jumlah'] }} }">
                                        @csrf @method('PATCH')

                                        <div class="qty-wrapper">
                                            <button type="button" class="qty-btn"
                                                    @click="if(qty > 1) { qty--; $nextTick(() => $el.closest('form').submit()) }">
                                                -
                                            </button>

                                            <input type="number"
                                                name="jumlah"
                                                x-model="qty"
                                                min="1"
                                                @input.debounce.500ms="$el.closest('form').submit()"
                                                class="qty-input">

                                            <button type="button" class="qty-btn"
                                                    @click="qty++; $nextTick(() => $el.closest('form').submit())">
                                                +
                                            </button>
                                        </div>
                                    </form>
                            </div>

                            <div class="item-subtotal">
                                <div class="subtotal-label">Subtotal</div>
                                <div class="subtotal-value">
                                    Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="order-summary">
                <div class="summary-card">
                    <div class="summary-title">
                        <i class="ri-receipt-line"></i>
                        Ringkasan Pesanan
                    </div>

                    <div class="summary-items">
                        @foreach($cart as $item)
                        <div class="summary-item">
                            <div class="summary-item-info">
                                <span class="summary-item-name">{{ $item['nama_menu'] }}</span>
                                <span class="summary-item-qty">×{{ $item['jumlah'] }}</span>
                            </div>
                            <span class="summary-item-price">
                                Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="summary-total">
                        <div class="total-row">
                            <span class="total-label">Total Pembayaran</span>
                            <span class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.show') }}" class="checkout-btn">
                        <span>Lanjut ke Checkout</span>
                        <i class="ri-arrow-right-line"></i>
                    </a>

                    <a href="{{ route('menu.index') }}" class="add-more-link">
                        <i class="ri-add-line"></i> Tambah menu lainnya
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Smooth animations on page load
    document.addEventListener('DOMContentLoaded', () => {
        const items = document.querySelectorAll('.cart-item');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 100);
        });

        // Add to cart animation
        const checkoutBtn = document.querySelector('.checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function(e) {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        }
    });

    // Quantity input validation
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (value < 1 || isNaN(value)) {
                this.value = 1;
            }
        });
    });
</script>

@endsection