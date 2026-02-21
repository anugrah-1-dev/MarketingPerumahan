<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marketing Perumahan - Temukan Rumah Impian Anda</title>
    <meta name="description" content="Lihat ketersediaan unit per blok, cek lokasi langsung di peta, dan pesan rumah impian Anda sekarang juga.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #EEEEEE;
            color: #393939;
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* ===== NAVIGATION ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        }

        .navbar-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .navbar-logo {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a1a;
            letter-spacing: -0.5px;
        }

        .navbar-logo span {
            color: #2563EB;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a {
            font-size: 15px;
            font-weight: 500;
            color: #393939;
            padding: 8px 20px;
            border-radius: 100px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: #2563EB;
            background: rgba(37, 99, 235, 0.06);
        }

        .nav-links a.active {
            color: #2563EB;
            background: rgba(37, 99, 235, 0.08);
        }

        .nav-cta {
            background: #2563EB !important;
            color: #fff !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.3);
        }

        .nav-cta:hover {
            background: #1d4ed8 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4) !important;
        }

        /* Mobile menu button */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            background: none;
            border: none;
        }

        .menu-toggle span {
            width: 24px;
            height: 2px;
            background: #393939;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 60px 80px;
            max-width: 1440px;
            margin: 0 auto;
            gap: 60px;
        }

        .hero-content {
            flex: 1;
            max-width: 540px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37, 99, 235, 0.08);
            color: #2563EB;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 100px;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .hero-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #2563EB;
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .hero-title {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.1;
            color: #1a1a1a;
            margin-bottom: 24px;
            letter-spacing: -1.5px;
        }

        .hero-title .highlight {
            color: #2563EB;
            position: relative;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 0;
            right: 0;
            height: 12px;
            background: rgba(37, 99, 235, 0.12);
            border-radius: 4px;
            z-index: -1;
        }

        .hero-description {
            font-size: 18px;
            font-weight: 400;
            color: #676767;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: #2563EB;
            color: #fff;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.4);
        }

        .btn-secondary {
            background: #fff;
            color: #393939;
            border: 2px solid #e0e0e0;
        }

        .btn-secondary:hover {
            border-color: #2563EB;
            color: #2563EB;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a1a;
            letter-spacing: -1px;
        }

        .stat-number span {
            color: #2563EB;
        }

        .stat-label {
            font-size: 14px;
            color: #888;
            font-weight: 500;
            margin-top: 2px;
        }

        /* Hero image */
        .hero-image {
            flex: 1;
            max-width: 620px;
            position: relative;
        }

        .hero-image-wrapper {
            width: 100%;
            aspect-ratio: 671 / 768;
            background: linear-gradient(135deg, #e8e8e8, #d4d4d4);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .hero-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 16px;
            font-weight: 500;
            gap: 12px;
        }

        .hero-image-placeholder svg {
            width: 48px;
            height: 48px;
            opacity: 0.5;
        }

        .hero-float-card {
            position: absolute;
            bottom: -20px;
            left: -30px;
            background: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            gap: 14px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .float-icon {
            width: 48px;
            height: 48px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .float-icon svg {
            width: 24px;
            height: 24px;
            fill: #2563EB;
        }

        .float-text strong {
            display: block;
            font-size: 15px;
            color: #1a1a1a;
        }

        .float-text small {
            font-size: 13px;
            color: #888;
        }

        /* ===== SECTION COMMON ===== */
        .section {
            padding: 100px 60px;
            max-width: 1440px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 60px;
        }

        .section-badge {
            display: inline-block;
            font-size: 13px;
            font-weight: 600;
            color: #2563EB;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 40px;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1.2;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }

        .section-subtitle {
            font-size: 17px;
            color: #676767;
            line-height: 1.7;
        }

        /* ===== UNIT TERSEDIA ===== */
        .units-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .unit-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .unit-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .unit-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #e0e7ff, #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93a3b8;
            font-size: 14px;
            font-weight: 500;
        }

        .unit-info {
            padding: 24px;
        }

        .unit-type {
            font-size: 12px;
            font-weight: 700;
            color: #2563EB;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .unit-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .unit-specs {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            color: #888;
            font-size: 13px;
        }

        .unit-specs span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .unit-price {
            font-size: 22px;
            font-weight: 800;
            color: #2563EB;
        }

        .unit-price small {
            font-size: 13px;
            font-weight: 500;
            color: #888;
        }

        .unit-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
        }

        .status-available {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .status-sold {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* ===== SITE PLAN ===== */
        .siteplan-wrapper {
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .siteplan-map {
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, #f0f4ff, #e8eeff);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93a3b8;
            font-size: 16px;
            font-weight: 500;
        }

        /* ===== SIMULASI KPR ===== */
        .simulasi-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        .simulasi-form {
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #393939;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            color: #393939;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563EB;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .simulasi-result {
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .result-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-label {
            font-size: 14px;
            color: #888;
        }

        .result-value {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .result-highlight {
            background: linear-gradient(135deg, #2563EB, #1d4ed8);
            border-radius: 16px;
            padding: 24px;
            margin-top: 24px;
            text-align: center;
        }

        .result-highlight p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .result-highlight strong {
            color: #fff;
            font-size: 28px;
            font-weight: 800;
        }

        /* ===== TENTANG KAMI ===== */
        .about-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .about-image {
            width: 100%;
            height: 450px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93a3b8;
            font-size: 16px;
            font-weight: 500;
        }

        .about-content h2 {
            font-size: 36px;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .about-content p {
            font-size: 16px;
            color: #676767;
            line-height: 1.8;
            margin-bottom: 24px;
        }

        .about-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 32px;
        }

        .about-feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 500;
            color: #393939;
        }

        .about-feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .about-feature-icon svg {
            width: 18px;
            height: 18px;
            fill: #2563EB;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #1a1a1a;
            color: #fff;
            padding: 60px;
            margin-top: 40px;
        }

        .footer-container {
            max-width: 1440px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
        }

        .footer-brand h3 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .footer-brand h3 span {
            color: #60a5fa;
        }

        .footer-brand p {
            font-size: 14px;
            color: #999;
            line-height: 1.7;
            max-width: 300px;
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            color: #ccc;
        }

        .footer-col a {
            display: block;
            font-size: 14px;
            color: #999;
            padding: 6px 0;
            transition: color 0.3s ease;
        }

        .footer-col a:hover {
            color: #60a5fa;
        }

        .footer-bottom {
            max-width: 1440px;
            margin: 0 auto;
            padding-top: 32px;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        /* ===== ANIMATIONS ===== */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero {
                flex-direction: column;
                padding: 120px 40px 60px;
                gap: 40px;
            }

            .hero-content {
                max-width: 100%;
                text-align: center;
            }

            .hero-title {
                font-size: 40px;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-image {
                max-width: 100%;
            }

            .hero-float-card {
                left: 20px;
                bottom: -10px;
            }

            .section {
                padding: 60px 40px;
            }

            .units-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .simulasi-wrapper,
            .about-wrapper {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 24px;
            }

            .nav-links {
                display: none;
                position: fixed;
                top: 72px;
                left: 0;
                right: 0;
                background: #fff;
                flex-direction: column;
                padding: 24px;
                gap: 4px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            }

            .nav-links.open {
                display: flex;
            }

            .nav-links a {
                width: 100%;
                text-align: center;
                padding: 12px 20px;
            }

            .menu-toggle {
                display: flex;
            }

            .hero {
                padding: 100px 24px 40px;
            }

            .hero-title {
                font-size: 32px;
            }

            .hero-description {
                font-size: 16px;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .hero-stats {
                gap: 24px;
            }

            .stat-number {
                font-size: 26px;
            }

            .section {
                padding: 50px 24px;
            }

            .section-title {
                font-size: 28px;
            }

            .units-grid {
                grid-template-columns: 1fr;
            }

            .siteplan-map {
                height: 300px;
            }

            .footer {
                padding: 40px 24px;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-logo">Griya<span>Asri</span></a>

            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#unit-tersedia">Unit Tersedia</a></li>
                <li><a href="#site-plan">Site Plan</a></li>
                <li><a href="#simulasi">Simulasi</a></li>
                <li><a href="#tentang-kami">Tentang Kami</a></li>
                <li><a href="#hubungi" class="nav-cta">Hubungi Kami</a></li>
            </ul>

            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- ===== HERO SECTION ===== -->
    <section class="hero" id="home">
        <div class="hero-content fade-in">
            <div class="hero-badge">Promo Terbatas</div>
            <h1 class="hero-title">
                Temukan <span class="highlight">Rumah Impian</span> Anda Sekarang
            </h1>
            <p class="hero-description">
                Lihat ketersediaan unit per blok, cek lokasi langsung di peta, dan pesan rumah impian Anda sekarang juga.
            </p>
            <div class="hero-actions">
                <a href="#unit-tersedia" class="btn btn-primary">
                    Lihat Unit
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#simulasi" class="btn btn-secondary">Simulasi KPR</a>
            </div>

            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">150<span>+</span></div>
                    <div class="stat-label">Unit Tersedia</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Tipe Rumah</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98<span>%</span></div>
                    <div class="stat-label">Kepuasan</div>
                </div>
            </div>
        </div>

        <div class="hero-image fade-in">
            <div class="hero-image-wrapper">
                <!-- Ganti placeholder ini dengan gambar asli nanti -->
                <div class="hero-image-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                    <span>Gambar Perumahan</span>
                </div>
            </div>

            <div class="hero-float-card">
                <div class="float-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <div class="float-text">
                    <strong>Lokasi Strategis</strong>
                    <small>Dekat pusat kota</small>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== UNIT TERSEDIA ===== -->
    <section class="section" id="unit-tersedia">
        <div class="section-header fade-in">
            <div class="section-badge">Unit Tersedia</div>
            <h2 class="section-title">Pilihan Rumah Terbaik untuk Keluarga Anda</h2>
            <p class="section-subtitle">Berbagai tipe rumah dengan desain modern dan harga terjangkau.</p>
        </div>

        <div class="units-grid">
            <div class="unit-card fade-in">
                <div class="unit-image">Gambar Tipe 36</div>
                <div class="unit-info">
                    <div class="unit-type">Tipe 36/72</div>
                    <div class="unit-name">Rumah Tipe Dahlia</div>
                    <div class="unit-specs">
                        <span>2 KT</span>
                        <span>1 KM</span>
                        <span>LT 72m²</span>
                    </div>
                    <div class="unit-price">Rp 350 Juta <small>/ unit</small></div>
                    <div class="unit-status status-available">✓ Tersedia</div>
                </div>
            </div>

            <div class="unit-card fade-in">
                <div class="unit-image">Gambar Tipe 45</div>
                <div class="unit-info">
                    <div class="unit-type">Tipe 45/90</div>
                    <div class="unit-name">Rumah Tipe Melati</div>
                    <div class="unit-specs">
                        <span>2 KT</span>
                        <span>1 KM</span>
                        <span>LT 90m²</span>
                    </div>
                    <div class="unit-price">Rp 480 Juta <small>/ unit</small></div>
                    <div class="unit-status status-available">✓ Tersedia</div>
                </div>
            </div>

            <div class="unit-card fade-in">
                <div class="unit-image">Gambar Tipe 60</div>
                <div class="unit-info">
                    <div class="unit-type">Tipe 60/120</div>
                    <div class="unit-name">Rumah Tipe Anggrek</div>
                    <div class="unit-specs">
                        <span>3 KT</span>
                        <span>2 KM</span>
                        <span>LT 120m²</span>
                    </div>
                    <div class="unit-price">Rp 650 Juta <small>/ unit</small></div>
                    <div class="unit-status status-available">✓ Tersedia</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SITE PLAN ===== -->
    <section class="section" id="site-plan">
        <div class="section-header fade-in">
            <div class="section-badge">Site Plan</div>
            <h2 class="section-title">Peta Lokasi Perumahan</h2>
            <p class="section-subtitle">Lihat denah lokasi dan ketersediaan unit pada setiap blok.</p>
        </div>

        <div class="siteplan-wrapper fade-in">
            <div class="siteplan-map">
                <span>Site Plan / Peta Lokasi (akan ditambahkan)</span>
            </div>
        </div>
    </section>

    <!-- ===== SIMULASI KPR ===== -->
    <section class="section" id="simulasi">
        <div class="section-header fade-in">
            <div class="section-badge">Simulasi</div>
            <h2 class="section-title">Simulasi Kredit KPR</h2>
            <p class="section-subtitle">Hitung estimasi cicilan bulanan sesuai kemampuan Anda.</p>
        </div>

        <div class="simulasi-wrapper">
            <div class="simulasi-form fade-in">
                <div class="form-group">
                    <label for="tipeRumah">Tipe Rumah</label>
                    <select id="tipeRumah" onchange="hitungKPR()">
                        <option value="350000000">Tipe Dahlia - Rp 350 Juta</option>
                        <option value="480000000">Tipe Melati - Rp 480 Juta</option>
                        <option value="650000000">Tipe Anggrek - Rp 650 Juta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dpPersen">Uang Muka (DP) %</label>
                    <input type="number" id="dpPersen" value="20" min="10" max="50" onchange="hitungKPR()">
                </div>
                <div class="form-group">
                    <label for="tenor">Tenor (Tahun)</label>
                    <select id="tenor" onchange="hitungKPR()">
                        <option value="10">10 Tahun</option>
                        <option value="15" selected>15 Tahun</option>
                        <option value="20">20 Tahun</option>
                        <option value="25">25 Tahun</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bunga">Suku Bunga (% / tahun)</label>
                    <input type="number" id="bunga" value="7.5" step="0.1" min="1" max="20" onchange="hitungKPR()">
                </div>
                <button class="btn btn-primary" onclick="hitungKPR()" style="width: 100%;">Hitung Cicilan</button>
            </div>

            <div class="simulasi-result fade-in">
                <div class="result-title">Hasil Simulasi</div>
                <div class="result-item">
                    <span class="result-label">Harga Rumah</span>
                    <span class="result-value" id="resHarga">Rp 350.000.000</span>
                </div>
                <div class="result-item">
                    <span class="result-label">Uang Muka (DP)</span>
                    <span class="result-value" id="resDP">Rp 70.000.000</span>
                </div>
                <div class="result-item">
                    <span class="result-label">Pinjaman Pokok</span>
                    <span class="result-value" id="resPinjaman">Rp 280.000.000</span>
                </div>
                <div class="result-item">
                    <span class="result-label">Tenor</span>
                    <span class="result-value" id="resTenor">15 Tahun</span>
                </div>
                <div class="result-item">
                    <span class="result-label">Suku Bunga</span>
                    <span class="result-value" id="resBunga">7.5% / tahun</span>
                </div>

                <div class="result-highlight">
                    <p>Estimasi Cicilan Bulanan</p>
                    <strong id="resCicilan">Rp 2.594.000</strong>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TENTANG KAMI ===== -->
    <section class="section" id="tentang-kami">
        <div class="about-wrapper">
            <div class="about-image fade-in">Gambar Developer / Perumahan</div>
            <div class="about-content fade-in">
                <div class="section-badge">Tentang Kami</div>
                <h2>Membangun Hunian Berkualitas untuk Keluarga Indonesia</h2>
                <p>
                    Kami berkomitmen menyediakan hunian berkualitas tinggi dengan harga yang terjangkau.
                    Dengan pengalaman bertahun-tahun di bidang properti, kami memahami kebutuhan
                    keluarga modern akan tempat tinggal yang nyaman, aman, dan strategis.
                </p>
                <p>
                    Setiap unit dirancang dengan perhatian terhadap detail, mulai dari pemilihan
                    material berkualitas hingga desain yang fungsional dan estetis.
                </p>

                <div class="about-features">
                    <div class="about-feature-item">
                        <div class="about-feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        </div>
                        Legalitas Lengkap
                    </div>
                    <div class="about-feature-item">
                        <div class="about-feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        </div>
                        Bangunan Kokoh
                    </div>
                    <div class="about-feature-item">
                        <div class="about-feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        </div>
                        Lokasi Strategis
                    </div>
                    <div class="about-feature-item">
                        <div class="about-feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        </div>
                        KPR Dibantu
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer" id="hubungi">
        <div class="footer-container">
            <div class="footer-brand">
                <h3>Griya<span>Asri</span></h3>
                <p>Mewujudkan impian keluarga Indonesia memiliki hunian berkualitas dengan harga terjangkau.</p>
            </div>
            <div class="footer-col">
                <h4>Menu</h4>
                <a href="#home">Home</a>
                <a href="#unit-tersedia">Unit Tersedia</a>
                <a href="#site-plan">Site Plan</a>
                <a href="#simulasi">Simulasi KPR</a>
            </div>
            <div class="footer-col">
                <h4>Informasi</h4>
                <a href="#tentang-kami">Tentang Kami</a>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Kebijakan Privasi</a>
            </div>
            <div class="footer-col">
                <h4>Kontak</h4>
                <a href="#">📞 +62 812-3456-7890</a>
                <a href="#">✉️ info@griyaasri.com</a>
                <a href="#">📍 Jl. Contoh No. 123</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 GriyaAsri. All rights reserved.
        </div>
    </footer>

    <script>
        // ===== Navbar Scroll Effect =====
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // ===== Mobile Menu Toggle =====
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('open');
        });

        // Close menu on link click
        document.querySelectorAll('.nav-links a').forEach(function(link) {
            link.addEventListener('click', function() {
                document.getElementById('navLinks').classList.remove('open');
            });
        });

        // ===== Active Link on Scroll =====
        window.addEventListener('scroll', function() {
            var sections = document.querySelectorAll('section[id]');
            var navLinks = document.querySelectorAll('.nav-links a:not(.nav-cta)');
            var scrollPos = window.scrollY + 120;

            sections.forEach(function(section) {
                if (scrollPos >= section.offsetTop && scrollPos < section.offsetTop + section.offsetHeight) {
                    navLinks.forEach(function(link) {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + section.id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });

        // ===== Fade In Animation =====
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in').forEach(function(el) {
            observer.observe(el);
        });

        // ===== Simulasi KPR =====
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function hitungKPR() {
            var harga = parseInt(document.getElementById('tipeRumah').value);
            var dpPersen = parseFloat(document.getElementById('dpPersen').value);
            var tenor = parseInt(document.getElementById('tenor').value);
            var bunga = parseFloat(document.getElementById('bunga').value);

            var dp = harga * (dpPersen / 100);
            var pinjaman = harga - dp;
            var bungaBulanan = bunga / 100 / 12;
            var totalBulan = tenor * 12;

            var cicilan = pinjaman * (bungaBulanan * Math.pow(1 + bungaBulanan, totalBulan)) / (Math.pow(1 + bungaBulanan, totalBulan) - 1);

            document.getElementById('resHarga').textContent = formatRupiah(harga);
            document.getElementById('resDP').textContent = formatRupiah(Math.round(dp));
            document.getElementById('resPinjaman').textContent = formatRupiah(Math.round(pinjaman));
            document.getElementById('resTenor').textContent = tenor + ' Tahun';
            document.getElementById('resBunga').textContent = bunga + '% / tahun';
            document.getElementById('resCicilan').textContent = formatRupiah(Math.round(cicilan));
        }

        // Initial calculation
        hitungKPR();
    </script>
</body>
</html>
