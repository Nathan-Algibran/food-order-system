{{--
 * Purpose: Checkout page — review order, choose payment method, upload bukti, submit
 * Used by: GET /checkout (CheckoutController@show)
--}}
@extends('layouts.app')
@section('title', 'Checkout')

@section('content')

<style>
    /* Modern Checkout Page Styles */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes checkBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 2rem 4rem;
    }

    /* Header */
    .checkout-header {
        margin-bottom: 2.5rem;
        animation: slideInLeft 0.6s ease;
    }

    .checkout-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .checkout-title i {
        color: var(--amber);
        font-size: 2.5rem;
        animation: pulse 2s infinite;
    }

    .checkout-subtitle {
        color: var(--muted);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .step-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
    }

    .step-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1.2rem;
        background: rgba(245,158,11,0.1);
        border-radius: 60px;
        color: var(--amber-dk);
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Checkout Grid */
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    /* Payment Section */
    .payment-section {
        animation: slideInLeft 0.7s ease 0.1s backwards;
    }

    .section-card {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(16px);
        border-radius: 24px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .section-card:hover {
        box-shadow: 0 10px 30px -5px rgba(245,158,11,0.1);
        border-color: var(--amber);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-number {
        width: 2.2rem;
        height: 2.2rem;
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 4px 10px rgba(245,158,11,0.3);
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--char);
    }

    .section-badge {
        margin-left: auto;
        font-size: 0.75rem;
        color: var(--muted);
    }

    /* Payment Methods */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        padding: 1.2rem;
        background: white;
        border: 2px solid var(--border);
        border-radius: 18px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        position: relative;
        overflow: hidden;
    }

    .payment-option:hover {
        border-color: var(--amber);
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(245,158,11,0.1);
    }

    .payment-option.selected {
        border-color: var(--amber);
        background: linear-gradient(135deg, rgba(254,243,199,0.5), rgba(255,255,255,0.9));
        box-shadow: 0 8px 20px rgba(245,158,11,0.15);
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-icon {
        width: 3.5rem;
        height: 3.5rem;
        background: linear-gradient(135deg, #fef3c7, #fff);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: all 0.3s;
    }

    .payment-option.selected .payment-icon {
        background: var(--amber);
        color: white;
        transform: scale(1.05);
    }

    .payment-info {
        flex: 1;
    }

    .payment-name {
        font-weight: 700;
        color: var(--char);
        margin-bottom: 0.2rem;
    }

    .payment-desc {
        font-size: 0.8rem;
        color: var(--muted);
    }

    .payment-check {
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .payment-option.selected .payment-check {
        background: var(--amber);
        border-color: var(--amber);
        animation: checkBounce 0.4s ease;
    }

    .payment-check i {
        color: white;
        font-size: 0.9rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .payment-option.selected .payment-check i {
        opacity: 1;
    }

    /* ===== Payment Info Box (QRIS / Bank Transfer) ===== */
    .payment-info-box {
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 0;
    }

    /* QRIS Box */
    .qris-box {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #86efac;
        border-radius: 18px;
        padding: 1.5rem;
        text-align: center;
    }

    .qris-box .qris-title {
        font-weight: 800;
        color: #166534;
        font-size: 1rem;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .qris-box .qris-subtitle {
        font-size: 0.78rem;
        color: #15803d;
        margin-bottom: 1.2rem;
    }

    .qris-image-wrapper {
        display: inline-block;
        background: white;
        padding: 0.75rem;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
    }

    .qris-image-wrapper img {
        width: 180px;
        height: 180px;
        object-fit: contain;
        display: block;
    }

    .qris-placeholder {
        width: 180px;
        height: 180px;
        background: repeating-conic-gradient(#e2e8f0 0% 25%, white 0% 50%) 0 0 / 20px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }

    .qris-note {
        font-size: 0.75rem;
        color: #16a34a;
        background: rgba(22,163,74,0.08);
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        display: inline-block;
    }

    /* Bank Transfer Box */
    .bank-box {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 2px solid #93c5fd;
        border-radius: 18px;
        padding: 1.5rem;
    }

    .bank-box .bank-title {
        font-weight: 800;
        color: #1e3a5f;
        font-size: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bank-accounts {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
    }

    .bank-account-row {
        background: white;
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: relative;
    }

    .bank-logo {
        width: 2.8rem;
        height: 2.8rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 900;
        flex-shrink: 0;
        color: white;
        font-size: 0.7rem;
        letter-spacing: 0.02em;
    }

    .bank-logo.bca   { background: linear-gradient(135deg, #003d8f, #005bbd); }
    .bank-logo.bni   { background: linear-gradient(135deg, #f77f00, #e86900); }
    .bank-logo.bri   { background: linear-gradient(135deg, #00529b, #003a70); }
    .bank-logo.mandiri { background: linear-gradient(135deg, #003087, #f7a800); }
    .bank-logo.dana  { background: linear-gradient(135deg, #118eea, #0070cc); }

    .bank-detail {
        flex: 1;
        min-width: 0;
    }

    .bank-name {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.15rem;
    }

    .bank-number {
        font-size: 1rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 0.08em;
        font-variant-numeric: tabular-nums;
    }

    .bank-holder {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    .copy-btn {
        background: none;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.35rem 0.7rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        flex-shrink: 0;
    }

    .copy-btn:hover {
        border-color: var(--amber);
        color: var(--amber-dk);
        background: rgba(245,158,11,0.05);
    }

    .copy-btn.copied {
        border-color: #22c55e;
        color: #16a34a;
        background: rgba(34,197,94,0.08);
    }

    .bank-note {
        margin-top: 1rem;
        background: rgba(59,130,246,0.08);
        border-radius: 10px;
        padding: 0.6rem 0.9rem;
        font-size: 0.75rem;
        color: #1d4ed8;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    /* Upload Section */
    .upload-area {
        border: 3px dashed var(--border);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: rgba(255,255,255,0.5);
        display: block;
    }

    .upload-area:hover {
        border-color: var(--amber);
        background: rgba(254,243,199,0.3);
    }

    .upload-area.has-preview {
        padding: 1rem;
        cursor: default;
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--amber);
        margin-bottom: 1rem;
        opacity: 0.6;
    }

    .upload-text {
        color: var(--muted);
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        font-size: 0.8rem;
        color: #cbd5e1;
    }

    .preview-image {
        max-height: 200px;
        border-radius: 16px;
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Notes Section */
    .notes-textarea {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 16px;
        font-size: 0.95rem;
        resize: vertical;
        transition: all 0.3s;
        background: white;
    }

    .notes-textarea:focus {
        outline: none;
        border-color: var(--amber);
        box-shadow: 0 4px 15px rgba(245,158,11,0.1);
    }

    /* Order Summary */
    .summary-section {
        animation: slideInRight 0.7s ease 0.1s backwards;
    }

    .summary-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(254,243,199,0.2) 100%);
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
        font-size: 1.4rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .summary-title i {
        color: var(--amber);
    }

    .order-items {
        max-height: 350px;
        overflow-y: auto;
        margin-bottom: 1.5rem;
        padding-right: 0.5rem;
    }

    .order-items::-webkit-scrollbar { width: 4px; }
    .order-items::-webkit-scrollbar-thumb { background: var(--amber); border-radius: 4px; }

    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 0;
        border-bottom: 1px dashed var(--border);
    }

    .order-item:last-child { border-bottom: none; }

    .item-image {
        width: 3rem;
        height: 3rem;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, #fef3c7, #fff);
        flex-shrink: 0;
    }

    .item-image img { width: 100%; height: 100%; object-fit: cover; }

    .item-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .item-details { flex: 1; min-width: 0; }

    .item-name {
        font-weight: 600;
        color: var(--char);
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-qty { font-size: 0.75rem; color: var(--muted); }

    .item-price { font-weight: 700; color: var(--char); font-size: 0.85rem; }

    .summary-total {
        padding-top: 1.5rem;
        border-top: 3px solid var(--amber);
        margin-top: 1rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label { font-size: 1.1rem; font-weight: 700; color: var(--char); }

    .total-value {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--amber-dk);
    }

    /* Checkout Button */
    .checkout-btn {
        width: 100%;
        padding: 1.2rem;
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        border: none;
        border-radius: 18px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        margin-top: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -5px rgba(245,158,11,0.4);
    }

    .checkout-btn::before {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        width: 0; height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .checkout-btn:hover::before { width: 400px; height: 400px; }

    .checkout-btn:hover:not(:disabled) {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -8px rgba(245,158,11,0.6);
    }

    .checkout-btn:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: var(--muted);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s;
    }

    .back-link:hover { color: var(--amber); }
    .back-link i { margin-right: 0.3rem; }

    /* Error Message */
    .error-message {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #ef4444;
        animation: fadeInUp 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .checkout-grid { grid-template-columns: 1fr; }
        .summary-card { position: static; }
    }

    @media (max-width: 640px) {
        .checkout-container { padding: 1rem 1rem 3rem; }
        .checkout-title { font-size: 2rem; }
        .section-card { padding: 1.2rem; }
        .payment-option { padding: 1rem; }
        .payment-icon { width: 3rem; height: 3rem; font-size: 1.5rem; }
        .qris-image-wrapper img, .qris-placeholder { width: 150px; height: 150px; }
    }
</style>

<div class="checkout-container">
    {{-- Header --}}
    <div class="checkout-header">
        <h1 class="checkout-title">
            <i class="ri-shopping-cart-2-line"></i>
            Checkout
        </h1>
        <div class="checkout-subtitle">
            <span>Periksa pesananmu sebelum konfirmasi</span>
            <div class="step-indicator">
                <span class="step-badge">
                    <i class="ri-check-line"></i>
                    Keranjang
                </span>
                <i class="ri-arrow-right-line" style="color: var(--muted);"></i>
                <span class="step-badge" style="background: var(--amber); color: white;">
                    <i class="ri-edit-line"></i>
                    Checkout
                </span>
                <i class="ri-arrow-right-line" style="color: var(--muted);"></i>
                <span class="step-badge">
                    <i class="ri-truck-line"></i>
                    Selesai
                </span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.process') }}"
          enctype="multipart/form-data"
          x-data="{
            method: '',
            preview: null,
            copiedBank: null,
            handleFile(e) {
                const file = e.target.files[0];
                if (file) {
                    this.preview = URL.createObjectURL(file);
                }
            },
            removePreview() {
                this.preview = null;
                this.$refs.fileInput.value = '';
            },
            copyNumber(text, bank) {
                navigator.clipboard.writeText(text).then(() => {
                    this.copiedBank = bank;
                    setTimeout(() => this.copiedBank = null, 2000);
                });
            }
          }"
          class="checkout-grid">
        @csrf

        {{-- Left Column: Payment & Notes --}}
        <div class="payment-section">

            {{-- Step 1: Payment Method --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-number">1</span>
                    <h2 class="section-title">Metode Pembayaran</h2>
                    <span class="section-badge">Pilih salah satu</span>
                </div>

                @error('metode_pembayaran')
                <div class="error-message">
                    <i class="ri-error-warning-line"></i>
                    {{ $message }}
                </div>
                @enderror

                <div class="payment-methods">
                    {{-- QRIS --}}
                    <label class="payment-option" :class="{ 'selected': method === 'qris' }">
                        <input type="radio" name="metode_pembayaran" value="qris" x-model="method">
                        <span class="payment-icon">📱</span>
                        <span class="payment-info">
                            <span class="payment-name">QRIS</span>
                            <span class="payment-desc">Scan QR Code, lalu upload bukti bayar</span>
                        </span>
                        <span class="payment-check">
                            <i class="ri-check-line"></i>
                        </span>
                    </label>

                    {{-- COD --}}
                    <label class="payment-option" :class="{ 'selected': method === 'cod' }">
                        <input type="radio" name="metode_pembayaran" value="cod" x-model="method">
                        <span class="payment-icon">💵</span>
                        <span class="payment-info">
                            <span class="payment-name">COD (Bayar di Tempat)</span>
                            <span class="payment-desc">Bayar saat pesanan tiba</span>
                        </span>
                        <span class="payment-check">
                            <i class="ri-check-line"></i>
                        </span>
                    </label>

                    {{-- Bank Transfer --}}
                    <label class="payment-option" :class="{ 'selected': method === 'bank_transfer' }">
                        <input type="radio" name="metode_pembayaran" value="bank_transfer" x-model="method">
                        <span class="payment-icon">🏦</span>
                        <span class="payment-info">
                            <span class="payment-name">Transfer Bank</span>
                            <span class="payment-desc">Transfer ke rekening, lalu upload bukti</span>
                        </span>
                        <span class="payment-check">
                            <i class="ri-check-line"></i>
                        </span>
                    </label>
                </div>
            </div>

            {{-- Step 2: Info Pembayaran (QRIS atau Bank Transfer) --}}
            <div x-show="method === 'qris' || method === 'bank_transfer'"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="section-card">
                <div class="section-header">
                    <span class="section-number">2</span>
                    <h2 class="section-title">Informasi Pembayaran</h2>
                    <span class="section-badge">Bayar sekarang</span>
                </div>

                {{-- ── QRIS ── --}}
                <div x-show="method === 'qris'" class="qris-box">
                    <p class="qris-title">
                        <i class="ri-qr-code-line" style="font-size:1.1rem;"></i>
                        Scan QRIS untuk Membayar
                    </p>
                    <p class="qris-subtitle">Gunakan aplikasi dompet digital atau m-banking apapun</p>

                    <div class="qris-image-wrapper">

                        @if(file_exists(public_path('images/qris.jpeg')))
                            <img src="{{ asset('images/qris.jpeg') }}" alt="QRIS">
                        @else
                            <div class="qris-placeholder">
                                <span style="font-size:2rem;">📲</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <p style="font-size:0.85rem; font-weight:700; color:#166534; margin-bottom:0.3rem;">
                            a.n. <strong>FoodApp / Kelompok 2</strong>
                        </p>
                        <span class="qris-note">
                            <i class="ri-information-line"></i>
                            Pastikan nominal sesuai total pesanan, lalu upload bukti pembayaran di bawah
                        </span>
                    </div>
                </div>

                {{-- ── Bank Transfer ── --}}
                <div x-show="method === 'bank_transfer'" x-cloak class="bank-box">
                    <p class="bank-title">
                        🏦 Transfer ke salah satu rekening berikut
                    </p>

                    <div class="bank-accounts">

                        {{-- BCA --}}
                        <div class="bank-account-row">
                            <div class="bank-logo bca">BCA</div>
                            <div class="bank-detail">
                                <div class="bank-name">Bank Central Asia</div>
                                <div class="bank-number">1234 5678 90</div>
                                <div class="bank-holder">Foodapp</div>
                            </div>
                            <button type="button"
                                    @click="copyNumber('1234567890', 'bca')"
                                    class="copy-btn"
                                    :class="{ 'copied': copiedBank === 'bca' }">
                                <i :class="copiedBank === 'bca' ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                                <span x-text="copiedBank === 'bca' ? 'Tersalin!' : 'Salin'">Salin</span>
                            </button>
                        </div>

                        {{-- BRI --}}
                        <div class="bank-account-row">
                            <div class="bank-logo bri">BRI</div>
                            <div class="bank-detail">
                                <div class="bank-name">Bank Rakyat Indonesia</div>
                                <div class="bank-number">0987 6543 210</div>
                                <div class="bank-holder">Foodapp</div>
                            </div>
                            <button type="button"
                                    @click="copyNumber('09876543210', 'bri')"
                                    class="copy-btn"
                                    :class="{ 'copied': copiedBank === 'bri' }">
                                <i :class="copiedBank === 'bri' ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                                <span x-text="copiedBank === 'bri' ? 'Tersalin!' : 'Salin'">Salin</span>
                            </button>
                        </div>

                        {{-- BNI --}}
                        <div class="bank-account-row">
                            <div class="bank-logo bni">BNI</div>
                            <div class="bank-detail">
                                <div class="bank-name">Bank Negara Indonesia</div>
                                <div class="bank-number">1122 3344 55</div>
                                <div class="bank-holder">FoodApp</div>
                            </div>
                            <button type="button"
                                    @click="copyNumber('1122334455', 'bni')"
                                    class="copy-btn"
                                    :class="{ 'copied': copiedBank === 'bni' }">
                                <i :class="copiedBank === 'bni' ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                                <span x-text="copiedBank === 'bni' ? 'Tersalin!' : 'Salin'">Salin</span>
                            </button>
                        </div>

                    </div>

                    <div class="bank-note">
                        <i class="ri-information-line" style="flex-shrink:0; margin-top:0.1rem;"></i>
                        <span>Transfer tepat sesuai total tagihan. Setelah transfer, upload bukti pembayaran di bawah agar pesanan segera diproses admin.</span>
                    </div>
                </div>
            </div>

            <div x-show="method && method !== 'cod'"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="section-card">
                <div class="section-header">
                    <span class="section-number">3</span>
                    <h2 class="section-title">Bukti Pembayaran</h2>
                    <span class="section-badge">Opsional</span>
                </div>

                <input type="file"
                       name="bukti_bayar"
                       id="bukti_bayar_input"
                       accept="image/*"
                       @change="handleFile($event)"
                       x-ref="fileInput"
                       class="sr-only">

                {{-- Sebelum preview --}}
                <label for="bukti_bayar_input" class="upload-area" x-show="!preview">
                    <div class="upload-icon">
                        <i class="ri-upload-cloud-2-line"></i>
                    </div>
                    <p class="upload-text">Klik untuk upload bukti transfer</p>
                    <p class="upload-hint">JPG, PNG max. 2MB</p>
                </label>

                {{-- Setelah ada preview --}}
                <div class="upload-area has-preview" x-show="preview" x-cloak>
                    <img :src="preview" class="preview-image" alt="Preview">
                    <button type="button"
                            @click="removePreview()"
                            class="btn-ghost"
                            style="margin-top: 1rem; padding: 0.5rem 1.5rem;">
                        <i class="ri-delete-bin-line"></i> Hapus & Upload Ulang
                    </button>
                </div>

                @error('bukti_bayar')
                <div class="error-message">
                    <i class="ri-error-warning-line"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- Step: Catatan --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-number" x-text="method && method !== 'cod' ? '4' : '2'">2</span>
                    <h2 class="section-title">Catatan</h2>
                    <span class="section-badge">Opsional</span>
                </div>

                <textarea name="catatan"
                          rows="3"
                          class="notes-textarea"
                          placeholder="Contoh: tidak pedas, tanpa bawang, request khusus...">{{ old('catatan') }}</textarea>

                @error('catatan')
                <div class="error-message">
                    <i class="ri-error-warning-line"></i>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        {{-- Right Column: Order Summary --}}
        <div class="summary-section">
            <div class="summary-card">
                <div class="summary-title">
                    <i class="ri-receipt-line"></i>
                    Ringkasan Pesanan
                </div>

                <div class="order-items">
                    @foreach($cart as $item)
                    <div class="order-item">
                        <div class="item-image">
                            @if($item['gambar'])
                            <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama_menu'] }}">
                            @else
                            <div class="item-placeholder">🍱</div>
                            @endif
                        </div>
                        <div class="item-details">
                            <div class="item-name">{{ $item['nama_menu'] }}</div>
                            <div class="item-qty">{{ $item['jumlah'] }} × Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                        </div>
                        <div class="item-price">
                            Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="summary-total">
                    <div class="total-row">
                        <span class="total-label">Total Pembayaran</span>
                        <span class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit"
                        class="checkout-btn"
                        :disabled="!method">
                    <i class="ri-check-line"></i>
                    Konfirmasi Pesanan
                </button>

                <a href="{{ route('cart.index') }}" class="back-link">
                    <i class="ri-arrow-left-line"></i> Kembali ke keranjang
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('.section-card, .summary-card');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            setTimeout(() => { el.style.opacity = '1'; }, index * 100);
        });
    });
</script>

@endsection