{{--
 * Purpose: Order detail — items, payment info, status timeline, confirm delivery, write review
 * Used by: GET /pesanan/{pemesanan} (PemesananController@show)
--}}
@extends('layouts.app')
@section('title', 'Detail Pesanan #' . str_pad($pemesanan->id_pemesanan, 5, '0', STR_PAD_LEFT))

@section('content')

<style>
    /* Modern Order Detail Styles */
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

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes progressGlow {
        0%, 100% { box-shadow: 0 0 10px rgba(245,158,11,0.3); }
        50% { box-shadow: 0 0 20px rgba(245,158,11,0.6); }
    }

    .detail-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
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

    /* Status Timeline Card */
    .timeline-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(254,243,199,0.2) 100%);
        backdrop-filter: blur(12px);
        border-radius: 28px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.6);
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
        animation: scaleIn 0.6s ease;
    }

    .timeline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-line {
        position: absolute;
        left: 2.5rem;
        right: 2.5rem;
        height: 3px;
        background: #e2e8f0;
        top: 2.2rem;
        z-index: 0;
        border-radius: 3px;
    }

    .timeline-progress {
        position: absolute;
        left: 2.5rem;
        height: 3px;
        background: linear-gradient(90deg, var(--amber), var(--amber-dk));
        top: 2.2rem;
        z-index: 1;
        border-radius: 3px;
        transition: width 1s cubic-bezier(0.16, 1, 0.3, 1);
        animation: progressGlow 2s infinite;
    }

    .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        flex: 1;
    }

    .step-icon {
        width: 4.5rem;
        height: 4.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: white;
        border: 3px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        margin-bottom: 0.8rem;
    }

    .timeline-step.completed .step-icon {
        background: var(--amber);
        border-color: var(--amber);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(245,158,11,0.3);
    }

    .timeline-step.active .step-icon {
        border-color: var(--amber);
        color: var(--amber);
        animation: pulse 2s infinite;
        transform: scale(1.15);
        box-shadow: 0 8px 25px rgba(245,158,11,0.4);
    }

    .step-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #cbd5e1;
        text-align: center;
        transition: color 0.3s;
    }

    .timeline-step.completed .step-label,
    .timeline-step.active .step-label {
        color: var(--char);
    }

    .order-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1.5rem;
        border-top: 2px dashed var(--border);
    }

    .order-id {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .order-id i {
        font-size: 1.5rem;
        color: var(--amber);
    }

    .order-id-text {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--char);
    }

    .order-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* Content Cards */
    .content-card {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
        border-radius: 24px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        animation: fadeInUp 0.6s ease backwards;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .card-title i {
        color: var(--amber);
        font-size: 1.5rem;
    }

    /* Order Items */
    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        overflow: hidden;
        background: linear-gradient(135deg, #fef3c7, #fff);
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 700;
        color: var(--char);
        margin-bottom: 0.25rem;
    }

    .item-details {
        font-size: 0.85rem;
        color: var(--muted);
    }

    .item-price {
        font-weight: 800;
        color: var(--char);
        text-align: right;
    }

    .total-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 3px solid var(--amber);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--char);
    }

    .total-value {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--amber-dk);
    }

    /* Payment Info */
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .payment-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .payment-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .payment-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--char);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 1rem;
        border-radius: 60px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    .badge-warning {
        background: rgba(251, 191, 36, 0.15);
        color: #d97706;
    }

    .payment-proof {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .proof-image {
        max-height: 200px;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .proof-image:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(245,158,11,0.2);
    }

    /* Confirm Delivery */
    .confirm-card {
        background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(99,102,241,0.05) 100%);
        border: 2px solid rgba(99,102,241,0.2);
    }

    .confirm-btn {
        width: 100%;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 1rem;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        box-shadow: 0 8px 20px rgba(99,102,241,0.3);
    }

    .confirm-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(99,102,241,0.4);
    }

    /* Review Section */
    .review-toggle {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .review-form {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .star-rating {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .star-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: all 0.2s;
    }

    .star-btn:hover {
        transform: scale(1.2);
    }

    .review-textarea {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 16px;
        font-size: 0.9rem;
        resize: vertical;
        transition: all 0.3s;
        background: white;
    }

    .review-textarea:focus {
        outline: none;
        border-color: var(--amber);
        box-shadow: 0 4px 15px rgba(245,158,11,0.1);
    }

    .submit-review-btn {
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1rem;
    }

    .submit-review-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245,158,11,0.3);
    }

    .submit-review-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Existing Reviews */
    .review-item {
        padding: 1.2rem;
        background: rgba(255,255,255,0.5);
        border-radius: 16px;
        margin-bottom: 1rem;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.8rem;
    }

    .review-stars {
        display: flex;
        gap: 2px;
    }

    .review-comment {
        color: var(--char);
        line-height: 1.6;
        padding: 0.8rem;
        background: white;
        border-radius: 12px;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .detail-container {
            padding: 1rem 1rem 3rem;
        }

        .timeline {
            flex-direction: column;
            gap: 1.5rem;
        }

        .timeline-line,
        .timeline-progress {
            display: none;
        }

        .timeline-step {
            flex-direction: row;
            gap: 1rem;
            width: 100%;
        }

        .step-icon {
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.5rem;
        }

        .payment-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .order-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.8rem;
        }
    }
</style>

<div class="detail-container">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('pemesanan.index') }}">
            <i class="ri-arrow-left-line"></i> Pesanan Saya
        </a>
        <span class="separator">/</span>
        <span class="current">#{{ str_pad($pemesanan->id_pemesanan, 5, '0', STR_PAD_LEFT) }}</span>
    </nav>

    @php
        $statusSteps = ['pending','paid','prepared','shipped','delivered'];
        $current = $pemesanan->status_pemesanan;
        $currentIdx = array_search($current, $statusSteps);
        $statusConfig = [
            'pending' => ['icon' => '🕐', 'label' => 'Menunggu'],
            'paid' => ['icon' => '💳', 'label' => 'Dibayar'],
            'prepared' => ['icon' => '👨‍🍳', 'label' => 'Disiapkan'],
            'shipped' => ['icon' => '🚚', 'label' => 'Dikirim'],
            'delivered' => ['icon' => '✅', 'label' => 'Selesai'],
        ];
    @endphp

    {{-- Status Timeline --}}
    <div class="timeline-card">
        <div class="timeline">
            <div class="timeline-line"></div>
            <div class="timeline-progress" style="width: {{ $current !== 'cancelled' ? ($currentIdx / 4 * 100) : 0 }}%"></div>
            
            @foreach($statusSteps as $i => $step)
                @php 
                    $completed = $i <= $currentIdx && $current !== 'cancelled';
                    $active = $i === $currentIdx && $current !== 'cancelled';
                @endphp
                <div class="timeline-step {{ $completed ? 'completed' : '' }} {{ $active ? 'active' : '' }}">
                    <div class="step-icon">
                        {{ $statusConfig[$step]['icon'] }}
                    </div>
                    <span class="step-label">{{ $statusConfig[$step]['label'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="order-meta">
            <div class="order-id">
                <i class="ri-receipt-line"></i>
                <span class="order-id-text">#{{ str_pad($pemesanan->id_pemesanan, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="order-date">
                <i class="ri-calendar-line"></i>
                {{ $pemesanan->created_at->isoFormat('dddd, D MMMM Y') }}
                <span style="margin: 0 0.5rem;">•</span>
                <i class="ri-time-line"></i>
                {{ $pemesanan->created_at->format('H:i') }} WIB
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="content-card" style="animation-delay: 0.1s;">
        <div class="card-title">
            <i class="ri-shopping-bag-line"></i>
            Item Pesanan
        </div>

        <div class="order-items">
            @foreach($pemesanan->items as $item)
            <div class="order-item">
                <div class="item-image">
                    @if($item->menu?->gambar)
                    <img src="{{ asset('storage/' . $item->menu->gambar) }}" alt="{{ $item->menu->nama_menu }}">
                    @else
                    <div class="item-placeholder">🍱</div>
                    @endif
                </div>
                <div class="item-info">
                    <div class="item-name">{{ $item->menu?->nama_menu ?? 'Menu dihapus' }}</div>
                    <div class="item-details">{{ $item->jumlah }} × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</div>
                </div>
                <div class="item-price">
                    Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        <div class="total-section">
            <span class="total-label">Total Pembayaran</span>
            <span class="total-value">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
        </div>

        @if($pemesanan->catatan)
        <div style="margin-top: 1rem; padding: 1rem; background: #fef3c7; border-radius: 12px; color: #92400e;">
            <i class="ri-chat-1-line"></i> Catatan: {{ $pemesanan->catatan }}
        </div>
        @endif
    </div>

    {{-- Payment Info --}}
    @if($pemesanan->pembayaran)
    <div class="content-card" style="animation-delay: 0.2s;">
        <div class="card-title">
            <i class="ri-bank-card-line"></i>
            Informasi Pembayaran
        </div>

        <div class="payment-grid">
            <div class="payment-item">
                <span class="payment-label">Metode Pembayaran</span>
                <span class="payment-value">
                    <i class="ri-bank-line"></i>
                    {{ ucwords(str_replace('_', ' ', $pemesanan->pembayaran->metode_pembayaran)) }}
                </span>
            </div>
            <div class="payment-item">
                <span class="payment-label">Status Pembayaran</span>
                <span class="payment-value">
                    <span class="payment-badge {{ $pemesanan->pembayaran->status_pembayaran === 'paid' ? 'badge-success' : 'badge-warning' }}">
                        <i class="{{ $pemesanan->pembayaran->status_pembayaran === 'paid' ? 'ri-checkbox-circle-line' : 'ri-time-line' }}"></i>
                        {{ $pemesanan->pembayaran->status_pembayaran === 'paid' ? 'Lunas' : 'Menunggu Pembayaran' }}
                    </span>
                </span>
            </div>
        </div>

        @if($pemesanan->pembayaran->bukti_bayar)
        <div class="payment-proof">
            <span class="payment-label" style="margin-bottom: 0.8rem; display: block;">Bukti Pembayaran</span>
            <a href="{{ asset('storage/' . $pemesanan->pembayaran->bukti_bayar) }}" target="_blank">
                <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_bayar) }}" 
                     class="proof-image" 
                     alt="Bukti Pembayaran">
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- Confirm Delivery --}}
    @if($pemesanan->status_pemesanan === 'shipped')
    <div class="content-card confirm-card" style="animation-delay: 0.3s;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <span style="font-size: 2.5rem;">🚚</span>
            <div>
                <h3 style="font-weight: 800; color: #4338ca; margin-bottom: 0.25rem;">Pesanan Dalam Perjalanan!</h3>
                <p style="color: #6366f1; font-size: 0.9rem;">Konfirmasi saat pesanan sudah kamu terima</p>
            </div>
        </div>
        <form method="POST" action="{{ route('pemesanan.confirm', $pemesanan) }}">
            @csrf @method('PATCH')
            <button type="submit" class="confirm-btn">
                <i class="ri-check-line"></i>
                Konfirmasi Pesanan Diterima
            </button>
        </form>
    </div>
    @endif

    {{-- Review Form --}}
    @if($pemesanan->status_pemesanan === 'delivered')
    @php
        $reviewedMenuIds = $pemesanan->ulasans->pluck('id_menu')->toArray();
        $unreviewedItems = $pemesanan->items->filter(fn($i) => $i->menu && !in_array($i->id_menu, $reviewedMenuIds));
    @endphp

    @if($unreviewedItems->isNotEmpty())
    <div class="content-card" style="animation-delay: 0.3s;" x-data="{ open: true }">
        <button @click="open = !open" class="review-toggle">
            <div class="card-title" style="margin-bottom: 0;">
                <i class="ri-star-line"></i>
                Beri Ulasan
                <span style="background: var(--amber); color: white; padding: 0.2rem 0.8rem; border-radius: 60px; font-size: 0.8rem; margin-left: 0.5rem;">
                    {{ $unreviewedItems->count() }}
                </span>
            </div>
            <i class="ri-arrow-down-s-line" x-show="!open"></i>
            <i class="ri-arrow-up-s-line" x-show="open"></i>
        </button>

        <div x-show="open" x-transition>
            @foreach($unreviewedItems as $index => $item)
            <div class="review-form" x-data="{ rating: 0 }">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div class="item-image" style="width: 50px; height: 50px;">
                        @if($item->menu->gambar)
                        <img src="{{ asset('storage/' . $item->menu->gambar) }}" alt="{{ $item->menu->nama_menu }}">
                        @else
                        <div class="item-placeholder" style="font-size: 1.5rem;">🍱</div>
                        @endif
                    </div>
                    <span style="font-weight: 700; color: var(--char);">{{ $item->menu->nama_menu }}</span>
                </div>

                <form method="POST" action="{{ route('ulasan.store', $pemesanan) }}">
                    @csrf
                    <input type="hidden" name="ulasans[0][id_menu]" value="{{ $item->id_menu }}">
                    <input type="hidden" name="ulasans[0][rating]" :value="rating">

                    <div class="star-rating">
                        @for($star = 1; $star <= 5; $star++)
                        <button type="button"
                                class="star-btn"
                                @click="rating = {{ $star }}">
                            <i class="ri-star-fill"
                            :style="{{ $star }} <= rating ? 'font-size: 2rem; color: #fbbf24; transition: all 0.2s;' : 'font-size: 2rem; color: #e2e8f0; transition: all 0.2s;'"></i>
                        </button>
                        @endfor
                        <span style="margin-left: 1rem; color: var(--muted);" x-show="rating > 0">
                            <span x-text="rating"></span>/5
                        </span>
                    </div>

                    <textarea name="ulasans[0][komentar]"
                            rows="3"
                            class="review-textarea"
                            placeholder="Bagaimana rasanya? Ceritakan pengalamanmu..."></textarea>

                    <button type="submit"
                            class="submit-review-btn"
                            :disabled="rating === 0">
                        <i class="ri-send-plane-line"></i> Kirim Ulasan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Existing Reviews --}}
    @if($pemesanan->ulasans->isNotEmpty())
    <div class="content-card" style="animation-delay: 0.4s;">
        <div class="card-title">
            <i class="ri-chat-check-line"></i>
            Ulasan Saya
            <span style="background: #10b981; color: white; padding: 0.2rem 0.8rem; border-radius: 60px; font-size: 0.8rem; margin-left: 0.5rem;">
                {{ $pemesanan->ulasans->count() }}
            </span>
        </div>

        @foreach($pemesanan->ulasans as $ulasan)
        <div class="review-item">
            <div class="review-header">
                <div class="item-image" style="width: 45px; height: 45px;">
                    @if($ulasan->menu?->gambar)
                    <img src="{{ asset('storage/' . $ulasan->menu->gambar) }}" alt="{{ $ulasan->menu->nama_menu }}">
                    @else
                    <div class="item-placeholder" style="font-size: 1.3rem;">🍱</div>
                    @endif
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; color: var(--char); margin-bottom: 0.25rem;">
                        {{ $ulasan->menu?->nama_menu ?? 'Menu dihapus' }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="ri-star-fill" style="color: {{ $i <= $ulasan->rating ? '#fbbf24' : '#e2e8f0' }}; font-size: 1rem;"></i>
                            @endfor
                        </div>
                        <span style="font-size: 0.8rem; color: var(--muted);">{{ $ulasan->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @if($ulasan->komentar)
            <div class="review-comment">
                <i class="ri-double-quotes-l" style="color: var(--amber); opacity: 0.5;"></i>
                {{ $ulasan->komentar }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Animate progress bar on load
        const progressBar = document.querySelector('.timeline-progress');
        if (progressBar) {
            setTimeout(() => {
                progressBar.style.transition = 'width 1s cubic-bezier(0.16, 1, 0.3, 1)';
            }, 100);
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
</script>

@endsection