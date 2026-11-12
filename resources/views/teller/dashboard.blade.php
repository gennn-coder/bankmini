<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Teller | E-Teller Bank Mini</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_bank_mini.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #f4f7fe;
            --bg-surface: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary-600: #2563eb;
            --primary-gradient: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            --emerald-600: #16a34a;
            --rose-600: #dc2626;
        }
        *, *::before, *::after {
            box-sizing: border-box !important;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-primary);
            color: var(--text-main);
            margin: 0;
            overflow-x: hidden;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 950;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.95rem 2rem;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* HORIZONTAL NAVIGATION TABS (STICKY SUB-BAR - 100% CONSISTENT WITH ADMIN & REFERENCE) */
        .nav-tabs-bar {
            background: #ffffff;
            border-bottom: 2px solid var(--border-color);
            padding: 0.55rem 1.25rem;
            width: 100%;
            position: sticky;
            top: 67px;
            z-index: 900;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 90px;
            opacity: 1;
            overflow: visible;
        }
        .nav-tabs-bar.collapsed {
            max-height: 0 !important;
            opacity: 0 !important;
            padding: 0 !important;
            border: none !important;
            margin: 0 !important;
            overflow: hidden !important;
            pointer-events: none !important;
            box-shadow: none !important;
            display: none !important;
        }

        .btn-toggle-nav {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 0.42rem 0.85rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            user-select: none;
            font-family: inherit;
        }
        .btn-toggle-nav:hover {
            background: #e2e8f0;
            color: #0f172a;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .btn-toggle-nav.nav-is-collapsed {
            background: #eff6ff;
            color: #2563eb;
            border-color: #93c5fd;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.18);
        }
        .btn-toggle-nav.nav-is-collapsed:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }

        /* USER PROFILE DROPDOWN MENU */
        .user-menu-container {
            position: relative;
            display: inline-block;
        }
        .btn-user-profile-trigger {
            background: transparent;
            border: 1px solid transparent;
            padding: 0.35rem 0.65rem;
            border-radius: 10px;
            font-size: 0.92rem;
            color: #334155;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.2s ease;
            white-space: nowrap;
            font-family: inherit;
        }
        .btn-user-profile-trigger:hover,
        .btn-user-profile-trigger.active {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .user-name-wrapper {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .user-greeting-name {
            white-space: nowrap;
        }
        .btn-user-profile-trigger .chevron-user-icon {
            transition: transform 0.2s ease;
            color: #64748b;
            flex-shrink: 0;
        }
        .btn-user-profile-trigger.active .chevron-user-icon {
            transform: rotate(180deg);
        }
        .user-dropdown-popover {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 290px;
            max-width: calc(100vw - 24px);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(15, 23, 42, 0.08);
            z-index: 1050;
            overflow: hidden;
            animation: dropdownFadeIn 0.15s ease-out;
        }
        .user-dropdown-popover.show {
            display: block;
        }
        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .user-dropdown-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.15rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .user-dropdown-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .user-dropdown-name {
            font-weight: 800;
            font-size: 0.92rem;
            color: #0f172a;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-dropdown-role {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 2px;
        }
        .user-dropdown-body {
            padding: 0.5rem;
        }
        .btn-dropdown-action {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0.85rem;
            border-radius: 10px;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background 0.15s ease;
            text-align: left;
            font-family: inherit;
        }
        .btn-dropdown-action:hover {
            background: #f1f5f9;
        }
        .btn-dropdown-action-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-quick-collapse {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            padding: 0.38rem 0.65rem;
            border-radius: 10px;
            margin-left: auto;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-family: inherit;
            flex-shrink: 0;
        }
        .btn-quick-collapse:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }
        .nav-tabs {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 0.35rem;
            width: 100%;
            max-width: 1540px;
            margin: 0 auto;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        .nav-tabs::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 0.52rem 0.8rem;
            font-size: 0.83rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            border-radius: 12px;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            font-family: inherit;
            flex-shrink: 0;
        }
        .tab-btn:hover {
            color: var(--primary-600);
            background: #eff6ff;
        }
        .tab-btn.active {
            background: var(--primary-600) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28) !important;
            font-weight: 700;
        }

        .container {
            max-width: 1540px;
            width: 100%;
            margin: 1.75rem auto 3.5rem;
            padding: 0 1.75rem;
            box-sizing: border-box;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            max-width: 100%;
            overflow: hidden;
        }
        .card h4 { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin: 0 0 0.5rem; letter-spacing: 0.5px; }
        .card .value { font-size: 1.75rem; font-weight: 800; color: var(--primary-600); }
        .btn-logout {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        .btn-primary {
            background: var(--primary-gradient);
            color: #fff;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }
        .btn-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        /* Status Layanan Toggle (Tanpa Icon, Bersih & Minimalis) */
        .status-toggle-wrapper {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            gap: 3px;
        }
        .btn-status-option {
            border: none;
            background: transparent;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 7px;
            cursor: pointer;
            color: #64748b;
            transition: all 0.2s ease;
        }
        .btn-status-option:hover {
            color: #1e293b;
        }
        .btn-status-option.active-buka {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        }
        .btn-status-option.active-istirahat {
            background: #d97706;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.25);
        }
        .btn-status-option.active-tutup {
            background: #dc2626;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
        }
        .tab-content { display: none; width: 100%; }
        .tab-content.active { display: block; }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            margin-top: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            background: var(--bg-surface);
        }
        th, td {
            padding: 0.9rem 1.2rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
            white-space: nowrap;
        }
        th { background: #eff6ff; color: var(--primary-600); font-weight: 700; }
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .form-group { margin-bottom: 1.25rem; width: 100%; }
        .form-group label { display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b; }
        .form-control {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        .search-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 18px;
            padding: 1.5rem;
            margin-bottom: 1.75rem;
            width: 100%;
            max-width: 100%;
        }
        .search-flex {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            width: 100%;
        }
        .search-flex .form-control {
            flex: 1 1 260px;
            min-width: 0;
        }
        .nasabah-info-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            margin-top: 1.25rem;
            display: none;
            width: 100%;
            max-width: 100%;
            animation: fadeIn 0.3s ease;
        }
        .nasabah-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            font-size: 0.9rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes scanLineAnim {
            0% { top: 6%; opacity: 0.85; }
            50% { top: 90%; opacity: 1; }
            100% { top: 6%; opacity: 0.85; }
        }
        @keyframes spinDouble {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .modal-qr-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-qr-overlay.active {
            display: flex;
        }
        .modal-qr-content {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.25s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .scanner-viewport-box {
            position: relative !important;
            background: #0f172a !important;
            border-radius: 14px !important;
            overflow: hidden !important;
            width: 100% !important;
            height: 270px !important;
            min-height: 270px !important;
            max-height: 270px !important;
            flex-shrink: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box !important;
        }
        #reader {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 270px !important;
            max-height: 270px !important;
            border: none !important;
            background: #0f172a !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }
        #reader video {
            width: 100% !important;
            height: 100% !important;
            min-height: 270px !important;
            max-height: 270px !important;
            border-radius: 14px !important;
            object-fit: cover !important;
            display: block !important;
        }
        #reader__scan_region {
            width: 100% !important;
            height: 100% !important;
            min-height: 270px !important;
            max-height: 270px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: transparent !important;
            border: none !important;
        }
        #reader__scan_region video {
            width: 100% !important;
            height: 100% !important;
            min-height: 270px !important;
            max-height: 270px !important;
            object-fit: cover !important;
        }
        #reader img {
            display: none !important;
        }
        #reader__header_message {
            display: none !important;
        }
        #reader__dashboard {
            display: none !important;
        }
        .scanner-laser {
            position: absolute;
            left: 8%;
            right: 8%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #22c55e, #10b981, transparent);
            box-shadow: 0 0 12px #22c55e;
            animation: scanLineAnim 2s infinite ease-in-out;
            pointer-events: none;
            z-index: 10;
        }
        .scanner-guide-corners {
            position: absolute;
            width: 200px;
            height: 200px;
            border: 2px dashed rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            pointer-events: none;
            z-index: 9;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.35);
        }
        .camera-mirrored video,
        .camera-mirrored canvas {
            transform: scaleX(-1) !important;
        }
        @media (max-width: 1366px) {
            .nav-tabs-bar {
                padding: 0.45rem 1rem;
            }
            .nav-tabs {
                gap: 0.25rem;
            }
            .tab-btn {
                padding: 0.42rem 0.65rem;
                font-size: 0.8rem;
                gap: 0.25rem;
            }
            .btn-quick-collapse {
                padding: 0.35rem 0.55rem;
                font-size: 0.75rem;
            }
        }
        @media (max-width: 1200px) {
            .nav-tabs-bar {
                padding: 0.4rem 0.75rem;
            }
            .nav-tabs {
                gap: 0.2rem;
            }
            .tab-btn {
                padding: 0.38rem 0.52rem;
                font-size: 0.76rem;
                gap: 0.2rem;
            }
            .btn-quick-collapse {
                padding: 0.32rem 0.48rem;
                font-size: 0.72rem;
            }
        }
        @media (max-width: 992px) {
            .navbar {
                padding: 0.75rem 1.25rem;
                gap: 0.75rem;
            }
            .nav-tabs-bar {
                padding: 0.35rem 0.5rem;
                top: 65px;
            }
            .tab-btn {
                padding: 0.35rem 0.48rem;
                font-size: 0.74rem;
            }
            .container {
                padding: 0 1rem;
            }
            .user-name-wrapper {
                max-width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .user-greeting-name {
                max-width: 130px;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
        @media (max-width: 768px) {
            .navbar {
                padding: 0.65rem 1rem;
            }
            .user-greeting-prefix {
                display: none;
            }
            .user-greeting-name {
                max-width: 110px;
            }
            .btn-toggle-nav {
                padding: 0.35rem 0.65rem;
                font-size: 0.78rem;
            }
            .btn-logout {
                padding: 0.35rem 0.75rem !important;
                font-size: 0.78rem !important;
            }
            .profile-tab-header {
                flex-direction: column;
                align-items: stretch !important;
                text-align: left;
                padding: 1rem !important;
            }
            .profile-tab-header button {
                width: 100%;
                justify-content: center;
            }
        }
        @media (max-width: 576px) {
            .navbar {
                padding: 0.55rem 0.75rem;
            }
            #btnToggleNavText {
                display: none;
            }
            .btn-toggle-nav {
                padding: 0.35rem 0.55rem;
            }
            .status-toggle-wrapper {
                padding: 2px;
            }
            .btn-status-option {
                padding: 0.3rem 0.6rem !important;
                font-size: 0.75rem !important;
            }
            .user-greeting-name {
                max-width: 85px;
            }
            .user-dropdown-popover {
                width: calc(100vw - 20px);
                max-width: 290px;
                right: -6px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <img src="{{ asset('images/logo_bank_mini.png') }}" alt="Logo Bank Mini" style="height: 44px; width: 44px; object-fit: contain; border-radius: 10px; border: 1px solid var(--border-color); background: #fff; padding: 2px; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);">
            <div>
                <h2 style="margin:0; font-size: 1.25rem; font-weight: 800; line-height: 1.2;">Teller</h2>
                <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap;">
            <button type="button" id="btnToggleNav" onclick="toggleNavTabs()" class="btn-toggle-nav" title="Kecilkan / Sembunyikan Navigasi Menu">
                <span id="btnToggleNavIcon">▲</span>
                <span id="btnToggleNavText">Kecilkan Menu</span>
            </button>
            <div class="status-toggle-wrapper">
                <button type="button" onclick="ubahStatusLayanan('buka')" id="btn-status-buka" class="btn-status-option {{ $statusLayanan === 'buka' ? 'active-buka' : '' }}">Buka</button>
                <button type="button" onclick="ubahStatusLayanan('istirahat')" id="btn-status-istirahat" class="btn-status-option {{ $statusLayanan === 'istirahat' ? 'active-istirahat' : '' }}">Istirahat</button>
                <button type="button" onclick="ubahStatusLayanan('tutup')" id="btn-status-tutup" class="btn-status-option {{ $statusLayanan === 'tutup' ? 'active-tutup' : '' }}">Tutup</button>
            </div>
            <!-- USER PROFILE DROPDOWN MENU -->
            <div class="user-menu-container" id="userMenuContainer">
                <button type="button" id="btnUserMenu" onclick="toggleUserMenu(event)" class="btn-user-profile-trigger" title="Klik untuk membuka menu profil & akun">
                    <span class="user-name-wrapper">
                        <span class="user-greeting-prefix">Halo, </span>
                        <strong class="user-greeting-name">{{ $user->nama_petugas }}</strong>
                    </span>
                    <svg class="chevron-user-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>

                <!-- DROPDOWN POPOVER -->
                <div class="user-dropdown-popover" id="userDropdownPopover">
                    <div class="user-dropdown-header">
                        <div class="user-dropdown-avatar">
                            {{ strtoupper(substr($user->nama_petugas, 0, 1)) }}
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div class="user-dropdown-name" title="{{ $user->nama_petugas }}">{{ $user->nama_petugas }}</div>
                            <div class="user-dropdown-role">Petugas Teller &bull; {{ '@' . $user->username }}</div>
                        </div>
                    </div>
                    <div class="user-dropdown-body">
                        <button type="button" onclick="bukaHalamanProfil()" class="btn-dropdown-action">
                            <span class="btn-dropdown-action-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <div style="text-align: left; flex: 1;">
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">Masuk ke Profil / Edit Profil</div>
                                <div style="font-size: 0.74rem; color: #64748b;">Kelola identitas, username & sandi</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    </div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout" onclick="try{sessionStorage.removeItem('teller_active_tab');localStorage.removeItem('teller_active_tab');}catch(e){}">Keluar</button>
            </form>
        </div>
    </nav>

    <!-- HORIZONTAL NAV TABS (STICKY FULL-WIDTH SUB-BAR) -->
    <div class="nav-tabs-bar" id="navTabsBar">
        <div class="nav-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('tab-dashboard')">Dashboard</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-aktivasi')">Aktivasi</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-setoran')">Setoran</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-penarikan')">Penarikan</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-perjanjian')">
                Perjanjian
                @if(isset($totalPerjanjianPending) && $totalPerjanjianPending > 0)
                    <span style="background: #f59e0b; color: #fff; padding: 2px 7px; border-radius: 999px; font-size: 0.72rem; margin-left: 5px; font-weight: 700;">{{ $totalPerjanjianPending }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-reset-pin')">Reset PIN</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-laporan')">
                Penutup Kas
                @if($laporanHariIni && $laporanHariIni->status_approval === 'rejected')
                    <span style="background: #dc2626; color: #fff; padding: 2px 7px; border-radius: 999px; font-size: 0.72rem; margin-left: 5px; font-weight: 700;">Revisi</span>
                @endif
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-riwayat')">Riwayat Transaksi</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-bulanan')">Laporan Bulanan</button>
            <button type="button" onclick="toggleNavTabs()" class="btn-quick-collapse" title="Sembunyikan Navigasi Menu">
                <span>▲</span> Sembunyikan
            </button>
        </div>
    </div>

    <div class="container">
        @if(session('transaksi_sukses'))
        @php $ts = session('transaksi_sukses'); @endphp
        <div id="modalTransaksiSukses" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">
            <div style="background: #ffffff; border-radius: 24px; max-width: 500px; width: 100%; box-shadow: 0 25px 60px rgba(0,0,0,0.35); overflow: hidden;">
                <!-- Header Modal -->
                <div style="background: {{ $ts['jenis_trans'] === 'setoran' ? 'linear-gradient(135deg, #16a34a, #22c55e)' : 'linear-gradient(135deg, #dc2626, #ef4444)' }}; padding: 1.5rem 1.5rem; text-align: center; color: #ffffff;">
                    <h3 style="margin: 0; font-size: 1.35rem; font-weight: 800; letter-spacing: 0.5px;">{{ $ts['judul'] ?? 'Transaksi Berhasil!' }}</h3>
                    <p style="margin: 6px 0 0 0; font-size: 0.85rem; opacity: 0.95;">Bukti transaksi telah dicatat ke kas & jurnal akuntansi</p>
                </div>

                <!-- Body Modal Rincian Struk -->
                <div style="padding: 1.5rem 1.75rem; background: #fafafa;">
                    <div style="background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 1.25rem; font-size: 0.9rem; line-height: 1.8;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem;">
                            <span style="color: #64748b;">No. Transaksi</span>
                            <strong style="font-family: monospace; font-size: 0.95rem; color: #0f172a;">{{ $ts['no_transaksi'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="color: #64748b;">Waktu</span>
                            <strong>{{ $ts['waktu'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="color: #64748b;">Nama Penabung</span>
                            <strong>{{ $ts['nama_siswa'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="color: #64748b;">No. Rekening</span>
                            <strong style="font-family: monospace;">{{ $ts['no_rekening'] }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                            <span style="color: #64748b;">Jenis Transaksi</span>
                            <strong style="text-transform: uppercase; color: {{ $ts['jenis_trans'] === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                {{ $ts['jenis_trans'] }}
                            </strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; border-top: 1px dashed #e2e8f0; padding-top: 0.5rem;">
                            <span style="color: #0f172a; font-weight: 700;">Nominal Transaksi</span>
                            <strong style="font-size: 1.2rem; color: {{ $ts['jenis_trans'] === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                Rp {{ number_format($ts['nominal'], 0, ',', '.') }}
                            </strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; background: #f1f5f9; padding: 0.5rem 0.75rem; border-radius: 8px; margin-top: 0.5rem;">
                            <span style="color: #475569; font-weight: 600;">Saldo Akhir Saat Ini</span>
                            <strong style="color: #0f172a; font-size: 1.05rem;">Rp {{ number_format($ts['saldo_akhir'], 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                        <button type="button" onclick="cetakStruk('{{ $ts['no_transaksi'] }}', '{{ addslashes($ts['nama_siswa']) }}', '{{ $ts['no_rekening'] }}', '{{ $ts['jenis_trans'] }}', '{{ number_format($ts['nominal']) }}', '{{ $ts['waktu'] }}', '{{ number_format($ts['saldo_akhir'] ?? 0) }}')" style="flex: 2; background: #2563eb; color: #ffffff; border: none; padding: 0.85rem 1rem; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
                            Cetak Struk Transaksi
                        </button>
                        <button type="button" onclick="document.getElementById('modalTransaksiSukses').style.display='none'" style="flex: 1; background: #e2e8f0; color: #334155; border: none; padding: 0.85rem 1rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- MODAL POPUP NOTIFIKASI KESALAHAN TRANSAKSI / VALIDASI GAGAL -->
        @if(session('error') || $errors->any())
        <div id="modalTransaksiGagal" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">
            <div style="background: #ffffff; border-radius: 24px; max-width: 480px; width: 100%; box-shadow: 0 25px 60px rgba(0,0,0,0.35); overflow: hidden;">
                <!-- Header Modal Gagal -->
                <div style="background: linear-gradient(135deg, #dc2626, #ef4444); padding: 1.5rem 1.5rem; text-align: center; color: #ffffff;">
                    <h3 style="margin: 0; font-size: 1.35rem; font-weight: 800; letter-spacing: 0.5px;">Transaksi Gagal / Ditolak</h3>
                    <p style="margin: 6px 0 0 0; font-size: 0.85rem; opacity: 0.95;">Sistem mendeteksi kesalahan pada data transaksi</p>
                </div>

                <!-- Body Modal -->
                <div style="padding: 1.5rem 1.75rem; background: #fafafa;">
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 16px; padding: 1.25rem; color: #991b1b; font-size: 0.92rem; line-height: 1.6;">
                        @if(session('error'))
                            <div>
                                <strong style="color: #b91c1c; font-size: 0.95rem;">Pemberitahuan:</strong><br>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div style="margin-top: {{ session('error') ? '0.75rem' : '0' }};">
                                <strong style="color: #b91c1c;">Periksa kembali isian form berikut:</strong>
                                <ul style="margin: 0.35rem 0 0; padding-left: 1.25rem;">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    @if(session('error') && str_contains(session('error'), 'PIN'))
                        <div style="margin-top: 1rem; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.85rem; color: #92400e;">
                            Jika siswa lupa PIN keamanan 6-digit, silakan buka menu <strong>Reset PIN Nasabah</strong> untuk mengatur PIN baru.
                        </div>
                    @endif

                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                        @if(session('error') && str_contains(session('error'), 'PIN'))
                            <button type="button" onclick="goToResetPinFromGagal()" style="flex: 1; background: #d97706; color: #ffffff; border: none; padding: 0.85rem 1rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer;">
                                Reset PIN Nasabah
                            </button>
                        @endif
                        <button type="button" onclick="closeModalTransaksiGagal()" style="flex: 1; background: #2563eb; color: #ffffff; border: none; padding: 0.85rem 1rem; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                            Tutup &amp; Perbaiki Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- MODAL POPUP LUPA PIN DENGAN VERIFIKASI KODE OTP -->
        <div id="modalLupaPinOtp" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem;">
            <div style="background: #ffffff; border-radius: 24px; max-width: 520px; width: 100%; box-shadow: 0 25px 60px rgba(0,0,0,0.35); overflow: hidden;">
                <!-- Header Modal -->
                <div style="background: linear-gradient(135deg, #d97706, #f59e0b); padding: 1.5rem 1.75rem; color: #ffffff; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <span style="background: rgba(255,255,255,0.25); padding: 0.2rem 0.55rem; border-radius: 8px; font-size: 0.8rem; font-weight: 800;">VERIFIKASI OTP</span>
                            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800;">Pemulihan PIN Transaksi</h3>
                        </div>
                        <p style="margin: 0; font-size: 0.85rem; opacity: 0.95;">Kirim kode OTP ke Gmail nasabah untuk membuat PIN baru dengan aman.</p>
                    </div>
                    <button type="button" onclick="closeLupaPinOtpModal()" style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; width: 34px; height: 34px; border-radius: 50%; font-size: 1.25rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
                </div>

                <div style="padding: 1.5rem 1.75rem;">
                    <!-- TAHAP 1: INPUT IDENTITAS, EMAIL & VERIFIKASI OTP -->
                    <div id="lupaPinStep1">
                        <div id="lupaPinAlert1" style="display: none; padding: 0.75rem 1rem; border-radius: 12px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600;"></div>

                        <div class="form-group">
                            <label>Identitas Penabung (NIS / No. Rekening)</label>
                            <input type="text" id="lupaPinIdentifier" class="form-control" placeholder="Contoh: 100.2026.0019 atau NIS Siswa" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat Gmail Siswa / Penabung</label>
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="email" id="lupaPinEmail" class="form-control" placeholder="Contoh: siswa@gmail.com" style="flex: 1;" required>
                                <button type="button" id="btnLupaPinKirimOtp" onclick="handleLupaPinKirimOtp()" class="btn-primary" style="background: #d97706; padding: 0.65rem 1rem; font-size: 0.85rem; white-space: nowrap;">
                                    Kirim OTP
                                </button>
                            </div>
                            <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 3px;">Kode OTP 6-digit akan dikirimkan ke kotak masuk (Inbox/Spam) Gmail ini.</small>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Masukkan 6-Digit Kode OTP dari Gmail</label>
                            <input type="text" id="lupaPinOtpCode" class="form-control" maxlength="6" placeholder="Contoh: 123456" style="letter-spacing: 4px; font-weight: 800; text-align: center; font-size: 1.2rem;">
                        </div>

                        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                            <button type="button" onclick="closeLupaPinOtpModal()" class="btn-logout" style="background: #e2e8f0; color: #475569; flex: 1;">
                                Batal
                            </button>
                            <button type="button" id="btnLupaPinVerifOtp" onclick="handleLupaPinVerifOtp()" class="btn-primary" style="background: linear-gradient(135deg, #d97706, #f59e0b); flex: 2; padding: 0.75rem;">
                                Verifikasi OTP &amp; Lanjut
                            </button>
                        </div>
                    </div>

                    <!-- TAHAP 2: INPUT PIN 6-DIGIT BARU -->
                    <div id="lupaPinStep2" style="display: none;">
                        <div id="lupaPinAlert2" style="display: none; padding: 0.75rem 1rem; border-radius: 12px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600;"></div>

                        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.88rem;">
                            <div style="color: #1e40af; font-weight: 700;">Nasabah Terverifikasi:</div>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a;" id="lupaPinNamaSiswa">-</div>
                            <div style="color: #64748b; font-size: 0.82rem;" id="lupaPinRekeningSiswa">-</div>
                        </div>

                        <div class="form-group">
                            <label>Buat PIN 6-Digit Baru</label>
                            <div style="position: relative;">
                                <input type="password" id="lupaPinBaru" class="form-control" maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN baru" required style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('lupaPinBaru', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi PIN 6-Digit Baru</label>
                            <div style="position: relative;">
                                <input type="password" id="lupaPinBaruConfirm" class="form-control" maxlength="6" minlength="6" placeholder="Ulangi 6 angka PIN baru" required style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('lupaPinBaruConfirm', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                            <button type="button" onclick="goToLupaPinStep1()" class="btn-logout" style="background: #e2e8f0; color: #475569; flex: 1;">
                                Kembali
                            </button>
                            <button type="button" id="btnLupaPinSimpan" onclick="handleLupaPinSimpan()" class="btn-primary" style="background: linear-gradient(135deg, #16a34a, #22c55e); flex: 2; padding: 0.75rem;">
                                Simpan PIN Baru &amp; Pasang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR (LANGSUNG TAMPIL DI PALING ATAS DENGAN DESAIN MODERN) -->
        @if(session('success'))
            <div class="alert alert-success" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; box-shadow: 0 4px 14px rgba(22, 163, 74, 0.12); border-left: 5px solid #16a34a;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="background: #16a34a; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; flex-shrink: 0;">✓</div>
                    <div>
                        <div style="font-weight: 800; color: #15803d; font-size: 0.95rem;">Berhasil Dikonfirmasi</div>
                        <div style="font-size: 0.88rem; color: #166534; margin-top: 2px;">{{ session('success') }}</div>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 1.4rem; color: #15803d; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.12); border-left: 5px solid #dc2626;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="background: #dc2626; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; flex-shrink: 0;">!</div>
                    <div>
                        <div style="font-weight: 800; color: #b91c1c; font-size: 0.95rem;">Pemberitahuan Sistem</div>
                        <div style="font-size: 0.88rem; color: #991b1b; margin-top: 2px;">{{ session('error') }}</div>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 1.4rem; color: #b91c1c; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="box-shadow: 0 4px 14px rgba(220, 38, 38, 0.12); border-left: 5px solid #dc2626;">
                <strong style="color: #b91c1c;">Terjadi kesalahan input data:</strong>
                <ul style="margin: 6px 0 0; padding-left: 1.25rem; font-size: 0.88rem; color: #991b1b;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- STATUS PENGAJUAN TAMBAHAN KAS AKTIF (LANGSUNG TAMPIL DI BAGIAN ATAS) -->
        @if(isset($tambahanKasHariIni) && $tambahanKasHariIni->where('status', 'pending')->count() > 0)
            @foreach($tambahanKasHariIni->where('status', 'pending') as $pk)
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #f59e0b; border-radius: 14px; padding: 0.85rem 1.25rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.08);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="background: #f59e0b; color: #fff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; flex-shrink: 0;">⏳</div>
                    <div>
                        <div style="font-weight: 800; color: #92400e; font-size: 0.95rem;">
                            Pengajuan Tambahan Kas: <strong>Rp {{ number_format($pk->nominal, 0, ',', '.') }}</strong> Sedang Menunggu Otorisasi Admin
                        </div>
                        <div style="font-size: 0.82rem; color: #b45309; margin-top: 2px;">
                            Alasan: <em>"{{ $pk->alasan }}"</em> &bull; Diajukan pada: {{ date('H:i', strtotime($pk->created_at)) }} WIB
                        </div>
                    </div>
                </div>
                <span style="background: #fef3c7; color: #92400e; font-weight: 800; font-size: 0.78rem; padding: 0.35rem 0.75rem; border-radius: 999px; border: 1px solid #fcd34d; letter-spacing: 0.5px;">
                    STATUS: MENUNGGU ACC
                </span>
            </div>
            @endforeach
        @endif

        @if(isset($tambahanKasHariIni) && $tambahanKasHariIni->where('status', 'rejected')->count() > 0)
            @foreach($tambahanKasHariIni->where('status', 'rejected')->take(1) as $pk)
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-left: 5px solid #dc2626; border-radius: 14px; padding: 0.85rem 1.25rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.08);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="background: #dc2626; color: #fff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; flex-shrink: 0;">✕</div>
                    <div>
                        <div style="font-weight: 800; color: #991b1b; font-size: 0.95rem;">
                            Pengajuan Tambahan Kas Rp {{ number_format($pk->nominal, 0, ',', '.') }} Ditolak oleh Admin
                        </div>
                        <div style="font-size: 0.82rem; color: #b91c1c; margin-top: 2px;">
                            Alasan Penolakan: <strong>{{ $pk->catatan_admin ?? 'Tidak disetujui / saldo kasir mencukupi' }}</strong>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="openAjukanKasModal()" class="btn-primary" style="background: #dc2626; padding: 0.45rem 0.9rem; font-size: 0.82rem; border-radius: 8px;">
                    Ajukan Ulang Kas
                </button>
            </div>
            @endforeach
        @endif

        @if($laporanHariIni && $laporanHariIni->status_approval === 'rejected')
            <div class="alert alert-error" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.15); border-left: 5px solid #dc2626; background: #fef2f2; border-color: #fecaca; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="background: #dc2626; color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">✕</div>
                    <div>
                        <div style="font-weight: 800; color: #991b1b; font-size: 0.98rem;">
                            Laporan Penutupan Kas Ditolak oleh Admin
                        </div>
                        <div style="font-size: 0.88rem; color: #b91c1c; margin-top: 3px;">
                            Alasan: <strong>"{{ $laporanHariIni->catatan_penolakan ?? 'Periksa kembali perhitungan fisik kas di laci.' }}"</strong>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="switchTab('tab-laporan')" class="btn-primary" style="background: #dc2626; padding: 0.55rem 1.1rem; font-size: 0.85rem; border-radius: 8px; white-space: nowrap;">
                    Perbaiki Laporan Kas
                </button>
            </div>
        @endif



        <!-- TAB 0: DASHBOARD OPERASIONAL TELLER -->
        <div id="tab-dashboard" class="tab-content active">
            <!-- 4 KARTU STATISTIK KAS UTAMA (TERINTEGRASI DI PALING ATAS & KONSISTEN) -->
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
            <!-- KARTU 1: MODAL AWAL KAS -->
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0;">MODAL AWAL KAS</h4>
                        @if(!$isModalAwalSet)
                            <span style="font-size: 0.72rem; background: #fef3c7; color: #92400e; padding: 0.2rem 0.55rem; border-radius: 999px; font-weight: 700; border: 1px solid #fde68a;">
                                ⚠️ Belum Diset
                            </span>
                        @endif
                    </div>
                    <div class="value" style="color: #2563eb; font-variant-numeric: tabular-nums;">
                        Rp {{ number_format($modalAwal, 0, ',', '.') }}
                    </div>
                </div>

                @if(!$isModalAwalSet)
                    <!-- FORM INPUT CEPAT MODAL AWAL LANGSUNG DI DALAM KARTU ATAS -->
                    <div style="margin-top: 0.85rem; padding-top: 0.75rem; border-top: 1px dashed var(--border-color);">
                        <div style="font-size: 0.78rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 600;">Input modal awal kas sebelum mulai:</div>
                        <form action="{{ route('teller.modal-awal') }}" method="POST" class="prevent-double-submit" style="display: flex; gap: 0.4rem;" onsubmit="return confirm('Konfirmasi: Simpan modal awal kas laci teller sebesar Rp ' + (this.modal_awal.value ? Number(this.modal_awal.value).toLocaleString('id-ID') : '0') + '?')">
                            @csrf
                            <input type="number" name="modal_awal" class="form-control" required min="1000" step="1000" placeholder="Rp..." style="padding: 0.45rem 0.75rem; font-size: 0.85rem; flex: 1;">
                            <button type="submit" class="btn-primary" style="padding: 0.45rem 0.85rem; font-size: 0.82rem; border-radius: 8px;">
                                Simpan
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- KARTU 2: SISA KAS DI LACI (DENGAN TOMBOL AJUKAN KAS LANGSUNG DI ATAS) -->
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-color: {{ $sisaKasLaci < 10000 ? '#fecaca' : 'var(--border-color)' }};">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0;">SISA KAS DI LACI</h4>
                        <button type="button" onclick="openAjukanKasModal()" class="btn-primary" style="padding: 0.3rem 0.65rem; font-size: 0.75rem; border-radius: 8px; background: #2563eb; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);" title="Ajukan Tambahan Kas ke Admin">
                            <span>+</span> Ajukan Kas
                        </button>
                    </div>
                    <div class="value" style="color: {{ $sisaKasLaci >= 0 ? '#0f172a' : '#dc2626' }}; font-variant-numeric: tabular-nums;">
                        Rp {{ number_format($sisaKasLaci, 0, ',', '.') }}
                    </div>
                </div>

                <div style="margin-top: 0.75rem; font-size: 0.78rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.25rem;">
                    @if($tambahanKasApproved > 0)
                        <span style="color: #16a34a; font-weight: 700;">+ Rp {{ number_format($tambahanKasApproved, 0, ',', '.') }} ACC Admin</span>
                    @elseif(isset($tambahanKasHariIni) && $tambahanKasHariIni->where('status', 'pending')->count() > 0)
                        <span style="color: #d97706; font-weight: 700;">⏳ Ajuan Kas Menunggu ACC</span>
                    @else
                        <span style="color: #64748b;">Saldo fisik kas siap transaksi</span>
                    @endif
                    <a href="javascript:void(0)" onclick="openAjukanKasModal()" style="color: #2563eb; font-weight: 700; text-decoration: underline; font-size: 0.78rem;">Tambah Kas</a>
                </div>
            </div>

            <!-- KARTU 3: TOTAL SETORAN TUNAI -->
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0;">TOTAL SETORAN TUNAI</h4>
                    </div>
                    <div class="value" style="color: #16a34a; font-variant-numeric: tabular-nums;">
                        Rp {{ number_format($totalSetoran, 0, ',', '.') }}
                    </div>
                </div>
                <div style="margin-top: 0.75rem; font-size: 0.78rem;">
                    <a href="javascript:void(0)" onclick="switchTab('tab-setoran', true)" style="color: #16a34a; font-weight: 700; text-decoration: underline;">
                        Buka Form Setoran Siswa &rarr;
                    </a>
                </div>
            </div>

            <!-- KARTU 4: TOTAL PENARIKAN TUNAI -->
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0;">TOTAL PENARIKAN TUNAI</h4>
                    </div>
                    <div class="value" style="color: #dc2626; font-variant-numeric: tabular-nums;">
                        Rp {{ number_format($totalPenarikan, 0, ',', '.') }}
                    </div>
                </div>
                <div style="margin-top: 0.75rem; font-size: 0.78rem;">
                    <a href="javascript:void(0)" onclick="switchTab('tab-penarikan', true)" style="color: #dc2626; font-weight: 700; text-decoration: underline;">
                        Buka Form Penarikan Siswa &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- SEARCH BOX: PENCARIAN PENABUNG (DI ATAS) -->
        <div class="search-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="margin: 0; font-size: 1.15rem; color: var(--primary-600); font-weight: 800;">
                    Pencarian Rekening Penabung
                </h3>
            </div>
            <div class="search-flex">
                <input type="text" id="searchKeyword" class="form-control" placeholder="Scan Barcode / QR / Ketik No Rekening / NIS / Nama Siswa (Tekan Enter)" autofocus autocomplete="off" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); doSearchNasabah(); }">
                <button type="button" class="btn-primary" onclick="doSearchNasabah()">Cari Penabung</button>
                <button type="button" class="btn-primary" onclick="openCameraModal()" style="background: linear-gradient(135deg, #0284c7, #0ea5e9); display: inline-flex; align-items: center; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                    Buka Kamera Scan Barcode / QR
                </button>
            </div>

            <div id="nasabahInfoCard" class="nasabah-info-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <h4 style="margin: 0; color: var(--primary-600); font-size: 1.05rem;" id="infoNamaSiswa">Nama Penabung</h4>
                    <span style="background: #dcfce7; color: #15803d; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px;">Data Ditemukan</span>
                </div>
                <div class="nasabah-info-grid">
                    <div>No Rekening: <strong id="infoNoRek">-</strong></div>
                    <div>NIS / Jurusan: <strong id="infoNisKelas">-</strong></div>
                    <div>Saldo Real-Time: <strong style="color: #16a34a;" id="infoSaldo">-</strong></div>
                    <div>Status: <strong id="infoStatus">-</strong></div>
                </div>
                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                    <button type="button" onclick="selectForSetoran()" class="btn-success" style="padding: 0.45rem 0.9rem; font-size: 0.85rem;">Pilih untuk Setoran</button>
                    <button type="button" onclick="selectForPenarikan()" class="btn-primary" style="background: #dc2626; padding: 0.45rem 0.9rem; font-size: 0.85rem;">Pilih untuk Penarikan</button>
                    <button type="button" onclick="selectForResetPin()" class="btn-primary" style="background: #f59e0b; padding: 0.45rem 0.9rem; font-size: 0.85rem;">Reset PIN Siswa</button>
                </div>
            </div>
        </div>

        <!-- SISTEM ANTREAN BANK MINI (TELLER) (DI BAWAH) -->
        <div class="card" id="cardAntreanTeller" style="border-left: 5px solid var(--primary-600); margin-bottom: 1.75rem; background: #ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h4 style="margin: 0; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Antrean Pelayanan Loket Bank Mini</h4>
                    <div style="display: flex; align-items: baseline; gap: 0.75rem; margin-top: 0.35rem; flex-wrap: wrap;">
                        <span style="font-size: 0.9rem; color: #64748b; font-weight: 600;">Nomor Sedang Dilayani:</span>
                        <strong id="antreanNomorSedangDilayani" style="font-size: 1.5rem; color: {{ $antreanSedangDilayani ? '#2563eb' : '#64748b' }};">
                            {{ $antreanSedangDilayani ? $antreanSedangDilayani->nomor_antrean : '-' }}
                        </strong>
                        <span id="antreanInfoSiswaSedangDilayani" style="font-size: 0.85rem; color: #1e293b; font-weight: 700; {{ $antreanSedangDilayani ? '' : 'display: none;' }}">
                            @if($antreanSedangDilayani)
                                ({{ $antreanSedangDilayani->nama_siswa }} - NIS: {{ $antreanSedangDilayani->nis }})
                            @endif
                        </span>
                    </div>
                    <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.25rem;">
                        Antrean menunggu: <strong style="color: #0f172a;"><span id="antreanTotalMenungguText">{{ $totalAntreanMenunggu }}</span> siswa</strong> &bull; Total nomor terbit hari ini: <strong><span id="antreanTotalTerbitText">{{ isset($antreanHariIni) ? count($antreanHariIni) : 0 }}</span></strong>
                    </div>
                </div>

                <div id="antreanActionButtons" style="display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap;">
                    <div id="wrapperBtnSelesaiAntrean" style="{{ $antreanSedangDilayani ? 'display: inline-block;' : 'display: none;' }}">
                        <form id="formSelesaiAntrean" action="{{ $antreanSedangDilayani ? route('teller.antrean.selesai', $antreanSedangDilayani->id) : '#' }}" method="POST" style="margin: 0;" onsubmit="handleSelesaiAntreanSubmit(event)">
                            @csrf
                            <button type="submit" id="btnSelesaiAntrean" class="btn-success" data-id="{{ $antreanSedangDilayani ? $antreanSedangDilayani->id : '' }}" data-nomor="{{ $antreanSedangDilayani ? $antreanSedangDilayani->nomor_antrean : '' }}" style="padding: 0.65rem 1.15rem; font-size: 0.88rem; font-weight: 700; border-radius: 8px;">
                                Selesaikan Antrean (<span id="labelNomorSelesai">{{ $antreanSedangDilayani ? $antreanSedangDilayani->nomor_antrean : '' }}</span>)
                            </button>
                        </form>
                    </div>

                    <form id="formPanggilAntrean" action="{{ route('teller.antrean.panggil') }}" method="POST" style="margin: 0;" onsubmit="handlePanggilAntreanSubmit(event)">
                        @csrf
                        <button type="submit" id="btnPanggilAntrean" class="btn-primary" style="padding: 0.65rem 1.25rem; font-size: 0.88rem; font-weight: 700; border-radius: 8px; {{ $totalAntreanMenunggu == 0 ? 'opacity: 0.75;' : '' }}">
                            Panggil Antrean Berikutnya
                        </button>
                    </form>
                </div>
            </div>
            
            @if(isset($antreanHariIni) && count($antreanHariIni) > 0)
                <details id="detailsAntreanHariIni" style="margin-top: 1rem; border-top: 1px dashed var(--border-color); padding-top: 0.75rem;">
                    <summary style="font-size: 0.8rem; font-weight: 700; color: #64748b; cursor: pointer;">
                        Lihat Daftar Lengkap Antrean Hari Ini (<span id="labelJumlahAntreanHariIni">{{ count($antreanHariIni) }}</span> Siswa)
                    </summary>
                    <div style="margin-top: 0.5rem; max-height: 180px; overflow-y: auto;">
                        <table style="width: 100%; font-size: 0.82rem; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f8fafc; color: #64748b; text-align: left;">
                                    <th style="padding: 6px 10px;">Nomor</th>
                                    <th style="padding: 6px 10px;">Nama Siswa</th>
                                    <th style="padding: 6px 10px;">NIS</th>
                                    <th style="padding: 6px 10px;">Waktu Ambil</th>
                                    <th style="padding: 6px 10px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDaftarAntreanHariIni">
                                @foreach($antreanHariIni as $ant)
                                <tr id="row-antrean-{{ $ant->nomor_antrean }}" style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 6px 10px; font-weight: 700; font-family: monospace;">{{ $ant->nomor_antrean }}</td>
                                    <td style="padding: 6px 10px;">{{ $ant->nama_siswa }}</td>
                                    <td style="padding: 6px 10px;">{{ $ant->nis }}</td>
                                    <td style="padding: 6px 10px; color: #64748b;">{{ date('H:i', strtotime($ant->created_at)) }} WIB</td>
                                    <td style="padding: 6px 10px;" id="badge-antrean-{{ $ant->nomor_antrean }}">
                                        @if($ant->status === 'dilayani')
                                            <span style="background: #dbeafe; color: #1d4ed8; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Dilayani</span>
                                        @elseif($ant->status === 'menunggu')
                                            <span style="background: #fef3c7; color: #b45309; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Menunggu</span>
                                        @else
                                            <span style="background: #f1f5f9; color: #64748b; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            @endif
        </div>
        </div>

        <!-- TAB 1: AKTIVASI REKENING ANGGOTA SISWA -->
        <div id="tab-aktivasi" class="tab-content">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                    <div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 1.2rem;">Daftar Siswa Menunggu Aktivasi Rekening</h3>
                        <p style="margin: 0.35rem 0 0; font-size: 0.85rem; color: var(--text-muted); line-height: 1.4;">
                            Data anggota siswa yang belum memiliki rekening tabungan. Klik tombol <strong>Aktivasi</strong> untuk memproses setoran awal (minimal Rp 10.000) dan membuat PIN transaksi.
                        </p>
                    </div>
                    <div>
                        <span style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 0.82rem; padding: 0.4rem 0.85rem; border-radius: 999px; font-weight: 700;">
                            Belum Aktif ({{ $membersUnactivated->count() }})
                        </span>
                    </div>
                </div>

                <!-- PENCARIAN & FILTER TABEL AKTIVASI -->
                @php
                    $uniqueAktivasiKelas = $membersUnactivated->pluck('class_grade')->filter()->unique()->sort()->values();
                    $uniqueAktivasiAngkatan = $membersUnactivated->pluck('graduation_year')->filter()->unique()->sort()->values();
                @endphp
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 220px;">
                        <input type="text" id="searchAktivasiTable" class="form-control" style="padding: 0.5rem 0.85rem; font-size: 0.85rem;" placeholder="Cari Siswa (Nama, NIS, Email)..." oninput="filterAktivasiTable()">
                    </div>
                    <div style="width: 160px;">
                        <select id="filterAktivasiKelas" class="form-control" style="padding: 0.5rem 0.85rem; font-size: 0.85rem;" onchange="filterAktivasiTable()">
                            <option value="">Semua Jurusan</option>
                            @foreach($uniqueAktivasiKelas as $uk)
                                <option value="{{ strtolower($uk) }}">{{ $uk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 160px;">
                        <select id="filterAktivasiAngkatan" class="form-control" style="padding: 0.5rem 0.85rem; font-size: 0.85rem;" onchange="filterAktivasiTable()">
                            <option value="">Semua Tahun Kelulusan</option>
                            @foreach($uniqueAktivasiAngkatan as $ua)
                                <option value="{{ strtolower($ua) }}">{{ $ua }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 45px;">No</th>
                                <th>Nama Siswa</th>
                                <th>NIS</th>
                                <th>Jurusan</th>
                                <th>Tahun Kelulusan</th>
                                <th>Email / Gmail</th>
                                <th style="width: 110px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableAktivasiBody">
                            @forelse($membersUnactivated as $idx => $mu)
                            <tr class="aktivasi-row" data-search="{{ strtolower($mu->student_name . ' ' . $mu->nis . ' ' . ($mu->email ?? '')) }}" data-kelas="{{ strtolower($mu->class_grade ?? '') }}" data-angkatan="{{ strtolower($mu->graduation_year ?? '') }}">
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $mu->student_name }}</strong></td>
                                <td><code>{{ $mu->nis }}</code></td>
                                <td>{{ $mu->class_grade ?: '-' }}</td>
                                <td>{{ $mu->graduation_year ?: '-' }}</td>
                                <td><small>{{ $mu->email ?: '-' }}</small></td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-primary" style="padding: 0.42rem 0.9rem; font-size: 0.82rem; border-radius: 8px;" onclick="openModalAktivasi('{{ $mu->user_id }}', '{{ addslashes($mu->student_name) }}', '{{ $mu->nis }}', '{{ addslashes($mu->class_grade ?? '') }}', '{{ addslashes($mu->graduation_year ?? '') }}', '{{ addslashes($mu->email ?? '') }}')">
                                        Aktivasi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr id="emptyAktivasiRow">
                                <td colspan="7" style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);">
                                    Tidak ada siswa yang menunggu aktivasi rekening. Seluruh anggota telah aktif atau belum ada data baru yang diimpor.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINASI DAFTAR SISWA MENUNGGU AKTIVASI (20 PER HALAMAN) -->
                <div id="aktivasiPaginationWrapper" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid var(--border-color);">
                    <div style="font-size: 0.85rem; color: var(--text-muted);">
                        Menampilkan <strong id="aktivasiPageInfo">0 - 0 dari 0</strong> data siswa
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button type="button" id="btnAktivasiPrev" onclick="prevAktivasiPage()" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.82rem; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px;">
                            Sebelumnya
                        </button>
                        
                        <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; color: #475569;">
                            <span>Halaman</span>
                            <input type="number" id="aktivasiPageInput" min="1" value="1" onchange="jumpAktivasiPage(this.value)" style="width: 58px; text-align: center; padding: 0.3rem 0.4rem; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 700; font-size: 0.85rem;">
                            <span>dari <strong id="aktivasiTotalPages">1</strong></span>
                        </div>

                        <button type="button" id="btnAktivasiNext" onclick="nextAktivasiPage()" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.82rem; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px;">
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: SETORAN TUNAI -->
        <div id="tab-setoran" class="tab-content">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <h3 style="margin-top:0; color: #16a34a; margin-bottom: 1.25rem;">Form Transaksi Setoran Tunai Penabung</h3>
                <form action="{{ route('teller.setoran') }}" method="POST" class="prevent-double-submit" onsubmit="return confirmSetoran(this);">
                    @csrf
                    <input type="hidden" name="_token_transaksi" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                    <div class="form-group">
                        <label>Pilih Penabung</label>
                        <select name="id_nasabah" id="setoranNasabahSelect" class="form-control" required>
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                <option value="{{ $s->id }}" {{ old('id_nasabah') == $s->id ? 'selected' : '' }}>
                                    {{ $s->account_number }} - {{ $s->student_name }} (Saldo: Rp {{ number_format($s->rekening->saldo ?? 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nominal Setoran (Rp)</label>
                        <input type="number" name="nominal" id="setoranNominal" class="form-control" required min="1000" step="1000" placeholder="Contoh: 50000" value="{{ old('nominal') }}">
                    </div>
                    <div class="form-group">
                        <label>Keterangan Slip</label>
                        <input type="text" name="keterangan" maxlength="50" class="form-control" placeholder="Contoh: Setoran tabungan mingguan" value="{{ old('keterangan') }}">
                    </div>
                    <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #16a34a, #22c55e); width: 100%; margin-top: 0.5rem; padding: 0.85rem;">
                        Proses Setoran & Simpan Jurnal
                    </button>
                </form>
            </div>
        </div>

        <!-- TAB 3: PENARIKAN TUNAI -->
        <div id="tab-penarikan" class="tab-content">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <h3 style="margin-top:0; color: #dc2626; margin-bottom: 0.5rem;">Penarikan Tunai</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Minimal Rp 10.000, sisa saldo minimal Rp 10.000, verifikasi PIN 6-digit.</p>
                
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.6rem 0.85rem; margin-bottom: 1rem; font-size: 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #64748b;">Sisa Kas di Laci:</span>
                    <strong style="color: {{ $sisaKasLaci >= 10000 ? '#16a34a' : '#dc2626' }};">Rp {{ number_format($sisaKasLaci) }}</strong>
                    <button type="button" onclick="openAjukanKasModal()" style="background: none; border: none; color: #0284c7; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.82rem;">
                        Ajukan Tambahan Kas
                    </button>
                </div>

                @if(session('error'))
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; color: #991b1b; font-size: 0.9rem;">
                        <div><strong>Gagal:</strong> {{ session('error') }}</div>
                        @if(str_contains(session('error'), 'tidak cukup') || str_contains(session('error'), 'mencukupi'))
                            <div style="margin-top: 0.5rem;">
                                <button type="button" onclick="openAjukanKasModal()" class="btn-primary" style="background: #0284c7; padding: 0.4rem 0.8rem; font-size: 0.82rem;">
                                    Ajukan Tambahan Kas ke Admin
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
                @if($errors->any())
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; color: #991b1b; font-size: 0.88rem;">
                        <strong>Periksa kesalahan input:</strong>
                        <ul style="margin: 4px 0 0; padding-left: 1.25rem;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('teller.penarikan') }}" method="POST" class="prevent-double-submit" onsubmit="return confirmPenarikan(this);">
                    @csrf
                    <input type="hidden" name="_token_transaksi" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                    <div class="form-group">
                        <label>Pilih Penabung</label>
                        <select name="id_nasabah" id="penarikanNasabahSelect" class="form-control" required onchange="onPenarikanSelectChange()">
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                @php
                                    $penarikanHariIni = (int) ($s->transaksi ? $s->transaksi->sum('nominal') : 0);
                                    $perjanjianArr = ($s->perjanjianPenarikan ?? collect())->map(fn($p) => [
                                        'id' => $p->id,
                                        'nominal' => (int)$p->nominal,
                                        'status' => $p->status,
                                        'alasan' => $p->alasan,
                                        'tanggal_rencana' => date('Y-m-d', strtotime($p->tanggal_rencana)),
                                        'diajukan_pada' => date('d/m/Y H:i', strtotime($p->diajukan_pada ?? $p->created_at))
                                    ])->values()->all();
                                @endphp
                                <option value="{{ $s->id }}" {{ old('id_nasabah') == $s->id ? 'selected' : '' }} 
                                    data-saldo="{{ $s->rekening->saldo ?? 0 }}" 
                                    data-name="{{ $s->student_name }}" 
                                    data-norek="{{ $s->account_number }}" 
                                    data-nis="{{ $s->nis }}" 
                                    data-email="{{ $s->email ?? '' }}"
                                    data-penarikan-hari-ini="{{ $penarikanHariIni }}"
                                    data-perjanjian="{{ json_encode($perjanjianArr) }}">
                                    {{ $s->account_number }} - {{ $s->student_name }} (Saldo: Rp {{ number_format($s->rekening->saldo ?? 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="penarikanInfoBox" style="display: {{ old('id_nasabah') ? 'block' : 'none' }}; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1rem; font-size: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="color: #64748b;">Saldo Tabungan Saat Ini:</span>
                            <strong id="penarikanInfoSaldo" style="color: #16a34a; font-size: 0.95rem;">Rp 0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="color: #64748b;">Batas Penarikan Reguler Hari Ini (Maks Rp 1 Juta & Wajib Sisa Rp 10.000):</span>
                            <strong id="penarikanInfoMaksimal" style="color: #dc2626; font-size: 0.95rem;">Rp 0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.78rem; border-top: 1px dashed #bfdbfe; padding-top: 5px; margin-top: 4px;">
                            <span style="color: #64748b;">Total Penarikan Nasabah Hari Ini:</span>
                            <span id="penarikanInfoHariIni" style="color: #475569; font-weight: 700;">Rp 0</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nominal Penarikan (Rp)</label>
                        <input type="number" name="nominal" id="penarikanNominal" class="form-control" required min="10000" step="1000" placeholder="Minimal Rp 10.000" value="{{ old('nominal') }}" oninput="validatePenarikanNominalRealtime()">
                        <div id="penarikanWarningMsg" style="display: none; margin-top: 6px; padding: 8px 12px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; line-height: 1.4;"></div>
                    </div>

                    <!-- KOTAK VALIDASI PERJANJIAN H-1 KHUSUS NOMINAL > 1 JUTA -->
                    <div id="boxPerjanjianH1" style="display: none; background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
                            <span style="background: #f59e0b; color: #fff; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; flex-shrink: 0;">!</span>
                            <strong style="color: #92400e; font-size: 0.88rem;">Penarikan Khusus di Atas Rp 1.000.000 (Wajib Perjanjian Minimal H-1)</strong>
                        </div>
                        <p style="margin: 0 0 0.65rem 0; color: #78350f; font-size: 0.8rem; line-height: 1.4;">
                            Sesuai aturan bank sekolah, penarikan di atas Rp 1.000.000 wajib melakukan perjanjian dengan teller minimal 1 hari sebelumnya agar uang kas fisik dapat disiapkan di loket.
                        </p>

                        <div id="perjanjianTerdaftarWrap" style="display: none; margin-bottom: 0.65rem;">
                            <label style="font-weight: 700; color: #166534; font-size: 0.82rem; display: block; margin-bottom: 3px;">
                                ✓ Pilih Reservasi Penarikan H-1 Terdaftar Hari Ini:
                            </label>
                            <select name="id_perjanjian" id="selectPerjanjianH1" class="form-control" style="font-size: 0.85rem; border-color: #86efac; background: #f0fdf4;" onchange="validatePenarikanNominalRealtime()">
                                <option value="">-- Pilih Reservasi H-1 --</option>
                            </select>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; background: #ffffff; border: 1px solid #fef3c7; border-radius: 8px; padding: 0.6rem 0.75rem;">
                            <input type="checkbox" name="konfirmasi_perjanjian_h1" id="checkPerjanjianManual" value="1" style="margin-top: 3px; cursor: pointer;" onchange="validatePenarikanNominalRealtime()">
                            <label for="checkPerjanjianManual" style="font-size: 0.8rem; color: #92400e; font-weight: 700; cursor: pointer; line-height: 1.35; margin: 0;">
                                Konfirmasi Perjanjian Manual H-1: Nasabah telah janjian penarikan ini minimal 1 hari sebelumnya (secara lisan / WhatsApp ke loket Teller).
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Verifikasi PIN 6-Digit Keamanan Penabung</label>
                        <div style="position: relative;">
                            <input type="password" name="pin" id="penarikanPin" class="form-control" required maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN penabung" style="padding-right: 2.75rem; {{ (session('error_pin_salah') || (session('error') && str_contains(session('error'), 'PIN'))) ? 'border: 2px solid #dc2626; background: #fff5f5;' : '' }}">
                            <button type="button" onclick="togglePasswordVisibility('penarikanPin', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        @if(session('error_pin_salah') || (session('error') && str_contains(session('error'), 'PIN')))
                            <div style="margin-top: 8px; padding: 9px 12px; background: #fef2f2; border: 1.5px solid #ef4444; border-radius: 10px; color: #b91c1c; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(239,68,68,0.15);">
                                <span style="background: #ef4444; color: #fff; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0;">!</span>
                                <span>PIN Keamanan Salah! 6 digit PIN yang Anda masukkan tidak sesuai dengan data penabung.</span>
                            </div>
                        @endif
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px; flex-wrap: wrap; gap: 0.25rem;">
                            <small style="color: #64748b; font-size: 0.78rem;">Wajib 6 angka PIN keamanan penabung.</small>
                            <a href="javascript:void(0)" onclick="openLupaPinOtpModal()" style="font-size: 0.82rem; color: #2563eb; font-weight: 700; text-decoration: underline;">
                                Lupa PIN Nasabah?
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Slip</label>
                        <input type="text" name="keterangan" maxlength="50" class="form-control" placeholder="Contoh: Penarikan kebutuhan praktek" value="{{ old('keterangan') }}">
                    </div>
                    <button type="submit" id="penarikanSubmitBtn" class="btn-primary" style="background: linear-gradient(135deg, #dc2626, #ef4444); width: 100%; margin-top: 0.5rem; padding: 0.85rem;">
                        Proses Penarikan & Simpan Jurnal
                    </button>
                </form>
            </div>
        </div>

        <!-- TAB: PERJANJIAN PENARIKAN H-1 (> 1 JUTA) -->
        <div id="tab-perjanjian" class="tab-content">
            <div class="card" style="box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                    <div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 1.25rem; font-weight: 800;">
                            Daftar Perjanjian Penarikan
                        </h3>
                        <p style="margin: 3px 0 0; font-size: 0.85rem; color: var(--text-muted);">
                            Reservasi penarikan khusus dana besar oleh nasabah siswa untuk dipersiapkan fisik kasnya oleh Teller.
                        </p>
                    </div>

                    <!-- filter status perjanjian penarikan -->
                    <div style="display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: #64748b; margin-right: 0.2rem;">Filter Status:</span>
                        <button type="button" class="btn-filter-pj" id="btnFilterPjAll" onclick="filterPerjanjianStatus('all')" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 999px; border: 1px solid #2563eb; background: #2563eb; color: #ffffff; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                            Semua ({{ count($perjanjianList) }})
                        </button>
                        <button type="button" class="btn-filter-pj" id="btnFilterPjMenunggu" onclick="filterPerjanjianStatus('menunggu')" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 999px; border: 1px solid #fde68a; background: #fffbeb; color: #b45309; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                            Menunggu ({{ $perjanjianList->where('status', 'menunggu')->count() }})
                        </button>
                        <button type="button" class="btn-filter-pj" id="btnFilterPjSelesai" onclick="filterPerjanjianStatus('selesai')" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 999px; border: 1px solid #bfdbfe; background: #eff6ff; color: #1d4ed8; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                            Udah Selesai ({{ $perjanjianList->where('status', 'selesai')->count() }})
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Tgl Pengajuan</th>
                                <th>Rencana Ambil</th>
                                <th>Nasabah &amp; Rekening</th>
                                <th>Nominal</th>
                                <th>Alasan / Keperluan</th>
                                <th>Status</th>
                                <th style="text-align: center;">Aksi Teller</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyPerjanjianList">
                            @forelse($perjanjianList as $idx => $pj)
                                <tr class="row-perjanjian" data-status="{{ $pj->status }}">
                                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                                    <td>{{ date('d/m/Y H:i', strtotime($pj->diajukan_pada ?? $pj->created_at)) }}</td>
                                    <td>
                                        <strong style="color: #2563eb;">{{ date('d F Y', strtotime($pj->tanggal_rencana)) }}</strong>
                                        @if(date('Y-m-d', strtotime($pj->tanggal_rencana)) === date('Y-m-d'))
                                            <span style="background: #dcfce7; color: #166534; font-size: 0.72rem; padding: 2px 6px; border-radius: 999px; font-weight: 800; margin-left: 4px;">HARI INI</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $pj->saver->student_name ?? '-' }}</strong><br>
                                        <small style="color: #64748b;">Rek: {{ $pj->saver->account_number ?? '-' }} (Saldo: Rp {{ number_format($pj->saver->rekening->saldo ?? 0) }})</small>
                                    </td>
                                    <td style="font-weight: 800; font-size: 1rem; color: #dc2626;">
                                        Rp {{ number_format($pj->nominal, 0, ',', '.') }}
                                    </td>
                                    <td style="max-width: 200px; white-space: normal; font-size: 0.82rem; color: #475569;">
                                        {{ $pj->alasan ?: '-' }}
                                    </td>
                                    <td>
                                        @if($pj->status === 'menunggu')
                                            <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">MENUNGGU</span>
                                        @elseif($pj->status === 'disetujui')
                                            <span class="badge" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">DISETUJUI</span>
                                        @elseif($pj->status === 'selesai')
                                            <span class="badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">SELESAI</span>
                                        @else
                                            <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">DITOLAK</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; gap: 0.4rem; align-items: center;">
                                            @if($pj->status === 'menunggu')
                                                <form action="{{ route('teller.perjanjian.approve', $pj->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Konfirmasi ACC:\n\nApakah Anda menyetujui reservasi penarikan Rp {{ number_format($pj->nominal) }} untuk nasabah {{ addslashes($pj->saver->student_name ?? '') }} pada tanggal {{ date('d F Y', strtotime($pj->tanggal_rencana)) }}?');">
                                                    @csrf
                                                    <button type="submit" class="btn-success" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 8px;">
                                                        ✓ ACC
                                                    </button>
                                                </form>
                                                <button type="button" class="btn-danger" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; border-radius: 8px;" onclick="openTolakPerjanjianModal({{ $pj->id }}, '{{ addslashes($pj->saver->student_name ?? '') }}', '{{ number_format($pj->nominal) }}')">
                                                    Tolak
                                                </button>
                                            @elseif($pj->status === 'disetujui')
                                                <button type="button" class="btn-primary" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; border-radius: 8px; background: linear-gradient(135deg, #0ea5e9, #2563eb);" onclick="selectPerjanjianForPenarikan({{ $pj->saver_id }}, {{ $pj->nominal }}, {{ $pj->id }})">
                                                    Proses di Loket &rarr;
                                                </button>
                                            @else
                                                <span style="color: #94a3b8; font-size: 0.8rem;">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                        Belum ada permohonan perjanjian penarikan.
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="rowPerjanjianEmptyFilter" style="display: none;">
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                    Tidak ada data perjanjian dengan status tersebut.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: LUPA / RESET PIN PENABUNG (DENGAN VERIFIKASI KODE OTP) -->
        <div id="tab-reset-pin" class="tab-content">
            <div class="card" style="max-width: 640px; margin: 0 auto; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);">
                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                    <h3 style="margin: 0; color: #d97706; font-size: 1.25rem; font-weight: 800;">
                        Layanan Reset PIN Nasabah
                    </h3>
                </div>

                <!-- TAHAP 1: PILIH NASABAH & VERIFIKASI OTP -->
                <div id="tabResetPinStep1">
                    <div id="tabResetPinAlert1" style="display: none; padding: 0.75rem 1rem; border-radius: 12px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600;"></div>

                    <!-- status kunci data setelah scan qr kartu -->
                    <div id="tabResetPinLockedNotice" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; font-size: 0.82rem; padding: 0.55rem 0.85rem; border-radius: 8px; margin-bottom: 1.15rem; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <span>Data identitas &amp; Gmail terkunci otomatis dari hasil scan kartu nasabah.</span>
                        <button type="button" onclick="unlockResetPinFields()" style="background: none; border: none; color: #d97706; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.8rem; white-space: nowrap;">Buka Kunci / Pilih Manual</button>
                    </div>

                    <div class="form-group">
                        <label>Pilih Penabung (Saver)</label>
                        <select id="resetPinSelect" class="form-control" onchange="onTabResetPinSelectChange()">
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                <option value="{{ $s->id }}" 
                                    data-name="{{ $s->student_name }}" 
                                    data-norek="{{ $s->account_number }}" 
                                    data-nis="{{ $s->nis }}" 
                                    data-email="{{ $s->email ?? ($s->member->email ?? '') }}">
                                    {{ $s->account_number }} - {{ $s->student_name }} (NIS: {{ $s->nis }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Identitas Penabung (NIS / No. Rekening)</label>
                        <input type="text" id="tabResetPinIdentifier" class="form-control" placeholder="Contoh: 10001001 atau NIS Siswa" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat Gmail Siswa / Penabung</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="email" id="tabResetPinEmail" class="form-control" placeholder="Contoh: siswa@gmail.com" style="flex: 1;" required>
                            <button type="button" id="btnTabKirimOtp" onclick="handleTabKirimOtp()" class="btn-primary" style="background: #d97706; padding: 0.65rem 1.15rem; font-size: 0.85rem; font-weight: 700; white-space: nowrap;">
                                Kirim OTP
                            </button>
                        </div>
                        <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 3px;">
                            Kode OTP 6-digit akan dikirimkan ke kotak masuk (Inbox/Spam) Gmail ini.
                        </small>
                    </div>

                    <div class="form-group" style="margin-top: 1.15rem;">
                        <label>Masukkan 6-Digit Kode OTP dari Gmail</label>
                        <input type="text" id="tabResetPinOtpCode" class="form-control" maxlength="6" placeholder="Contoh: 123456" style="letter-spacing: 4px; font-weight: 800; text-align: center; font-size: 1.25rem;">
                    </div>

                    <button type="button" id="btnTabVerifOtp" onclick="handleTabVerifOtp()" class="btn-primary" style="background: linear-gradient(135deg, #d97706, #f59e0b); width: 100%; margin-top: 1.25rem; padding: 0.85rem; font-size: 0.95rem; font-weight: 800; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);">
                        Verifikasi OTP &amp; Lanjut Buat PIN
                    </button>
                </div>

                <!-- TAHAP 2: INPUT PIN 6-DIGIT BARU SETELAH OTP TERVERIFIKASI -->
                <div id="tabResetPinStep2" style="display: none;">
                    <div id="tabResetPinAlert2" style="display: none; padding: 0.75rem 1rem; border-radius: 12px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600;"></div>

                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.88rem;">
                        <div style="color: #1e40af; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Nasabah Terverifikasi:</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 2px;" id="tabResetPinNamaSiswa">-</div>
                        <div style="color: #64748b; font-size: 0.82rem;" id="tabResetPinRekeningSiswa">-</div>
                    </div>

                    <div class="form-group">
                        <label>Buat PIN 6-Digit Baru</label>
                        <div style="position: relative;">
                            <input type="password" id="tabResetPinBaru" class="form-control" maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN baru" required style="padding-right: 2.75rem;">
                            <button type="button" onclick="togglePasswordVisibility('tabResetPinBaru', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi PIN 6-Digit Baru</label>
                        <div style="position: relative;">
                            <input type="password" id="tabResetPinBaruConfirm" class="form-control" maxlength="6" minlength="6" placeholder="Ulangi 6 angka PIN baru" required style="padding-right: 2.75rem;">
                            <button type="button" onclick="togglePasswordVisibility('tabResetPinBaruConfirm', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                        <button type="button" onclick="goToTabResetPinStep1()" class="btn-logout" style="background: #e2e8f0; color: #475569; flex: 1;">
                            Batal
                        </button>
                        <button type="button" id="btnTabSimpanPin" onclick="handleTabSimpanPin()" class="btn-primary" style="background: linear-gradient(135deg, #16a34a, #22c55e); flex: 2; padding: 0.85rem; font-weight: 800; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);">
                            Simpan PIN Baru &amp; Aktifkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: PENUTUPAN KAS & LAPORAN HARIAN -->
        <div id="tab-laporan" class="tab-content">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <h3 style="margin-top:0; margin-bottom: 1.25rem;">Laporan Kas Harian</h3>

                <div style="background: #eff6ff; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid #bfdbfe; margin-bottom: 1.25rem; font-size: 0.88rem; line-height: 1.8;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Tanggal:</span>
                        <strong>{{ date('d/m/Y') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Modal Awal Kas:</span>
                        <strong style="color: #2563eb;">Rp {{ number_format($modalAwal) }}</strong>
                    </div>
                    @if($tambahanKasApproved > 0)
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Tambahan Kas (ACC Admin):</span>
                        <strong style="color: #16a34a;">+ Rp {{ number_format($tambahanKasApproved) }}</strong>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Total Setoran:</span>
                        <strong style="color: #16a34a;">+ Rp {{ number_format($totalSetoran) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Total Penarikan:</span>
                        <strong style="color: #dc2626;">- Rp {{ number_format($totalPenarikan) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px dashed #bfdbfe; padding-top: 4px; margin-top: 4px;">
                        <span style="color: #64748b;">Saldo Kas di Laci:</span>
                        <strong style="color: #0f172a;">Rp {{ number_format($sisaKasLaci) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                        <span style="color: #64748b;">Status Approval:</span>
                        @if(!$isLaporanSubmitted)
                            <strong style="color: #64748b;">BELUM TUTUP KAS</strong>
                        @elseif($laporanHariIni->status_approval === 'approved')
                            <strong style="color: #16a34a;">DISETUJUI (KAS PINDAH KE ADMIN)</strong>
                        @elseif($laporanHariIni->status_approval === 'rejected')
                            <strong style="color: #dc2626;">DITOLAK ADMIN</strong>
                        @else
                            <strong style="color: #d97706;">MENUNGGU ACC ADMIN</strong>
                        @endif
                    </div>
                </div>

                @if($tambahanKasHariIni->count() > 0)
                <div style="margin-bottom: 1.25rem;">
                    <div style="font-weight: 700; font-size: 0.88rem; margin-bottom: 0.4rem; color: #334155;">Pengajuan Tambahan Kas Hari Ini</div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nominal</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tambahanKasHariIni as $tk)
                                <tr>
                                    <td><strong>Rp {{ number_format($tk->nominal) }}</strong></td>
                                    <td>
                                        <div>{{ $tk->alasan }}</div>
                                        @if($tk->status === 'rejected' && $tk->catatan_admin)
                                            <div style="font-size: 0.8rem; color: #dc2626; margin-top: 4px; background: #fef2f2; border: 1px solid #fee2e2; padding: 4px 8px; border-radius: 6px;">
                                                <strong>Alasan Ditolak:</strong> {{ $tk->catatan_admin }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-size: 0.8rem; font-weight: 700; color: {{ $tk->status === 'approved' ? '#16a34a' : ($tk->status === 'rejected' ? '#dc2626' : '#d97706') }};">
                                            {{ strtoupper($tk->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($laporanHariIni && $laporanHariIni->status_approval === 'rejected')
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem; margin-bottom: 1.25rem;">
                        <div style="font-weight: 700; color: #dc2626; font-size: 0.95rem; margin-bottom: 4px;">
                            Laporan Ditolak Admin
                        </div>
                        <div style="color: #991b1b; font-size: 0.88rem;">
                            Alasan: <strong>"{{ $laporanHariIni->catatan_penolakan ?? 'Periksa kembali hitungan uang fisik.' }}"</strong>
                        </div>
                    </div>
                @endif

                @if(!$isModalAwalSet)
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 1rem; color: #1e40af; font-size: 0.88rem;">
                        Input modal awal kas terlebih dahulu sebelum melakukan penutupan kas.
                    </div>
                @elseif(!$isLaporanSubmitted || $laporanHariIni->status_approval === 'rejected')
                    <form action="{{ route('teller.laporan-harian') }}" method="POST" class="prevent-double-submit" onsubmit="return confirm('Konfirmasi Tutup Kas Harian:\n\nApakah Anda yakin ingin mengirimkan laporan penutupan kas hari ini ke Administrator?');">
                        @csrf
                        @if($laporanHariIni && $laporanHariIni->status_approval === 'rejected')
                            <input type="hidden" name="laporan_id" value="{{ $laporanHariIni->id }}">
                        @endif
                        <div class="form-group">
                            <label>Uang Fisik Kas di Laci (Rp)</label>
                            <input type="number" name="uang_fisik" class="form-control" required min="0" value="{{ $sisaKasLaci }}" placeholder="Nominal uang fisik...">
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                Laporan kas harian hanya 1 kali per hari. Masukkan nominal uang fisik laci kasir.
                            </small>
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 0.5rem; padding: 0.75rem;">
                            {{ ($laporanHariIni && $laporanHariIni->status_approval === 'rejected') ? 'Kirim Perbaikan Laporan Kas' : 'Kirim Laporan Kas Harian' }}
                        </button>
                    </form>
                @elseif($laporanHariIni->status_approval === 'approved')
                    <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.9rem; color: #166534;">
                        <div style="font-weight: 700; margin-bottom: 4px;">Laporan Kas Disetujui Admin</div>
                        <div style="margin-bottom: 0.75rem;">Uang kas sebesar <strong>Rp {{ number_format($laporanHariIni->saldo_akhir_sistem) }}</strong> telah disetujui dan dipindahkan ke brankas Admin.</div>
                        <a href="{{ route('admin.laporan.pdf', $laporanHariIni->id) }}" class="btn-success" style="display: inline-block; padding: 0.45rem 0.9rem; font-size: 0.85rem; text-decoration: none;">
                            Download PDF Laporan
                        </a>
                    </div>
                @else
                    <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.9rem; color: #92400e;">
                        <div style="font-weight: 700; margin-bottom: 4px;">Laporan Kas Sudah Dikirim</div>
                        <div>Uang fisik dilaporkan: <strong>Rp {{ number_format($laporanHariIni->uang_fisik) }}</strong>. Laporan hanya 1 kali per hari dan sedang menunggu approval dari Admin.</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB 6: RIWAYAT TRANSAKSI HARIAN -->
        <div id="tab-riwayat" class="tab-content">
            <div class="card">
                <h3 style="margin-top:0;">Riwayat Transaksi Harian Teller</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>No Transaksi</th>
                                <th>Nama Penabung</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                                <th>Struk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiHariIni as $t)
                            <tr>
                                <td>{{ date('H:i:s', strtotime($t->tanggal)) }}</td>
                                <td><code>{{ $t->no_transaksi }}</code></td>
                                <td>
                                    <strong>{{ $t->student_name_display }}</strong>
                                    <br><small style="color: var(--text-muted);">Rek: {{ $t->account_number_display }}</small>
                                </td>
                                <td>
                                    <strong style="text-transform: uppercase; color: {{ $t->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                        {{ $t->jenis_trans }}
                                    </strong>
                                </td>
                                <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                                <td>{{ $t->keterangan ?? '-' }}</td>
                                <td>
                                    <button onclick="cetakStruk('{{ $t->no_transaksi }}', '{{ addslashes($t->student_name_display) }}', '{{ $t->account_number_display }}', '{{ $t->jenis_trans }}', '{{ number_format($t->nominal) }}', '{{ $t->tanggal }}')" style="background: #2563eb; color: #fff; border:none; padding: 0.35rem 0.75rem; border-radius: 6px; font-weight:700; cursor:pointer;">
                                        Cetak Struk
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada transaksi hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 7: LAPORAN BULANAN (TELLER VIEW) -->
        <div id="tab-bulanan" class="tab-content">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <h3 style="margin:0; color: #2563eb;">Laporan Mutasi Kas Bulanan</h3>
                        <p style="margin:0; font-size: 0.85rem; color: var(--text-muted);">Rekapitulasi mutasi kas per periode bulan untuk teller operasional.</p>
                    </div>

                    <form method="GET" action="{{ route('teller.dashboard') }}" style="display: flex; gap: 0.5rem; align-items: center; margin:0;">
                        <select name="month" class="form-control" style="width: 140px;">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        <select name="year" class="form-control" style="width: 110px;">
                            @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn-primary" style="padding: 0.75rem 1rem;">Filter</button>
                    </form>
                </div>

                <div class="grid" style="margin-bottom: 1.5rem;">
                    <div class="card" style="background: #f0fdf4;">
                        <h4>Setoran Masuk Bulan Ini</h4>
                        <div class="value" style="font-size: 1.5rem; color: #16a34a;">Rp {{ number_format($monthlySetoran, 0, ',', '.') }}</div>
                    </div>
                    <div class="card" style="background: #fef2f2;">
                        <h4>Penarikan Keluar Bulan Ini</h4>
                        <div class="value" style="font-size: 1.5rem; color: #dc2626;">Rp {{ number_format($monthlyPenarikan, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                    <a href="{{ route('admin.laporan-bulanan.pdf', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="btn-success">
                        Download PDF Laporan Bulanan
                    </a>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No Transaksi</th>
                                <th>Nama Penabung</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyTransactions as $mt)
                            <tr>
                                <td>{{ date('d/m/Y H:i', strtotime($mt->tanggal)) }}</td>
                                <td><code>{{ $mt->no_transaksi }}</code></td>
                                <td>{{ $mt->student_name_display }}</td>
                                <td><strong style="color: {{ $mt->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">{{ strtoupper($mt->jenis_trans) }}</strong></td>
                                <td>Rp {{ number_format($mt->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada transaksi di bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 10: PROFIL PETUGAS TELLER              -->
        <!-- ========================================== -->
        <div id="tab-profil" class="tab-content">
            <!-- HEADER PROFIL & TOMBOL KEMBALI -->
            <div class="profile-tab-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; background: #ffffff; padding: 1.25rem 1.5rem; border-radius: 16px; border: 1px solid var(--border-color); box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);">
                <div>
                    <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0;">Pengaturan Profil & Akun Teller</h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 3px 0 0;">Perbarui data identitas diri, username akun login, dan kata sandi Anda</p>
                </div>
                <button type="button" onclick="switchTab('tab-dashboard')" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.55rem 1.15rem; font-size: 0.85rem; font-weight: 700; border-radius: 10px; background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <span>&larr;</span> Kembali ke Dashboard
                </button>
            </div>

            @if(session('success_profile'))
                <div class="alert alert-success" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <strong>✓ Sukses:</strong> {{ session('success_profile') }}
                </div>
            @endif
            @if(session('success_password'))
                <div class="alert alert-success" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <strong>✓ Sukses:</strong> {{ session('success_password') }}
                </div>
            @endif
            @if(session('error_password'))
                <div class="alert alert-danger" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <strong>⚠️ Peringatan:</strong> {{ session('error_password') }}
                </div>
            @endif
            @if($errors->has('nama_petugas') || $errors->has('username') || $errors->has('email') || $errors->has('sandi_lama') || $errors->has('sandi_baru'))
                <div class="alert alert-danger" style="margin-bottom: 1.25rem;">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
                <!-- KARTU DATA IDENTITAS TELLER -->
                <div class="card" style="background: #ffffff; border-radius: 16px; padding: 1.75rem; border: 1px solid var(--border-color); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                        <div>
                            <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Profil Petugas Teller</h3>
                            <p style="margin: 3px 0 0; font-size: 0.8rem; color: var(--text-muted);">Informasi akun dan identitas resmi petugas loket</p>
                        </div>
                        <span style="background: #eff6ff; color: #2563eb; font-weight: 800; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 999px; border: 1px solid #bfdbfe;">
                            TELLER LOKET
                        </span>
                    </div>

                    <form action="{{ route('profil.update') }}" method="POST" onsubmit="try{ sessionStorage.setItem('teller_active_tab', 'tab-profil'); }catch(e){} return confirm('Konfirmasi: Simpan perubahan data profil Anda?');">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">Nama Lengkap Teller <span style="color: #dc2626;">*</span></label>
                            <input type="text" name="nama_petugas" maxlength="50" class="form-control" required value="{{ old('nama_petugas', $user->nama_petugas) }}" placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">Username Login <span style="color: #dc2626;">*</span></label>
                            <input type="text" name="username" class="form-control" required minlength="3" maxlength="10" value="{{ old('username', $user->username) }}" placeholder="Masukkan username login" autocomplete="username">
                            <small style="color: #64748b; font-size: 0.75rem;">Username digunakan untuk login ke sistem.</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">Alamat Email / Gmail Teller <span style="color: #dc2626;">*</span></label>
                            <input type="email" name="email" maxlength="30" class="form-control" required value="{{ old('email', $user->email) }}" placeholder="Contoh: teller@bankmini.sch.id">
                            <small style="color: #2563eb; font-size: 0.75rem; font-weight: 600;">Penting: Email ini digunakan untuk verifikasi kode OTP saat lupa kata sandi di awal login.</small>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem 1.25rem; font-weight: 700; border-radius: 12px; background: #2563eb; color: #fff; border: none; cursor: pointer;">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KARTU GANTI KATA SANDI MANDIRI -->
                <div class="card" style="background: #ffffff; border-radius: 16px; padding: 1.75rem; border: 1px solid var(--border-color); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);">
                    <div style="margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                        <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Ganti Kata Sandi</h3>
                        <p style="margin: 3px 0 0; font-size: 0.8rem; color: var(--text-muted);">Perbarui kata sandi akun login Anda secara berkala</p>
                    </div>

                    <form action="{{ route('profil.ganti-password') }}" method="POST" onsubmit="try{ sessionStorage.setItem('teller_active_tab', 'tab-profil'); }catch(e){} return confirm('Konfirmasi: Apakah Anda yakin ingin memperbarui kata sandi akun ini?');">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">Kata Sandi Saat Ini <span style="color: #dc2626;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_lama" id="tellerOldPassword" class="form-control" required placeholder="Masukkan kata sandi lama" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('tellerOldPassword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">Kata Sandi Baru (Min. 6 Karakter) <span style="color: #dc2626;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_baru" id="tellerNewPassword" class="form-control" required minlength="6" placeholder="Masukkan kata sandi baru" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('tellerNewPassword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">Konfirmasi Kata Sandi Baru <span style="color: #dc2626;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_baru_confirmation" id="tellerNewPasswordConfirm" class="form-control" required minlength="6" placeholder="Ulangi kata sandi baru" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('tellerNewPasswordConfirm', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem 1.25rem; font-weight: 700; border-radius: 12px; background: #0f172a; color: #fff; border: none; cursor: pointer;">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==========================================================
        // PENCEGAHAN DOUBLE SUBMIT / DOUBLE ENTRY (KLIK GANDA FORM)
        // ==========================================================
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form.prevent-double-submit').forEach(function(form) {
                let isSubmitting = false;

                form.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }

                    if (!form.checkValidity()) {
                        return;
                    }

                    isSubmitting = true;
                    form.dataset.submitting = 'true';

                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);
                        submitBtn.style.opacity = '0.7';
                        submitBtn.style.cursor = 'not-allowed';
                        submitBtn.innerHTML = `
                            <span style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                                <svg style="animation: spinDouble 0.9s linear infinite; width:18px; height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"></path></svg>
                                Sedang Memproses... Harap Tunggu
                            </span>
                        `;
                        // Gunakan setTimeout agar browser menyelesaikan event submit POST sebelum tombol disabled
                        setTimeout(() => {
                            if (isSubmitting) {
                                submitBtn.disabled = true;
                                submitBtn.style.pointerEvents = 'none';
                            }
                        }, 50);
                    }

                    form.style.pointerEvents = 'none';
                });

                form.querySelectorAll('button[type="submit"]').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        if (isSubmitting) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });
                });

                // Reset jika validasi browser membatalkan submit
                form.addEventListener('invalid', function() {
                    isSubmitting = false;
                    form.dataset.submitting = 'false';
                    form.style.pointerEvents = 'auto';
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && submitBtn.hasAttribute('data-original-html')) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                        submitBtn.style.pointerEvents = 'auto';
                        submitBtn.innerHTML = submitBtn.getAttribute('data-original-html');
                    }
                }, true);
            });
        });

        // Reset state jika browser membuka halaman dari BFCache
        window.addEventListener('pageshow', function() {
            document.querySelectorAll('form.prevent-double-submit').forEach(function(form) {
                form.dataset.submitting = 'false';
                form.style.pointerEvents = 'auto';
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.hasAttribute('data-original-html')) {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    submitBtn.style.pointerEvents = 'auto';
                    submitBtn.innerHTML = submitBtn.getAttribute('data-original-html');
                }
            });
        });

        let selectedSaverData = null;

        function applyNavCollapseState(isCollapsed) {
            const navBar = document.getElementById('navTabsBar');
            const btn = document.getElementById('btnToggleNav');
            const icon = document.getElementById('btnToggleNavIcon');
            const text = document.getElementById('btnToggleNavText');
            if (!navBar) return;

            if (isCollapsed) {
                navBar.classList.add('collapsed');
                if (icon) icon.textContent = '▼';
                if (text) text.textContent = 'Besarkan Menu';
                if (btn) {
                    btn.classList.add('nav-is-collapsed');
                    btn.title = 'Besarkan / Tampilkan Navigasi Menu';
                }
            } else {
                navBar.classList.remove('collapsed');
                if (icon) icon.textContent = '▲';
                if (text) text.textContent = 'Kecilkan Menu';
                if (btn) {
                    btn.classList.remove('nav-is-collapsed');
                    btn.title = 'Kecilkan / Sembunyikan Navigasi Menu';
                }
            }
        }

        function toggleNavTabs() {
            const navBar = document.getElementById('navTabsBar');
            if (!navBar) return;
            const willCollapse = !navBar.classList.contains('collapsed');
            applyNavCollapseState(willCollapse);
            try {
                localStorage.setItem('teller_nav_collapsed', willCollapse ? 'true' : 'false');
            } catch(e) {}
        }

        function restoreNavCollapseState() {
            try {
                if (localStorage.getItem('teller_nav_collapsed') === 'true') {
                    applyNavCollapseState(true);
                }
            } catch(e) {}
        }

        function toggleUserMenu(e) {
            if (e) e.stopPropagation();
            const popover = document.getElementById('userDropdownPopover');
            const trigger = document.getElementById('btnUserMenu');
            if (!popover) return;
            const isShown = popover.classList.contains('show');
            if (isShown) {
                closeUserMenu();
            } else {
                popover.classList.add('show');
                if (trigger) trigger.classList.add('active');
            }
        }

        function closeUserMenu() {
            const popover = document.getElementById('userDropdownPopover');
            const trigger = document.getElementById('btnUserMenu');
            if (popover) popover.classList.remove('show');
            if (trigger) trigger.classList.remove('active');
        }

        function bukaHalamanProfil() {
            closeUserMenu();
            switchTab('tab-profil', true);
        }

        window.addEventListener('click', function(e) {
            const container = document.getElementById('userMenuContainer');
            if (container && !container.contains(e.target)) {
                closeUserMenu();
            }
        });

        window.addEventListener('resize', closeUserMenu);
        window.addEventListener('scroll', closeUserMenu, { passive: true });

        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUserMenu();
            }
        });

        function switchTab(tabId, shouldScroll = false) {
            if (!tabId) return;
            const targetContent = document.getElementById(tabId);
            if (!targetContent) return;

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                const oc = btn.getAttribute('onclick');
                if (oc && oc.includes("'" + tabId + "'")) {
                    btn.classList.add('active');
                }
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            targetContent.classList.add('active');

            if (shouldScroll) {
                setTimeout(() => {
                    targetContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 60);
            }

            try {
                sessionStorage.setItem('teller_active_tab', tabId);
                localStorage.setItem('teller_active_tab', tabId);
            } catch (e) {}

            try {
                if (window.history && window.history.replaceState && window.location.hash) {
                    window.history.replaceState(null, null, window.location.pathname + window.location.search);
                }
            } catch (e) {}
        }

        function openModalAktivasi(userId, name, nis, kelas, angkatan, email) {
            document.getElementById('modalAktUserId').value = userId;
            document.getElementById('modalAktNama').innerText = name;
            document.getElementById('modalAktNis').innerText = nis;
            document.getElementById('modalAktKelasAngkatan').innerText = (kelas || '-') + ' / ' + (angkatan || '-');
            document.getElementById('modalAktEmail').value = email || '';
            document.getElementById('modalAktNominal').value = 10000;
            
            const pinInp = document.getElementById('modalAktPin');
            if (pinInp) {
                pinInp.value = '';
                pinInp.type = 'password';
            }
            const pinConfirmInp = document.getElementById('modalAktPinConfirm');
            if (pinConfirmInp) {
                pinConfirmInp.value = '';
                pinConfirmInp.type = 'password';
            }

            const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>`;
            const btnToggle1 = document.getElementById('btnTogglePinAktivasi');
            if (btnToggle1) {
                btnToggle1.innerHTML = eyeSvg;
                btnToggle1.title = 'Lihat PIN';
                btnToggle1.style.color = '#64748b';
            }
            const btnToggle2 = document.getElementById('btnTogglePinConfirmAktivasi');
            if (btnToggle2) {
                btnToggle2.innerHTML = eyeSvg;
                btnToggle2.title = 'Lihat PIN';
                btnToggle2.style.color = '#64748b';
            }

            const modal = document.getElementById('modalAktivasiAnggota');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    document.getElementById('modalAktNominal')?.focus();
                }, 100);
            }
        }

        function closeModalAktivasi() {
            const modal = document.getElementById('modalAktivasiAnggota');
            if (modal) modal.style.display = 'none';
            const pinInp = document.getElementById('modalAktPin');
            if (pinInp) pinInp.value = '';
            const pinConfirmInp = document.getElementById('modalAktPinConfirm');
            if (pinConfirmInp) pinConfirmInp.value = '';
        }

        function validateAktivasiForm(form) {
            const pin = (document.getElementById('modalAktPin')?.value || '').trim();
            const pinConfirm = (document.getElementById('modalAktPinConfirm')?.value || '').trim();
            const email = (document.getElementById('modalAktEmail')?.value || '').trim();

            if (!email) {
                alert('Alamat Gmail siswa / nasabah wajib diisi.');
                document.getElementById('modalAktEmail')?.focus();
                return false;
            }

            if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
                alert('PIN Transaksi harus terdiri dari tepat 6 angka numerik (0-9).');
                document.getElementById('modalAktPin')?.focus();
                return false;
            }

            if (pinConfirm.length !== 6 || !/^\d{6}$/.test(pinConfirm)) {
                alert('Konfirmasi PIN Transaksi harus terdiri dari tepat 6 angka numerik.');
                document.getElementById('modalAktPinConfirm')?.focus();
                return false;
            }

            if (pin !== pinConfirm) {
                alert('Konfirmasi PIN tidak cocok dengan PIN yang dibuat. Pastikan kedua kolom PIN sama!');
                document.getElementById('modalAktPinConfirm')?.focus();
                return false;
            }

            const studentName = document.getElementById('modalAktNama')?.innerText || 'siswa';
            return confirm('Konfirmasi Aktivasi Rekening:\n\nApakah Anda yakin ingin mengaktivasi buku rekening tabungan untuk ' + studentName + ' dengan PIN yang telah dibuat?');
        }

        function setModalAktNominal(val) {
            const input = document.getElementById('modalAktNominal');
            if (input) input.value = val;
        }

        let currentAktivasiPage = 1;
        const AKTIVASI_PER_PAGE = 20;

        function filterAktivasiTable(resetPage = true) {
            if (resetPage) {
                currentAktivasiPage = 1;
            }
            const search = (document.getElementById('searchAktivasiTable')?.value || '').toLowerCase().trim();
            const kelas = (document.getElementById('filterAktivasiKelas')?.value || '').toLowerCase().trim();
            const angkatan = (document.getElementById('filterAktivasiAngkatan')?.value || '').toLowerCase().trim();

            const rows = document.querySelectorAll('.aktivasi-row');
            const matchingRows = [];

            rows.forEach(row => {
                const text = row.getAttribute('data-search') || '';
                const rowKelas = row.getAttribute('data-kelas') || '';
                const rowAngkatan = row.getAttribute('data-angkatan') || '';

                const matchSearch = !search || text.includes(search);
                const matchKelas = !kelas || rowKelas === kelas;
                const matchAngkatan = !angkatan || rowAngkatan === angkatan;

                if (matchSearch && matchKelas && matchAngkatan) {
                    matchingRows.push(row);
                } else {
                    row.style.display = 'none';
                }
            });

            const totalMatches = matchingRows.length;
            const totalPages = Math.ceil(totalMatches / AKTIVASI_PER_PAGE) || 1;

            if (currentAktivasiPage > totalPages) currentAktivasiPage = totalPages;
            if (currentAktivasiPage < 1) currentAktivasiPage = 1;

            const startIndex = (currentAktivasiPage - 1) * AKTIVASI_PER_PAGE;
            const endIndex = Math.min(startIndex + AKTIVASI_PER_PAGE, totalMatches);

            matchingRows.forEach((row, idx) => {
                if (idx >= startIndex && idx < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update UI Pagination Controls
            const pageInfo = document.getElementById('aktivasiPageInfo');
            const pageInput = document.getElementById('aktivasiPageInput');
            const totalPagesEl = document.getElementById('aktivasiTotalPages');
            const btnPrev = document.getElementById('btnAktivasiPrev');
            const btnNext = document.getElementById('btnAktivasiNext');

            if (pageInfo) {
                pageInfo.innerText = totalMatches > 0 
                    ? `${startIndex + 1} - ${endIndex} dari ${totalMatches}` 
                    : `0 dari 0`;
            }
            if (pageInput) {
                pageInput.value = currentAktivasiPage;
                pageInput.max = totalPages;
            }
            if (totalPagesEl) {
                totalPagesEl.innerText = totalPages;
            }
            if (btnPrev) {
                btnPrev.disabled = (currentAktivasiPage <= 1);
                btnPrev.style.opacity = (currentAktivasiPage <= 1) ? '0.4' : '1';
                btnPrev.style.cursor = (currentAktivasiPage <= 1) ? 'not-allowed' : 'pointer';
            }
            if (btnNext) {
                btnNext.disabled = (currentAktivasiPage >= totalPages);
                btnNext.style.opacity = (currentAktivasiPage >= totalPages) ? '0.4' : '1';
                btnNext.style.cursor = (currentAktivasiPage >= totalPages) ? 'not-allowed' : 'pointer';
            }

            const emptyRow = document.getElementById('emptyAktivasiRow');
            if (emptyRow) {
                emptyRow.style.display = totalMatches === 0 ? '' : 'none';
            }
        }

        function prevAktivasiPage() {
            if (currentAktivasiPage > 1) {
                currentAktivasiPage--;
                filterAktivasiTable(false);
            }
        }

        function nextAktivasiPage() {
            currentAktivasiPage++;
            filterAktivasiTable(false);
        }

        function jumpAktivasiPage(val) {
            const pageNum = parseInt(val, 10);
            if (!isNaN(pageNum)) {
                currentAktivasiPage = pageNum;
                filterAktivasiTable(false);
            }
        }

        async function doSearchNasabah() {
            const q = document.getElementById('searchKeyword').value.trim();
            if (!q) return;

            try {
                const res = await fetch('/teller/cari-nasabah?q=' + encodeURIComponent(q));
                const json = await res.json();

                if (json.success) {
                    selectedSaverData = json.data;
                    document.getElementById('infoNamaSiswa').innerText = json.data.nama_siswa;
                    document.getElementById('infoNoRek').innerText = json.data.no_rekening;
                    document.getElementById('infoNisKelas').innerText = json.data.nis + ' (' + json.data.kelas + ')';
                    document.getElementById('infoSaldo').innerText = 'Rp ' + Number(json.data.saldo).toLocaleString('id-ID');
                    document.getElementById('infoStatus').innerText = json.data.status.toUpperCase();
                    document.getElementById('nasabahInfoCard').style.display = 'block';
                } else {
                    alert(json.message);
                    document.getElementById('nasabahInfoCard').style.display = 'none';
                }
            } catch (err) {
                alert('Gagal mencari penabung: ' + err.message);
            }
        }

        function confirmSetoran(form) {
            const select = document.getElementById('setoranNasabahSelect');
            const nasabahText = (select && select.selectedIndex > 0) ? select.options[select.selectedIndex].text.trim() : 'Nasabah';
            const nominal = document.getElementById('setoranNominal')?.value || 0;
            return confirm('Konfirmasi Transaksi Setoran Tunai:\n\n' +
                'Nasabah: ' + nasabahText + '\n' +
                'Nominal: Rp ' + Number(nominal).toLocaleString('id-ID') + '\n\n' +
                'Apakah Anda yakin ingin memproses setoran tunai ini?');
        }

        function confirmPenarikan(form) {
            const select = document.getElementById('penarikanNasabahSelect');
            const nasabahText = (select && select.selectedIndex > 0) ? select.options[select.selectedIndex].text.trim() : 'Nasabah';
            const nominal = document.getElementById('penarikanNominal')?.value || 0;
            return confirm('Konfirmasi Transaksi Penarikan Tunai:\n\n' +
                'Nasabah: ' + nasabahText + '\n' +
                'Nominal: Rp ' + Number(nominal).toLocaleString('id-ID') + '\n\n' +
                'Pastikan uang kas fisik telah disiapkan di loket dan PIN penabung telah terverifikasi.\nApakah Anda yakin ingin memproses penarikan ini?');
        }

        function closeModalTransaksiGagal() {
            const modal = document.getElementById('modalTransaksiGagal');
            if (modal) modal.style.display = 'none';
            const pinInput = document.getElementById('penarikanPin');
            if (pinInput && document.querySelector('#tab-penarikan.active')) {
                pinInput.focus();
                pinInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function goToResetPinFromGagal() {
            const modal = document.getElementById('modalTransaksiGagal');
            if (modal) modal.style.display = 'none';
            selectForResetPin();
        }

        function selectForSetoran() {
            if (!selectedSaverData) return;
            switchTab('tab-setoran', true);
            const sel = document.getElementById('setoranNasabahSelect');
            if (sel) {
                sel.value = selectedSaverData.id;
            }
            setTimeout(() => {
                const target = document.getElementById('tab-setoran');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                document.getElementById('setoranNominal')?.focus();
            }, 100);
        }

        function onPenarikanSelectChange() {
            const sel = document.getElementById('penarikanNasabahSelect');
            const box = document.getElementById('penarikanInfoBox');
            const selectPj = document.getElementById('selectPerjanjianH1');
            const wrapPj = document.getElementById('perjanjianTerdaftarWrap');
            if (!box || !sel) return;
            const opt = sel.options[sel.selectedIndex];
            if (sel.value && opt) {
                const saldo = parseInt(opt.getAttribute('data-saldo') || '0', 10);
                const penarikanHariIni = parseInt(opt.getAttribute('data-penarikan-hari-ini') || '0', 10);
                const maxSaldoTarik = Math.max(0, saldo - 10000);
                const sisaLimitReguler = Math.max(0, 1000000 - penarikanHariIni);
                const maxRegulerTarik = Math.min(sisaLimitReguler, maxSaldoTarik);

                document.getElementById('penarikanInfoSaldo').innerText = 'Rp ' + saldo.toLocaleString('id-ID');
                document.getElementById('penarikanInfoMaksimal').innerText = 'Rp ' + maxRegulerTarik.toLocaleString('id-ID');
                const elHariIni = document.getElementById('penarikanInfoHariIni');
                if (elHariIni) {
                    elHariIni.innerText = 'Rp ' + penarikanHariIni.toLocaleString('id-ID') + (penarikanHariIni > 0 ? ' (Sisa jatah reguler: Rp ' + sisaLimitReguler.toLocaleString('id-ID') + ')' : '');
                }
                box.style.display = 'block';

                // Populate active reservations for today if any
                if (selectPj && wrapPj) {
                    selectPj.innerHTML = '<option value="">-- Pilih Reservasi H-1 --</option>';
                    try {
                        const pjList = JSON.parse(opt.getAttribute('data-perjanjian') || '[]');
                        if (pjList && pjList.length > 0) {
                            pjList.forEach(p => {
                                const optPj = document.createElement('option');
                                optPj.value = p.id;
                                optPj.text = `Rp ${Number(p.nominal).toLocaleString('id-ID')} (${p.status.toUpperCase()} - ${p.alasan || 'Penarikan Khusus'})`;
                                selectPj.appendChild(optPj);
                            });
                            wrapPj.style.display = 'block';
                        } else {
                            wrapPj.style.display = 'none';
                        }
                    } catch(e) {
                        wrapPj.style.display = 'none';
                    }
                }
            } else {
                box.style.display = 'none';
                if (wrapPj) wrapPj.style.display = 'none';
            }
            validatePenarikanNominalRealtime();
        }

        function validatePenarikanNominalRealtime() {
            const sel = document.getElementById('penarikanNasabahSelect');
            const nominalInput = document.getElementById('penarikanNominal');
            const warnBox = document.getElementById('penarikanWarningMsg');
            const submitBtn = document.getElementById('penarikanSubmitBtn');
            const boxH1 = document.getElementById('boxPerjanjianH1');
            const selectPj = document.getElementById('selectPerjanjianH1');
            const checkManual = document.getElementById('checkPerjanjianManual');
            if (!nominalInput || !warnBox) return true;

            const val = parseInt(nominalInput.value || '0', 10);
            const opt = (sel && sel.selectedIndex >= 0) ? sel.options[sel.selectedIndex] : null;
            const hasSaver = Boolean(sel && sel.value && opt);
            const saldo = hasSaver ? parseInt(opt.getAttribute('data-saldo') || '0', 10) : null;
            const penarikanHariIni = hasSaver ? parseInt(opt.getAttribute('data-penarikan-hari-ini') || '0', 10) : 0;
            const maxSaldoTarik = (saldo !== null) ? Math.max(0, saldo - 10000) : null;
            const kasLaci = {{ (int)$sisaKasLaci }};

            // 1. Cek saldo mengendap minimal Rp 10.000
            if (val > 0 && maxSaldoTarik !== null && val > maxSaldoTarik) {
                if (boxH1) boxH1.style.display = 'none';
                warnBox.style.display = 'block';
                warnBox.style.background = '#fef2f2';
                warnBox.style.borderColor = '#fecaca';
                warnBox.style.color = '#dc2626';
                warnBox.innerHTML = `⚠️ <strong>Nominal Melebihi Batas Saldo!</strong> Nasabah hanya bisa menarik maksimal <strong>Rp ${maxSaldoTarik.toLocaleString('id-ID')}</strong> (Saldo saat ini Rp ${saldo.toLocaleString('id-ID')}, wajib sisa mengendap minimal Rp 10.000).`;
                nominalInput.style.borderColor = '#dc2626';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                }
                return false;
            }

            // 2. Cek kas laci
            if (val > 0 && val > kasLaci) {
                if (boxH1) boxH1.style.display = 'none';
                warnBox.style.display = 'block';
                warnBox.style.background = '#fffbeb';
                warnBox.style.borderColor = '#fde68a';
                warnBox.style.color = '#b45309';
                warnBox.innerHTML = `⚠️ <strong>Kas di Laci Tidak Cukup!</strong> Fisik kas teller saat ini hanya <strong>Rp ${kasLaci.toLocaleString('id-ID')}</strong>. Ajukan tambahan kas ke Admin terlebih dahulu.`;
                nominalInput.style.borderColor = '#f59e0b';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                }
                return false;
            }

            // 3. Cek minimal Rp 10.000
            if (val > 0 && val < 10000) {
                if (boxH1) boxH1.style.display = 'none';
                warnBox.style.display = 'block';
                warnBox.style.background = '#fef2f2';
                warnBox.style.borderColor = '#fecaca';
                warnBox.style.color = '#dc2626';
                warnBox.innerHTML = `⚠️ <strong>Minimal Penarikan:</strong> Nominal minimal penarikan tunai adalah Rp 10.000.`;
                nominalInput.style.borderColor = '#dc2626';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                }
                return false;
            }

            // 4. Cek aturan penarikan di atas Rp 1.000.000 (Wajib Perjanjian Minimal H-1)
            const isOverLimit = (val > 1000000) || ((penarikanHariIni + val) > 1000000);
            if (val > 0 && isOverLimit) {
                if (boxH1) boxH1.style.display = 'block';

                const hasPjSelected = Boolean(selectPj && selectPj.value);
                const hasManualCheck = Boolean(checkManual && checkManual.checked);

                if (!hasPjSelected && !hasManualCheck) {
                    warnBox.style.display = 'block';
                    warnBox.style.background = '#fffbeb';
                    warnBox.style.borderColor = '#fde68a';
                    warnBox.style.color = '#92400e';
                    warnBox.innerHTML = `⚠️ <strong>Wajib Perjanjian H-1:</strong> Penarikan di atas Rp 1.000.000 (atau akumulasi hari ini melebihi Rp 1 Juta) wajib diverifikasi perjanjian H-1. Silakan pilih nomor reservasi H-1 atau centang konfirmasi perjanjian manual.`;
                    nominalInput.style.borderColor = '#f59e0b';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.5';
                        submitBtn.style.cursor = 'not-allowed';
                    }
                    return false;
                } else {
                    warnBox.style.display = 'none';
                    warnBox.innerHTML = '';
                    nominalInput.style.borderColor = '#16a34a';
                    if (submitBtn && (!submitBtn.closest('form') || submitBtn.closest('form').dataset.submitting !== 'true')) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                    }
                    return true;
                }
            } else {
                if (boxH1) boxH1.style.display = 'none';
            }

            // Valid normal
            warnBox.style.display = 'none';
            warnBox.innerHTML = '';
            nominalInput.style.borderColor = '';
            if (submitBtn && (!submitBtn.closest('form') || submitBtn.closest('form').dataset.submitting !== 'true')) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
            return true;
        }

        function selectPerjanjianForPenarikan(saverId, nominal, idPj) {
            switchTab('tab-penarikan', true);
            const sel = document.getElementById('penarikanNasabahSelect');
            if (sel) {
                sel.value = saverId;
            }
            const nom = document.getElementById('penarikanNominal');
            if (nom) {
                nom.value = nominal;
            }
            onPenarikanSelectChange();

            setTimeout(() => {
                const selectPj = document.getElementById('selectPerjanjianH1');
                if (selectPj) {
                    selectPj.value = idPj;
                }
                const checkManual = document.getElementById('checkPerjanjianManual');
                if (checkManual) {
                    checkManual.checked = true;
                }
                validatePenarikanNominalRealtime();
                const target = document.getElementById('tab-penarikan');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                document.getElementById('penarikanPin')?.focus();
            }, 150);
        }

        function openTolakPerjanjianModal(idPj, nama, nominal) {
            const form = document.getElementById('rejectPerjanjianForm');
            if (form) form.action = '/teller/perjanjian/' + idPj + '/reject';
            document.getElementById('rejectPjNasabah').innerText = nama;
            document.getElementById('rejectPjNominal').innerText = 'Rp ' + nominal;
            document.getElementById('rejectPjCatatanInput').value = '';
            document.getElementById('rejectPerjanjianModal').style.display = 'flex';
        }

        function closeTolakPerjanjianModal() {
            document.getElementById('rejectPerjanjianModal').style.display = 'none';
        }

        // filter data status perjanjian penarikan teller
        function filterPerjanjianStatus(status) {
            document.querySelectorAll('.btn-filter-pj').forEach(btn => {
                btn.style.background = '#f8fafc';
                btn.style.color = '#475569';
                btn.style.borderColor = '#cbd5e1';
            });

            const activeBtn = document.getElementById('btnFilterPj' + (status.charAt(0).toUpperCase() + status.slice(1)));
            if (activeBtn) {
                if (status === 'all') {
                    activeBtn.style.background = '#2563eb';
                    activeBtn.style.color = '#ffffff';
                    activeBtn.style.borderColor = '#2563eb';
                } else if (status === 'menunggu') {
                    activeBtn.style.background = '#d97706';
                    activeBtn.style.color = '#ffffff';
                    activeBtn.style.borderColor = '#d97706';
                } else if (status === 'selesai') {
                    activeBtn.style.background = '#2563eb';
                    activeBtn.style.color = '#ffffff';
                    activeBtn.style.borderColor = '#2563eb';
                }
            }

            const rows = document.querySelectorAll('.row-perjanjian');
            let visibleCount = 0;
            rows.forEach(r => {
                const s = r.getAttribute('data-status');
                if (status === 'all' || s === status) {
                    r.style.display = '';
                    visibleCount++;
                } else {
                    r.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('rowPerjanjianEmptyFilter');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        function selectForPenarikan() {
            if (!selectedSaverData) return;
            switchTab('tab-penarikan', true);
            const sel = document.getElementById('penarikanNasabahSelect');
            if (sel) {
                sel.value = selectedSaverData.id;
            }
            onPenarikanSelectChange();
            setTimeout(() => {
                const target = document.getElementById('tab-penarikan');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                document.getElementById('penarikanNominal')?.focus();
            }, 100);
        }

        function selectForResetPin() {
            if (!selectedSaverData) return;
            switchTab('tab-reset-pin', true);
            const sel = document.getElementById('resetPinSelect');
            if (sel) {
                sel.value = selectedSaverData.id;
                onTabResetPinSelectChange();
            }
            const idInput = document.getElementById('tabResetPinIdentifier');
            const emailInput = document.getElementById('tabResetPinEmail');
            if (idInput && (selectedSaverData.no_rekening || selectedSaverData.nis)) {
                idInput.value = selectedSaverData.no_rekening || selectedSaverData.nis;
            }
            if (emailInput && selectedSaverData.email) {
                emailInput.value = selectedSaverData.email;
            }
            // kunci identitas dan email otomatis dari hasil scan
            lockResetPinFields();
            goToTabResetPinStep1();
            setTimeout(() => {
                const target = document.getElementById('tab-reset-pin');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }

        // kunci input identitas dan gmail agar tidak dapat diedit setelah scan qr
        function lockResetPinFields() {
            const sel = document.getElementById('resetPinSelect');
            const idInput = document.getElementById('tabResetPinIdentifier');
            const emailInput = document.getElementById('tabResetPinEmail');
            const notice = document.getElementById('tabResetPinLockedNotice');

            if (sel) {
                sel.disabled = true;
                sel.style.background = '#f1f5f9';
                sel.style.cursor = 'not-allowed';
            }
            if (idInput) {
                idInput.readOnly = true;
                idInput.style.background = '#f1f5f9';
                idInput.style.cursor = 'not-allowed';
            }
            if (emailInput) {
                emailInput.readOnly = true;
                emailInput.style.background = '#f1f5f9';
                emailInput.style.cursor = 'not-allowed';
            }
            if (notice) {
                notice.style.display = 'flex';
            }
        }

        // buka kunci input jika ingin memilih penabung secara manual
        function unlockResetPinFields() {
            const sel = document.getElementById('resetPinSelect');
            const idInput = document.getElementById('tabResetPinIdentifier');
            const emailInput = document.getElementById('tabResetPinEmail');
            const notice = document.getElementById('tabResetPinLockedNotice');

            if (sel) {
                sel.disabled = false;
                sel.style.background = '#ffffff';
                sel.style.cursor = 'default';
            }
            if (idInput) {
                idInput.readOnly = false;
                idInput.style.background = '#ffffff';
                idInput.style.cursor = 'text';
            }
            if (emailInput) {
                emailInput.readOnly = false;
                emailInput.style.background = '#ffffff';
                emailInput.style.cursor = 'text';
            }
            if (notice) {
                notice.style.display = 'none';
            }
        }

        // ====================================================
        // FITUR TAB 4: LUPA / RESET PIN DENGAN KODE OTP
        // ====================================================
        let tabResetPinResetToken = null;
        let tabResetPinOtpTimer = null;

        function onTabResetPinSelectChange() {
            const sel = document.getElementById('resetPinSelect');
            const opt = sel && sel.selectedIndex > 0 ? sel.options[sel.selectedIndex] : null;
            const idInput = document.getElementById('tabResetPinIdentifier');
            const emailInput = document.getElementById('tabResetPinEmail');

            if (opt) {
                const norek = opt.getAttribute('data-norek') || '';
                const nis = opt.getAttribute('data-nis') || '';
                const email = opt.getAttribute('data-email') || '';
                if (idInput) idInput.value = norek || nis;
                if (emailInput) emailInput.value = email;
            }
            hideTabResetPinAlert(1);
            hideTabResetPinAlert(2);
        }

        function goToTabResetPinStep1() {
            const s1 = document.getElementById('tabResetPinStep1');
            const s2 = document.getElementById('tabResetPinStep2');
            if (s1) s1.style.display = 'block';
            if (s2) s2.style.display = 'none';
            hideTabResetPinAlert(1);
            hideTabResetPinAlert(2);
        }

        function showTabResetPinAlert(step, msg, type) {
            const el = document.getElementById('tabResetPinAlert' + step);
            if (!el) return;
            el.style.display = 'block';
            el.innerText = msg;
            if (type === 'success') {
                el.style.background = '#dcfce7';
                el.style.color = '#15803d';
                el.style.border = '1px solid #bbf7d0';
            } else {
                el.style.background = '#fef2f2';
                el.style.color = '#b91c1c';
                el.style.border = '1px solid #fecaca';
            }
        }

        function hideTabResetPinAlert(step) {
            const el = document.getElementById('tabResetPinAlert' + step);
            if (el) el.style.display = 'none';
        }

        async function handleTabKirimOtp() {
            const identifier = document.getElementById('tabResetPinIdentifier').value.trim();
            const email = document.getElementById('tabResetPinEmail').value.trim();
            const btn = document.getElementById('btnTabKirimOtp');

            hideTabResetPinAlert(1);

            if (!identifier) {
                showTabResetPinAlert(1, 'Pilih penabung atau ketik NIS / Nomor Rekening siswa terlebih dahulu.', 'error');
                document.getElementById('tabResetPinIdentifier').focus();
                return;
            }
            if (!email) {
                showTabResetPinAlert(1, 'Masukkan alamat Gmail siswa untuk menerima kode OTP.', 'error');
                document.getElementById('tabResetPinEmail').focus();
                return;
            }

            btn.disabled = true;
            const orig = btn.innerText;
            btn.innerText = 'Mengirim...';

            try {
                const res = await fetch('{{ route("lupa-sandi.kirim-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier, email })
                });
                let data = null;
                try { data = await res.json(); } catch (_) { data = null; }

                if (res.ok && data && data.success) {
                    showTabResetPinAlert(1, data.message, 'success');
                    document.getElementById('tabResetPinOtpCode').focus();

                    let t = 60;
                    btn.innerText = 'Ulang (' + t + 's)';
                    if (tabResetPinOtpTimer) clearInterval(tabResetPinOtpTimer);
                    tabResetPinOtpTimer = setInterval(() => {
                        t--;
                        if (t <= 0) {
                            clearInterval(tabResetPinOtpTimer);
                            tabResetPinOtpTimer = null;
                            btn.disabled = false;
                            btn.innerText = 'Kirim Ulang OTP';
                        } else {
                            btn.innerText = 'Ulang (' + t + 's)';
                        }
                    }, 1000);
                } else {
                    const msg = (data && data.message) ? data.message : ('Gagal mengirim kode OTP (Status ' + res.status + '). Silakan periksa kembali alamat Gmail atau koneksi server.');
                    showTabResetPinAlert(1, msg, 'error');
                    btn.disabled = false;
                    btn.innerText = orig;
                }
            } catch (e) {
                showTabResetPinAlert(1, 'Terjadi kesalahan sistem saat kirim OTP: ' + e.message, 'error');
                btn.disabled = false;
                btn.innerText = orig;
            }
        }

        async function handleTabVerifOtp() {
            const identifier = document.getElementById('tabResetPinIdentifier').value.trim();
            const email = document.getElementById('tabResetPinEmail').value.trim();
            const otp = document.getElementById('tabResetPinOtpCode').value.trim();
            const btn = document.getElementById('btnTabVerifOtp');

            hideTabResetPinAlert(1);

            if (!identifier || !email) {
                showTabResetPinAlert(1, 'Mohon lengkapi identitas dan email siswa.', 'error');
                return;
            }
            if (!otp || otp.length < 6) {
                showTabResetPinAlert(1, 'Masukkan 6 digit kode OTP yang diterima di Gmail siswa.', 'error');
                document.getElementById('tabResetPinOtpCode').focus();
                return;
            }

            btn.disabled = true;
            const orig = btn.innerText;
            btn.innerText = 'Memverifikasi OTP...';

            try {
                const res = await fetch('{{ route("lupa-sandi.verifikasi-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier, email, otp })
                });
                let data = null;
                try { data = await res.json(); } catch (_) { data = null; }

                if (res.ok && data && data.success) {
                    tabResetPinResetToken = data.reset_token;
                    document.getElementById('tabResetPinNamaSiswa').innerText = data.nama || identifier;
                    document.getElementById('tabResetPinRekeningSiswa').innerText = 'NIS / Akun: ' + (data.username || identifier);

                    document.getElementById('tabResetPinStep1').style.display = 'none';
                    document.getElementById('tabResetPinStep2').style.display = 'block';
                    document.getElementById('tabResetPinBaru').value = '';
                    document.getElementById('tabResetPinBaruConfirm').value = '';
                    document.getElementById('tabResetPinBaru').focus();
                } else {
                    const msg = (data && data.message) ? data.message : ('Kode OTP tidak valid atau kadaluarsa (Status ' + res.status + ').');
                    showTabResetPinAlert(1, msg, 'error');
                }
            } catch (e) {
                showTabResetPinAlert(1, 'Gagal verifikasi OTP: ' + e.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = orig;
            }
        }

        async function handleTabSimpanPin() {
            const pin = document.getElementById('tabResetPinBaru').value.trim();
            const pinConfirm = document.getElementById('tabResetPinBaruConfirm').value.trim();
            const btn = document.getElementById('btnTabSimpanPin');

            hideTabResetPinAlert(2);

            if (!pin || pin.length !== 6 || !/^\d+$/.test(pin)) {
                showTabResetPinAlert(2, 'PIN baru wajib berupa 6 angka digit.', 'error');
                document.getElementById('tabResetPinBaru').focus();
                return;
            }

            if (pin !== pinConfirm) {
                showTabResetPinAlert(2, 'Konfirmasi PIN baru tidak cocok.', 'error');
                document.getElementById('tabResetPinBaruConfirm').focus();
                return;
            }

            if (!tabResetPinResetToken) {
                showTabResetPinAlert(2, 'Sesi verifikasi kadaluarsa. Silakan ulangi.', 'error');
                return;
            }

            if (!confirm('Konfirmasi Reset PIN:\n\nApakah Anda yakin ingin menyimpan PIN 6-digit baru ini untuk nasabah?')) {
                return;
            }

            btn.disabled = true;
            const orig = btn.innerText;
            btn.innerText = 'Menyimpan PIN...';

            try {
                const res = await fetch('{{ route("lupa-pin.simpan-pin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reset_token: tabResetPinResetToken,
                        pin: pin,
                        pin_confirmation: pinConfirm
                    })
                });
                let data = null;
                try { data = await res.json(); } catch (_) { data = null; }

                if (res.ok && data && data.success) {
                    const penarikanPinInput = document.getElementById('penarikanPin');
                    if (penarikanPinInput) {
                        penarikanPinInput.value = pin;
                    }
                    alert('✓ Berhasil! PIN 6-digit baru nasabah telah berhasil disimpan dan diaktifkan melalui verifikasi OTP.');
                    goToTabResetPinStep1();
                    document.getElementById('tabResetPinOtpCode').value = '';
                    document.getElementById('tabResetPinBaru').value = '';
                    document.getElementById('tabResetPinBaruConfirm').value = '';
                    tabResetPinResetToken = null;
                } else {
                    const msg = (data && data.message) ? data.message : ('Gagal menyimpan PIN baru (Status ' + res.status + '). Silakan coba lagi.');
                    showTabResetPinAlert(2, msg, 'error');
                }
            } catch (e) {
                showTabResetPinAlert(2, 'Gagal menyimpan PIN: ' + e.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = orig;
            }
        }

        // ==========================================
        // FITUR PEMULIHAN / LUPA PIN DENGAN KODE OTP
        // ==========================================
        let lupaPinResetToken = null;
        let lupaPinOtpTimer = null;

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const eyeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>`;
            const eyeOffSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>`;

            btn.innerHTML = isPassword ? eyeOffSvg : eyeSvg;
            btn.title = isPassword ? 'Sembunyikan' : 'Lihat';
            btn.style.color = isPassword ? '#2563eb' : '#64748b';
        }

        function openLupaPinOtpModal() {
            const sel = document.getElementById('penarikanNasabahSelect');
            const opt = sel && sel.selectedIndex > 0 ? sel.options[sel.selectedIndex] : null;

            const idInput = document.getElementById('lupaPinIdentifier');
            const emailInput = document.getElementById('lupaPinEmail');
            const otpInput = document.getElementById('lupaPinOtpCode');

            if (opt) {
                const norek = opt.getAttribute('data-norek') || '';
                const nis = opt.getAttribute('data-nis') || '';
                const email = opt.getAttribute('data-email') || '';
                idInput.value = norek || nis;
                emailInput.value = email;
            } else if (typeof selectedSaverData !== 'undefined' && selectedSaverData) {
                idInput.value = selectedSaverData.no_rekening || selectedSaverData.nis;
                emailInput.value = selectedSaverData.email || '';
            }

            if (otpInput) otpInput.value = '';
            goToLupaPinStep1();
            document.getElementById('modalLupaPinOtp').style.display = 'flex';
        }

        function closeLupaPinOtpModal() {
            document.getElementById('modalLupaPinOtp').style.display = 'none';
            if (lupaPinOtpTimer) {
                clearInterval(lupaPinOtpTimer);
                lupaPinOtpTimer = null;
            }
        }

        function goToLupaPinStep1() {
            document.getElementById('lupaPinStep2').style.display = 'none';
            document.getElementById('lupaPinStep1').style.display = 'block';
            hideLupaPinAlert(1);
            hideLupaPinAlert(2);
        }

        function showLupaPinAlert(step, msg, type) {
            const el = document.getElementById('lupaPinAlert' + step);
            if (!el) return;
            el.style.display = 'block';
            el.innerText = msg;
            if (type === 'success') {
                el.style.background = '#dcfce7';
                el.style.color = '#15803d';
                el.style.border = '1px solid #bbf7d0';
            } else {
                el.style.background = '#fef2f2';
                el.style.color = '#b91c1c';
                el.style.border = '1px solid #fecaca';
            }
        }

        function hideLupaPinAlert(step) {
            const el = document.getElementById('lupaPinAlert' + step);
            if (el) el.style.display = 'none';
        }

        async function handleLupaPinKirimOtp() {
            const identifier = document.getElementById('lupaPinIdentifier').value.trim();
            const email = document.getElementById('lupaPinEmail').value.trim();
            const btn = document.getElementById('btnLupaPinKirimOtp');

            hideLupaPinAlert(1);

            if (!identifier) {
                showLupaPinAlert(1, 'Masukkan NIS / Nomor Rekening siswa terlebih dahulu.', 'error');
                return;
            }
            if (!email) {
                showLupaPinAlert(1, 'Masukkan alamat Gmail siswa untuk menerima kode OTP.', 'error');
                return;
            }

            btn.disabled = true;
            const orig = btn.innerText;
            btn.innerText = 'Mengirim...';

            try {
                const res = await fetch('{{ route("lupa-sandi.kirim-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier, email })
                });
                let data = null;
                try { data = await res.json(); } catch (_) { data = null; }

                if (res.ok && data && data.success) {
                    showLupaPinAlert(1, data.message, 'success');
                    document.getElementById('lupaPinOtpCode').focus();

                    let t = 60;
                    btn.innerText = 'Ulang (' + t + 's)';
                    if (lupaPinOtpTimer) clearInterval(lupaPinOtpTimer);
                    lupaPinOtpTimer = setInterval(() => {
                        t--;
                        if (t <= 0) {
                            clearInterval(lupaPinOtpTimer);
                            lupaPinOtpTimer = null;
                            btn.disabled = false;
                            btn.innerText = 'Kirim Ulang OTP';
                        } else {
                            btn.innerText = 'Ulang (' + t + 's)';
                        }
                    }, 1000);
                } else {
                    const msg = (data && data.message) ? data.message : ('Gagal mengirim kode OTP (Status ' + res.status + '). Silakan coba lagi.');
                    showLupaPinAlert(1, msg, 'error');
                    btn.disabled = false;
                    btn.innerText = orig;
                }
            } catch (e) {
                showLupaPinAlert(1, 'Terjadi kesalahan sistem saat kirim OTP: ' + e.message, 'error');
                btn.disabled = false;
                btn.innerText = orig;
            }
        }

        async function handleLupaPinVerifOtp() {
            const identifier = document.getElementById('lupaPinIdentifier').value.trim();
            const email = document.getElementById('lupaPinEmail').value.trim();
            const otp = document.getElementById('lupaPinOtpCode').value.trim();
            const btn = document.getElementById('btnLupaPinVerifOtp');

            hideLupaPinAlert(1);

            if (!identifier || !email) {
                showLupaPinAlert(1, 'Mohon lengkapi identitas dan email.', 'error');
                return;
            }
            if (!otp || otp.length < 6) {
                showLupaPinAlert(1, 'Masukkan 6 digit kode OTP yang diterima di Gmail.', 'error');
                document.getElementById('lupaPinOtpCode').focus();
                return;
            }

            btn.disabled = true;
            const orig = btn.innerText;
            btn.innerText = 'Memverifikasi...';

            try {
                const res = await fetch('{{ route("lupa-sandi.verifikasi-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier, email, otp })
                });
                let data = null;
                try { data = await res.json(); } catch (_) { data = null; }

                if (res.ok && data && data.success) {
                    lupaPinResetToken = data.reset_token;
                    document.getElementById('lupaPinNamaSiswa').innerText = data.nama || identifier;
                    document.getElementById('lupaPinRekeningSiswa').innerText = 'NIS / Akun: ' + (data.username || identifier);

                    document.getElementById('lupaPinStep1').style.display = 'none';
                    document.getElementById('lupaPinStep2').style.display = 'block';
                    document.getElementById('lupaPinBaru').focus();
                } else {
                    const msg = (data && data.message) ? data.message : ('Kode OTP tidak valid atau terjadi kendala sistem (Status ' + res.status + ').');
                    showLupaPinAlert(1, msg, 'error');
                }
            } catch (e) {
                showLupaPinAlert(1, 'Gagal verifikasi OTP: ' + e.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = orig;
            }
        }

        async function handleLupaPinSimpan() {
            const pin = document.getElementById('lupaPinBaru').value.trim();
            const pinConfirm = document.getElementById('lupaPinBaruConfirm').value.trim();
            const btn = document.getElementById('btnLupaPinSimpan');

            hideLupaPinAlert(2);

            if (!pin || pin.length !== 6 || !/^\d+$/.test(pin)) {
                showLupaPinAlert(2, 'PIN baru wajib berupa 6 angka digit.', 'error');
                document.getElementById('lupaPinBaru').focus();
                return;
            }

            if (pin !== pinConfirm) {
                showLupaPinAlert(2, 'Konfirmasi PIN baru tidak cocok.', 'error');
                document.getElementById('lupaPinBaruConfirm').focus();
                return;
            }

            if (!lupaPinResetToken) {
                showLupaPinAlert(2, 'Sesi verifikasi kadaluarsa. Silakan ulangi.', 'error');
                return;
            }

            if (!confirm('Konfirmasi Reset PIN:\n\nApakah Anda yakin ingin menyimpan PIN 6-digit baru ini untuk nasabah?')) {
                return;
            }

            btn.disabled = true;
            const orig = btn.innerText;
            btn.innerText = 'Menyimpan PIN...';

            try {
                const res = await fetch('{{ route("lupa-pin.simpan-pin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reset_token: lupaPinResetToken,
                        pin: pin,
                        pin_confirmation: pinConfirm
                    })
                });
                let data = null;
                try { data = await res.json(); } catch (_) { data = null; }

                if (res.ok && data && data.success) {
                    const penarikanPinInput = document.getElementById('penarikanPin');
                    if (penarikanPinInput) {
                        penarikanPinInput.value = pin;
                    }
                    closeLupaPinOtpModal();
                    alert('✓ Berhasil! PIN 6-digit baru nasabah berhasil disimpan dan telah otomatis terpasang pada form penarikan.');
                    if (penarikanPinInput) penarikanPinInput.focus();
                } else {
                    const msg = (data && data.message) ? data.message : ('Gagal menyimpan PIN baru (Status ' + res.status + '). Silakan coba lagi.');
                    showLupaPinAlert(2, msg, 'error');
                }
            } catch (e) {
                showLupaPinAlert(2, 'Gagal menyimpan PIN: ' + e.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = orig;
            }
        }

        function cetakStruk(noTrans, nama, noRek, jenis, nominal, waktu, saldoAkhir) {
            const printWindow = window.open('', '', 'width=450,height=680');
            const jenisText = String(jenis || '').toUpperCase();
            const isPenarikan = jenisText.includes('PENARIKAN') || jenisText.includes('TARIK');

            const subJudul = isPenarikan ? 'STRUK BUKTI PENARIKAN TUNAI' : 'STRUK BUKTI SETORAN TUNAI';
            const labelNominal = isPenarikan ? 'Nominal Tarik' : 'Nominal Setor';

            let saldoRow = '';
            if (saldoAkhir && String(saldoAkhir).trim() !== '' && String(saldoAkhir).trim() !== '0') {
                saldoRow = '<div class="row"><span>Saldo Akhir</span><span class="bold">Rp ' + saldoAkhir + '</span></div>';
            }

            let ttdSection = '';
            if (isPenarikan) {
                ttdSection = `
                    <div class="hr"></div>
                    <div style="display: flex; justify-content: space-between; text-align: center; font-size: 8.5pt; margin-top: 15px;">
                        <div style="width: 45%;">
                            Petugas Teller<br><br><br><br>
                            ( .................... )
                        </div>
                        <div style="width: 45%;">
                            Penerima / Siswa<br><br><br><br>
                            ( .................... )
                        </div>
                    </div>
                `;
            }

            const pesanFooter = isPenarikan
                ? 'Harap periksa dan hitung kembali uang tunai yang diterima sebelum meninggalkan loket.<br>Dokumen ini merupakan bukti transaksi penarikan yang sah.'
                : 'Simpan struk ini sebagai bukti setoran yang sah.<br>Terima kasih telah menabung di Bank Mini Sekolah!';

            const html = `<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>` + subJudul + ` - ` + noTrans + `</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10.5pt;
            padding: 20px;
            line-height: 1.5;
            color: #000000;
            background: #ffffff;
            max-width: 380px;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .hr { border-bottom: 1px dashed #000; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .bold { font-weight: bold; }
        @media print {
            body { max-width: 100%; padding: 5px; }
        }
    </style>
</head>
<body>
    <div class="text-center">
        <strong style="font-size: 13pt;">BANK MINI SEKOLAH</strong><br>
        ` + subJudul + `<br>
        ================================
    </div>
    <div class="hr"></div>
    <div class="row"><span>Waktu</span><span>` + waktu + `</span></div>
    <div class="row"><span>No Trx</span><span class="bold">` + noTrans + `</span></div>
    <div class="row"><span>No Rek</span><span>` + noRek + `</span></div>
    <div class="row"><span>Penabung</span><span class="bold">` + nama + `</span></div>
    <div class="row"><span>Jenis Trx</span><span class="bold">` + jenisText + `</span></div>
    <div class="row"><span>` + labelNominal + `</span><span class="bold">Rp ` + nominal + `</span></div>
    ` + saldoRow + `
    ` + ttdSection + `
    <div class="hr"></div>
    <div class="text-center" style="font-size: 8.5pt; margin-top: 8px;">
        ` + pesanFooter + `
    </div>
</body>
</html>`;
            printWindow.document.open();
            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); }, 250);
        }

        // ==========================================
        // FITUR SCANNER BARCODE & QR CODE
        // ==========================================
        let html5QrScanner = null;
        let isScannerRunning = false;
        let currentFacingMode = 'environment'; // Default Kamera Belakang
        let availableCameraDevices = [];

        async function openCameraModal() {
            if (window.location.protocol === 'http:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                window.location.protocol = 'https:';
                return;
            }

            const modal = document.getElementById('cameraModal');
            if (modal) {
                modal.classList.add('active');
                modal.style.display = 'flex';
            }
            const laser = document.getElementById('scannerLaser');
            if (laser) laser.style.display = 'block';
            const corners = document.getElementById('scannerCorners');
            if (corners) corners.style.display = 'block';
            const statusMsg = document.getElementById('scannerStatusMsg');
            if (statusMsg) statusMsg.innerText = 'Menghubungkan ke kamera...';

            await populateCameraDevices();
            await startCameraScanner();
        }

        async function populateCameraDevices() {
            const select = document.getElementById('cameraSelect');
            if (!select) return;

            const prevVal = select.value;
            select.innerHTML = '';

            const optBack = document.createElement('option');
            optBack.value = 'environment';
            optBack.text = 'Kamera Belakang (Utama)';
            select.appendChild(optBack);

            const optFront = document.createElement('option');
            optFront.value = 'user';
            optFront.text = 'Kamera Depan';
            select.appendChild(optFront);

            try {
                if (typeof Html5Qrcode !== 'undefined') {
                    availableCameraDevices = await Html5Qrcode.getCameras();
                    if (availableCameraDevices && availableCameraDevices.length > 0) {
                        availableCameraDevices.forEach((dev, idx) => {
                            const opt = document.createElement('option');
                            opt.value = dev.id;
                            const lbl = (dev.label || '').toLowerCase();
                            if (lbl.includes('back') || lbl.includes('rear') || lbl.includes('environment')) {
                                opt.text = `Kamera Belakang: ${dev.label}`;
                            } else if (lbl.includes('front') || lbl.includes('user') || lbl.includes('facing')) {
                                opt.text = `Kamera Depan: ${dev.label}`;
                            } else {
                                opt.text = dev.label || `Kamera ${idx + 1}`;
                            }
                            select.appendChild(opt);
                        });
                    }
                }
            } catch (e) {
                console.warn("Gagal mendapatkan daftar kamera:", e);
            }

            if (prevVal && select.querySelector(`option[value="${prevVal}"]`)) {
                select.value = prevVal;
            } else {
                select.value = 'environment';
            }
        }

        async function closeCameraModal() {
            const modal = document.getElementById('cameraModal');
            if (modal) {
                modal.classList.remove('active');
                modal.style.display = 'none';
            }
            await stopCameraScanner();
        }

        async function startCameraScanner(specificCameraId = null) {
            try {
                if (typeof Html5Qrcode === 'undefined') {
                    const statusMsg = document.getElementById('scannerStatusMsg');
                    if (statusMsg) statusMsg.innerText = 'Library pemindai sedang dimuat, mohon tunggu...';
                    setTimeout(() => startCameraScanner(specificCameraId), 400);
                    return;
                }

                if (html5QrScanner && isScannerRunning) {
                    try {
                        await html5QrScanner.stop();
                    } catch (e) {}
                    isScannerRunning = false;
                }

                if (!html5QrScanner) {
                    html5QrScanner = new Html5Qrcode("reader");
                }

                const select = document.getElementById('cameraSelect');
                const chosen = specificCameraId || (select ? select.value : 'environment') || 'environment';

                let cameraParam;
                if (chosen === 'environment') {
                    cameraParam = { facingMode: "environment" };
                    currentFacingMode = 'environment';
                } else if (chosen === 'user') {
                    cameraParam = { facingMode: "user" };
                    currentFacingMode = 'user';
                } else {
                    // ID perangkat fisik kamera harus berupa string murni ke library Html5Qrcode
                    cameraParam = chosen;
                }

                const btnSwitch = document.getElementById('btnSwitchCamera');
                if (btnSwitch) {
                    btnSwitch.innerText = currentFacingMode === 'environment' ? 'Ganti ke Depan' : 'Ganti ke Belakang';
                }

                const config = {
                    fps: 20,
                    qrbox: { width: 190, height: 190 }
                };

                // Coba jalankan kamera terpilih, jika gagal (misal laptop tidak punya kamera belakang) fallback otomatis
                try {
                    await html5QrScanner.start(
                        cameraParam,
                        config,
                        (decodedText) => onScanSuccess(decodedText),
                        (errorMessage) => {}
                    );
                } catch (err1) {
                    console.warn("Percobaan kamera pertama gagal, melakukan fallback:", err1);
                    let fallbackParam;
                    if (availableCameraDevices && availableCameraDevices.length > 0) {
                        fallbackParam = availableCameraDevices[0].id;
                        if (select) select.value = fallbackParam;
                    } else if (chosen === 'environment') {
                        fallbackParam = { facingMode: "user" };
                        currentFacingMode = 'user';
                        if (select) select.value = 'user';
                        if (btnSwitch) btnSwitch.innerText = 'Ganti ke Belakang';
                    } else {
                        fallbackParam = { facingMode: "environment" };
                        currentFacingMode = 'environment';
                        if (select) select.value = 'environment';
                    }

                    await html5QrScanner.start(
                        fallbackParam,
                        config,
                        (decodedText) => onScanSuccess(decodedText),
                        (errorMessage) => {}
                    );
                }

                isScannerRunning = true;
                if (isCameraMirrored) {
                    document.getElementById('reader')?.classList.add('camera-mirrored');
                }
                const toggleBtn = document.getElementById('btnToggleCamera');
                if (toggleBtn) {
                    toggleBtn.innerText = 'Matikan Kamera';
                    toggleBtn.style.background = '#dc2626';
                }
                const statusMsg = document.getElementById('scannerStatusMsg');
                if (statusMsg) {
                    statusMsg.innerText = 'Kamera aktif. Posisikan barcode atau QR siswa tepat di dalam kotak.';
                }
            } catch (err) {
                console.error("Gagal start kamera:", err);
                isScannerRunning = false;
                const toggleBtn = document.getElementById('btnToggleCamera');
                if (toggleBtn) {
                    toggleBtn.innerText = 'Nyalakan Kamera';
                    toggleBtn.style.background = '#2563eb';
                }
                const errText = (typeof err === 'string' ? err : (err && err.message ? err.message : '')) || 'Izin kamera belum diberikan atau sedang digunakan aplikasi lain';
                const statusMsg = document.getElementById('scannerStatusMsg');
                if (statusMsg) {
                    statusMsg.innerHTML = `<span style="color: #ef4444;">Kamera belum aktif: ${errText}. Silakan klik "Nyalakan Kamera" atau izinkan izin kamera browser.</span>`;
                }
            }
        }

        async function stopCameraScanner() {
            if (html5QrScanner && isScannerRunning) {
                try {
                    await html5QrScanner.stop();
                } catch (e) {
                    console.warn(e);
                }
                isScannerRunning = false;
            }
            const toggleBtn = document.getElementById('btnToggleCamera');
            if (toggleBtn) {
                toggleBtn.innerText = 'Nyalakan Kamera';
                toggleBtn.style.background = '#2563eb';
            }
            const statusMsg = document.getElementById('scannerStatusMsg');
            if (statusMsg) {
                statusMsg.innerText = 'Kamera dijeda / mati.';
            }
        }

        async function toggleCameraScanner() {
            if (isScannerRunning) {
                await stopCameraScanner();
            } else {
                const select = document.getElementById('cameraSelect');
                await startCameraScanner(select ? select.value : 'environment');
            }
        }

        function switchCameraFacing() {
            const select = document.getElementById('cameraSelect');
            if (currentFacingMode === 'environment') {
                currentFacingMode = 'user';
            } else {
                currentFacingMode = 'environment';
            }
            if (select) {
                select.value = currentFacingMode;
            }
            const btnSwitch = document.getElementById('btnSwitchCamera');
            if (btnSwitch) {
                btnSwitch.innerText = currentFacingMode === 'environment' ? 'Ganti ke Depan' : 'Ganti ke Belakang';
            }
            if (isScannerRunning) {
                startCameraScanner(currentFacingMode);
            }
        }

        function handleCameraSelectChange(val) {
            if (val === 'environment' || val === 'user') {
                currentFacingMode = val;
            }
            const btnSwitch = document.getElementById('btnSwitchCamera');
            if (btnSwitch) {
                btnSwitch.innerText = currentFacingMode === 'environment' ? 'Ganti ke Depan' : 'Ganti ke Belakang';
            }
            if (isScannerRunning) {
                startCameraScanner(val);
            }
        }

        let isCameraMirrored = false;

        function toggleCameraMirror() {
            isCameraMirrored = !isCameraMirrored;
            const readerEl = document.getElementById('reader');
            const btnMirror = document.getElementById('btnMirrorCamera');
            if (isCameraMirrored) {
                readerEl?.classList.add('camera-mirrored');
                if (btnMirror) {
                    btnMirror.style.background = '#2563eb';
                    btnMirror.innerText = 'Mirror: Aktif';
                }
            } else {
                readerEl?.classList.remove('camera-mirrored');
                if (btnMirror) {
                    btnMirror.style.background = '#475569';
                    btnMirror.innerText = 'Mirror Kamera';
                }
            }
        }

        document.getElementById('cameraSelect')?.addEventListener('change', function() {
            startCameraScanner(this.value);
        });

        async function handleQrFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            try {
                if (typeof Html5Qrcode === 'undefined') {
                    alert('Library scanner sedang dimuat, silakan coba sesaat lagi.');
                    return;
                }
                if (!html5QrScanner) {
                    html5QrScanner = new Html5Qrcode("reader");
                }
                const decodedText = await html5QrScanner.scanFile(file, true);
                onScanSuccess(decodedText);
            } catch (err) {
                alert("Barcode atau QR Code tidak terdeteksi pada gambar tersebut. Pastikan gambar cukup terang dan tidak buram.");
            } finally {
                event.target.value = '';
            }
        }

        function playBeepSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            } catch(e) {}
        }

        function parseScannedCode(rawText) {
            let text = (rawText || '').trim();
            if (!text) return '';

            // Jika format JSON
            if (text.startsWith('{') && text.endsWith('}')) {
                try {
                    const data = JSON.parse(text);
                    return data.account_number || data.no_rekening || data.nis || data.student_name || text;
                } catch (e) {}
            }

            // Jika ada prefix seperti NIS:, REK:, NO_REKENING:
            if (text.toUpperCase().startsWith('NIS:')) {
                return text.substring(4).trim();
            }
            if (text.toUpperCase().startsWith('REK:')) {
                return text.substring(4).trim();
            }
            if (text.toUpperCase().startsWith('NO_REKENING:')) {
                return text.substring(12).trim();
            }

            return text;
        }

        function onScanSuccess(decodedText) {
            const cleaned = parseScannedCode(decodedText);
            playBeepSound();
            closeCameraModal();

            const searchInput = document.getElementById('searchKeyword');
            if (searchInput) {
                searchInput.value = cleaned;
            }

            const searchCard = document.querySelector('.search-box');
            if (searchCard) {
                searchCard.scrollIntoView({ behavior: 'smooth' });
            }

            // Otomatis jalankan pencarian rekening
            doSearchNasabah();
        }

        function restoreTellerActiveTab() {
            try {
                if (@json(session('success_profile') || session('success_password') || session('error_password') || $errors->has('nama_petugas') || $errors->has('username') || $errors->has('email') || $errors->has('sandi_lama') || $errors->has('sandi_baru'))) {
                    switchTab('tab-profil');
                    return;
                }

                const urlParams = new URLSearchParams(window.location.search);
                const hasMonth = urlParams.get('month');
                const queryTab = urlParams.get('tab');
                const hashTab = window.location.hash ? window.location.hash.substring(1) : null;
                const sessionTab = sessionStorage.getItem('teller_active_tab');
                const localTab = localStorage.getItem('teller_active_tab');

                // Urutan prioritas: filter bulan -> query tab -> hash -> sessionStorage -> localStorage
                const tabToActivate = hasMonth ? 'tab-bulanan'
                                    : (queryTab && document.getElementById(queryTab)) ? queryTab
                                    : (hashTab && document.getElementById(hashTab)) ? hashTab
                                    : (sessionTab && document.getElementById(sessionTab)) ? sessionTab
                                    : (localTab && document.getElementById(localTab)) ? localTab
                                    : 'tab-dashboard';

                if (tabToActivate && document.getElementById(tabToActivate)) {
                    switchTab(tabToActivate);
                }
            } catch (e) {}
        }

        // Jalankan segera saat skrip dieksekusi agar bebas flicker
        restoreTellerActiveTab();
        restoreNavCollapseState();

        // Restore tab saat refresh / navigasi & daftarkan listener form submit
        window.addEventListener('DOMContentLoaded', () => {
            restoreTellerActiveTab();
            restoreNavCollapseState();
            filterAktivasiTable(true);

            // Pastikan setiap form teller (setoran, penarikan, aktivasi, reset pin, laporan)
            // selalu mencatat tab aktif sebelum form dikirimkan
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    const activeContent = document.querySelector('.tab-content.active');
                    if (activeContent && activeContent.id) {
                        try {
                            sessionStorage.setItem('teller_active_tab', activeContent.id);
                            localStorage.setItem('teller_active_tab', activeContent.id);
                        } catch(e) {}
                    }
                });
            });
        });
    </script>

    <!-- MODAL CAMERA SCANNER BARCODE & QR -->
    <div id="cameraModal" class="modal-qr-overlay">
        <div class="modal-qr-content">
            <div style="padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a;">Pemindai Barcode &amp; QR Siswa</h4>
                    <p style="margin: 0; font-size: 0.78rem; color: var(--text-muted);">Posisikan Barcode atau QR Code tepat di dalam kotak</p>
                </div>
                <button type="button" onclick="closeCameraModal()" style="background: transparent; border: none; font-size: 1.75rem; line-height: 1; cursor: pointer; color: #94a3b8; padding: 0.25rem;">&times;</button>
            </div>

            <div style="padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                <!-- Camera Select & Action (Tanpa Icon, Elegan & Simpel) -->
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                    <select id="cameraSelect" class="form-control" style="flex: 1; min-width: 170px; padding: 0.55rem 0.75rem; font-size: 0.85rem;" onchange="handleCameraSelectChange(this.value)">
                        <option value="environment">Kamera Belakang</option>
                        <option value="user">Kamera Depan</option>
                    </select>
                    <button type="button" id="btnSwitchCamera" onclick="switchCameraFacing()" class="btn-primary" style="padding: 0.55rem 0.85rem; font-size: 0.82rem; background: #0284c7; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        Ganti ke Depan
                    </button>
                    <button type="button" id="btnToggleCamera" onclick="toggleCameraScanner()" class="btn-primary" style="padding: 0.55rem 0.95rem; font-size: 0.82rem; background: #2563eb; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                        Nyalakan Kamera
                    </button>
                    <button type="button" id="btnMirrorCamera" onclick="toggleCameraMirror()" class="btn-primary" style="padding: 0.55rem 0.85rem; font-size: 0.82rem; background: #475569; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        Mirror Kamera
                    </button>
                </div>



                <!-- Scanner Viewport -->
                <div class="scanner-viewport-box">
                    <div id="reader"></div>
                    <div class="scanner-laser" id="scannerLaser"></div>
                    <div class="scanner-guide-corners" id="scannerCorners"></div>
                </div>

                <div id="scannerStatusMsg" style="font-size: 0.83rem; text-align: center; color: var(--text-muted); font-weight: 600;">
                    Menyiapkan pemindai kamera...
                </div>

                <!-- Fallback: Upload Barcode/QR Image -->
                <div style="border-top: 1px dashed var(--border-color); padding-top: 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: #475569;">Punya foto barcode / QR?</span>
                    <label for="qrFileInput" style="font-size: 0.82rem; color: #2563eb; cursor: pointer; font-weight: 700; text-decoration: underline;">
                        Unggah File Gambar
                    </label>
                    <input type="file" id="qrFileInput" accept="image/*" style="display: none;" onchange="handleQrFileUpload(event)">
                </div>
            </div>

            <div style="padding: 0.85rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
                <button type="button" onclick="closeCameraModal()" class="btn-logout" style="background: #e2e8f0; color: #475569; padding: 0.55rem 1.25rem;">Tutup Pemindai</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT LIBRARY HTML5-QRCODE (OFFLINE & ONLINE FALLBACK) -->
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script>
        if (typeof Html5Qrcode === 'undefined') {
            document.write('<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"><\/script>');
        }
    </script>
    <!-- MODAL AKTIVASI REKENING ANGGOTA (TANPA ICON) -->
    <div id="modalAktivasiAnggota" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="card" style="max-width: 480px; width: 100%; background: #ffffff; border-radius: 20px; padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); border: 1px solid rgba(226, 232, 240, 0.8);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">Aktivasi Rekening Siswa</h3>
                    <p style="margin: 3px 0 0; font-size: 0.82rem; color: #64748b;">Setoran awal minimal Rp 10.000 & pembuatan PIN transaksi</p>
                </div>
                <button type="button" onclick="closeModalAktivasi()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; font-weight: 700; line-height: 1;">&times;</button>
            </div>

            <!-- Detail Siswa Terpilih -->
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem; font-size: 0.85rem; line-height: 1.6;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Nama Siswa:</span>
                    <strong id="modalAktNama" style="color: #0f172a;">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">NIS:</span>
                    <code id="modalAktNis" style="font-weight: 700;">-</code>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Jurusan / Tahun Lulus:</span>
                    <span id="modalAktKelasAngkatan" style="color: #0f172a; font-weight: 600;">-</span>
                </div>
            </div>

            <form action="{{ route('teller.aktivasi-anggota') }}" method="POST" class="prevent-double-submit" onsubmit="return validateAktivasiForm(this);">
                @csrf
                <input type="hidden" name="_token_transaksi" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                <input type="hidden" name="user_id" id="modalAktUserId" required>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">
                        Nominal Setoran Awal (Minimal Rp 10.000)
                    </label>
                    <input type="number" name="nominal" id="modalAktNominal" class="form-control" required min="10000" step="1000" value="10000" placeholder="Minimal Rp 10.000" style="font-weight: 700; font-size: 1.05rem;">
                    <div style="display: flex; gap: 0.4rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        <button type="button" onclick="setModalAktNominal(10000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">Rp 10.000</button>
                        <button type="button" onclick="setModalAktNominal(20000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">Rp 20.000</button>
                        <button type="button" onclick="setModalAktNominal(50000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">Rp 50.000</button>
                        <button type="button" onclick="setModalAktNominal(100000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">Rp 100.000</button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">
                        Alamat Email Siswa / Nasabah <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="email" name="email" id="modalAktEmail" maxlength="30" class="form-control" required placeholder="Contoh: siswa@gmail.com">
                    <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Digunakan untuk pengiriman informasi dan pemulihan PIN nasabah.</small>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">
                        Buat PIN 6-Digit Keamanan Penabung Baru <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" name="pin" id="modalAktPin" class="form-control" required maxlength="6" minlength="6" placeholder="Contoh: 123456" pattern="[0-9]{6}" inputmode="numeric" style="padding-right: 2.75rem; letter-spacing: 2px; font-weight: 700;">
                        <button type="button" id="btnTogglePinAktivasi" onclick="togglePasswordVisibility('modalAktPin', this)" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">PIN 6 angka ini digunakan nasabah saat verifikasi penarikan uang tabungan.</small>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">
                        Konfirmasi PIN 6-Digit <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" name="pin_confirmation" id="modalAktPinConfirm" class="form-control" required maxlength="6" minlength="6" placeholder="Ketik ulang 6 angka PIN" pattern="[0-9]{6}" inputmode="numeric" style="padding-right: 2.75rem; letter-spacing: 2px; font-weight: 700;">
                        <button type="button" id="btnTogglePinConfirmAktivasi" onclick="togglePasswordVisibility('modalAktPinConfirm', this)" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Masukkan ulang 6 angka PIN yang sama untuk verifikasi kecocokan.</small>
                </div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick="closeModalAktivasi()" class="btn-logout" style="background: #e2e8f0; color: #475569; flex: 1; padding: 0.7rem; font-size: 0.88rem; border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2; padding: 0.7rem; font-size: 0.88rem; border-radius: 10px;">Aktifkan Rekening</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PENGAJUAN TAMBAHAN KAS (DESAIN ELEGAN FINTECH DENGAN QUICK CHIPS) -->
    <div id="ajukanKasModal" onclick="if(event.target === this) closeAjukanKasModal()" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="card" style="max-width: 460px; width: 100%; background: #ffffff; border-radius: 20px; padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); border: 1px solid rgba(226, 232, 240, 0.8);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                <div>
                    <h3 style="font-size: 1.18rem; font-weight: 800; color: #0f172a; margin: 0;">Ajukan Tambahan Kas</h3>
                    <p style="margin: 3px 0 0; font-size: 0.82rem; color: #64748b;">Permintaan dana tunai operasional kasir ke Admin</p>
                </div>
                <button type="button" onclick="closeAjukanKasModal()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; font-weight: 700; line-height: 1;">&times;</button>
            </div>

            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; color: #1e40af; font-weight: 600;">Sisa Kas Laci Saat Ini:</span>
                <strong style="color: #0f172a; font-size: 1rem; font-variant-numeric: tabular-nums;">Rp {{ number_format($sisaKasLaci, 0, ',', '.') }}</strong>
            </div>

            <form action="{{ route('teller.ajukan-kas') }}" method="POST" id="formAjukanKas" class="prevent-double-submit" autocomplete="off" onsubmit="return confirm('Konfirmasi Pengajuan Kas:\n\nApakah Anda yakin ingin mengajukan tambahan kas sebesar Rp ' + (document.getElementById('inputNominalKas')?.value ? Number(document.getElementById('inputNominalKas').value).toLocaleString('id-ID') : '') + ' ke Administrator?');">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #1e293b;">Nominal Tambahan (Rp) <span style="color: #dc2626;">*</span></label>
                    <input type="number" id="inputNominalKas" name="nominal" class="form-control" required min="1000" step="1000" value="0" placeholder="0" autocomplete="off" style="font-weight: 700; font-size: 1.05rem;">
                    
                    <!-- Quick Amount Chips -->
                    <div style="display: flex; gap: 0.4rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        <button type="button" onclick="setNominalKas(500000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">+500 Ribu</button>
                        <button type="button" onclick="setNominalKas(1000000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">+1 Juta</button>
                        <button type="button" onclick="setNominalKas(2000000)" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.75rem; font-weight: 700; color: #334155; cursor: pointer;">+2 Juta</button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 700; font-size: 0.85rem; color: #1e293b;">Alasan / Keterangan Kebutuhan Kas <span style="color: #dc2626;">*</span></label>
                    <input type="text" id="inputAlasanKas" name="alasan" class="form-control" required placeholder="Contoh: Antrean penarikan siswa tinggi..." autocomplete="off">
                    
                    <!-- Quick Reason Chips -->
                    <div style="display: flex; gap: 0.4rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        <button type="button" onclick="setAlasanKas('Antrean penarikan tabungan siswa meningkat')" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.2rem 0.5rem; font-size: 0.72rem; color: #64748b; cursor: pointer;">Penarikan Siswa Meningkat</button>
                        <button type="button" onclick="setAlasanKas('Pencairan dana kegiatan/ekskul')" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.2rem 0.5rem; font-size: 0.72rem; color: #64748b; cursor: pointer;">Pencairan Kegiatan</button>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick="closeAjukanKasModal()" class="btn-logout" style="background: #e2e8f0; color: #475569; flex: 1; padding: 0.7rem; border-radius: 10px; font-weight: 700; border: none; cursor: pointer;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2; padding: 0.7rem; font-size: 0.92rem; border-radius: 10px;">Kirim Pengajuan Kas</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAjukanKasModal() {
            const form = document.getElementById('formAjukanKas');
            if (form) form.reset();
            const inpNominal = document.getElementById('inputNominalKas');
            if (inpNominal) {
                inpNominal.value = '0';
                inpNominal.defaultValue = '0';
            }
            const inpAlasan = document.getElementById('inputAlasanKas');
            if (inpAlasan) {
                inpAlasan.value = '';
                inpAlasan.defaultValue = '';
            }
            const m = document.getElementById('ajukanKasModal');
            if (m) {
                m.style.display = 'flex';
                setTimeout(() => {
                    const inp = document.getElementById('inputNominalKas');
                    if (inp) {
                        inp.focus();
                        inp.select();
                    }
                }, 100);
            }
        }
        function closeAjukanKasModal() {
            const m = document.getElementById('ajukanKasModal');
            if (m) m.style.display = 'none';
            const form = document.getElementById('formAjukanKas');
            if (form) form.reset();
            const inpNominal = document.getElementById('inputNominalKas');
            if (inpNominal) {
                inpNominal.value = '0';
                inpNominal.defaultValue = '0';
            }
            const inpAlasan = document.getElementById('inputAlasanKas');
            if (inpAlasan) {
                inpAlasan.value = '';
                inpAlasan.defaultValue = '';
            }
        }
        function setNominalKas(val) {
            const input = document.getElementById('inputNominalKas');
            if (input) input.value = val;
        }
        function setAlasanKas(text) {
            const input = document.getElementById('inputAlasanKas');
            if (input) input.value = text;
        }

        function ubahStatusLayanan(status) {
            const btnBuka = document.getElementById('btn-status-buka');
            const btnIstirahat = document.getElementById('btn-status-istirahat');
            const btnTutup = document.getElementById('btn-status-tutup');

            if (btnBuka) btnBuka.className = 'btn-status-option' + (status === 'buka' ? ' active-buka' : '');
            if (btnIstirahat) btnIstirahat.className = 'btn-status-option' + (status === 'istirahat' ? ' active-istirahat' : '');
            if (btnTutup) btnTutup.className = 'btn-status-option' + (status === 'tutup' ? ' active-tutup' : '');

            fetch('{{ route("teller.status-layanan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(res => res.json())
            .catch(err => {
                console.error('Gagal memperbarui status layanan:', err);
            });
        }

        /* Handler Antrean Loket Teller (AJAX Tanpa Scroll / Tanpa Reload) */
        async function handlePanggilAntreanSubmit(e) {
            if (e) e.preventDefault();
            const btn = document.getElementById('btnPanggilAntrean');
            const originalText = btn ? btn.innerText : 'Panggil Antrean Berikutnya';
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Memanggil...';
            }

            try {
                const response = await fetch('{{ route("teller.antrean.panggil") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success && data.antrean) {
                    applyAntreanSedangDilayani(data.antrean, data.total_menunggu, data.total_hari_ini);
                } else {
                    alert(data.message || 'Tidak ada antrean yang menunggu.');
                    if (data.total_menunggu !== undefined) {
                        applyAntreanCounts(data.total_menunggu, data.total_hari_ini);
                    }
                }
            } catch (err) {
                console.error('Fetch panggil error, fallback submit:', err);
                sessionStorage.setItem('teller_stay_antrean', 'true');
                document.getElementById('formPanggilAntrean')?.submit();
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = originalText;
                }
            }
        }

        async function handleSelesaiAntreanSubmit(e) {
            if (e) e.preventDefault();
            const btn = document.getElementById('btnSelesaiAntrean');
            const antreanId = btn ? btn.getAttribute('data-id') : null;
            const nomorAntrean = btn ? btn.getAttribute('data-nomor') : '';

            if (!antreanId) {
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Menyelesaikan...';
            }

            try {
                const url = '{{ url("/teller/antrean") }}/' + antreanId + '/selesai';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    applyAntreanSelesai(data.nomor_antrean || nomorAntrean, data.total_menunggu, data.total_hari_ini);
                } else {
                    alert(data.message || 'Gagal menyelesaikan antrean.');
                }
            } catch (err) {
                console.error('Fetch selesai error, fallback submit:', err);
                sessionStorage.setItem('teller_stay_antrean', 'true');
                document.getElementById('formSelesaiAntrean')?.submit();
            } finally {
                if (btn) {
                    btn.disabled = false;
                    const curNomor = btn.getAttribute('data-nomor') || '';
                    btn.innerHTML = 'Selesaikan Antrean (<span id="labelNomorSelesai">' + curNomor + '</span>)';
                }
            }
        }

        function applyAntreanSedangDilayani(antrean, totalMenunggu, totalHariIni) {
            const elNomor = document.getElementById('antreanNomorSedangDilayani');
            if (elNomor) {
                elNomor.textContent = antrean.nomor_antrean;
                elNomor.style.color = '#2563eb';
            }

            const elInfo = document.getElementById('antreanInfoSiswaSedangDilayani');
            if (elInfo) {
                elInfo.textContent = '(' + antrean.nama_siswa + ' - NIS: ' + antrean.nis + ')';
                elInfo.style.display = 'inline';
            }

            const wrapperSelesai = document.getElementById('wrapperBtnSelesaiAntrean');
            const btnSelesai = document.getElementById('btnSelesaiAntrean');
            const labelNomorSelesai = document.getElementById('labelNomorSelesai');
            const formSelesai = document.getElementById('formSelesaiAntrean');

            if (wrapperSelesai) wrapperSelesai.style.display = 'inline-block';
            if (btnSelesai) {
                btnSelesai.setAttribute('data-id', antrean.id);
                btnSelesai.setAttribute('data-nomor', antrean.nomor_antrean);
            }
            if (labelNomorSelesai) {
                labelNomorSelesai.textContent = antrean.nomor_antrean;
            }
            if (formSelesai) {
                formSelesai.action = '{{ url("/teller/antrean") }}/' + antrean.id + '/selesai';
            }

            document.querySelectorAll('#tbodyDaftarAntreanHariIni span').forEach(span => {
                if (span.textContent.trim() === 'Dilayani') {
                    span.textContent = 'Selesai';
                    span.style.background = '#f1f5f9';
                    span.style.color = '#64748b';
                }
            });
            const badgeNow = document.getElementById('badge-antrean-' + antrean.nomor_antrean);
            if (badgeNow) {
                badgeNow.innerHTML = '<span style="background: #dbeafe; color: #1d4ed8; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Dilayani</span>';
            }

            applyAntreanCounts(totalMenunggu, totalHariIni);
        }

        function applyAntreanSelesai(nomorAntrean, totalMenunggu, totalHariIni) {
            const elNomor = document.getElementById('antreanNomorSedangDilayani');
            if (elNomor) {
                elNomor.textContent = '-';
                elNomor.style.color = '#64748b';
            }

            const elInfo = document.getElementById('antreanInfoSiswaSedangDilayani');
            if (elInfo) {
                elInfo.textContent = '';
                elInfo.style.display = 'none';
            }

            const wrapperSelesai = document.getElementById('wrapperBtnSelesaiAntrean');
            const btnSelesai = document.getElementById('btnSelesaiAntrean');
            if (wrapperSelesai) wrapperSelesai.style.display = 'none';
            if (btnSelesai) {
                btnSelesai.removeAttribute('data-id');
                btnSelesai.removeAttribute('data-nomor');
            }

            if (nomorAntrean) {
                const badgeNow = document.getElementById('badge-antrean-' + nomorAntrean);
                if (badgeNow) {
                    badgeNow.innerHTML = '<span style="background: #f1f5f9; color: #64748b; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Selesai</span>';
                }
            }

            applyAntreanCounts(totalMenunggu, totalHariIni);
        }

        function applyAntreanCounts(totalMenunggu, totalHariIni) {
            if (totalMenunggu !== undefined) {
                const elMenunggu = document.getElementById('antreanTotalMenungguText');
                if (elMenunggu) elMenunggu.textContent = totalMenunggu;
                const btnPanggil = document.getElementById('btnPanggilAntrean');
                if (btnPanggil) {
                    btnPanggil.style.opacity = totalMenunggu === 0 ? '0.75' : '1';
                }
            }
            if (totalHariIni !== undefined) {
                const elTerbit = document.getElementById('antreanTotalTerbitText');
                if (elTerbit) elTerbit.textContent = totalHariIni;
                const labelJml = document.getElementById('labelJumlahAntreanHariIni');
                if (labelJml) labelJml.textContent = totalHariIni;
            }
        }

        // Pertahankan posisi stay pada kartu antrean jika terjadi reload
        window.addEventListener('DOMContentLoaded', () => {
            if (sessionStorage.getItem('teller_stay_antrean') === 'true') {
                sessionStorage.removeItem('teller_stay_antrean');
                const antreanCard = document.getElementById('cardAntreanTeller');
                if (antreanCard) {
                    antreanCard.scrollIntoView({ behavior: 'instant', block: 'center' });
                }
            }
        });

        // Pastikan saat ada konfirmasi transaksi/laporan, layar langsung fokus di atas
        @if(session('success') || session('error') || $errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                @if(session('error_pin_salah') || (session('error') && str_contains(session('error'), 'PIN')))
                    switchTab('tab-penarikan');
                    setTimeout(() => {
                        const pinInput = document.getElementById('penarikanPin');
                        if (pinInput) {
                            pinInput.focus();
                            pinInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }, 250);
                @else
                    if (sessionStorage.getItem('teller_stay_antrean') === 'true') {
                        sessionStorage.removeItem('teller_stay_antrean');
                        const antreanCard = document.getElementById('cardAntreanTeller');
                        if (antreanCard) {
                            antreanCard.scrollIntoView({ behavior: 'instant', block: 'center' });
                        }
                    } else {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                @endif
            });
        @endif
    </script>
    <!-- MODAL PENOLAKAN PERJANJIAN H-1 -->
    <div id="rejectPerjanjianModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
        <div class="card" style="max-width: 440px; width: 100%; background: #ffffff; border-radius: 20px; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #dc2626; margin:0;">Tolak Perjanjian Penarikan</h3>
                <button type="button" onclick="closeTolakPerjanjianModal()" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer; font-weight: 700; line-height: 1;">&times;</button>
            </div>

            <div style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem; line-height: 1.5;">
                Nasabah: <strong id="rejectPjNasabah">-</strong> &bull; Nominal: <strong id="rejectPjNominal" style="color: #dc2626;">-</strong>
            </div>

            <form id="rejectPerjanjianForm" method="POST" onsubmit="return confirm('Konfirmasi: Apakah Anda yakin ingin MENOLAK permohonan perjanjian penarikan ini?');">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="font-weight: 700; font-size: 0.85rem;">Alasan Penolakan <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="catatan_teller" id="rejectPjCatatanInput" class="form-control" required placeholder="Contoh: Ketersediaan kas laci belum mencukupi / jadwal bentrok...">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                    <button type="button" onclick="closeTolakPerjanjianModal()" class="btn-logout" style="background: #e2e8f0; color: #475569; flex: 1; padding: 0.65rem; border-radius: 10px; font-weight: 700; border: none; cursor: pointer;">Batal</button>
                    <button type="submit" class="btn-danger" style="flex: 1.5; padding: 0.65rem; border-radius: 10px; font-weight: 700;">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
