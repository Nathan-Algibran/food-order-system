<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodApp · Culinary Journey</title>
    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Remix Icon (modern icon set) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>

    <style>
        /* ----- MODERN VARIABLES ----- */
        :root {
            --amber:    #F59E0B;
            --amber-dk: #D97706;
            --amber-lt: #FEF3C7;
            --char:     #171717;
            --cream:    #FFFDF7;
            --muted:    #6B6B6B;
            --border:   rgba(0,0,0,0.06);
            --glass:    rgba(255, 253, 247, 0.8);
            --shadow-sm: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.02);
            --shadow-lg: 0 25px 35px -12px rgba(0,0,0,0.15);
            --card-radius: 28px;
            --transition-smooth: cubic-bezier(0.23, 1, 0.32, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: var(--char);
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* ----- DYNAMIC NOISE & GRADIENTS ----- */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Animated Orbs */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
            will-change: transform;
            animation: floatOrb 22s infinite alternate ease-in-out;
        }
        .orb-1 { width: 70vw; height: 70vw; max-width: 800px; max-height: 800px; background: radial-gradient(circle at 30% 30%, rgba(245,158,11,0.18), rgba(239,68,68,0.05) 70%, transparent); top: -20vh; right: -15vw; animation-duration: 28s; }
        .orb-2 { width: 60vw; height: 60vw; max-width: 650px; max-height: 650px; background: radial-gradient(circle, rgba(245,158,11,0.12), rgba(168,85,247,0.05) 80%, transparent); bottom: -10vh; left: -10vw; animation-duration: 24s; animation-delay: -7s; }
        .orb-3 { width: 45vw; height: 45vw; max-width: 500px; max-height: 500px; background: radial-gradient(circle, rgba(239,68,68,0.1), rgba(245,158,11,0.02) 80%); top: 30vh; left: 40vw; filter: blur(120px); animation-duration: 32s; animation-delay: -15s; opacity: 0.4; }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            33% { transform: translate(4vw, 6vh) scale(1.05) rotate(2deg); }
            66% { transform: translate(-2vw, 10vh) scale(0.98) rotate(-1deg); }
            100% { transform: translate(5vw, -3vh) scale(1.02) rotate(1deg); }
        }

        /* ----- NAVBAR — RAPI & MODERN ----- */
        nav {
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 0 2.5rem;
            height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--glass);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            animation: navReveal 0.8s var(--transition-smooth) forwards;
        }
        @keyframes navReveal {
            0% { opacity: 0; transform: translateY(-100%); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--char);
            letter-spacing: -0.02em;
            transition: all 0.25s var(--transition-smooth);
            position: relative;
            flex-shrink: 0; /* Mencegah logo mengecil */
        }
        .logo:hover { transform: scale(1.03); filter: drop-shadow(0 4px 8px rgba(245,158,11,0.2)); }
        .logo .dot {
            display: inline-block;
            width: 10px; height: 10px;
            background: var(--amber);
            border-radius: 50%;
            margin-left: 2px;
            animation: pulseGlow 2.2s infinite;
            box-shadow: 0 0 12px var(--amber);
        }
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.9; box-shadow: 0 0 5px var(--amber); }
            50% { transform: scale(1.6); opacity: 1; box-shadow: 0 0 18px var(--amber-dk); }
        }

        /* === NAVIGATION LINKS (RAPI) === */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem; /* Jarak antar item konsisten */
        }

        /* Style dasar untuk semua link/button di dalam nav-links */
        .nav-item {
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            transition: all 0.2s ease;
            white-space: nowrap; /* Mencegah teks turun ke bawah */
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--char);
            background: transparent;
            border: 1px solid transparent;
        }

        /* Menu Link Biasa */
        .nav-link {
            background: transparent;
        }
        .nav-link i { font-size: 1.2rem; color: var(--muted); transition: color 0.2s; }
        .nav-link:hover {
            background: rgba(0,0,0,0.03);
            color: var(--amber-dk);
        }
        .nav-link:hover i { color: var(--amber); }

        /* Cart Button (Special Style) */
        .cart-btn {
            background: white;
            border: 1.5px solid var(--amber) !important;
            box-shadow: 0 2px 6px rgba(245,158,11,0.1);
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }
        .cart-btn i { font-size: 1.3rem; color: var(--amber); transition: transform 0.3s; }
        .cart-badge {
            background: var(--amber-lt);
            color: var(--amber-dk);
            border-radius: 30px;
            padding: 2px 8px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-left: 4px;
        }
        .cart-btn:hover {
            background: var(--amber);
            color: white;
            border-color: var(--amber);
            transform: translateY(-3px);
            box-shadow: 0 16px 30px -8px rgba(245,158,11,0.5);
        }
        .cart-btn:hover i { color: white; transform: scale(1.1) rotate(-5deg); }
        .cart-btn:hover .cart-badge { background: white; color: var(--amber); }

        /* User Chip */
        .user-chip {
            background: white;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 0.25rem 1rem 0.25rem 0.5rem;
            margin: 0 0.25rem;
        }
        .user-chip:hover {
            border-color: var(--amber);
            box-shadow: var(--shadow-lg);
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(145deg, var(--amber), #dc2626);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.9rem;
            box-shadow: 0 6px 12px rgba(245,158,11,0.25);
        }

        /* Register Button */
        .register-btn {
            background: var(--char);
            color: white;
            font-weight: 700;
            padding: 0.7rem 1.8rem;
            box-shadow: 0 8px 16px -6px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.3px;
        }
        .register-btn i { font-size: 1.1rem; }
        .register-btn:hover {
            background: var(--amber-dk);
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 18px 28px -8px var(--amber-dk);
            color: white;
        }

        /* Logout Button */
        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: #b91c1c;
            padding: 0.5rem 1.2rem;
        }
        .logout-btn i { font-size: 1.2rem; }
        .logout-btn:hover {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Login Link (ketika belum login) */
        .login-link {
            font-weight: 500;
            color: var(--char);
        }
        .login-link i { color: var(--muted); }
        .login-link:hover {
            background: rgba(0,0,0,0.03);
            color: var(--amber-dk);
        }

        /* === MOBILE (RAPI) === */
        .mobile-toggle {
            display: none;
            background: white;
            border: 1.5px solid var(--border);
            width: 42px;
            height: 42px;
            border-radius: 14px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            padding: 8px;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }
        .mobile-toggle:hover { border-color: var(--amber); background: var(--amber-lt); }
        .mobile-toggle span {
            display: block;
            width: 100%;
            height: 2.5px;
            background: var(--char);
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .mobile-menu {
            position: absolute;
            top: 76px;
            left: 0;
            width: 100%;
            background: rgba(255, 253, 247, 0.95);
            backdrop-filter: blur(30px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: none;
            flex-direction: column;
            gap: 0.25rem;
            animation: slideDown 0.3s ease;
            box-shadow: var(--shadow-lg);
            border-radius: 0 0 24px 24px;
        }
        .mobile-menu.active { display: flex; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .mobile-menu a, .mobile-menu form button {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--char);
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.9rem 0.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: color 0.2s;
            background: none;
            border-left: none;
            border-right: none;
            border-top: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        .mobile-menu a i, .mobile-menu button i {
            font-size: 1.5rem;
            width: 32px;
            color: var(--amber);
        }
        .mobile-menu a:hover, .mobile-menu button:hover { color: var(--amber-dk); background: rgba(245,158,11,0.05); }

        /* Responsive Breakpoint */
        @media (max-width: 900px) {
            nav { padding: 0 1.5rem; }
            .nav-links { gap: 0.4rem; }
            .nav-item { padding: 0.4rem 0.9rem; font-size: 0.9rem; }
        }
        @media (max-width: 768px) {
            .nav-links { display: none !important; }
            .mobile-toggle { display: flex; }
        }

        /* ----- MAIN CONTENT (Tetap Modern) ----- */
        main {
            position: relative;
            z-index: 5;
            max-width: 1280px;
            margin: 0 auto;
            padding: 2.5rem 2rem 5rem;
        }

        .page-wrapper {
            opacity: 0;
            transform: translateY(40px) scale(0.98);
            transition: opacity 0.9s var(--transition-smooth), transform 0.9s var(--transition-smooth);
            will-change: transform, opacity;
        }
        .page-wrapper.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Card animation untuk konten */
        .menu-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(12px);
            border-radius: var(--card-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(255,255,255,0.6);
            transition: all 0.4s var(--transition-smooth);
        }
        .menu-card:hover {
            transform: translateY(-12px) scale(1.01);
            box-shadow: var(--shadow-lg);
            border-color: var(--amber);
            background: white;
        }

        /* Footer */
        footer {
            position: relative;
            z-index: 5;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 1rem 2rem;
            padding: 2rem 2.5rem;
            border-top: 1px solid var(--border);
            backdrop-filter: blur(8px);
            background: rgba(255,251,240,0.5);
            font-weight: 400;
        }
        .footer-link { text-decoration: none; color: var(--char); font-weight: 500; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px; }
        .footer-link:hover { color: var(--amber); transform: translateY(-2px); }

        /* Utility */
        .btn-amber {
            background: var(--amber);
            color: white;
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 60px;
            font-weight: 700;
            transition: 0.25s;
            box-shadow: 0 8px 18px -8px var(--amber);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

    <!-- Ambient Orbs -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <!-- ═══════════════ NAVIGATION (RAPI) ═══════════════ -->
    <nav>
        <!-- Logo -->
        <a href="/" class="logo">
            Food<span style="font-weight:300">App</span><span class="dot"></span>
        </a>

        <!-- Desktop Navigation (Rapi) -->
        {{-- SESUDAH --}}
        <div class="nav-links">
            @auth
                @if(auth()->user()->role === 'admin')
                    {{-- Admin: hanya tampilkan link admin dashboard + logout --}}
                    <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link">
                        <i class="ri-dashboard-line"></i> Admin Dashboard
                    </a>
                    <div class="nav-item user-chip">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}
                        </div>
                        <span>{{ auth()->user()->nama ?? 'Admin' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: contents;">
                        @csrf
                        <button class="nav-item logout-btn">
                            <i class="ri-logout-box-r-line"></i> Sign out
                        </button>
                    </form>
                @else
                    {{-- User biasa --}}
                    <a href="{{ route('menu.index') }}" class="nav-item nav-link">
                        <i class="ri-restaurant-line"></i> Menu
                    </a>
                    <a href="/pesanan" class="nav-item nav-link">
                        <i class="ri-shopping-bag-3-line"></i> Pesanan saya
                    </a>
                    <a href="/cart" class="nav-item cart-btn">
                        <i class="ri-shopping-bag-3-line"></i> Cart
                        <span class="cart-badge">{{ $cartCount }}</span>
                    </a>
                    <div class="nav-item user-chip">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->nama ?? 'U', 0, 1)) }}
                        </div>
                        <span>{{ auth()->user()->nama ?? 'User' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: contents;">
                        @csrf
                        <button class="nav-item logout-btn">
                            <i class="ri-logout-box-r-line"></i> Sign out
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('menu.index') }}" class="nav-item nav-link">
                    <i class="ri-restaurant-line"></i> Menu
                </a>
                <a href="/login" class="nav-item login-link">
                    <i class="ri-user-line"></i> Login
                </a>
                <a href="/register" class="nav-item register-btn">
                    <i class="ri-sparkling-line"></i> Get Started
                </a>
            @endauth
        </div>

        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" onclick="document.getElementById('mobile-menu').classList.toggle('active')" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <a href="/menu"><i class="ri-restaurant-line"></i> Explore Menu</a>
            @auth
                <a href="/cart"><i class="ri-shopping-bag-3-line"></i> Your Cart <span style="margin-left:auto; background:var(--amber-lt); padding:2px 10px; border-radius:30px;">3</span></a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"><i class="ri-logout-box-r-line"></i> Sign out</button>
                </form>
            @else
                <a href="/login"><i class="ri-user-line"></i> Login</a>
                <a href="/register"><i class="ri-sparkling-line"></i> Register</a>
            @endauth
        </div>
    </nav>

    <!-- ═══════════════ MAIN CONTENT ═══════════════ -->
    <main>
        <div class="page-wrapper" id="page-wrapper">
            @yield('content')
        </div>
    </main>

    <!-- ═══════════════ FOOTER ═══════════════ -->
    <footer>
        <span>© 2026 FoodApp — Savor the moment</span>
        <span style="opacity:0.4;">•</span>
        <span>✨ Crafted with passion</span>
        <span style="opacity:0.4;">•</span>
        <a href="/menu" class="footer-link"><i class="ri-restaurant-line"></i> Menu</a>
        <a href="#" class="footer-link"><i class="ri-lock-line"></i> Privacy</a>
        <a href="#" class="footer-link"><i class="ri-instagram-line"></i></a>
    </footer>

    <!-- Scripts untuk Animasi dan Interaktivitas -->
    <script>
        (function(){
            // Page entrance animation
            const wrapper = document.getElementById('page-wrapper');
            if (wrapper) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => wrapper.classList.add('visible'));
                });
            }

            // Intersection Observer untuk animasi scroll elemen
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.menu-card, .animate-onscroll');
                if (!elements.length) return;
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.transition = 'opacity 0.7s cubic-bezier(0.23,1,0.32,1), transform 0.7s cubic-bezier(0.23,1,0.32,1)';
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0) scale(1)';
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                elements.forEach(el => {
                    if (!el.style.opacity || el.style.opacity === '1') {
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(30px) scale(0.98)';
                    }
                    observer.observe(el);
                });
            };

            // Parallax ringan untuk orb
            const handleParallax = (e) => {
                const orbs = document.querySelectorAll('.bg-orb');
                if (!orbs.length) return;
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                orbs.forEach((orb, idx) => {
                    const speed = 0.8 + idx * 0.2;
                    orb.style.transform = `translate(${x * speed}px, ${y * speed}px) scale(1)`;
                });
            };

            window.addEventListener('DOMContentLoaded', () => {
                animateOnScroll();
                if (window.innerWidth > 768) {
                    document.addEventListener('mousemove', handleParallax);
                }
            });

            window.addEventListener('load', animateOnScroll);
            window.addEventListener('resize', () => {
                if (window.innerWidth <= 768) {
                    document.removeEventListener('mousemove', handleParallax);
                } else {
                    document.removeEventListener('mousemove', handleParallax);
                    document.addEventListener('mousemove', handleParallax);
                }
            });
        })();
    </script>
</body>
</html>