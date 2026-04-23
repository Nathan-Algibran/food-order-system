@extends('layouts.app')

@section('content')

<style>
    /* Modern Landing Page Styles */
    :root {
        --hero-overlay: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 100%);
    }

    /* Hero Section Animations */
    @keyframes floatSlow {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-15px) rotate(-2deg); }
        75% { transform: translateY(15px) rotate(2deg); }
    }

    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.3); }
        50% { box-shadow: 0 0 40px rgba(245,158,11,0.6); }
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(60px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes rotateIn {
        from { opacity: 0; transform: rotate(-10deg) scale(0.9); }
        to { opacity: 1; transform: rotate(0) scale(1); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        min-height: 85vh;
        display: flex;
        align-items: center;
        padding: 4rem 2rem;
        overflow: hidden;
        border-radius: 0 0 60px 60px;
        margin-bottom: 4rem;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fbbf24 100%);
        z-index: -2;
    }

    .hero-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f59e0b' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.4;
    }

    .hero-pattern {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.3) 0%, transparent 50%);
        z-index: -1;
    }

    .hero-content {
        max-width: 600px;
        animation: slideUpFade 1s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
        padding: 0.5rem 1.2rem;
        border-radius: 60px;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--amber-dk);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.5);
        animation: pulseGlow 3s infinite;
    }

    .hero-badge i {
        font-size: 1.2rem;
        animation: rotateIn 2s ease;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(3rem, 8vw, 5rem);
        font-weight: 900;
        color: var(--char);
        line-height: 1.1;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .hero-title span {
        background: linear-gradient(135deg, var(--amber-dk) 0%, #ea580c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: inline-block;
        position: relative;
    }

    .hero-description {
        font-size: 1.1rem;
        color: #4a5568;
        line-height: 1.7;
        margin-bottom: 2.5rem;
        max-width: 500px;
    }

    .hero-cta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-primary-large {
        background: var(--char);
        color: white;
        text-decoration: none;
        padding: 1rem 2.5rem;
        border-radius: 60px;
        font-weight: 700;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-primary-large::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dk) 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn-primary-large:hover::before {
        opacity: 1;
    }

    .btn-primary-large span {
        position: relative;
        z-index: 1;
    }

    .btn-primary-large i {
        position: relative;
        z-index: 1;
        transition: transform 0.3s;
    }

    .btn-primary-large:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -8px rgba(245,158,11,0.5);
    }

    .btn-primary-large:hover i {
        transform: translateX(5px);
    }

    .btn-outline-large {
        background: transparent;
        color: var(--char);
        text-decoration: none;
        padding: 1rem 2.5rem;
        border-radius: 60px;
        font-weight: 600;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s;
        border: 2px solid var(--char);
        backdrop-filter: blur(8px);
    }

    .btn-outline-large:hover {
        background: var(--char);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
    }

    .hero-image {
        position: absolute;
        opacity: 0.3;
        right: 2%;
        bottom: 0;
        max-width: 45%;
        animation: floatSlow 6s ease-in-out infinite;
    }

    .hero-image img {
        width: 100%;
        height: auto;
        filter: drop-shadow(0 30px 40px rgba(0,0,0,0.15));
    }

    .floating-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .float-item {
        position: absolute;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(8px);
        padding: 0.8rem 1.5rem;
        border-radius: 60px;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
        animation: floatSlow 5s ease-in-out infinite;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .float-item-1 { top: 3%; left: 67%; animation-delay: 0s; }
    .float-item-2 { top: 60%; left: 80%; animation-delay: 1.5s; }
    .float-item-3 { bottom: 20%; right: 35%; animation-delay: 3s; }

    /* Stats Section */
    .stats-section {
        padding: 4rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }

    .stat-card {
        text-align: center;
        padding: 2rem;
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(12px);
        border-radius: 32px;
        border: 1px solid rgba(255,255,255,0.5);
        transition: all 0.3s;
        animation: scaleIn 0.6s ease backwards;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        background: white;
        box-shadow: 0 20px 40px -12px rgba(245,158,11,0.2);
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--amber);
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 0.5rem;
        font-family: 'Playfair Display', serif;
    }

    .stat-label {
        color: var(--muted);
        font-weight: 500;
    }

    /* Featured Menu Section */
    .featured-section {
        padding: 5rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
        animation: slideUpFade 0.8s ease;
    }

    .section-subtitle {
        color: var(--amber);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 900;
        color: var(--char);
        margin-bottom: 1rem;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .featured-card {
        background: white;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        animation: scaleIn 0.6s ease backwards;
    }

    .featured-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 30px 50px -15px rgba(245,158,11,0.3);
    }

    .featured-image {
        height: 220px;
        overflow: hidden;
        position: relative;
    }

    .featured-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s;
    }

    .featured-card:hover .featured-image img {
        transform: scale(1.15);
    }

    .featured-content {
        padding: 1.5rem;
    }

    .featured-category {
        color: var(--amber);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .featured-name {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--char);
        margin-bottom: 0.5rem;
    }

    .featured-price {
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--amber-dk);
        font-family: 'Playfair Display', serif;
    }

    /* How It Works */
    .how-it-works {
        padding: 5rem 2rem;
        background: linear-gradient(135deg, rgba(254,243,199,0.3) 0%, rgba(255,255,255,0) 100%);
        border-radius: 60px;
        margin: 2rem;
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .step-card {
        text-align: center;
        padding: 2rem;
        animation: slideUpFade 0.6s ease backwards;
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: var(--amber);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 900;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(245,158,11,0.4);
    }

    .step-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--char);
        margin-bottom: 0.8rem;
    }

    .step-desc {
        color: var(--muted);
        line-height: 1.6;
    }

    /* Testimonials */
    .testimonials-section {
        padding: 5rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .testimonial-card {
        background: white;
        border-radius: 32px;
        padding: 2rem;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
        transition: all 0.3s;
        animation: slideUpFade 0.6s ease backwards;
    }

    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(245,158,11,0.15);
    }

    .testimonial-quote {
        font-size: 1.1rem;
        color: var(--char);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        position: relative;
        padding-left: 2rem;
    }

    .testimonial-quote i {
        position: absolute;
        left: 0;
        top: 0;
        color: var(--amber);
        opacity: 0.3;
        font-size: 1.5rem;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--amber), #ef4444);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
    }

    .author-info h4 {
        font-weight: 700;
        color: var(--char);
        margin-bottom: 0.2rem;
    }

    .author-info p {
        color: var(--muted);
        font-size: 0.9rem;
    }

    /* CTA Section */
    .cta-section {
        padding: 5rem 2rem;
        margin: 3rem 2rem;
        background: linear-gradient(135deg, var(--char) 0%, #1f2937 100%);
        border-radius: 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f59e0b' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .cta-content {
        position: relative;
        z-index: 1;
        max-width: 700px;
        margin: 0 auto;
    }

    .cta-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        color: white;
        margin-bottom: 1rem;
    }

    .cta-description {
        color: #9ca3af;
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }

    .cta-btn {
        display: inline-block;
        background: var(--amber);
        color: white;
        text-decoration: none;
        padding: 1rem 3rem;
        border-radius: 60px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 10px 30px -5px rgba(245,158,11,0.5);
    }

    .cta-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -8px rgba(245,158,11,0.7);
        background: var(--amber-dk);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .featured-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .hero-image {
            opacity: 0.3;
            right: -10%;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            min-height: auto;
            padding: 3rem 1.5rem;
        }
        
        .hero-image {
            display: none;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .featured-grid {
            grid-template-columns: 1fr;
        }
        
        .steps-grid {
            grid-template-columns: 1fr;
        }
        
        .testimonials-grid {
            grid-template-columns: 1fr;
        }
        
        .floating-elements {
            display: none;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-pattern"></div>
    
    <div class="hero-content">
        <div class="hero-badge">
            <i class="ri-flashlight-fill"></i>
            <span>#1 Food Delivery di Indonesia</span>
        </div>
        
        <h1 class="hero-title">
            Nikmati <span>Kelezatan</span> Tanpa Batas
        </h1>
        
        <p class="hero-description">
            Pesan makanan favoritmu dari restoran terbaik. 
            Cepat, mudah, dan langsung diantar ke pintu rumahmu.
        </p>
        
        <div class="hero-cta">
            <a href="{{ route('menu.index') }}" class="btn-primary-large">
                <span>Pesan Sekarang</span>
                <i class="ri-arrow-right-line"></i>
            </a>
            <a href="#featured" class="btn-outline-large">
                <i class="ri-restaurant-line"></i>
                <span>Lihat Menu</span>
            </a>
        </div>
    </div>
    
    <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80" alt="Delicious Food">
    </div>
    
    <div class="floating-elements">
        <div class="float-item float-item-1">
            <i class="ri-star-fill" style="color: #fbbf24;"></i>
            <span>4.9 Rating</span>
        </div>
        <div class="float-item float-item-2">
            <i class="ri-time-line" style="color: var(--amber);"></i>
            <span>30 min delivery</span>
        </div>
        <div class="float-item float-item-3">
            <i class="ri-restaurant-2-line" style="color: #ef4444;"></i>
            <span>100+ Restaurants</span>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card" style="animation-delay: 0.1s;">
            <div class="stat-icon">
                <i class="ri-restaurant-2-line"></i>
            </div>
            <div class="stat-number">150+</div>
            <div class="stat-label">Restaurant Partners</div>
        </div>
        <div class="stat-card" style="animation-delay: 0.2s;">
            <div class="stat-icon">
                <i class="ri-user-smile-line"></i>
            </div>
            <div class="stat-number">50K+</div>
            <div class="stat-label">Happy Customers</div>
        </div>
        <div class="stat-card" style="animation-delay: 0.3s;">
            <div class="stat-icon">
                <i class="ri-motorbike-line"></i>
            </div>
            <div class="stat-number">1.2M+</div>
            <div class="stat-label">Orders Delivered</div>
        </div>
        <div class="stat-card" style="animation-delay: 0.4s;">
            <div class="stat-icon">
                <i class="ri-star-line"></i>
            </div>
            <div class="stat-number">4.9</div>
            <div class="stat-label">Average Rating</div>
        </div>
    </div>
</section>

<!-- Featured Menu Section -->
<section id="featured" class="featured-section">
    <div class="section-header">
        <div class="section-subtitle">Menu Favorit</div>
        <h2 class="section-title">Paling Banyak Dipesan</h2>
        <p style="color: var(--muted);">Pilihan terbaik yang selalu jadi favorit pelanggan setia kami</p>
    </div>
    
    <div class="featured-grid">
        @php
            $featuredItems = [
                ['name' => 'Beef Burger Special', 'category' => 'Burger', 'price' => 45000, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&q=80'],
                ['name' => 'Mie Ayam Bakso', 'category' => 'Mie', 'price' => 25000, 'image' => 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&q=80'],
                ['name' => 'Ayam Geprek Sambal', 'category' => 'Ayam', 'price' => 28000, 'image' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=400&q=80'],
            ];
        @endphp
        
        @foreach($featuredItems as $index => $item)
        <div class="featured-card" style="animation-delay: {{ $index * 0.1 }}s;">
            <div class="featured-image">
                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
            </div>
            <div class="featured-content">
                <div class="featured-category">{{ $item['category'] }}</div>
                <h3 class="featured-name">{{ $item['name'] }}</h3>
                <div class="featured-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works">
    <div class="section-header">
        <div class="section-subtitle">Mudah & Cepat</div>
        <h2 class="section-title">Cara Kerjanya</h2>
    </div>
    
    <div class="steps-grid">
        <div class="step-card" style="animation-delay: 0.1s;">
            <div class="step-number">1</div>
            <h3 class="step-title">Pilih Menu</h3>
            <p class="step-desc">Jelajahi berbagai pilihan makanan dari restoran terbaik di kotamu</p>
        </div>
        <div class="step-card" style="animation-delay: 0.2s;">
            <div class="step-number">2</div>
            <h3 class="step-title">Checkout</h3>
            <p class="step-desc">Pilih metode pembayaran yang kamu inginkan, proses aman dan cepat</p>
        </div>
        <div class="step-card" style="animation-delay: 0.3s;">
            <div class="step-number">3</div>
            <h3 class="step-title">Nikmati</h3>
            <p class="step-desc">Makanan diantar langsung ke rumahmu, siap untuk dinikmati</p>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section">
    <div class="section-header">
        <div class="section-subtitle">Testimoni</div>
        <h2 class="section-title">Apa Kata Mereka</h2>
    </div>
    
    <div class="testimonials-grid">
        <div class="testimonial-card" style="animation-delay: 0.1s;">
            <div class="testimonial-quote">
                <i class="ri-double-quotes-L"></i>
                Makanannya selalu fresh dan enak. Delivery cepat dan driver ramah. Recommended banget!
            </div>
            <div class="testimonial-author">
                <div class="author-avatar">S</div>
                <div class="author-info">
                    <h4>Sarah Amanda</h4>
                    <p>Food Enthusiast</p>
                </div>
            </div>
        </div>
        <div class="testimonial-card" style="animation-delay: 0.2s;">
            <div class="testimonial-quote">
                <i class="ri-double-quotes-L"></i>
                Aplikasi paling lengkap. Ada diskon setiap hari dan pilihan menunya banyak banget!
            </div>
            <div class="testimonial-author">
                <div class="author-avatar">R</div>
                <div class="author-info">
                    <h4>Rizky Pratama</h4>
                    <p>Regular Customer</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Siap Memesan?</h2>
        <p class="cta-description">
            Dapatkan diskon 20% untuk pesanan pertamamu. 
            Gunakan kode: <strong style="color: var(--amber);">WELCOME20</strong>
        </p>
        <a href="{{ route('menu.index') }}" class="cta-btn">
            Pesan Sekarang <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</section>

<script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-card, .featured-card, .step-card, .testimonial-card').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });

    // Counter animation for stats
    const animateNumber = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = current + (element.textContent.includes('+') ? '+' : element.textContent.includes('K') ? 'K+' : element.textContent.includes('M') ? 'M+' : '');
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };

    // Animate stats when they come into view
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(num => {
                    const text = num.textContent;
                    const value = parseInt(text.replace(/[^0-9]/g, ''));
                    if (!isNaN(value)) {
                        num.textContent = '0' + (text.includes('+') ? '+' : text.includes('K') ? 'K+' : text.includes('M') ? 'M+' : '');
                        animateNumber(num, 0, value, 2000);
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelector('.stats-grid')?.let(grid => statsObserver.observe(grid));
</script>

@endsection