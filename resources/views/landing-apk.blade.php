<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unduh Aplikasi Nasabah | E-Teller Bank Mini Sekolah</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-deep: #1e3a8a;
            --primary-light: #eff6ff;
            --accent-cyan: #38bdf8;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-page: #f8fafc;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-page);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #0f172a;
        }

        .brand-badge {
            background: #dbeafe;
            color: #1e40af;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 999px;
            letter-spacing: 0.5px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-nav-portal {
            background: #ffffff;
            color: var(--primary-blue);
            border: 1.5px solid #bfdbfe;
            padding: 0.55rem 1.15rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-nav-portal:hover {
            background: var(--primary-light);
            border-color: var(--primary-blue);
            color: var(--primary-dark);
        }

        .btn-nav-download {
            background: var(--primary-blue);
            color: #ffffff;
            border: none;
            padding: 0.55rem 1.15rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-nav-download:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* HERO SECTION */
        .hero-section {
            background: linear-gradient(145deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);
            color: #ffffff;
            padding: 4.5rem 2rem 5.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero-curve {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .hero-curve svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 60px;
        }

        .hero-curve .shape-fill {
            fill: var(--bg-page);
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 3rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-tag {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 1.25rem;
            letter-spacing: 0.5px;
        }

        .hero-title {
            font-size: 2.65rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            letter-spacing: -0.5px;
        }

        .hero-desc {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #dbeafe;
            margin-bottom: 2rem;
            max-width: 520px;
        }

        .hero-btn-group {
            display: flex;
            gap: 0.85rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .btn-hero-primary {
            background: #ffffff;
            color: var(--primary-blue);
            padding: 0.85rem 1.75rem;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.25);
            color: var(--primary-dark);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            padding: 0.85rem 1.75rem;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: #ffffff;
            color: #ffffff;
        }

        .hero-meta-note {
            font-size: 0.82rem;
            color: #bfdbfe;
        }

        /* PHONE MOCKUP */
        .mockup-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .phone-frame {
            width: 320px;
            background: #0f172a;
            border-radius: 42px;
            padding: 12px;
            box-shadow: 0 25px 60px -10px rgba(15, 23, 42, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.15);
            position: relative;
        }

        .phone-notch {
            width: 120px;
            height: 18px;
            background: #0f172a;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .phone-screen {
            background: #f8fafc;
            border-radius: 32px;
            padding: 1.5rem 1rem 1rem;
            overflow: hidden;
            font-size: 0.8rem;
            color: var(--text-main);
        }

        .mock-app-header {
            background: #2563eb;
            color: #ffffff;
            padding: 0.85rem;
            border-radius: 14px;
            margin-bottom: 0.85rem;
            text-align: center;
        }

        .mock-app-header h5 {
            font-size: 0.88rem;
            font-weight: 800;
            margin: 0;
        }

        .mock-app-header p {
            font-size: 0.72rem;
            color: #dbeafe;
            margin: 2px 0 0;
        }

        .mock-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.85rem;
            margin-bottom: 0.65rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .mock-card-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mock-status-pill {
            background: #dcfce7;
            color: #15803d;
            font-size: 0.62rem;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 999px;
        }

        .mock-card-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: #2563eb;
            margin-top: 4px;
        }

        .mock-btn-qr {
            background: #2563eb;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            padding: 0.55rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 0.65rem;
        }

        .mock-mutasi-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.55rem 0.75rem;
            margin-bottom: 0.45rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mock-mutasi-name {
            font-weight: 800;
            font-size: 0.72rem;
        }

        .mock-mutasi-date {
            font-size: 0.62rem;
            color: #94a3b8;
        }

        .mock-mutasi-val {
            font-weight: 800;
            font-size: 0.75rem;
        }

        /* SECTION CONTAINER */
        .section-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4.5rem 2rem;
        }

        .section-header {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 3.5rem;
        }

        .section-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 0.75rem;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }

        .section-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        /* QR DOWNLOAD SECTION */
        .qr-card-container {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 28px;
            box-shadow: 0 15px 40px -10px rgba(15, 23, 42, 0.08);
            max-width: 820px;
            margin: 0 auto;
            padding: 3rem 2.5rem;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 2.5rem;
            align-items: center;
        }

        .qr-img-box {
            background: #ffffff;
            border: 2px dashed #bfdbfe;
            border-radius: 20px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.06);
        }

        .qr-img-box img {
            width: 200px;
            height: 200px;
            display: block;
            border-radius: 10px;
        }

        .qr-caption {
            margin-top: 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-blue);
            text-align: center;
        }

        .qr-content h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }

        .qr-content p {
            font-size: 0.92rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
            line-height: 1.6;
        }

        .qr-btn-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-qr-download {
            background: var(--primary-blue);
            color: #ffffff;
            padding: 0.8rem 1.6rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }

        .btn-qr-download:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-qr-secondary {
            background: #f1f5f9;
            color: #334155;
            padding: 0.8rem 1.4rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-qr-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* FEATURES GRID */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem 1.75rem;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
            transition: all 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -5px rgba(37, 99, 235, 0.09);
            border-color: #bfdbfe;
        }

        .feature-badge {
            background: #eff6ff;
            color: #2563eb;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 999px;
            display: inline-block;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        .feature-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.55;
        }

        /* INSTALL GUIDE */
        .guide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .guide-step-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 1.75rem;
            position: relative;
        }

        .step-number {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #2563eb;
            color: #ffffff;
            font-weight: 800;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }

        .step-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* BANNER ALERT */
        .alert-info-banner {
            max-width: 820px;
            margin: 1.5rem auto 0;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            color: #1e40af;
            font-size: 0.88rem;
            text-align: center;
        }

        /* FOOTER */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 2.5rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .footer-links {
            margin-top: 0.75rem;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }

        .footer-links a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 700;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2.5rem;
            }

            .hero-desc {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-btn-group {
                justify-content: center;
            }

            .qr-card-container {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 2rem 1.5rem;
            }

            .qr-img-box {
                margin: 0 auto;
            }

            .qr-btn-row {
                justify-content: center;
            }

            .hero-title {
                font-size: 2.15rem;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="brand-box">
                <span class="brand-title">E-Teller Bank Mini</span>
                <span class="brand-badge">Aplikasi Siswa</span>
            </a>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-nav-portal">Masuk Web Portal</a>
                <a href="{{ route('app.download') }}" class="btn-nav-download">Unduh APK</a>
            </div>
        </div>
    </header>

    @if(session('info'))
        <div class="alert-info-banner">
            {{ session('info') }}
        </div>
    @endif

    <!-- HERO SECTION DENGAN WAVE GRADIENT BIRU -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-text">
                <span class="hero-tag">APLIKASI ANDROID RESMI</span>
                <h1 class="hero-title">Tabungan Sekolah dalam Genggaman</h1>
                <p class="hero-desc">
                    Layanan perbankan digital untuk siswa penabung E-Teller Bank Mini Sekolah. Cek saldo real-time, tampilkan kode QR kartu digital, dan pantau seluruh mutasi tabungan secara mandiri dari ponsel Android Anda.
                </p>
                <div class="hero-btn-group">
                    <a href="{{ route('app.download') }}" class="btn-hero-primary">
                        Unduh File APK
                    </a>
                    <a href="#qr-download" class="btn-hero-secondary">
                        Pindai Kode QR
                    </a>
                </div>
                <div class="hero-meta-note">
                    Versi 1.0.0 &bull; Android 8.0 ke atas &bull; Bebas Iklan &amp; Aman
                </div>
            </div>

            <!-- MOCKUP HANDPHONE DENGAN TAMPILAN APLIKASI NASABAH -->
            <div class="mockup-wrapper">
                <div class="phone-frame">
                    <div class="phone-notch"></div>
                    <div class="phone-screen">
                        <div class="mock-app-header">
                            <h5>Portal Nasabah Siswa</h5>
                            <p>E-Teller Bank Mini Sekolah</p>
                        </div>

                        <div class="mock-card">
                            <div class="mock-card-label">
                                <span>No. Rekening Nasabah</span>
                                <span class="mock-status-pill">AKTIF</span>
                            </div>
                            <div class="mock-card-val">10001003</div>
                            <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Kelas: X AKL 1 (NIS: 8888)</div>
                        </div>

                        <div class="mock-card">
                            <div class="mock-card-label">
                                <span>Saldo Tabungan Real-Time</span>
                            </div>
                            <div class="mock-card-val" style="color: #16a34a;">Rp 42.000</div>
                        </div>

                        <div class="mock-btn-qr">
                            Tampilkan Kode QR Kartu Digital
                        </div>

                        <div style="font-size: 0.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">
                            Riwayat Mutasi Terkini
                        </div>

                        <div class="mock-mutasi-item">
                            <div>
                                <div class="mock-mutasi-name" style="color: #16a34a;">SETORAN</div>
                                <div class="mock-mutasi-date">Setoran Tunai Teller</div>
                            </div>
                            <div class="mock-mutasi-val" style="color: #16a34a;">+ Rp 10.000</div>
                        </div>

                        <div class="mock-mutasi-item">
                            <div>
                                <div class="mock-mutasi-name" style="color: #dc2626;">PENARIKAN</div>
                                <div class="mock-mutasi-date">Penarikan Tunai Teller</div>
                            </div>
                            <div class="mock-mutasi-val" style="color: #dc2626;">- Rp 6.000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Divider Bottom -->
        <div class="hero-curve">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- SECTION PINDAI QR CODE UNTUK DOWNLOAD LANGSUNG DI HP -->
    <section class="section-wrap" id="qr-download">
        <div class="section-header">
            <span class="section-badge">UNDUH INSTAN VIA HP</span>
            <h2 class="section-title">Pindai Kode QR untuk Mengunduh</h2>
            <p class="section-desc">
                Cukup buka kamera ponsel Android Anda, arahkan ke kode QR di bawah ini, dan berkas APK aplikasi nasabah akan langsung terunduh secara otomatis.
            </p>
        </div>

        <div class="qr-card-container">
            <div class="qr-img-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=12&data={{ urlencode(route('app.download')) }}" alt="Kode QR Unduh APK">
                <div class="qr-caption">Pindai dengan Kamera Ponsel</div>
            </div>

            <div class="qr-content">
                <h3>Unduh Aplikasi Langsung ke Ponsel</h3>
                <p>
                    Aplikasi ini dirancang khusus untuk siswa penabung E-Teller Bank Mini Sekolah. Tanpa perlu kabel data atau flashdisk, Anda dapat mengunduh berkas instalasi APK langsung ke ponsel Android melalui QR Code ini.
                </p>
                <div class="qr-btn-row">
                    <a href="{{ route('app.download') }}" class="btn-qr-download">
                        Unduh Berkas APK Langsung
                    </a>
                    <a href="{{ route('login') }}" class="btn-qr-secondary">
                        Masuk via Web Portal
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR UTAMA APLIKASI -->
    <section class="section-wrap" style="background: #ffffff; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="section-header">
            <span class="section-badge">KEMUDAHAN LAYANAN</span>
            <h2 class="section-title">Fitur Utama Aplikasi Nasabah</h2>
            <p class="section-desc">
                Seluruh kebutuhan transaksi tabungan sekolah tersedia secara terintegrasi dengan loket teller.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-badge">REAL-TIME</span>
                <h3 class="feature-title">Saldo Tabungan Real-Time</h3>
                <p class="feature-desc">
                    Pantau saldo tabungan aktif Anda secara langsung. Setiap transaksi setoran atau penarikan di loket otomatis terbarui tanpa jeda.
                </p>
            </div>

            <div class="feature-card">
                <span class="feature-badge">KARTU DIGITAL</span>
                <h3 class="feature-title">Kode QR Kartu Digital</h3>
                <p class="feature-desc">
                    Tunjukkan kode QR akun di layar ponsel untuk dipindai oleh teller loket saat ingin menabung atau menarik saldo tanpa kartu fisik.
                </p>
            </div>

            <div class="feature-card">
                <span class="feature-badge">RIWAYAT MUTASI</span>
                <h3 class="feature-title">Filter Riwayat Transaksi</h3>
                <p class="feature-desc">
                    Kategori transaksi tersusun rapi antara Setoran (Nabung) dan Penarikan, lengkap dengan pencarian nomor transaksi dan tanggal.
                </p>
            </div>

            <div class="feature-card">
                <span class="feature-badge">KEAMANAN</span>
                <h3 class="feature-title">Keamanan PIN 6-Digit</h3>
                <p class="feature-desc">
                    Setiap penarikan dana tabungan di loket wajib diverifikasi menggunakan PIN 6 angka rahasia pilihan Anda.
                </p>
            </div>
        </div>
    </section>

    <!-- PANDUAN PEMASANGAN APK -->
    <section class="section-wrap">
        <div class="section-header">
            <span class="section-badge">CARA INSTALASI</span>
            <h2 class="section-title">Panduan Singkat Pemasangan</h2>
            <p class="section-desc">
                Ikuti 4 langkah praktis berikut untuk memasang aplikasi E-Teller Nasabah di ponsel Android Anda.
            </p>
        </div>

        <div class="guide-grid">
            <div class="guide-step-card">
                <div class="step-number">1</div>
                <h4 class="step-title">Unduh File APK</h4>
                <p class="step-desc">
                    Klik tombol unduh atau pindai kode QR di atas untuk mengunduh berkas <code>eteller-nasabah.apk</code>.
                </p>
            </div>

            <div class="guide-step-card">
                <div class="step-number">2</div>
                <h4 class="step-title">Buka Berkas Unduhan</h4>
                <p class="step-desc">
                    Buka panel notifikasi ponsel atau cari file APK di dalam folder Download ponsel Android Anda.
                </p>
            </div>

            <div class="guide-step-card">
                <div class="step-number">3</div>
                <h4 class="step-title">Izinkan Pemasangan</h4>
                <p class="step-desc">
                    Pilih opsi Instal. Jika muncul konfirmasi keamanan, pilih izinkan pemasangan aplikasi dari sumber ini.
                </p>
            </div>

            <div class="guide-step-card">
                <div class="step-number">4</div>
                <h4 class="step-title">Masuk ke Akun Anda</h4>
                <p class="step-desc">
                    Buka aplikasi dan masuk menggunakan NISN / Username serta Password akun siswa Anda yang terdaftar.
                </p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div>E-Teller Bank Mini Sekolah &bull; UKK RPL 2026/2027</div>
        <div class="footer-links">
            <a href="{{ route('login') }}">Masuk ke Web Portal</a>
            <a href="{{ route('app.download') }}">Unduh Berkas APK</a>
            <a href="#">Kembali ke Atas</a>
        </div>
    </footer>

</body>
</html>

