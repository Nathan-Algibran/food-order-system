<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodApp · Admin Dashboard</title>
    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Remix Icon -->
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
            --transition-smooth: cubic-bezier(0.23, 1, 0.32, 1);
            
            /* Admin specific */
            --blue: #3B82F6;
            --green: #10B981;
            --red: #EF4444;
            --purple: #8B5CF6;
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
        .orb-1 { width: 70vw; height: 70vw; max-width: 800px; max-height: 800px; background: radial-gradient(circle at 30% 30%, rgba(59,130,246,0.15), rgba(139,92,246,0.05) 70%, transparent); top: -20vh; right: -15vw; animation-duration: 28s; }
        .orb-2 { width: 60vw; height: 60vw; max-width: 650px; max-height: 650px; background: radial-gradient(circle, rgba(245,158,11,0.1), rgba(16,185,129,0.05) 80%, transparent); bottom: -10vh; left: -10vw; animation-duration: 24s; animation-delay: -7s; }
        .orb-3 { width: 45vw; height: 45vw; max-width: 500px; max-height: 500px; background: radial-gradient(circle, rgba(239,68,68,0.08), rgba(245,158,11,0.02) 80%); top: 30vh; left: 40vw; filter: blur(120px); animation-duration: 32s; animation-delay: -15s; opacity: 0.4; }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            33% { transform: translate(4vw, 6vh) scale(1.05) rotate(2deg); }
            66% { transform: translate(-2vw, 10vh) scale(0.98) rotate(-1deg); }
            100% { transform: translate(5vw, -3vh) scale(1.02) rotate(1deg); }
        }

        /* ----- NAVBAR ----- */
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
            flex-shrink: 0;
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

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-item {
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--char);
            background: transparent;
            border: 1px solid transparent;
        }

        .nav-link { background: transparent; }
        .nav-link i { font-size: 1.2rem; color: var(--muted); transition: color 0.2s; }
        .nav-link:hover {
            background: rgba(0,0,0,0.03);
            color: var(--amber-dk);
        }
        .nav-link:hover i { color: var(--amber); }

        .admin-badge {
            background: linear-gradient(135deg, var(--purple), #6366f1);
            color: white;
            padding: 0.25rem 0.8rem;
            border-radius: 60px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 0.5rem;
            letter-spacing: 0.05em;
        }

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
            background: linear-gradient(145deg, var(--purple), #6366f1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.9rem;
            box-shadow: 0 6px 12px rgba(139,92,246,0.25);
        }

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

        /* Mobile */
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
            to { opacity: 1; transform: translateY(0); }
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

        @media (max-width: 900px) {
            nav { padding: 0 1.5rem; }
            .nav-links { gap: 0.4rem; }
            .nav-item { padding: 0.4rem 0.9rem; font-size: 0.9rem; }
        }
        @media (max-width: 768px) {
            .nav-links { display: none !important; }
            .mobile-toggle { display: flex; }
        }

        /* ----- MAIN CONTENT ----- */
        main {
            position: relative;
            z-index: 5;
            max-width: 1400px;
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

        /* ----- ADMIN DASHBOARD STYLES ----- */
        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .admin-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .welcome-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--char);
            margin-bottom: 0.5rem;
        }

        .date-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .date-display i { color: var(--amber); }

        .quick-actions {
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s var(--transition-smooth);
            text-decoration: none;
        }

        .action-btn-primary {
            background: linear-gradient(135deg, var(--amber), var(--amber-dk));
            color: white;
            box-shadow: 0 8px 20px -5px rgba(245,158,11,0.3);
        }
        .action-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px -5px rgba(245,158,11,0.5);
        }

        .action-btn-secondary {
            background: white;
            color: var(--char);
            border: 2px solid var(--border);
        }
        .action-btn-secondary:hover {
            border-color: var(--amber);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -5px rgba(0,0,0,0.1);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--amber);
            background: white;
        }

        .stat-icon-wrapper {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.2rem;
            transition: all 0.3s;
        }

        .stat-card:hover .stat-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .stat-trend {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 60px;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        /* Orders Card */
        .orders-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(12px);
            border-radius: 28px;
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--border);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--char);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .card-title i { color: var(--amber); font-size: 1.6rem; }

        .view-all-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--amber);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 12px;
        }
        .view-all-link:hover {
            background: var(--amber-lt);
            transform: translateX(5px);
        }

        /* Table Styles */
        .table-wrapper {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table thead tr {
            background: rgba(254,243,199,0.3);
            border-bottom: 2px solid var(--amber);
        }

        .orders-table th {
            padding: 1rem 1.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            white-space: nowrap;
        }

        .orders-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.3s;
        }

        .orders-table tbody tr:hover {
            background: rgba(245,158,11,0.05);
        }

        .orders-table td {
            padding: 1.2rem 1.5rem;
            white-space: nowrap;
        }

        .order-id {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--amber-dk);
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .customer-avatar {
            width: 2.2rem;
            height: 2.2rem;
            background: linear-gradient(135deg, var(--amber), #ef4444);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .customer-name {
            font-weight: 600;
            color: var(--char);
        }

        .total-amount {
            font-weight: 800;
            color: var(--amber-dk);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.8rem;
            border-radius: 60px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .status-pending { background: rgba(251, 191, 36, 0.15); color: #d97706; }
        .status-paid { background: rgba(59, 130, 246, 0.15); color: #2563eb; }
        .status-prepared { background: rgba(168, 85, 247, 0.15); color: #9333ea; }
        .status-shipped { background: rgba(99, 102, 241, 0.15); color: #4f46e5; }
        .status-delivered { background: rgba(16, 185, 129, 0.15); color: #059669; }
        .status-cancelled { background: rgba(239, 68, 68, 0.15); color: #dc2626; }

        .detail-link {
            color: var(--amber);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.3s;
        }
        .detail-link:hover {
            color: var(--amber-dk);
            transform: translateX(3px);
        }

        /* Quick Nav */
        .quick-nav-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .nav-card {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 1.8rem;
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s var(--transition-smooth);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
            border-color: var(--amber);
            background: white;
        }

        .nav-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            transition: all 0.3s;
        }

        .nav-card:hover .nav-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .nav-content { flex: 1; }
        .nav-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--char);
            margin-bottom: 0.25rem;
        }
        .nav-description {
            font-size: 0.85rem;
            color: var(--muted);
        }
        .nav-arrow {
            color: var(--amber);
            font-size: 1.5rem;
            transition: all 0.3s;
        }
        .nav-card:hover .nav-arrow { transform: translateX(8px); }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.5; }
        .empty-text { color: var(--muted); font-size: 1rem; }

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
        .footer-link {
            text-decoration: none;
            color: var(--char);
            font-weight: 500;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .footer-link:hover { color: var(--amber); transform: translateY(-2px); }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
            .quick-nav-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            main { padding: 1.5rem 1rem 3rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .quick-nav-grid { grid-template-columns: 1fr; }
            .admin-header { flex-direction: column; }
            .welcome-section h1 { font-size: 1.8rem; }
            .quick-actions { width: 100%; }
            .action-btn { flex: 1; justify-content: center; }
            .orders-table th, .orders-table td { padding: 0.8rem 1rem; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Ambient Orbs -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <!-- ═══════════════ NAVIGATION ═══════════════ -->
    <nav>
        <a href="/" class="logo">
            Food<span style="font-weight:300">App</span><span class="dot"></span>
            <span class="admin-badge">ADMIN</span>
        </a>

        <div class="nav-links">
            <a href="/admin/dashboard" class="nav-item nav-link">
                <i class="ri-dashboard-line"></i> Dashboard
            </a>
            <a href="/admin/menu" class="nav-item nav-link">
                <i class="ri-restaurant-line"></i> Menu
            </a>
            <a href="/admin/orders" class="nav-item nav-link">
                <i class="ri-shopping-bag-3-line"></i> Pesanan
            </a>
            <div class="nav-item user-chip">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}
                </div>
                <span>{{ auth()->user()->nama ?? 'Admin' }}</span>
            </div>

            <form action="/logout" method="POST" style="display: contents;">
                @csrf
                <button class="nav-item logout-btn">
                    <i class="ri-logout-box-r-line"></i> Sign out
                </button>
            </form>
        </div>

        <button class="mobile-toggle" onclick="document.getElementById('mobile-menu').classList.toggle('active')" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <div id="mobile-menu" class="mobile-menu">
            <a href="/admin/dashboard"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="/admin/menu"><i class="ri-restaurant-line"></i> Menu</a>
            <a href="/admin/orders"><i class="ri-shopping-bag-3-line"></i> Pesanan</a>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit"><i class="ri-logout-box-r-line"></i> Sign out</button>
            </form>
        </div>
    </nav>

    <!-- ═══════════════ MAIN CONTENT ═══════════════ -->
    <main>
        <div class="page-wrapper" id="page-wrapper">
            @yield('content')
        </div>
    </main>

            {{-- Recent Orders --}}
            <div class="orders-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ri-shopping-bag-line"></i>
                        Pesanan Terbaru
                    </div>
                    <a href="/admin/orders" class="view-all-link">
                        Lihat Semua
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </div>

                @if(empty($recentOrders))
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p class="empty-text">Belum ada pesanan</p>
                </div>
                @else
                <div class="table-wrapper">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="order-id">#{{ str_pad($order->id_pemesanan ?? '12345', 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="customer-cell">
                                        <div class="customer-avatar">
                                            {{ strtoupper(substr($order->user->nama ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="customer-name">{{ $order->user->nama ?? 'User' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="total-amount">Rp {{ number_format($order->total_harga ?? 75000, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $status = $order->status_pemesanan ?? 'pending';
                                        $statusConfig = [
                                            'pending' => ['class' => 'status-pending', 'icon' => 'ri-time-line', 'label' => 'Menunggu'],
                                            'paid' => ['class' => 'status-paid', 'icon' => 'ri-bank-card-line', 'label' => 'Dibayar'],
                                            'prepared' => ['class' => 'status-prepared', 'icon' => 'ri-restaurant-line', 'label' => 'Disiapkan'],
                                            'shipped' => ['class' => 'status-shipped', 'icon' => 'ri-truck-line', 'label' => 'Dikirim'],
                                            'delivered' => ['class' => 'status-delivered', 'icon' => 'ri-checkbox-circle-line', 'label' => 'Selesai'],
                                            'cancelled' => ['class' => 'status-cancelled', 'icon' => 'ri-close-circle-line', 'label' => 'Batal'],
                                        ][$status] ?? ['class' => 'status-pending', 'icon' => 'ri-information-line', 'label' => $status];
                                    @endphp
                                    <span class="status-badge {{ $statusConfig['class'] }}">
                                        <i class="{{ $statusConfig['icon'] }}"></i>
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color: var(--muted); font-size: 0.85rem;">
                                        {{ $order->created_at->diffForHumans() ?? 'Baru saja' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/orders/{{ $order->id_pemesanan ?? '1' }}" class="detail-link">
                                        Detail
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- Quick Navigation --}}
            <div class="quick-nav-grid">
                <a href="/admin/menu" class="nav-card">
                    <div class="nav-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                        <span>🍽️</span>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Kelola Menu</div>
                        <div class="nav-description">Tambah, edit, hapus menu</div>
                    </div>
                    <i class="ri-arrow-right-line nav-arrow"></i>
                </a>

                <a href="/admin/orders" class="nav-card">
                    <div class="nav-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <span>📋</span>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Kelola Pesanan</div>
                        <div class="nav-description">Proses & update status</div>
                    </div>
                    <i class="ri-arrow-right-line nav-arrow"></i>
                </a>
            </div>
        </div>
    </main>

    <!-- ═══════════════ FOOTER ═══════════════ -->
    <footer>
        <span>© 2026 FoodApp Admin — Savor the moment</span>
        <span style="opacity:0.4;">•</span>
        <span>✨ Crafted with passion</span>
        <span style="opacity:0.4;">•</span>
        <a href="/admin/dashboard" class="footer-link"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a href="#" class="footer-link"><i class="ri-lock-line"></i> Privacy</a>
    </footer>

    <!-- Scripts -->
    <script>
        (function(){
            // Page entrance animation
            const wrapper = document.getElementById('page-wrapper');
            if (wrapper) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => wrapper.classList.add('visible'));
                });
            }

            // Update date and time
            function updateDateTime() {
                const now = new Date();
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const dateStr = now.toLocaleDateString('id-ID', dateOptions);
                const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                
                const dateEl = document.getElementById('currentDate');
                const timeEl = document.getElementById('currentTime');
                if (dateEl) dateEl.textContent = dateStr;
                if (timeEl) timeEl.textContent = timeStr + ' WIB';
            }
            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Parallax untuk orb
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

            if (window.innerWidth > 768) {
                document.addEventListener('mousemove', handleParallax);
            }

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