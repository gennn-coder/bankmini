<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrator | Bank Mini E-Teller</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_bank_mini.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --canvas-bg: #e8f1fd;
            --app-bg: #ffffff;
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-gradient: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            --cyan-gradient: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            --accent-cyan: #38bdf8;
            --text-navy: #0f172a;
            --text-slate: #475569;
            --text-muted: #94a3b8;
            --border-soft: #f1f5f9;
            --border-light: #e2e8f0;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            --window-shadow: 0 25px 70px rgba(37, 99, 235, 0.12), 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        *, *::before, *::after {
            box-sizing: border-box !important;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f7fe;
            color: var(--text-navy, #0f172a);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
        }

        /* 1. TOP NAVBAR (STICKY FULL-WIDTH) */
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
            border-bottom: 1px solid var(--border-light, #e2e8f0);
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
        }
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        /* 2. HORIZONTAL NAVIGATION TABS (NO ICONS AS REQUESTED BY USER) */
        .nav-tabs-bar {
            background: #ffffff;
            border-bottom: 2px solid var(--border-light, #e2e8f0);
            padding: 0.65rem 2rem;
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
            max-width: 240px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .user-greeting-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 170px;
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
            background: #dcfce7;
            color: #166534;
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
            padding: 0.4rem 0.75rem;
            border-radius: 10px;
            margin-left: auto;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
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
            gap: 0.4rem;
            width: 100%;
            max-width: 1540px;
            margin: 0 auto;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        .nav-tabs::-webkit-scrollbar { height: 4px; }
        .nav-tabs::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 0.6rem 1.15rem;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-slate, #475569);
            cursor: pointer;
            border-radius: 12px;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            font-family: inherit;
        }
        .tab-btn:hover {
            color: var(--primary-blue, #2563eb);
            background: #eff6ff;
        }
        .tab-btn.active {
            background: var(--primary-blue, #2563eb) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28) !important;
        }
        .tab-badge {
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            line-height: 1;
            margin-left: 3px;
        }
        .tab-badge-danger { background: #ef4444; color: #ffffff; }
        .tab-badge-warning { background: #f59e0b; color: #ffffff; }

        /* 3. MAIN FULL-SCREEN CONTENT CONTAINER */
        .container {
            max-width: 1540px;
            width: 100%;
            margin: 1.75rem auto 3.5rem;
            padding: 0 1.75rem;
            box-sizing: border-box;
        }
        .tab-content {
            display: none;
            width: 100%;
        }
        .tab-content.active {
            display: block;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.5rem 1.75rem;
            border: 1px solid var(--border-light, #e2e8f0);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
            border-color: #cbd5e1;
        }
        .stat-card-label {
            font-size: 0.78rem;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .stat-card-value {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 0.35rem;
        }
        .stat-card-value.text-blue { color: #2563eb; }
        .stat-card-value.text-indigo { color: #6366f1; }
        .stat-card-value.text-emerald { color: #16a34a; }
        .stat-card-sub {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
        }
        .topbar-greeting {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 2px;
        }
        .topbar-page-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .search-pill-box {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: #f8fafc;
            border: 1px solid var(--border-light);
            border-radius: 9999px;
            padding: 0.6rem 1.25rem;
            width: 270px;
            transition: all 0.2s;
        }
        .search-pill-box:focus-within {
            border-color: var(--primary-blue);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .search-pill-box input {
            border: none;
            background: transparent;
            font-size: 0.85rem;
            color: #0f172a;
            width: 100%;
            outline: none;
            font-family: inherit;
        }

        /* HERO BANNER (CRYPTOWALL STYLE) */
        .hero-banner {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 65%, #1e40af 100%);
            border-radius: 26px;
            padding: 2rem 2.25rem;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 36px rgba(37, 99, 235, 0.22);
            margin-bottom: 1.75rem;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: 180px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        }
        .hero-content {
            max-width: 460px;
            z-index: 2;
        }
        .hero-heading {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 0.65rem;
            letter-spacing: -0.3px;
        }
        .hero-subtext {
            font-size: 0.88rem;
            opacity: 0.88;
            line-height: 1.5;
            margin-bottom: 1.35rem;
        }
        .hero-btn {
            background: var(--accent-cyan);
            color: #0f172a;
            font-weight: 800;
            font-size: 0.88rem;
            border-radius: 9999px;
            padding: 0.65rem 1.6rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(56, 189, 248, 0.35);
            transition: all 0.2s ease;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(56, 189, 248, 0.45);
        }

        /* 3D SMARTPHONE MOCKUP IN BANNER */
        .hero-illustration {
            position: relative;
            width: 180px;
            height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        .mockup-phone {
            width: 120px;
            height: 155px;
            background: #0f172a;
            border-radius: 20px;
            border: 3px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.3), inset 0 0 10px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            transform: perspective(600px) rotateY(-12deg) rotateX(6deg);
            padding: 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .phone-chip-pill {
            width: 32px;
            height: 4px;
            background: rgba(255,255,255,0.4);
            border-radius: 999px;
            margin: 0 auto 6px;
        }
        .phone-receipt-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 8px 6px;
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .receipt-icon {
            font-size: 1.1rem;
            line-height: 1;
        }
        .receipt-lines {
            flex: 1;
        }
        .r-line {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-bottom: 3px;
        }
        .r-line-short {
            width: 60%;
            background: #38bdf8;
        }
        .floating-glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            border-radius: 8px;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            margin: 4px 0;
        }
        .g-line {
            height: 4px;
            width: 80%;
            background: rgba(255,255,255,0.5);
            border-radius: 2px;
        }
        .g-line-full { width: 100%; }
        .phone-btn-bar {
            height: 8px;
            background: #38bdf8;
            border-radius: 999px;
            width: 100%;
        }
        .phone-float-badge {
            position: absolute;
            top: 15px;
            left: -20px;
            background: rgba(255, 255, 255, 0.95);
            color: #0284c7;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            white-space: nowrap;
        }

        /* "MY PORTOFOLIO" METRIC ROW */
        .portfolio-section {
            margin-bottom: 1.75rem;
        }
        .section-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.85rem;
        }
        .section-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .pagination-arrows {
            display: flex;
            gap: 0.35rem;
        }
        .arrow-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid var(--border-light);
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .arrow-btn:hover {
            background: var(--primary-blue);
            color: #ffffff;
            border-color: var(--primary-blue);
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1rem;
        }
        .portfolio-card {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 20px;
            padding: 1.15rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .portfolio-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
            border-color: #cbd5e1;
        }
        .p-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-cyan { background: #e0f2fe; color: #0284c7; }
        .icon-purple { background: #ede9fe; color: #7c3aed; }
        .icon-emerald { background: #dcfce7; color: #16a34a; }
        .icon-blue { background: #dbeafe; color: #2563eb; }

        .p-card-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }
        .p-card-val {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
        }
        .font-sm-balance {
            font-size: 1.05rem !important;
            letter-spacing: -0.3px;
        }
        .p-card-sub {
            font-size: 0.72rem;
            font-weight: 600;
            margin-top: 3px;
        }
        .text-cyan { color: #0284c7; }
        .text-purple { color: #7c3aed; }
        .text-emerald { color: #16a34a; }
        .text-blue { color: #2563eb; }

        /* SINE WAVE CHART & QUICK STATUS CARD */
        .chart-and-status-grid {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .chart-card {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 24px;
            padding: 1.5rem 1.75rem;
            box-shadow: 0 4px 18px rgba(0,0,0,0.02);
            position: relative;
        }
        .chart-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        .chart-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .chart-icon-box {
            font-size: 1.35rem;
        }
        .chart-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .chart-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 2px 0 0;
        }
        .chart-badge-pill {
            background: #eff6ff;
            color: var(--primary-blue);
            font-weight: 800;
            font-size: 0.85rem;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            border: 1px solid #bfdbfe;
        }
        .chart-visual-box {
            position: relative;
            width: 100%;
            height: 160px;
        }
        .trend-wave-svg {
            width: 100%;
            height: 130px;
        }
        .chart-floating-tag {
            position: absolute;
            top: 8px;
            left: 60%;
            transform: translateX(-50%);
            background: #0f172a;
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: flex;
            gap: 5px;
        }
        .tag-label { opacity: 0.75; }
        .tag-val { color: #38bdf8; }
        .chart-x-axis {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 1rem 0;
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 600;
            border-top: 1px dashed #f1f5f9;
        }

        .operations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
            gap: 1.25rem;
        }
        .op-card {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 20px;
            padding: 1.35rem 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.02);
        }
        .op-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.75rem;
        }
        .op-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }
        .btn-op-pill {
            background: #eff6ff;
            color: var(--primary-blue);
            border: 1px solid #bfdbfe;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-op-pill:hover {
            background: var(--primary-blue);
            color: #ffffff;
        }

        /* DASHBOARD BOTTOM GRID & WIDGETS */
        .dashboard-bottom-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.85fr) minmax(320px, 1.15fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
            align-items: start;
            width: 100%;
        }
        .dashboard-widgets-col {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
        }

        .user-profile-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.85rem;
        }
        .btn-notification {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .btn-notification:hover {
            background: #f1f5f9;
        }
        .notif-dot {
            position: absolute;
            top: 9px;
            right: 9px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #ffffff;
        }
        .user-meta-wrap {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.35rem 0.65rem;
            border-radius: 9999px;
            background: #f8fafc;
            border: 1px solid var(--border-light);
        }
        .user-avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }
        .user-name-box {
            display: flex;
            flex-direction: column;
        }
        .user-name-text {
            font-size: 0.82rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .user-role-badge {
            font-size: 0.65rem;
            font-weight: 700;
            color: #0284c7;
        }

        /* "MY CARDS" DEBIT CARD (EXACT CRYPTOWALL BLUE/CYAN CARD) */
        .my-cards-widget {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .widget-heading-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .widget-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .card-dots {
            display: flex;
            gap: 4px;
        }
        .card-dots .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #cbd5e1;
        }
        .card-dots .dot.active {
            background: var(--primary-blue);
            width: 14px;
            border-radius: 999px;
        }

        .debit-card-blue {
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            border-radius: 24px;
            padding: 1.5rem 1.4rem;
            color: #ffffff;
            box-shadow: 0 14px 30px rgba(0, 114, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        .debit-card-blue::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }
        .card-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.45rem;
        }
        .card-balance-lbl {
            font-size: 0.75rem;
            opacity: 0.88;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .card-balance-amount {
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 1.15rem;
            line-height: 1.1;
        }
        .card-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .card-sub-lbl {
            font-size: 0.68rem;
            opacity: 0.8;
            margin-bottom: 2px;
        }
        .card-sub-val {
            font-size: 0.88rem;
            font-weight: 800;
        }
        .card-growth-pill {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
        }

        .card-actions-row {
            display: flex;
            gap: 0.65rem;
        }
        .btn-card-action {
            flex: 1;
            padding: 0.75rem 0.5rem;
            border-radius: 14px;
            font-size: 0.82rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-card-request {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }
        .btn-card-request:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }
        .btn-card-transfer {
            background: #0ea5e9;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.25);
        }
        .btn-card-transfer:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        /* RECENT TRANSACTION WIDGET */
        .recent-trans-widget {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .trans-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }
        .trans-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            padding: 0.65rem 0.75rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid var(--border-soft);
            transition: all 0.2s;
        }
        .trans-item:hover {
            background: #f1f5f9;
        }
        .trans-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .t-icon-setoran {
            background: #dbeafe;
            color: #2563eb;
        }
        .t-icon-penarikan {
            background: #ede9fe;
            color: #7c3aed;
        }
        .trans-meta {
            flex: 1;
            min-width: 0;
        }
        .trans-desc {
            font-size: 0.82rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .trans-time {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 1px;
        }
        .trans-amount {
            font-size: 0.82rem;
            font-weight: 800;
            white-space: nowrap;
        }
        .amount-green { color: #16a34a; }
        .amount-red { color: #dc2626; }
        .empty-trans-text {
            font-size: 0.82rem;
            color: #94a3b8;
            text-align: center;
            padding: 1rem 0;
        }

        /* LOKET STATUS */
        .loket-quick-status {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 16px;
            padding: 0.85rem 1rem;
        }
        .l-status-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2px;
        }
        .l-dot-online {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #16a34a;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
        }
        .l-status-title {
            font-size: 0.82rem;
            font-weight: 800;
            color: #166534;
        }
        .l-status-desc {
            font-size: 0.75rem;
            color: #15803d;
            line-height: 1.35;
        }

        /* GENERAL STYLES FOR ALL TABS, TABLES, BUTTONS, FORMS */
        .tab-content { display: none; width: 100%; }
        .tab-content.active { display: block; }

        .card {
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 22px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
        }
        .card h4 { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin: 0 0 0.5rem; letter-spacing: 0.5px; }
        .card .value { font-size: 1.75rem; font-weight: 800; color: var(--primary-blue); }

        .grid-split {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) minmax(360px, 2fr);
            gap: 1.75rem;
            width: 100%;
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
            font-family: inherit;
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
            font-family: inherit;
        }
        .btn-success:hover { background: #15803d; }
        .btn-warning {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }
        .btn-danger {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }
        .btn-logout {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 16px;
            border: 1px solid var(--border-soft);
            margin-top: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
        }
        th, td {
            padding: 0.85rem 1.15rem;
            text-align: left;
            border-bottom: 1px solid var(--border-soft);
            font-size: 0.88rem;
            white-space: nowrap;
        }
        th { background: #f8fafc; color: #475569; font-weight: 700; }

        .form-group { margin-bottom: 1.25rem; width: 100%; }
        .form-group label { display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b; }
        .form-control {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            background: #ffffff;
            color: var(--text-navy);
            font-family: inherit;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .badge { padding: 0.35rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
        .badge-aktif { background: #dcfce7; color: #15803d; }
        .badge-tidak-aktif { background: #fee2e2; color: #b91c1c; }
        .badge-terblokir { background: #fef2f2; color: #b91c1c; }
        .badge-role-admin { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .badge-role-teller { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-role-nasabah { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        .sub-filter-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            width: 100%;
        }
        .sub-filter-bar {
            display: inline-flex;
            background: #e2e8f0;
            padding: 5px;
            border-radius: 14px;
            gap: 4px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.04);
            max-width: 100%;
            overflow-x: auto;
        }
        .sub-filter-btn {
            background: transparent;
            color: #475569;
            border: none;
            padding: 0.6rem 1.35rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            font-family: inherit;
        }
        .sub-filter-btn:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.6);
        }
        .sub-filter-btn.active {
            background: #0f172a !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
        }
        .sub-badge {
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            line-height: 1;
        }
        .sub-badge-danger { background: #ef4444; color: #ffffff; }
        .sub-badge-warning { background: #f59e0b; color: #ffffff; }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .notice-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            color: #1e40af;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* MODAL STYLES */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 520px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            position: relative;
        }

        /* RESPONSIVE BREAKPOINTS */
        @media (max-width: 1024px) {
            .dashboard-bottom-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 992px) {
            .navbar {
                padding: 0.75rem 1.25rem;
                gap: 0.75rem;
            }
            .navbar-right {
                gap: 0.65rem;
                flex-wrap: wrap;
            }
            .user-greeting-name {
                max-width: 130px;
            }
            .nav-tabs-bar {
                padding: 0.5rem 1rem;
                top: 65px;
            }
            .container {
                padding: 0 1rem;
                margin: 1.25rem auto 2.5rem;
            }
            .grid-split {
                grid-template-columns: 1fr;
            }
            .hero-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.5rem;
            }
            .hero-illustration {
                display: none;
            }
        }
        @media (max-width: 768px) {
            .navbar {
                padding: 0.65rem 1rem;
            }
            .navbar-brand h2 {
                font-size: 1.1rem !important;
            }
            .navbar-brand p {
                font-size: 0.72rem !important;
            }
            .navbar-right {
                gap: 0.45rem;
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
            .navbar-brand img {
                height: 36px !important;
                width: 36px !important;
            }
            .navbar-brand p {
                display: none;
            }
            #btnToggleNavText {
                display: none;
            }
            .btn-toggle-nav {
                padding: 0.35rem 0.55rem;
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
    <!-- TOP NAVBAR (FULL-WIDTH STICKY) -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('images/logo_bank_mini.png') }}" alt="Logo Bank Mini" style="height: 44px; width: 44px; object-fit: contain; border-radius: 12px; border: 1px solid var(--border-light, #e2e8f0); background: #ffffff; padding: 2px; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);">
            <div>
                <h2 style="margin:0; font-size: 1.25rem; font-weight: 800; line-height: 1.2; color: #0f172a;">Administrator</h2>
                <p style="margin:0; font-size: 0.8rem; color: var(--text-muted, #64748b); font-weight: 600;">E-Teller Bank Mini Sekolah</p>
            </div>
        </div>

        <div class="navbar-right">
            <button type="button" id="btnToggleNav" onclick="toggleNavTabs()" class="btn-toggle-nav" title="Kecilkan / Sembunyikan Navigasi Menu">
                <span id="btnToggleNavIcon">▲</span>
                <span id="btnToggleNavText">Kecilkan Menu</span>
            </button>
            <!-- USER PROFILE DROPDOWN MENU -->
            <div class="user-menu-container" id="userMenuContainer">
                <button type="button" id="btnUserMenu" onclick="toggleUserMenu(event)" class="btn-user-profile-trigger" title="Klik untuk membuka menu profil & akun">
                    <span class="user-name-wrapper">
                        <span class="user-greeting-prefix">Halo, </span>
                        <strong class="user-greeting-name">{{ $user->nama_petugas }}</strong>
                    </span>
                    <span class="badge" style="background:#dcfce7; color:#166534; font-weight:800; font-size:0.75rem; padding: 0.25rem 0.65rem; border-radius: 999px;">ADMIN</span>
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
                            <div class="user-dropdown-role">Administrator &bull; {{ '@' . $user->username }}</div>
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
                <button type="submit" class="btn-logout" onclick="try{sessionStorage.removeItem('admin_active_tab');localStorage.removeItem('admin_active_tab');}catch(e){}">Keluar</button>
            </form>
        </div>
    </nav>

    <!-- HORIZONTAL NAV TABS (TEXT ONLY - STRICTLY NO ICONS) -->
    <div class="nav-tabs-bar" id="navTabsBar">
        <div class="nav-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('tab-dashboard')">Dashboard</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-users')">Manajemen User</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-members')">Data Anggota Siswa</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-savers')">Data Penabung</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-supervisor')">
                Approval Kas & Tutup Kas
                @php $pendingKasCount = $pengajuanKasList->where('status', 'pending')->count(); @endphp
                @if($pendingKasCount > 0)
                    <span class="tab-badge tab-badge-danger">{{ $pendingKasCount }}</span>
                @endif
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-laporan-bulanan')">Laporan Bulanan</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-jurnal')">Jurnal Akuntansi</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-audit')">Audit Logs</button>
            <button type="button" class="tab-btn" onclick="switchTab('tab-pengaduan')">
                Pengaduan Nasabah
                @php $pendingPengaduanCount = isset($pengaduanList) ? $pengaduanList->where('status', 'menunggu')->count() : 0; @endphp
                @if($pendingPengaduanCount > 0)
                    <span class="tab-badge tab-badge-warning">{{ $pendingPengaduanCount }}</span>
                @endif
            </button>
            <button type="button" onclick="toggleNavTabs()" class="btn-quick-collapse" title="Sembunyikan Navigasi Menu">
                <span>▲</span> Sembunyikan
            </button>
        </div>
    </div>

    <!-- MAIN CONTAINER (FULL WIDTH, NO MARGINS / NO OUTER FRAMES) -->
    <div class="container">

            <!-- NOTIFIKASI SUKSES / ERROR SISTEM -->
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

            <!-- TAB 0: DASHBOARD ADMINISTRATOR -->
            <div id="tab-dashboard" class="tab-content active">
                <!-- BANNER PERMINTAAN TAMBAHAN KAS TELLER MENUNGGU APPROVAL -->
                @php $pendingKasTop = $pengajuanKasList->where('status', 'pending'); @endphp
                @if($pendingKasTop->count() > 0)
                    <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #f59e0b; border-radius: 18px; padding: 1.15rem 1.35rem; margin-bottom: 1.5rem; box-shadow: 0 4px 16px rgba(245, 158, 11, 0.12);">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                <span style="background: #f59e0b; color: #fff; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">!</span>
                                <h4 style="margin: 0; color: #92400e; font-size: 1rem; font-weight: 800;">
                                    {{ $pendingKasTop->count() }} Pengajuan Tambahan Kas Teller Menunggu Persetujuan
                                </h4>
                            </div>
                            <button type="button" onclick="switchTab('tab-supervisor')" style="background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 999px; cursor: pointer;">
                                BUKA APPROVAL &rarr;
                            </button>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                            @foreach($pendingKasTop as $pkTop)
                            <div style="background: #ffffff; border: 1px solid #fef3c7; border-radius: 12px; padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                                <div>
                                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a;">
                                        Teller: <span style="color: #2563eb;">{{ $pkTop->teller->nama_petugas ?? 'Teller' }}</span> &bull; 
                                        Nominal: <span style="color: #16a34a; font-size: 1.05rem;">Rp {{ number_format($pkTop->nominal, 0, ',', '.') }}</span>
                                    </div>
                                    <div style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                                        Alasan: <em>"{{ $pkTop->alasan }}"</em> &bull; Waktu: {{ date('H:i', strtotime($pkTop->created_at)) }} WIB
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <form action="{{ route('admin.pengajuan-kas.approve', $pkTop->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('ACC tambahan kas Rp {{ number_format($pkTop->nominal, 0, ',', '.') }} untuk {{ addslashes($pkTop->teller->nama_petugas ?? 'Teller') }}?')">
                                        @csrf
                                        <button type="submit" class="btn-success" style="padding: 0.45rem 1rem; font-size: 0.82rem; border-radius: 8px;">
                                            ✓ ACC Kas
                                        </button>
                                    </form>
                                    <button type="button" class="btn-danger" style="padding: 0.45rem 0.85rem; font-size: 0.82rem; border-radius: 8px;" onclick="openRejectPengajuanModal({{ $pkTop->id }}, '{{ addslashes($pkTop->teller->nama_petugas ?? 'Teller') }}', '{{ number_format($pkTop->nominal, 0, ',', '.') }}')">
                                        Tolak
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 4 STAT METRICS CARDS (MATCHING IMAGE 2 EXACTLY) -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <!-- 1. TOTAL PENGGUNA -->
                    <div class="stat-card" onclick="switchTab('tab-users')">
                        <div class="stat-card-label">TOTAL PENGGUNA</div>
                        <div class="stat-card-value text-blue">{{ $totalUsers }}</div>
                        <div class="stat-card-sub">Admin, Supervisor &amp; Teller &rarr;</div>
                    </div>

                    <!-- 2. TOTAL ANGGOTA SISWA -->
                    <div class="stat-card" onclick="switchTab('tab-members')">
                        <div class="stat-card-label">TOTAL ANGGOTA SISWA</div>
                        <div class="stat-card-value text-indigo">{{ $totalMembers }}</div>
                        <div class="stat-card-sub">Siswa terdaftar di sekolah &rarr;</div>
                    </div>

                    <!-- 3. TOTAL PENABUNG AKTIF -->
                    <div class="stat-card" onclick="switchTab('tab-savers')">
                        <div class="stat-card-label">TOTAL PENABUNG AKTIF</div>
                        <div class="stat-card-value text-emerald">{{ $totalSavers }}</div>
                        <div class="stat-card-sub">Rekening aktif menabung &rarr;</div>
                    </div>

                    <!-- 4. TOTAL SALDO PENABUNG -->
                    <div class="stat-card" onclick="switchTab('tab-jurnal')">
                        <div class="stat-card-label">TOTAL SALDO PENABUNG</div>
                        <div class="stat-card-value text-blue">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
                        <div class="stat-card-sub">Total dana tabungan siswa &rarr;</div>
                    </div>
                </div>

                <!-- 2 OPERASIONAL ALERTS CARDS (MATCHING IMAGE 2) -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <!-- KARTU PENGADUAN SISWA MENUNGGU RESPON -->
                    <div class="op-card" style="background: #ffffff; border-radius: 20px; padding: 1.5rem 1.75rem; border: 1px solid var(--border-light, #e2e8f0); box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                            <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">PENGADUAN SISWA MENUNGGU RESPON</h4>
                            <button type="button" onclick="switchTab('tab-pengaduan')" style="background: #dcfce7; color: #166534; font-weight: 800; font-size: 0.78rem; padding: 0.35rem 0.85rem; border-radius: 999px; border: none; cursor: pointer;">
                                Lihat Semua &rarr;
                            </button>
                        </div>
                        @php $pendingPengaduan = isset($pengaduanList) ? $pengaduanList->where('status', 'menunggu')->take(3) : collect(); @endphp
                        @forelse($pendingPengaduan as $pg)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.75rem 0.85rem; margin-bottom: 0.65rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <strong style="font-size: 0.88rem; color: #0f172a;">{{ $pg->nama_pengadu }}</strong>
                                    <span style="font-size: 0.72rem; color: #64748b;">{{ date('d/m H:i', strtotime($pg->created_at)) }}</span>
                                </div>
                                <div style="font-size: 0.82rem; font-weight: 700; color: #2563eb; margin-bottom: 2px;">{{ $pg->judul ?? $pg->subjek }}</div>
                                <div style="font-size: 0.8rem; color: #475569; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $pg->isi_pesan ?? $pg->pesan }}
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #64748b; padding: 1.75rem 0; font-size: 0.88rem;">
                                ✓ Semua pengaduan nasabah telah ditanggapi.
                            </div>
                        @endforelse
                    </div>

                    <!-- KARTU LAPORAN TUTUP KAS TELLER PENDING -->
                    <div class="op-card" style="background: #ffffff; border-radius: 20px; padding: 1.5rem 1.75rem; border: 1px solid var(--border-light, #e2e8f0); box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                            <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">LAPORAN TUTUP KAS TELLER PENDING</h4>
                            <button type="button" onclick="switchTab('tab-supervisor')" style="background: #dcfce7; color: #166534; font-weight: 800; font-size: 0.78rem; padding: 0.35rem 0.85rem; border-radius: 999px; border: none; cursor: pointer;">
                                Buka Approval &rarr;
                            </button>
                        </div>
                        @forelse($laporanPending->take(3) as $lpDash)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.75rem 0.85rem; margin-bottom: 0.65rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 0.88rem; color: #0f172a;">{{ $lpDash->teller->nama_petugas ?? 'Teller' }}</strong>
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">
                                        Tgl: {{ date('d/m/Y', strtotime($lpDash->tanggal)) }} &bull; Fisik: Rp {{ number_format($lpDash->uang_fisik) }}
                                    </div>
                                </div>
                                <button type="button" onclick="openRiwayatModal({{ $lpDash->id }})" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.78rem; border-radius: 8px;">
                                    Cek Mutasi
                                </button>
                            </div>
                        @empty
                            <div style="text-align: center; color: #64748b; padding: 1.75rem 0; font-size: 0.88rem;">
                                ✓ Tidak ada laporan tutup kas teller yang menunggu persetujuan.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TRANSAKSI TERKINI & KARTU KAS BANK MINI -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 1.25rem;">
                    <!-- RECENT TRANSACTIONS -->
                    <div class="recent-trans-widget" style="margin: 0; background: #ffffff; border-radius: 20px; padding: 1.5rem 1.75rem; border: 1px solid var(--border-light, #e2e8f0); box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h4 class="widget-title" style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Transaksi Terkini (Recent Transactions)</h4>
                            <button type="button" onclick="switchTab('tab-jurnal')" style="background: none; border: none; color: #2563eb; font-weight: 700; font-size: 0.8rem; cursor: pointer;">Lihat Semua &rarr;</button>
                        </div>
                        <div class="trans-list">
                            @php
                                $displayTransactions = collect($recentTransactions ?? \App\Models\Transaksi::with(['saver' => fn($q) => $q->withTrashed()])->orderBy('id', 'desc')->take(5)->get())->take(5);
                            @endphp
                            @forelse($displayTransactions as $trx)
                                @php
                                    $isSetoran = ($trx->jenis_trans === 'setoran');
                                    $trxNominal = (int)($trx->nominal ?? 0);
                                    $namaNasabah = $trx->saver->student_name ?? $trx->saver_name ?? '';
                                    $descTitle = $namaNasabah 
                                        ? ($isSetoran ? 'Setoran Nasabah (' . $namaNasabah . ')' : 'Penarikan Nasabah (' . $namaNasabah . ')')
                                        : ($trx->keterangan ?: ($isSetoran ? 'Setoran Nasabah' : 'Penarikan Nasabah'));
                                @endphp
                                <div class="trans-item">
                                    <div class="trans-icon {{ $isSetoran ? 't-icon-setoran' : 't-icon-penarikan' }}">
                                        @if($isSetoran)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" x2="12" y1="5" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" x2="12" y1="19" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                        @endif
                                    </div>
                                    <div class="trans-meta">
                                        <div class="trans-desc" title="{{ $descTitle }}">{{ $descTitle }}</div>
                                        <div class="trans-time">{{ date('d M Y, H:i', strtotime($trx->tanggal ?? $trx->created_at)) }}</div>
                                    </div>
                                    <div class="trans-amount {{ $isSetoran ? 'amount-green' : 'amount-red' }}">
                                        {{ $isSetoran ? '+' : '-' }}Rp {{ number_format($trxNominal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="empty-trans-text">Belum ada transaksi</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- GRAFIK ARUS KAS (PEMASUKAN & PENGELUARAN) -->
                    <div class="chart-flow-widget" style="margin: 0; background: #ffffff; border-radius: 20px; padding: 1.5rem 1.75rem; border: 1px solid var(--border-light, #e2e8f0); box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03); display: flex; flex-direction: column; justify-content: space-between;">
                        <!-- HEADER KARTU GRAFIK -->
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Grafik Pemasukan &amp; Pengeluaran</h4>
                                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600; margin-top: 2px;">Arus kas setoran &amp; penarikan 7 hari terakhir</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 700; color: #2563eb;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #2563eb; display: inline-block;"></span> Pemasukan
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; font-weight: 700; color: #94a3b8;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span> Pengeluaran
                                </div>
                                <span style="background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 8px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px;">
                                    Mingguan ▾
                                </span>
                            </div>
                        </div>

                        <!-- BODY GRAFIK DENGAN SVG DUAL-CURVE PERSIS SEPERTI GAMBAR CONTOH -->
                        @php
                            $chartData = $chartFlow ?? collect([
                                ['day' => 'Sun', 'pemasukan' => 0, 'pengeluaran' => 0],
                                ['day' => 'Mon', 'pemasukan' => 0, 'pengeluaran' => 0],
                                ['day' => 'Tue', 'pemasukan' => 0, 'pengeluaran' => 0],
                                ['day' => 'Wed', 'pemasukan' => 0, 'pengeluaran' => 0],
                                ['day' => 'Thu', 'pemasukan' => 0, 'pengeluaran' => 0],
                                ['day' => 'Fri', 'pemasukan' => 0, 'pengeluaran' => 0],
                                ['day' => 'Sat', 'pemasukan' => 0, 'pengeluaran' => 0],
                            ]);

                            $rawMax = max(500000, (float)$chartData->max('pemasukan'), (float)$chartData->max('pengeluaran'));
                            $magnitude = pow(10, max(1, floor(log10($rawMax))));
                            $chartMax = ceil($rawMax / $magnitude) * $magnitude;
                            if ($chartMax <= 0) $chartMax = 500000;

                            $formatYAxis = function($val) {
                                if ($val >= 1000000) {
                                    return round($val / 1000000, 1) . 'M';
                                } elseif ($val >= 1000) {
                                    return round($val / 1000) . 'k';
                                }
                                return (string)$val;
                            };

                            $svgW = 460;
                            $svgH = 190;
                            $chartLeft = 45;
                            $chartRight = 440;
                            $chartTop = 20;
                            $chartBottom = 160;
                            $plotW = $chartRight - $chartLeft;
                            $plotH = $chartBottom - $chartTop;

                            $countPts = count($chartData);
                            $stepX = $countPts > 1 ? $plotW / ($countPts - 1) : $plotW;

                            $ptsIn = [];
                            $ptsOut = [];
                            foreach ($chartData as $i => $row) {
                                $x = $chartLeft + ($i * $stepX);
                                $valIn = min($chartMax, (float)($row['pemasukan'] ?? 0));
                                $valOut = min($chartMax, (float)($row['pengeluaran'] ?? 0));
                                
                                $yIn = $chartBottom - (($valIn / $chartMax) * $plotH);
                                $yOut = $chartBottom - (($valOut / $chartMax) * $plotH);
                                
                                $ptsIn[] = ['x' => $x, 'y' => $yIn, 'val' => $row['pemasukan'] ?? 0, 'day' => $row['day'] ?? ''];
                                $ptsOut[] = ['x' => $x, 'y' => $yOut, 'val' => $row['pengeluaran'] ?? 0, 'day' => $row['day'] ?? ''];
                            }

                            $makeSpline = function($pts) {
                                if (count($pts) < 2) return '';
                                $path = 'M ' . $pts[0]['x'] . ' ' . $pts[0]['y'];
                                for ($i = 0; $i < count($pts) - 1; $i++) {
                                    $p0 = $pts[$i];
                                    $p1 = $pts[$i + 1];
                                    $cpx = $p0['x'] + ($p1['x'] - $p0['x']) / 2;
                                    $path .= ' C ' . $cpx . ' ' . $p0['y'] . ', ' . $cpx . ' ' . $p1['y'] . ', ' . $p1['x'] . ' ' . $p1['y'];
                                }
                                return $path;
                            };

                            $pathIn = $makeSpline($ptsIn);
                            $pathOut = $makeSpline($ptsOut);

                            $firstX = $ptsIn[0]['x'] ?? $chartLeft;
                            $lastX = $ptsIn[count($ptsIn) - 1]['x'] ?? $chartRight;
                            $areaIn = $pathIn . ' L ' . $lastX . ' ' . $chartBottom . ' L ' . $firstX . ' ' . $chartBottom . ' Z';

                            $lastPt = end($ptsIn);
                        @endphp

                        <div style="width: 100%; position: relative;">
                            <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" style="width: 100%; height: auto; overflow: visible; display: block;" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="flowBlueGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.22"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0"/>
                                    </linearGradient>
                                    <filter id="dotGlow" x="-50%" y="-50%" width="200%" height="200%">
                                        <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#2563eb" flood-opacity="0.35"/>
                                    </filter>
                                </defs>

                                <!-- HORIZONTAL GRID LINES & Y-AXIS LABELS -->
                                @for($g = 0; $g <= 4; $g++)
                                    @php
                                        $gy = $chartBottom - ($g * ($plotH / 4));
                                        $gVal = ($chartMax / 4) * $g;
                                    @endphp
                                    <line x1="{{ $chartLeft }}" y1="{{ $gy }}" x2="{{ $chartRight }}" y2="{{ $gy }}" stroke="#f1f5f9" stroke-width="1.2" stroke-dasharray="{{ $g === 0 ? 'none' : '3,3' }}"/>
                                    <text x="{{ $chartLeft - 8 }}" y="{{ $gy + 4 }}" font-size="10" font-weight="600" fill="#94a3b8" text-anchor="end" font-family="inherit">
                                        {{ $formatYAxis($gVal) }}
                                    </text>
                                @endfor

                                <!-- AREA GRADIENT PEMASUKAN -->
                                <path d="{{ $areaIn }}" fill="url(#flowBlueGrad)"/>

                                <!-- KURVA PENGELUARAN (GARIS ABU-ABU PERSIS SEPERTI CONTOH GAMBAR) -->
                                <path d="{{ $pathOut }}" fill="none" stroke="#94a3b8" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>

                                <!-- KURVA PEMASUKAN (GARIS BIRU HALUS) -->
                                <path d="{{ $pathIn }}" fill="none" stroke="#2563eb" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>

                                <!-- TITIK-TITIK DATA INTERAKTIF & X-AXIS LABELS -->
                                @foreach($ptsIn as $idx => $p)
                                    <text x="{{ $p['x'] }}" y="{{ $chartBottom + 20 }}" font-size="11" font-weight="700" fill="#64748b" text-anchor="middle" font-family="inherit">
                                        {{ $p['day'] }}
                                    </text>
                                    <g>
                                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="12" fill="transparent" style="cursor: pointer;">
                                            <title>{{ $p['day'] }}: Pemasukan Rp {{ number_format($p['val'], 0, ',', '.') }} | Pengeluaran Rp {{ number_format($ptsOut[$idx]['val'] ?? 0, 0, ',', '.') }}</title>
                                        </circle>
                                    </g>
                                @endforeach

                                <!-- ACTIVE HIGHLIGHTED NODE DI TITIK TERBARU -->
                                @if(!empty($lastPt))
                                    <circle cx="{{ $lastPt['x'] }}" cy="{{ $lastPt['y'] }}" r="6" fill="#2563eb" stroke="#ffffff" stroke-width="2.5" filter="url(#dotGlow)"/>
                                @endif
                            </svg>
                        </div>

                        <!-- STATISTIK FOOTER KARTU GRAFIK -->
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.65rem; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid #f1f5f9;">
                            <div style="background: #eff6ff; padding: 0.5rem 0.65rem; border-radius: 10px; border: 1px solid #dbeafe;">
                                <div style="font-size: 0.7rem; color: #1e40af; font-weight: 700;">Total Pemasukan</div>
                                <div style="font-size: 0.88rem; font-weight: 800; color: #1d4ed8; margin-top: 2px;">
                                    +Rp {{ number_format($totalChartPemasukan ?? $chartData->sum('pemasukan'), 0, ',', '.') }}
                                </div>
                            </div>
                            <div style="background: #f8fafc; padding: 0.5rem 0.65rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <div style="font-size: 0.7rem; color: #475569; font-weight: 700;">Total Pengeluaran</div>
                                <div style="font-size: 0.88rem; font-weight: 800; color: #dc2626; margin-top: 2px;">
                                    -Rp {{ number_format($totalChartPengeluaran ?? $chartData->sum('pengeluaran'), 0, ',', '.') }}
                                </div>
                            </div>
                            <div style="background: #f0fdf4; padding: 0.5rem 0.65rem; border-radius: 10px; border: 1px solid #bbf7d0;">
                                <div style="font-size: 0.7rem; color: #166534; font-weight: 700;">Kas Bank Mini</div>
                                <div style="font-size: 0.88rem; font-weight: 800; color: #15803d; margin-top: 2px;">
                                    Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS 1 TO 8 (PRESERVED IN FULL INTEGRITY) -->
<div id="tab-users" class="tab-content">
            <div class="grid-split">
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom: 1.25rem;">Tambah User Petugas</h3>
                    <form action="{{ route('admin.users.store') }}" method="POST" onsubmit="return confirm('Konfirmasi: Apakah Anda yakin ingin menyimpan dan membuat akun user petugas baru ini?');">
                        @csrf
                        <div class="form-group">
                            <label>Nama Petugas</label>
                            <input type="text" name="nama_petugas" class="form-control" required placeholder="Contoh: Siti Rahmawati">
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Contoh: teller2">
                        </div>
                        <div class="form-group">
                            <label>Alamat Gmail Petugas</label>
                            <input type="email" name="email" class="form-control" required placeholder="Contoh: teller@gmail.com">
                        </div>
                        <div class="form-group">
                            <label>Role Petugas</label>
                            <select name="role" class="form-control" required>
                                <option value="teller">Teller</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div style="position: relative;">
                                <input type="password" name="password" id="adminUserPassword" class="form-control" required placeholder="Minimal 6 karakter" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('adminUserPassword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 0.5rem;">Simpan Petugas</button>
                    </form>
                </div>

                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <h3 style="margin:0;">Daftar Pengguna Sistem</h3>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Total: <strong>{{ count($users) }}</strong> pengguna</span>
                    </div>

                    <!-- PENCARIAN & FILTER USER -->
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                        <div style="flex: 1; min-width: 200px;">
                            <input type="text" id="searchUser" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Nama, Username, Email..." oninput="filterUserTable()">
                        </div>
                        <div style="width: 160px;">
                            <select id="filterUserRole" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterUserTable()">
                                <option value="">Semua Role</option>
                                <option value="administrator">Administrator</option>
                                <option value="teller">Teller</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="nasabah">Nasabah</option>
                            </select>
                        </div>
                        <div style="width: 140px;">
                            <select id="filterUserStatus" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterUserTable()">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="terblokir">Terblokir</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengguna</th>
                                    <th>Username (NIS)</th>
                                    <th>Email / Gmail</th>
                                    <th>Jurusan</th>
                                    <th>Tahun Kelulusan</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableUserBody">
                                @foreach($users as $index => $u)
                                @php
                                    $userJurusan = $u->member->class_grade ?? ($u->saver->class_grade ?? '-');
                                    $userTahunLulus = $u->member->graduation_year ?? '-';
                                @endphp
                                <tr class="user-row" data-role="{{ strtolower($u->role) }}" data-status="{{ strtolower($u->status) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $u->nama_petugas }}</td>
                                    <td><code>{{ $u->username }}</code></td>
                                    <td><small>{{ $u->email ?: '-' }}</small></td>
                                    <td>
                                        @if(in_array($u->role, ['nasabah', 'saver']))
                                            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700;">{{ $userJurusan }}</span>
                                        @else
                                            <span style="color: var(--text-muted);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($u->role, ['nasabah', 'saver']))
                                            <span style="font-weight: 600;">{{ $userTahunLulus }}</span>
                                        @else
                                            <span style="color: var(--text-muted);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($u->role === 'administrator')
                                            <span class="badge badge-role-admin">ADMINISTRATOR</span>
                                        @elseif($u->role === 'teller')
                                            <span class="badge badge-role-teller">TELLER</span>
                                        @else
                                            <span class="badge badge-role-nasabah">{{ strtoupper($u->role) }}</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-{{ $u->status }}">{{ strtoupper($u->status) }}</span></td>
                                    <td>
                                        @if($u->id !== auth()->id())
                                            <form action="{{ route('admin.users.toggle-status', $u->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Konfirmasi: Apakah Anda yakin ingin {{ $u->status === 'aktif' ? 'MEMBLOKIR' : 'MEMBUKA BLOKIR' }} akun user {{ addslashes($u->nama_petugas) }}?');">
                                                @csrf
                                                <button type="submit" class="btn-warning">
                                                    {{ $u->status === 'aktif' ? 'Blokir' : 'Buka Blokir' }}
                                                </button>
                                            </form>
                                        @else
                                            <em>Akun Sendiri</em>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                <tr id="userEmptySearch" style="display: none;">
                                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada pengguna sistem yang sesuai dengan pencarian atau filter role.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: DATA ANGGOTA SISWA (MEMBERS) - CRUD -->
        <div id="tab-members" class="tab-content">
            <div class="grid-split">
                <!-- KOLOM KIRI: FORM TAMBAH ANGGOTA & DI BAWAHNYA BOX IMPOR DATA -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- 1. KARTU TAMBAH ANGGOTA SISWA BARU (FORM INLINE PERSIS SEPERTI BOX TAMBAH USER) -->
                    <div class="card">
                        <h3 style="margin-top:0; margin-bottom: 0.35rem;">Tambah Anggota Siswa Baru</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Tabel Anggota digunakan untuk menambah anggota</p>

                        <form action="{{ route('admin.members.store') }}" method="POST" onsubmit="return confirm('Konfirmasi: Apakah Anda yakin ingin mendaftarkan anggota siswa baru ini?');">
                            @csrf
                            <div class="form-group">
                                <label>Nama Siswa <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="student_name" class="form-control" required placeholder="Contoh: Budi Pratama">
                            </div>
                            <div class="form-group">
                                <label>NIS (Nomor Induk Siswa) <span style="color: #dc2626;">*</span></label>
                                <input type="text" name="nis" class="form-control" required placeholder="Contoh: 202611005">
                            </div>
                            <div class="form-group">
                                <label>Alamat Gmail Siswa <span style="color: #dc2626;">*</span></label>
                                <input type="email" name="email" class="form-control" required placeholder="Contoh: budi.pratama@gmail.com">
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                <div class="form-group">
                                    <label>Jurusan <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="class_grade" class="form-control" required placeholder="Contoh: RPL, AKL, dll">
                                </div>
                                <div class="form-group">
                                    <label>Tahun Kelulusan <span style="color: #dc2626;">*</span></label>
                                    <input type="text" name="graduation_year" class="form-control" required placeholder="Contoh: 2028">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Password Akun Login Siswa <span style="color: #dc2626;">*</span></label>
                                <div style="position: relative;">
                                    <input type="password" name="password" id="adminMemberPasswordInline" class="form-control" required minlength="6" placeholder="Minimal 6 karakter" style="padding-right: 2.75rem;">
                                    <button type="button" onclick="togglePasswordVisibility('adminMemberPasswordInline', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>PIN Keamanan Transaksi Penarikan (Wajib 6-Digit) <span style="color: #dc2626;">*</span></label>
                                <div style="position: relative;">
                                    <input type="password" name="pin" id="adminMemberPinInline" class="form-control" required maxlength="6" minlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="Wajib 6 digit angka" style="padding-right: 2.75rem;">
                                    <button type="button" onclick="togglePasswordVisibility('adminMemberPinInline', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 0.5rem; padding: 0.75rem;">Simpan Data Anggota</button>
                        </form>
                    </div>

                    <!-- 2. KARTU IMPOR DATA ANGGOTA (DI BAWAHNYA BOX TAMBAH ANGGOTA) -->
                    <div class="card">
                        <h3 style="margin-top:0; margin-bottom: 0.35rem; color: #1e293b;">Impor Data Anggota</h3>
                        <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.45;">
                            Format kolom template: <strong>nisn, nama, jurusan, tahun_keluar</strong>. Alamat Gmail dan PIN nasabah akan diinput oleh petugas saat aktivasi rekening di loket Teller.
                        </p>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-weight: 700; font-size: 0.82rem; margin-bottom: 0.45rem; color: #1e293b;">
                                Unduh Template Format Database:
                            </label>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('admin.members.template', ['format' => 'xlsx']) }}" class="btn-success" style="padding: 0.45rem 0.9rem; font-size: 0.82rem; border-radius: 9px; text-decoration: none;">
                                    Template Excel (.xlsx)
                                </a>
                                <a href="{{ route('admin.members.template', ['format' => 'csv']) }}" class="btn-primary" style="padding: 0.45rem 0.9rem; font-size: 0.82rem; border-radius: 9px; background: #475569; text-decoration: none;">
                                    Template CSV
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('admin.members.import') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Konfirmasi Impor:\n\nApakah Anda yakin ingin mengunggah dan mengimpor file data anggota siswa ini ke database?');">
                            @csrf
                            <div class="form-group" style="margin-bottom: 0.75rem;">
                                <label style="font-weight: 700; font-size: 0.82rem; margin-bottom: 0.35rem;">Pilih Berkas CSV / XLSX</label>
                                <input type="file" name="file" class="form-control" accept=".csv, .xlsx, .xls" required style="padding: 0.5rem 0.75rem; font-size: 0.85rem;">
                            </div>
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.65rem; font-size: 0.88rem;">
                                Unggah &amp; Impor Anggota
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KOLOM KANAN: DAFTAR ANGGOTA SISWA (MEMBERS) -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div>
                            <h3 style="margin:0;">Daftar Anggota Siswa (Members)</h3>
                        </div>
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                            <button type="button" id="btnFilterBelumAktif" onclick="setMemberStatusFilter('belum_aktif')" class="badge badge-role-teller" style="cursor: pointer; border: none; padding: 0.4rem 0.75rem; font-size: 0.78rem;">
                                Belum Aktif ({{ $totalBelumAktif }})
                            </button>
                            <button type="button" id="btnFilterSemua" onclick="setMemberStatusFilter('semua')" class="badge badge-tidak-aktif" style="cursor: pointer; border: none; padding: 0.4rem 0.75rem; font-size: 0.78rem;">
                                Semua Siswa ({{ $totalMembers }})
                            </button>
                        </div>
                    </div>

                    <!-- PENCARIAN & FILTER ANGGOTA SISWA -->
                    @php
                        $uniqueMemberKelas = \App\Models\Member::whereNotNull('class_grade')->where('class_grade', '!=', '')->distinct()->pluck('class_grade')->sort()->values();
                        $uniqueMemberAngkatan = \App\Models\Member::whereNotNull('graduation_year')->where('graduation_year', '!=', '')->distinct()->pluck('graduation_year')->push((string)date('Y'))->push((string)(date('Y')+1))->push((string)(date('Y')+2))->unique()->sort()->values();
                    @endphp
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                        <div style="flex: 1; min-width: 200px;">
                            <input type="text" id="searchMember" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Siswa (Nama, NIS, Email)..." oninput="filterMemberTable()">
                        </div>
                        <div style="width: 150px;">
                            <select id="filterMemberKelas" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterMemberTable()">
                                <option value="">Semua Jurusan</option>
                                @foreach($uniqueMemberKelas as $uk)
                                    <option value="{{ strtolower($uk) }}">{{ $uk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width: 150px;">
                            <select id="filterMemberAngkatan" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterMemberTable()">
                                <option value="">Semua Tahun Kelulusan</option>
                                @foreach($uniqueMemberAngkatan as $ua)
                                    <option value="{{ strtolower($ua) }}">{{ $ua }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>Email / Gmail</th>
                                    <th>Jurusan</th>
                                    <th>Tahun Kelulusan</th>
                                    <th>Status</th>
                                    <th>Aksi CRUD</th>
                                </tr>
                            </thead>
                            <tbody id="tableMemberBody">
                                @forelse($members as $index => $m)
                                <tr class="member-row" data-status="{{ $m->status === 'rekening aktif' ? 'aktif' : 'belum_aktif' }}" data-kelas="{{ strtolower($m->class_grade) }}" data-angkatan="{{ strtolower($m->graduation_year) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $m->student_name }}</strong></td>
                                    <td><code>{{ $m->nis }}</code></td>
                                    <td><small>{{ $m->email ?: '-' }}</small></td>
                                    <td>{{ $m->class_grade }}</td>
                                    <td>{{ $m->graduation_year }}</td>
                                    <td>
                                        @if($m->status === 'rekening aktif')
                                            <span class="badge badge-aktif">AKTIF</span>
                                        @else
                                            <span class="badge badge-tidak-aktif">BELUM AKTIF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.35rem;">
                                             <button type="button" class="btn-warning" onclick="openEditMemberModal('{{ $m->user_id }}', '{{ addslashes($m->student_name) }}', '{{ $m->nis }}', '{{ addslashes($m->email ?? '') }}', '{{ $m->class_grade }}', '{{ $m->graduation_year }}')">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.members.destroy', $m->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota {{ addslashes($m->student_name) }}?')" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                        Belum ada data anggota siswa yang terdaftar.
                                    </td>
                                </tr>
                                @endforelse
                                <tr id="memberEmptySearch" style="display: none;">
                                    <td colspan="8" id="memberEmptyText" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada anggota siswa yang sesuai dengan filter atau pencarian.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINASI DATA ANGGOTA SISWA (MAKSIMAL 20 PER HALAMAN AGAR TIDAK LAG) -->
                    <div id="memberPaginationWrapper" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid var(--border-color);">
                        <div style="font-size: 0.85rem; color: var(--text-muted);">
                            Menampilkan <strong id="memberPageInfo">0 - 0 dari 0</strong> data anggota siswa
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <button type="button" id="btnMemberPrev" onclick="prevMemberPage()" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.82rem; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px;">
                                Sebelumnya
                            </button>
                            
                            <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; color: #475569;">
                                <span>Halaman</span>
                                <input type="number" id="memberPageInput" min="1" value="1" onchange="jumpMemberPage(this.value)" style="width: 58px; text-align: center; padding: 0.3rem 0.4rem; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 700; font-size: 0.85rem;">
                                <span>dari <strong id="memberTotalPages">1</strong></span>
                            </div>

                            <button type="button" id="btnMemberNext" onclick="nextMemberPage()" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.82rem; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px;">
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>
            </div> <!-- END .grid-split -->
        </div>

        <!-- TAB 3: DATA PENABUNG (SAVER) -->
        <div id="tab-savers" class="tab-content">
            <div class="card">


                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin:0;">Daftar Rekening Penabung </h3>
                        <p style="margin:0; font-size: 0.85rem; color: var(--text-muted);">Siswa yang telah mengaktifkan rekening dan memiliki catatan saldo tabungan.</p>
                    </div>
                    <div>
                        <span class="badge badge-aktif" style="font-size: 0.85rem; padding: 0.5rem 1rem;">Total Saldo: Rp {{ number_format($totalSaldo, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- PENCARIAN & FILTER PENABUNG -->
                @php
                    $uniqueSaverKelas = \App\Models\Saver::whereNotNull('class_grade')->where('class_grade', '!=', '')->distinct()->pluck('class_grade')->sort()->values();
                    $uniqueSaverAngkatan = \App\Models\Member::whereNotNull('graduation_year')->where('graduation_year', '!=', '')->distinct()->pluck('graduation_year')->sort()->values();
                @endphp
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" id="searchSaver" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Penabung (Nama, No Rekening, NIS, Tahun Kelulusan)..." oninput="filterSaverTable()">
                    </div>
                    <div style="width: 150px;">
                        <select id="filterSaverKelas" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterSaverTable()">
                            <option value="">Semua Jurusan</option>
                            @foreach($uniqueSaverKelas as $sk)
                                <option value="{{ strtolower($sk) }}">{{ $sk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 170px;">
                        <select id="filterSaverAngkatan" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterSaverTable()">
                            <option value="">Semua Tahun Lulus</option>
                            @foreach($uniqueSaverAngkatan as $sa)
                                <option value="{{ strtolower($sa) }}">{{ $sa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No Rekening</th>
                                <th>Nama Penabung</th>
                                <th>NIS</th>
                                <th>Jurusan</th>
                                <th>Tahun Kelulusan</th>
                                <th>Saldo Real-Time</th>
                                <th>Status Rekening</th>
                                <th>Aksi Penabung</th>
                            </tr>
                        </thead>
                        <tbody id="tableSaverBody">
                            @forelse($savers as $s)
                            <tr class="saver-row" data-kelas="{{ strtolower($s->class_grade) }}" data-angkatan="{{ strtolower($s->graduation_year ?? '') }}">
                                <td><code>{{ $s->account_number }}</code></td>
                                <td><strong>{{ $s->student_name }}</strong></td>
                                <td><code>{{ $s->nis }}</code></td>
                                <td>{{ $s->class_grade }}</td>
                                <td><strong>{{ $s->graduation_year ?? '-' }}</strong></td>
                                <td><strong style="color: #16a34a; font-size: 1rem;">Rp {{ number_format($s->rekening->saldo ?? 0, 0, ',', '.') }}</strong></td>
                                <td><span class="badge badge-aktif">{{ strtoupper($s->rekening->status ?? 'aktif') }}</span></td>
                                <td>
                                    <div style="display: flex; gap: 0.35rem;">
                                        <button type="button" class="btn-warning" onclick="openResetPinModal('{{ $s->id }}', '{{ addslashes($s->student_name) }}', '{{ $s->account_number }}')">
                                             Reset PIN
                                        </button>
                                        <form action="{{ route('admin.savers.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Perhatian: Akun penabung {{ addslashes($s->student_name) }} akan dinonaktifkan/dihapus, namun catatan histori transaksi tetap aman tersimpan untuk rekonsiliasi kas. Lanjutkan?')" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="btn-danger">Hapus / Nonaktif</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada penabung aktif. Aktivasi penabung dilakukan oleh Teller melalui setoran awal minimal Rp 10.000.</td>
                            </tr>
                            @endforelse
                            <tr id="saverEmptySearch" style="display: none;">
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada penabung yang sesuai dengan pencarian atau filter jurusan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: APPROVAL KAS & TUTUP KAS TELLER -->
        <div id="tab-supervisor" class="tab-content">
            <!-- SUB-FILTER BUTTONS (DI TENGAH, TANPA ICON, BUKAN WARNA BIRU) -->
            <div class="sub-filter-wrapper">
                <div class="sub-filter-bar">
                    <button type="button" class="sub-filter-btn active" onclick="switchSubFilter('sub-pengajuan-kas')">
                        Pengajuan Kas Teller
                        @php $pendingKasSub = $pengajuanKasList->where('status', 'pending')->count(); @endphp
                        @if($pendingKasSub > 0)
                            <span class="sub-badge sub-badge-danger">{{ $pendingKasSub }}</span>
                        @endif
                    </button>
                    <button type="button" class="sub-filter-btn" onclick="switchSubFilter('sub-laporan-penutup')">
                        Laporan Penutup Kas
                        @if($laporanPending->count() > 0)
                            <span class="sub-badge sub-badge-warning">{{ $laporanPending->count() }}</span>
                        @endif
                    </button>
                    <button type="button" class="sub-filter-btn" onclick="switchSubFilter('sub-arsip-laporan')">
                        Arsip Laporan
                    </button>
                </div>
            </div>

            <!-- SUB-CONTENT: PENGAJUAN TAMBAHAN KAS TELLER -->
            <div id="sub-pengajuan-kas" class="sub-filter-content" style="display: block;">
            <div class="card" style="margin-bottom: 2rem; border-left: 4px solid #2563eb;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin: 0; color: #1e40af;">Pengajuan Tambahan Kas Teller</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.25rem 0 0 0;">Pengajuan dana tunai dari teller saat kas laci tidak cukup untuk melayani penarikan.</p>
                    </div>
                    @php $pendingCount = $pengajuanKasList->where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 0.85rem;">{{ $pendingCount }} Menunggu ACC</span>
                    @endif
                </div>

                <div class="table-responsive" style="margin-top: 1rem;">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Teller</th>
                                <th>Nominal</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuanKasList as $pk)
                            <tr>
                                <td>{{ $pk->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $pk->teller->nama_petugas ?? 'Teller' }}</strong></td>
                                <td><strong style="color: #2563eb;">Rp {{ number_format($pk->nominal, 0, ',', '.') }}</strong></td>
                                <td style="max-width: 260px; white-space: normal;">
                                    <div>{{ $pk->alasan }}</div>
                                    @if($pk->status === 'rejected' && $pk->catatan_admin)
                                        <div style="font-size: 0.8rem; color: #dc2626; margin-top: 5px; background: #fef2f2; border: 1px solid #fee2e2; padding: 4px 8px; border-radius: 6px;">
                                            <strong>Alasan Ditolak:</strong> {{ $pk->catatan_admin }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($pk->status === 'pending')
                                        <span class="badge" style="background: #fef3c7; color: #92400e;">Menunggu</span>
                                    @elseif($pk->status === 'approved')
                                        <span class="badge badge-aktif">Disetujui</span>
                                    @else
                                        <span class="badge badge-tidak-aktif">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pk->status === 'pending')
                                        <div style="display: flex; gap: 0.4rem; align-items: center;">
                                            <form action="{{ route('admin.pengajuan-kas.approve', $pk->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Konfirmasi Approval Kas:\n\nApakah Anda yakin ingin MENYETUJUI (ACC) tambahan modal kas sebesar Rp {{ number_format($pk->nominal, 0, ',', '.') }} untuk {{ addslashes($pk->teller->nama_petugas ?? 'Teller') }}?');">
                                                @csrf
                                                <button type="submit" class="btn-success" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;">ACC</button>
                                            </form>
                                            <button type="button" class="btn-danger" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onclick="openRejectPengajuanModal({{ $pk->id }}, '{{ addslashes($pk->teller->nama_petugas ?? 'Teller') }}', '{{ number_format($pk->nominal, 0, ',', '.') }}')">Tolak</button>
                                        </div>
                                    @else
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            @if($pk->status === 'approved')
                                                Di-ACC oleh {{ $pk->admin->nama_petugas ?? 'Admin' }}
                                            @else
                                                <span style="color: #dc2626; font-weight: 600;">Ditolak oleh {{ $pk->admin->nama_petugas ?? 'Admin' }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada pengajuan tambahan kas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

            <!-- SUB-CONTENT: LAPORAN PENUTUP KAS -->
            <div id="sub-laporan-penutup" class="sub-filter-content" style="display: none;">
            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="margin-top:0; color: #d97706; margin-bottom: 0.5rem;">Laporan Penutupan Kas Teller Menunggu Approval</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Administrator memvalidasi saldo akhir kas sistem dengan uang fisik kas laci teller sebelum menyetujui pemindahan ke brankas utama.</p>

                <!-- PENCARIAN & FILTER KAS PENDING -->
                @php
                    $uniqueTahunPending = $laporanPending->toBase()->map(fn($lp) => date('Y', strtotime($lp->tanggal)))->push((string)date('Y'))->unique()->sort()->values();
                @endphp
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" id="searchKasPending" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Nama Teller..." oninput="filterKasPendingTable()">
                    </div>
                    <div style="width: 170px;">
                        <input type="date" id="filterKasPendingTanggal" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterKasPendingTable()" title="Filter Tanggal">
                    </div>
                    <div style="width: 140px;">
                        <select id="filterKasPendingTahun" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterKasPendingTable()">
                            <option value="">Semua Tahun</option>
                            @foreach($uniqueTahunPending as $yt)
                                <option value="{{ $yt }}">{{ $yt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Teller</th>
                                <th>Saldo Awal</th>
                                <th>Total Setoran</th>
                                <th>Total Penarikan</th>
                                <th>Saldo Sistem</th>
                                <th>Uang Fisik</th>
                                <th>Selisih Kas</th>
                                <th>Aksi Approval</th>
                            </tr>
                        </thead>
                        <tbody id="tableKasPendingBody">
                            @forelse($laporanPending as $lp)
                            <tr class="kas-pending-row" data-tanggal="{{ date('Y-m-d', strtotime($lp->tanggal)) }}" data-tahun="{{ date('Y', strtotime($lp->tanggal)) }}">
                                <td><strong>{{ date('d/m/Y', strtotime($lp->tanggal)) }}</strong></td>
                                <td>{{ $lp->teller->nama_petugas ?? 'Teller' }}</td>
                                <td>Rp {{ number_format($lp->saldo_awal) }}</td>
                                <td><strong style="color:#16a34a;">Rp {{ number_format($lp->total_setoran) }}</strong></td>
                                <td><strong style="color:#dc2626;">Rp {{ number_format($lp->total_penarikan) }}</strong></td>
                                <td>Rp {{ number_format($lp->saldo_akhir_sistem) }}</td>
                                <td>Rp {{ number_format($lp->uang_fisik) }}</td>
                                <td>
                                    <strong style="color: {{ $lp->selisih == 0 ? '#16a34a' : '#dc2626' }};">
                                        Rp {{ number_format($lp->selisih) }}
                                    </strong>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap;">
                                        <button type="button" onclick="openRiwayatModal({{ $lp->id }})" style="background: #2563eb; color: #fff; border:none; padding: 0.45rem 0.75rem; border-radius: 6px; font-weight:700; font-size: 0.85rem; cursor:pointer;">
                                            Riwayat Transaksi
                                        </button>
                                        <form action="{{ route('admin.laporan.approve', $lp->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Konfirmasi Approval Tutup Kas:\n\nApakah Anda yakin ingin MENYETUJUI (Approve & Vault) penutupan kas harian {{ addslashes($lp->teller->nama_petugas ?? 'Teller') }} sebesar Rp {{ number_format($lp->saldo_akhir_sistem) }}?');">
                                            @csrf
                                            <button type="submit" class="btn-success" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;">Approve & Vault</button>
                                        </form>
                                        <button type="button" class="btn-danger" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onclick="openRejectModal({{ $lp->id }}, '{{ addslashes($lp->teller->nama_petugas ?? 'Teller') }}', '{{ date('d/m/Y', strtotime($lp->tanggal)) }}', '{{ number_format($lp->selisih) }}')">
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada laporan harian kas teller yang menunggu approval saat ini.</td>
                            </tr>
                            @endforelse
                            <tr id="kasPendingEmptySearch" style="display: none;">
                                <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada laporan kas pending yang sesuai dengan pencarian atau filter tanggal/tahun.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

            <!-- SUB-CONTENT: ARSIP LAPORAN -->
            <div id="sub-arsip-laporan" class="sub-filter-content" style="display: none;">
            <div class="card">
                <h3 style="margin-top:0; color: #16a34a; margin-bottom: 0.5rem;">Arsip Laporan Kas Disetujui (Cetak PDF)</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Laporan penutupan kas yang telah di-ACC. Transaksi akun yang dihapus tetap tersaji lengkap dan tidak terhapus pada PDF.</p>

                <!-- PENCARIAN & FILTER KAS APPROVED -->
                @php
                    $uniqueTahunApproved = $laporanApproved->toBase()->map(fn($la) => date('Y', strtotime($la->tanggal)))->push((string)date('Y'))->unique()->sort()->values();
                @endphp
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" id="searchKasApproved" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Nama Teller, Admin..." oninput="filterKasApprovedTable()">
                    </div>
                    <div style="width: 170px;">
                        <input type="date" id="filterKasApprovedTanggal" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterKasApprovedTable()" title="Filter Tanggal">
                    </div>
                    <div style="width: 140px;">
                        <select id="filterKasApprovedTahun" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterKasApprovedTable()">
                            <option value="">Semua Tahun</option>
                            @foreach($uniqueTahunApproved as $yt)
                                <option value="{{ $yt }}">{{ $yt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Teller</th>
                                <th>Total Setoran</th>
                                <th>Total Penarikan</th>
                                <th>Saldo Akhir</th>
                                <th>Status</th>
                                <th>Admin Approval</th>
                                <th>Dokumen PDF</th>
                            </tr>
                        </thead>
                        <tbody id="tableKasApprovedBody">
                            @forelse($laporanApproved as $la)
                            <tr class="kas-approved-row" data-tanggal="{{ date('Y-m-d', strtotime($la->tanggal)) }}" data-tahun="{{ date('Y', strtotime($la->tanggal)) }}">
                                <td><strong>{{ date('d/m/Y', strtotime($la->tanggal)) }}</strong></td>
                                <td>{{ $la->teller->nama_petugas ?? 'Teller' }}</td>
                                <td><strong style="color: #16a34a;">Rp {{ number_format($la->total_setoran) }}</strong></td>
                                <td><strong style="color: #dc2626;">Rp {{ number_format($la->total_penarikan) }}</strong></td>
                                <td>Rp {{ number_format($la->saldo_akhir_sistem) }}</td>
                                <td><span class="badge badge-aktif">APPROVED</span></td>
                                <td>{{ $la->supervisor->nama_petugas ?? 'Administrator' }}</td>
                                <td>
                                    <a href="{{ route('admin.laporan.pdf', $la->id) }}" class="btn-success" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">
                                        Download PDF
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada arsip laporan yang disetujui.</td>
                            </tr>
                            @endforelse
                            <tr id="kasApprovedEmptySearch" style="display: none;">
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada arsip laporan yang sesuai dengan pencarian atau filter tanggal/tahun.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>

        <!-- TAB 5: LAPORAN BULANAN (FITUR BARU) -->
        <div id="tab-laporan-bulanan" class="tab-content">
            <div class="card" style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <h3 style="margin:0; color: #2563eb;">Laporan Keuangan Bulanan Bank Mini</h3>
                        <p style="margin:0; font-size: 0.85rem; color: var(--text-muted);">Pilih periode bulan dan tahun untuk merekapitulasi mutasi kas dan mengunduh laporan PDF resmi.</p>
                    </div>

                    <!-- Filter Bulan & Tahun Form -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 0.5rem; align-items: center; margin:0;">
                        <input type="hidden" name="tab" value="tab-laporan-bulanan">
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
                    <div class="card" style="background: #f8fafc;">
                        <h4>Total Transaksi</h4>
                        <div class="value" style="font-size: 1.5rem;">{{ count($monthlyTransactions) }} Transaksi</div>
                    </div>
                    <div class="card" style="background: #f0fdf4;">
                        <h4>Total Setoran Masuk</h4>
                        <div class="value" style="font-size: 1.5rem; color: #16a34a;">+ Rp {{ number_format($monthlySetoran, 0, ',', '.') }}</div>
                    </div>
                    <div class="card" style="background: #fef2f2;">
                        <h4>Total Penarikan Keluar</h4>
                        <div class="value" style="font-size: 1.5rem; color: #dc2626;">- Rp {{ number_format($monthlyPenarikan, 0, ',', '.') }}</div>
                    </div>
                    <div class="card" style="background: #eff6ff;">
                        <h4>Net Cash Flow</h4>
                        <div class="value" style="font-size: 1.5rem; color: {{ ($monthlySetoran - $monthlyPenarikan) >= 0 ? '#2563eb' : '#dc2626' }};">
                            Rp {{ number_format($monthlySetoran - $monthlyPenarikan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                    <a href="{{ route('admin.laporan-bulanan.pdf', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="btn-success" style="padding: 0.75rem 1.5rem; font-size: 0.95rem;">
                        Download PDF Laporan Bulanan (Bulan {{ $selectedMonth }}/{{ $selectedYear }})
                    </a>
                </div>

                <h4 style="margin: 1.5rem 0 0.5rem;">Rincian Transaksi Periode {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}</h4>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>No Transaksi</th>
                                <th>Penabung / Rekening</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Teller</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyTransactions as $index => $mt)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($mt->tanggal)) }}</td>
                                <td><code>{{ $mt->no_transaksi }}</code></td>
                                <td>
                                    <strong>{{ $mt->student_name_display }}</strong>
                                    <br><small style="color: var(--text-muted);">Rek: {{ $mt->account_number_display }}</small>
                                    @if($mt->saver && $mt->saver->trashed())
                                        <br><small style="color: #dc2626;">[Akun Dihapus]</small>
                                    @endif
                                </td>
                                <td>
                                    <strong style="text-transform: uppercase; color: {{ $mt->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                        {{ $mt->jenis_trans }}
                                    </strong>
                                </td>
                                <td>Rp {{ number_format($mt->nominal, 0, ',', '.') }}</td>
                                <td>{{ $mt->teller->nama_petugas ?? 'Teller' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada transaksi tercatat pada bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 6: JURNAL AKUNTANSI -->
        <div id="tab-jurnal" class="tab-content">
            <div class="card">
                @php
                    $totalDebit = $jurnals->where('posisi', 'debit')->sum('jumlah');
                    $totalKredit = $jurnals->where('posisi', 'kredit')->sum('jumlah');
                    $isBalanced = ($totalDebit == $totalKredit);
                    $uniqueTahunJurnal = $jurnals->toBase()->map(fn($j) => date('Y', strtotime($j->tanggal)))->push((string)date('Y'))->unique()->sort()->values();
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin: 0; color: #0f172a; font-size: 1.25rem;">Jurnal Umum Akuntansi (Double-Entry Bookkeeping)</h3>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 4px 0 0 0;">
                            Pencatatan berpasangan: Tiap 1 transaksi loket otomatis memicu 2 baris jurnal seimbang (Debit & Kredit).
                        </p>
                    </div>
                    <div>
                        <span style="font-size: 0.85rem; font-weight: 700; padding: 0.4rem 0.85rem; border-radius: 999px; background: {{ $isBalanced ? '#dcfce7' : '#fee2e2' }}; color: {{ $isBalanced ? '#15803d' : '#b91c1c' }}; border: 1px solid {{ $isBalanced ? '#bbf7d0' : '#fca5a5' }};">
                            Status Jurnal: {{ $isBalanced ? '✓ SEIMBANG (BALANCED)' : '⚠️ TIDAK SEIMBANG' }}
                        </span>
                    </div>
                </div>

                <!-- PENCARIAN & FILTER JURNAL AKUNTANSI -->
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" id="searchJurnal" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari No. Transaksi, Keterangan Akun, Kode Ref..." oninput="filterJurnalTable()">
                    </div>
                    <div style="width: 170px;">
                        <input type="date" id="filterJurnalTanggal" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterJurnalTable()" title="Filter Tanggal Spesifik">
                    </div>
                    <div style="width: 140px;">
                        <select id="filterJurnalTahun" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterJurnalTable()">
                            <option value="">Semua Tahun</option>
                            @foreach($uniqueTahunJurnal as $yj)
                                <option value="{{ $yj }}">{{ $yj }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive" style="margin-top: 1rem;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="width: 16%;">Tanggal & Waktu</th>
                                <th style="width: 20%;">No. Transaksi</th>
                                <th style="width: 32%;">Keterangan Akun</th>
                                <th style="width: 8%; text-align: center;">Ref</th>
                                <th style="width: 12%; text-align: right;">Debit (Rp)</th>
                                <th style="width: 12%; text-align: right;">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="tableJurnalBody">
                            @forelse($jurnals as $j)
                            <tr class="jurnal-row" data-tanggal="{{ date('Y-m-d', strtotime($j->tanggal)) }}" data-tahun="{{ date('Y', strtotime($j->tanggal)) }}" style="border-bottom: 1px solid #f1f5f9;">
                                <td style="font-size: 0.85rem; color: #64748b;">{{ $j->tanggal }}</td>
                                <td>
                                    <code style="font-size: 0.85rem; font-weight: 600; color: #2563eb;">{{ $j->transaksi->no_transaksi ?? '-' }}</code>
                                </td>
                                <td>
                                    @if($j->posisi === 'debit')
                                        <strong style="color: #0f172a;">{{ $j->akun->nama_akun ?? 'Kas Teller' }}</strong>
                                    @else
                                        <span style="display: inline-block; padding-left: 20px; color: #475569; font-weight: 600;">
                                            &nbsp;&nbsp;&nbsp;&nbsp;{{ $j->akun->nama_akun ?? 'Simpanan Siswa' }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">{{ $j->kode_akun }}</code>
                                </td>
                                <td style="text-align: right; font-weight: {{ $j->posisi === 'debit' ? '700' : '400' }}; color: {{ $j->posisi === 'debit' ? '#2563eb' : '#94a3b8' }};">
                                    {{ $j->posisi === 'debit' ? number_format($j->jumlah, 0, ',', '.') : '-' }}
                                </td>
                                <td style="text-align: right; font-weight: {{ $j->posisi === 'kredit' ? '700' : '400' }}; color: {{ $j->posisi === 'kredit' ? '#16a34a' : '#94a3b8' }};">
                                    {{ $j->posisi === 'kredit' ? number_format($j->jumlah, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada catatan jurnal akuntansi.</td>
                            </tr>
                            @endforelse
                            <tr id="jurnalEmptySearch" style="display: none;">
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada catatan jurnal yang sesuai dengan pencarian atau filter tanggal/tahun.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8fafc; font-weight: 800; border-top: 2px solid #cbd5e1;">
                                <td colspan="4" style="text-align: right; padding: 0.75rem 1rem; color: #0f172a;">TOTAL KESEIMBANGAN JURNAL:</td>
                                <td style="text-align: right; padding: 0.75rem 1rem; color: #2563eb; font-size: 1rem;">
                                    Rp {{ number_format($totalDebit, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right; padding: 0.75rem 1rem; color: #16a34a; font-size: 1rem;">
                                    Rp {{ number_format($totalKredit, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 7: AUDIT LOGS -->
        <div id="tab-audit" class="tab-content">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <h3 style="margin:0;">Audit Trail Logs Aktivitas</h3>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Total: <strong>{{ count($auditLogs) }}</strong> aktivitas</span>
                </div>

                <!-- PENCARIAN & FILTER AUDIT LOGS -->
                @php
                    $uniqueTahunAudit = $auditLogs->toBase()->map(fn($l) => date('Y', strtotime($l->created_at)))->push((string)date('Y'))->unique()->sort()->values();
                @endphp
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" id="searchAudit" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Pengguna, Aktivitas, Detail Log, IP..." oninput="filterAuditTable()">
                    </div>
                    <div style="width: 170px;">
                        <input type="date" id="filterAuditTanggal" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterAuditTable()" title="Filter Tanggal Spesifik">
                    </div>
                    <div style="width: 140px;">
                        <select id="filterAuditTahun" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterAuditTable()">
                            <option value="">Semua Tahun</option>
                            @foreach($uniqueTahunAudit as $ya)
                                <option value="{{ $ya }}">{{ $ya }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pengguna</th>
                                <th>Aktivitas</th>
                                <th>Detail</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody id="tableAuditBody">
                            @forelse($auditLogs as $log)
                            <tr class="audit-row" data-tanggal="{{ date('Y-m-d', strtotime($log->created_at)) }}" data-tahun="{{ date('Y', strtotime($log->created_at)) }}">
                                <td>{{ $log->created_at }}</td>
                                <td><strong>{{ $log->user->nama_petugas ?? 'System' }}</strong></td>
                                <td><span class="badge badge-role-teller">{{ $log->aktivitas }}</span></td>
                                <td>{{ $log->detail }}</td>
                                <td><code>{{ $log->ip_address }}</code></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada catatan aktivitas sistem.</td>
                            </tr>
                            @endforelse
                            <tr id="auditEmptySearch" style="display: none;">
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada aktivitas log yang sesuai dengan pencarian pengguna atau filter tanggal/tahun.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 8: PENGADUAN NASABAH -->
        <div id="tab-pengaduan" class="tab-content">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                    <div>
                        <h3 style="margin:0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">Pusat Layanan & Pengaduan Nasabah</h3>
                        <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: var(--text-muted);">
                            Daftar pertanyaan, kendala transaksi, dan masukan yang diajukan oleh siswa penabung.
                        </p>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <button type="button" id="btnPengaduanAll" onclick="filterPengaduanStatus('semua')" class="badge badge-role-teller" style="cursor: pointer; border: none; padding: 0.4rem 0.75rem; font-size: 0.78rem;">
                            Semua ({{ isset($pengaduanList) ? $pengaduanList->count() : 0 }})
                        </button>
                        <button type="button" id="btnPengaduanMenunggu" onclick="filterPengaduanStatus('menunggu')" class="badge badge-tidak-aktif" style="cursor: pointer; border: none; padding: 0.4rem 0.75rem; font-size: 0.78rem;">
                            Menunggu ({{ isset($pengaduanList) ? $pengaduanList->where('status', 'menunggu')->count() : 0 }})
                        </button>
                        <button type="button" id="btnPengaduanDiproses" onclick="filterPengaduanStatus('diproses')" class="badge badge-tidak-aktif" style="cursor: pointer; border: none; padding: 0.4rem 0.75rem; font-size: 0.78rem;">
                            Diproses ({{ isset($pengaduanList) ? $pengaduanList->where('status', 'diproses')->count() : 0 }})
                        </button>
                        <button type="button" id="btnPengaduanSelesai" onclick="filterPengaduanStatus('selesai')" class="badge badge-tidak-aktif" style="cursor: pointer; border: none; padding: 0.4rem 0.75rem; font-size: 0.78rem;">
                            Selesai ({{ isset($pengaduanList) ? $pengaduanList->where('status', 'selesai')->count() : 0 }})
                        </button>
                    </div>
                </div>

                <!-- PENCARIAN PENGADUAN -->
                <div style="margin-bottom: 1rem;">
                    <input type="text" id="searchPengaduan" class="form-control" placeholder="Cari berdasarkan nama siswa, NIS, atau subjek pengaduan..." oninput="filterPengaduanTable()">
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu Pengaduan</th>
                                <th>Siswa Pengadu</th>
                                <th>NIS</th>
                                <th>Subjek</th>
                                <th>Pesan Siswa</th>
                                <th>Status</th>
                                <th>Tanggapan Admin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tablePengaduanBody">
                            @forelse($pengaduanList ?? [] as $index => $p)
                            <tr class="pengaduan-row" data-status="{{ $p->status }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($p->created_at)) }} WIB</td>
                                <td><strong>{{ $p->nama_pengadu }}</strong></td>
                                <td><code>{{ $p->nis }}</code></td>
                                <td><strong>{{ $p->judul ?? $p->subjek }}</strong></td>
                                <td style="max-width: 280px; white-space: normal; line-height: 1.4; font-size: 0.85rem;">
                                    {{ $p->isi_pesan ?? $p->pesan }}
                                </td>
                                <td>
                                    @if($p->status === 'selesai')
                                        <span class="badge badge-aktif">SELESAI</span>
                                    @elseif($p->status === 'diproses')
                                        <span class="badge badge-role-teller">DIPROSES</span>
                                    @else
                                        <span class="badge badge-tidak-aktif">MENUNGGU</span>
                                    @endif
                                </td>
                                <td style="max-width: 250px; white-space: normal; line-height: 1.4; font-size: 0.85rem; color: #475569;">
                                    {{ $p->tanggapan_admin ?: 'Belum ditanggapi' }}
                                </td>
                                <td>
                                    <button type="button" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.82rem; border-radius: 6px;" onclick="openTanggapiModal({{ $p->id }}, '{{ addslashes($p->nama_pengadu) }}', '{{ $p->nis }}', '{{ addslashes($p->judul ?? $p->subjek) }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $p->isi_pesan ?? $p->pesan)) }}', '{{ $p->status }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $p->tanggapan_admin ?? '')) }}')">
                                        Tanggapi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                    Belum ada pengaduan dari siswa nasabah.
                                </td>
                            </tr>
                            @endforelse
                            <tr id="pengaduanEmptySearch" style="display: none;">
                                <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                    Tidak ada pengaduan yang sesuai dengan filter atau kata kunci.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 10: PROFIL PETUGAS ADMINISTRATOR       -->
        <!-- ========================================== -->
        <div id="tab-profil" class="tab-content">
            <!-- HEADER PROFIL & TOMBOL KEMBALI -->
            <div class="profile-tab-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem; background: #ffffff; padding: 1.25rem 1.5rem; border-radius: 16px; border: 1px solid var(--border-light); box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);">
                <div>
                    <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0;">Pengaturan Profil & Akun</h2>
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
                <!-- KARTU DATA IDENTITAS PETUGAS -->
                <div class="card" style="background: #ffffff; border-radius: 16px; padding: 1.75rem; border: 1px solid var(--border-light); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-soft); padding-bottom: 0.75rem;">
                        <div>
                            <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Profil Administrator</h3>
                            <p style="margin: 3px 0 0; font-size: 0.8rem; color: var(--text-muted);">Informasi akun dan identitas resmi petugas</p>
                        </div>
                        <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 800; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 999px;">
                            {{ strtoupper($user->role) }}
                        </span>
                    </div>

                    <form action="{{ route('profil.update') }}" method="POST" onsubmit="try{ sessionStorage.setItem('admin_active_tab', 'tab-profil'); }catch(e){} return confirm('Konfirmasi: Simpan perubahan data profil Anda?');">
                        @csrf
                        <div class="form-group">
                            <label>Nama Lengkap Petugas <span style="color: #dc2626;">*</span></label>
                            <input type="text" name="nama_petugas" class="form-control" required value="{{ old('nama_petugas', $user->nama_petugas) }}" placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-group">
                            <label>Username Login <span style="color: #dc2626;">*</span></label>
                            <input type="text" name="username" class="form-control" required minlength="3" maxlength="50" value="{{ old('username', $user->username) }}" placeholder="Masukkan username login" autocomplete="username">
                            <small style="color: #64748b; font-size: 0.75rem;">Username digunakan untuk login ke sistem.</small>
                        </div>

                        <div class="form-group">
                            <label>Alamat Email / Gmail Petugas <span style="color: #dc2626;">*</span></label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}" placeholder="Contoh: admin@bankmini.sch.id">
                            <small style="color: #2563eb; font-size: 0.75rem; font-weight: 600;">Penting: Email ini digunakan untuk verifikasi kode OTP saat lupa kata sandi di halaman login.</small>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem 1.25rem; font-weight: 700; border-radius: 12px;">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KARTU GANTI KATA SANDI MANDIRI -->
                <div class="card" style="background: #ffffff; border-radius: 16px; padding: 1.75rem; border: 1px solid var(--border-light); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);">
                    <div style="margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-soft); padding-bottom: 0.75rem;">
                        <h3 style="margin:0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Ganti Kata Sandi</h3>
                        <p style="margin: 3px 0 0; font-size: 0.8rem; color: var(--text-muted);">Perbarui kata sandi akun login Anda secara berkala</p>
                    </div>

                    <form action="{{ route('profil.ganti-password') }}" method="POST" onsubmit="try{ sessionStorage.setItem('admin_active_tab', 'tab-profil'); }catch(e){} return confirm('Konfirmasi: Apakah Anda yakin ingin memperbarui kata sandi akun ini?');">
                        @csrf
                        <div class="form-group">
                            <label>Kata Sandi Saat Ini <span style="color: #dc2626;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_lama" id="adminOldPassword" class="form-control" required placeholder="Masukkan kata sandi lama" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('adminOldPassword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Kata Sandi Baru (Min. 6 Karakter) <span style="color: #dc2626;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_baru" id="adminNewPassword" class="form-control" required minlength="6" placeholder="Masukkan kata sandi baru" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('adminNewPassword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Kata Sandi Baru <span style="color: #dc2626;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_baru_confirmation" id="adminNewPasswordConfirm" class="form-control" required minlength="6" placeholder="Ulangi kata sandi baru" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('adminNewPasswordConfirm', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem 1.25rem; font-weight: 700; border-radius: 12px; background: #0f172a; border-color: #0f172a;">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- END .container -->

<!-- MODAL TAMBAH ANGGOTA SISWA BARU -->
    <div class="modal-overlay" id="modalTambahMember">
        <div class="modal-card" style="max-width: 540px; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Tambah Anggota Siswa Baru</h3>
                    <p style="margin: 3px 0 0; font-size: 0.82rem; color: var(--text-muted);">Tabel Anggota digunakan untuk menambah anggota</p>
                </div>
                <button type="button" onclick="closeTambahMemberModal()" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer; font-weight: 700; line-height: 1;">&times;</button>
            </div>

            <form action="{{ route('admin.members.store') }}" method="POST" onsubmit="return confirm('Konfirmasi: Apakah Anda yakin ingin mendaftarkan anggota siswa baru ini?');">
                @csrf
                <div class="form-group">
                    <label>Nama Siswa <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="student_name" class="form-control" required placeholder="Contoh: Budi Pratama">
                </div>
                <div class="form-group">
                    <label>NIS (Nomor Induk Siswa) <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="nis" class="form-control" required placeholder="Contoh: 202611005">
                </div>
                <div class="form-group">
                    <label>Alamat Gmail Siswa <span style="color: #dc2626;">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="Contoh: budi.pratama@gmail.com">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div class="form-group">
                        <label>Jurusan <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="class_grade" class="form-control" required placeholder="Contoh: RPL, AKL, dll">
                    </div>
                    <div class="form-group">
                        <label>Tahun Kelulusan <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="graduation_year" class="form-control" required placeholder="Contoh: 2028">
                    </div>
                </div>
                <div class="form-group">
                    <label>Password Akun Login Siswa <span style="color: #dc2626;">*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="adminMemberModalPassword" class="form-control" required minlength="6" placeholder="Masukkan kata sandi (min. 6 karakter)" style="padding-right: 2.75rem;">
                        <button type="button" onclick="togglePasswordVisibility('adminMemberModalPassword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Password">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label>PIN Keamanan Transaksi Penarikan (Wajib 6-Digit) <span style="color: #dc2626;">*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="pin" id="adminMemberModalPin" class="form-control" required maxlength="6" minlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="Wajib tepat 6 angka (contoh: 123456)" style="padding-right: 2.75rem;">
                        <button type="button" onclick="togglePasswordVisibility('adminMemberModalPin', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeTambahMemberModal()" class="btn-logout" style="background: #94a3b8; flex: 1;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2;">Simpan Data Anggota</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT MEMBER -->
    <div class="modal-overlay" id="editMemberModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Edit Data Anggota Siswa</h3>
                <button type="button" onclick="closeEditMemberModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <form id="editMemberForm" method="POST" onsubmit="return confirm('Konfirmasi: Simpan perubahan data profil anggota siswa ini?');">
                @csrf
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" name="student_name" id="edit_student_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" name="nis" id="edit_nis" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Alamat Gmail Siswa</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required placeholder="Contoh: siswa@gmail.com">
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="class_grade" id="edit_class_grade" class="form-control" required placeholder="Contoh: RPL, AKL, dll">
                </div>
                <div class="form-group">
                    <label>Tahun Kelulusan</label>
                    <input type="text" name="graduation_year" id="edit_graduation_year" class="form-control" required placeholder="Contoh: 2028">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeEditMemberModal()" class="btn-logout" style="background: #94a3b8; flex: 1;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2;">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PIN PENABUNG -->
    <div class="modal-overlay" id="resetPinModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Reset PIN Keamanan Penabung</h3>
                <button type="button" onclick="closeResetPinModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Mereset PIN 6 digit untuk penabung: <strong id="resetPinNama">-</strong> (No Rek: <code id="resetPinNoRek">-</code>)
            </p>

            <form id="resetPinForm" method="POST" onsubmit="return confirm('Konfirmasi Reset PIN:\n\nApakah Anda yakin ingin mengatur ulang PIN keamanan 6-digit untuk nasabah ini?');">
                @csrf
                <div class="form-group">
                    <label>PIN 6-Digit Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="pin" id="adminResetPinInput" class="form-control" required maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN baru" style="padding-right: 2.75rem;">
                        <button type="button" onclick="togglePasswordVisibility('adminResetPinInput', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeResetPinModal()" class="btn-logout" style="background: #94a3b8; flex: 1;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2;">Simpan PIN Baru</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        const adminTabTitles = {
            'tab-dashboard': { title: 'Dashboard Administrator', desc: 'Ringkasan operasional dan statistik Bank Mini' },
            'tab-users': { title: 'Manajemen User & Petugas', desc: 'Kelola akun Admin, Supervisor, dan Teller' },
            'tab-members': { title: 'Data Master Anggota Siswa', desc: 'Basis data siswa dan aktivasi rekening penabung' },
            'tab-savers': { title: 'Data Penabung & Rekening', desc: 'Daftar penabung aktif dan informasi saldo rekening' },
            'tab-supervisor': { title: 'Approval Kas & Tutup Kas Teller', desc: 'Validasi pengajuan modal awal dan tutup buku harian' },
            'tab-laporan-bulanan': { title: 'Laporan Keuangan Bulanan', desc: 'Rekapitulasi transaksi bulanan dan pembagian hasil' },
            'tab-jurnal': { title: 'Buku Jurnal Umum Akuntansi', desc: 'Pencatatan debet & kredit sistem berstandar akuntansi' },
            'tab-audit': { title: 'Audit Trail & Rekam Jejak Sistem', desc: 'Log aktivitas keamanan dan transaksi semua pengguna' },
            'tab-pengaduan': { title: 'Pengaduan & Aspirasi Nasabah', desc: 'Tindak lanjuti pesan dan keluhan dari nasabah siswa' },
            'tab-profil': { title: 'Profil Administrator', desc: 'Kelola data identitas diri dan perbarui kata sandi akun petugas' }
        };

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
            switchTab('tab-profil');
            const target = document.getElementById('tab-profil');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
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

        function switchTab(tabId) {
            if (!tabId) return;
            const targetContent = document.getElementById(tabId);
            if (!targetContent) return;

            document.querySelectorAll('.tab-btn, .sidebar-nav-btn').forEach(btn => {
                btn.classList.remove('active');
                const oc = btn.getAttribute('onclick');
                if (oc && oc.includes("'" + tabId + "'")) {
                    btn.classList.add('active');
                }
            });
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            targetContent.classList.add('active');

            if (adminTabTitles[tabId]) {
                const titleEl = document.getElementById('activeTabTitle');
                const descEl = document.getElementById('activeTabDesc');
                if (titleEl) titleEl.textContent = adminTabTitles[tabId].title;
                if (descEl) descEl.textContent = adminTabTitles[tabId].desc;
            }

            try {
                sessionStorage.setItem('admin_active_tab', tabId);
                localStorage.setItem('admin_active_tab', tabId);
            } catch (e) {}

            try {
                if (window.history && window.history.replaceState && window.location.hash) {
                    window.history.replaceState(null, null, window.location.pathname + window.location.search);
                }
            } catch (e) {}

            // Top navbar active
        }

        function toggleSidebar(forceState) {
            // No-op: Sidebar has been transitioned to sticky top navbar
        }

        function switchSubFilter(subId) {
            if (!subId) return;
            // Sembunyikan semua konten sub-filter
            document.querySelectorAll('.sub-filter-content').forEach(el => {
                el.style.display = 'none';
            });
            // Tampilkan konten yang dipilih
            const target = document.getElementById(subId);
            if (target) target.style.display = 'block';

            // Update status tombol filter
            document.querySelectorAll('.sub-filter-btn').forEach(btn => {
                btn.classList.remove('active');
                const oc = btn.getAttribute('onclick');
                if (oc && oc.includes("'" + subId + "'")) {
                    btn.classList.add('active');
                }
            });

            // Simpan state sub-filter aktif
            try {
                sessionStorage.setItem('admin_active_sub_filter', subId);
            } catch (e) {}
        }

        function restoreAdminActiveTab() {
            try {
                if (@json(session('success_profile') || session('success_password') || session('error_password') || $errors->has('nama_petugas') || $errors->has('username') || $errors->has('email') || $errors->has('sandi_lama') || $errors->has('sandi_baru'))) {
                    switchTab('tab-profil');
                    return;
                }

                const urlParams = new URLSearchParams(window.location.search);
                const queryTab = urlParams.get('tab');
                const sessionTab = sessionStorage.getItem('admin_active_tab');
                const localTab = localStorage.getItem('admin_active_tab');

                const tabToActivate = (queryTab && document.getElementById(queryTab)) ? queryTab
                                    : (sessionTab && document.getElementById(sessionTab)) ? sessionTab
                                    : (localTab && document.getElementById(localTab)) ? localTab
                                    : 'tab-dashboard';

                if (tabToActivate && document.getElementById(tabToActivate)) {
                    switchTab(tabToActivate);
                }

                // Restore sub-filter state
                const savedSubFilter = sessionStorage.getItem('admin_active_sub_filter');
                if (savedSubFilter && document.getElementById(savedSubFilter)) {
                    switchSubFilter(savedSubFilter);
                }
            } catch (e) {}
        }

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
                localStorage.setItem('admin_nav_collapsed', willCollapse ? 'true' : 'false');
            } catch(e) {}
        }

        function restoreNavCollapseState() {
            try {
                if (localStorage.getItem('admin_nav_collapsed') === 'true') {
                    applyNavCollapseState(true);
                }
            } catch(e) {}
        }

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

        // Restore tab & nav collapse segera saat skrip dieksekusi agar bebas flicker
        restoreAdminActiveTab();
        restoreNavCollapseState();

        // Restore tab & nav collapse kembali saat DOM selesai dimuat dan pasang listener submit form
        document.addEventListener('DOMContentLoaded', () => {
            restoreAdminActiveTab();
            restoreNavCollapseState();
            filterMemberTable();

            @if(session('success') || session('error'))
                window.scrollTo({ top: 0, behavior: 'smooth' });
            @endif

            // Pastikan setiap form (tambah, edit, hapus, approve, reject, reset PIN)
            // selalu menyimpan tab aktif sebelum submit ke server
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    const activeContent = document.querySelector('.tab-content.active');
                    if (activeContent && activeContent.id) {
                        try {
                            sessionStorage.setItem('admin_active_tab', activeContent.id);
                            localStorage.setItem('admin_active_tab', activeContent.id);
                        } catch(e) {}
                    }
                    // Save active sub-filter
                    const activeSubFilter = document.querySelector('.sub-filter-content[style*="display: block"], .sub-filter-content[style*="display:block"]');
                    if (activeSubFilter && activeSubFilter.id) {
                        try {
                            sessionStorage.setItem('admin_active_sub_filter', activeSubFilter.id);
                        } catch(e) {}
                    }
                });
            });
        });

        function openTambahMemberModal() {
            const m = document.getElementById('modalTambahMember');
            if (m) m.style.display = 'flex';
        }

        function closeTambahMemberModal() {
            const m = document.getElementById('modalTambahMember');
            if (m) m.style.display = 'none';
        }

        function openEditMemberModal(userId, name, nis, email, grade, gradYear) {
            document.getElementById('editMemberForm').action = '/admin/members/' + userId + '/update';
            document.getElementById('edit_student_name').value = name;
            document.getElementById('edit_nis').value = nis;
            document.getElementById('edit_email').value = email || '';
            document.getElementById('edit_class_grade').value = grade;
            document.getElementById('edit_graduation_year').value = gradYear;
            document.getElementById('editMemberModal').style.display = 'flex';
        }

        function closeEditMemberModal() {
            document.getElementById('editMemberModal').style.display = 'none';
        }

        function openResetPinModal(saverId, name, noRek) {
            document.getElementById('resetPinForm').action = '/admin/savers/' + saverId + '/reset-pin';
            document.getElementById('resetPinNama').innerText = name;
            document.getElementById('resetPinNoRek').innerText = noRek;
            document.getElementById('resetPinModal').style.display = 'flex';
        }

        function closeResetPinModal() {
            document.getElementById('resetPinModal').style.display = 'none';
        }

        function openRiwayatModal(id) {
            const m = document.getElementById('modalRiwayat-' + id);
            if (m) m.style.display = 'flex';
        }

        function closeRiwayatModal(id) {
            const m = document.getElementById('modalRiwayat-' + id);
            if (m) m.style.display = 'none';
        }

        function openRejectModal(id, tellerNama, tanggal, selisih) {
            const form = document.getElementById('rejectLaporanForm');
            if (form) form.action = '/admin/laporan/' + id + '/reject';
            const elTeller = document.getElementById('rejectTellerNama');
            if (elTeller) elTeller.innerText = tellerNama;
            const elTanggal = document.getElementById('rejectTanggal');
            if (elTanggal) elTanggal.innerText = tanggal;
            const elSelisih = document.getElementById('rejectSelisih');
            if (elSelisih) elSelisih.innerText = 'Rp ' + selisih;
            const elInput = document.getElementById('rejectCatatanInput');
            if (elInput) elInput.value = '';
            const modal = document.getElementById('rejectLaporanModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    document.getElementById('rejectCatatanInput')?.focus();
                }, 100);
            }
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectLaporanModal');
            if (modal) modal.style.display = 'none';
        }

        function openRejectPengajuanModal(id, tellerNama, nominal) {
            const form = document.getElementById('rejectPengajuanForm');
            if (form) form.action = '/admin/pengajuan-kas/' + id + '/reject';
            const elTeller = document.getElementById('rejectPengajuanTeller');
            if (elTeller) elTeller.innerText = tellerNama;
            const elNominal = document.getElementById('rejectPengajuanNominal');
            if (elNominal) elNominal.innerText = 'Rp ' + nominal;
            const elInput = document.getElementById('rejectPengajuanCatatanInput');
            if (elInput) elInput.value = '';
            const modal = document.getElementById('rejectPengajuanModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    document.getElementById('rejectPengajuanCatatanInput')?.focus();
                }, 100);
            }
        }

        function closeRejectPengajuanModal() {
            const modal = document.getElementById('rejectPengajuanModal');
            if (modal) modal.style.display = 'none';
            const elInput = document.getElementById('rejectPengajuanCatatanInput');
            if (elInput) elInput.value = '';
        }

        // Live Search & Filter Functions
        function filterUserTable() {
            const query = (document.getElementById('searchUser')?.value || '').toLowerCase().trim();
            const role = (document.getElementById('filterUserRole')?.value || '').toLowerCase();
            const status = (document.getElementById('filterUserStatus')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#tableUserBody tr.user-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rRole = row.getAttribute('data-role') || '';
                const rStatus = row.getAttribute('data-status') || '';

                const matchQuery = !query || text.includes(query);
                const matchRole = !role || rRole === role;
                const matchStatus = !status || rStatus === status;

                if (matchQuery && matchRole && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('userEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        let currentMemberStatusFilter = 'belum_aktif';
        let currentMemberPage = 1;
        const MEMBER_PER_PAGE = 20;

        function setMemberStatusFilter(status) {
            currentMemberStatusFilter = status;
            const btnBelum = document.getElementById('btnFilterBelumAktif');
            const btnSemua = document.getElementById('btnFilterSemua');
            if (btnBelum && btnSemua) {
                if (status === 'belum_aktif') {
                    btnBelum.className = 'badge badge-role-teller';
                    btnSemua.className = 'badge badge-tidak-aktif';
                } else {
                    btnBelum.className = 'badge badge-tidak-aktif';
                    btnSemua.className = 'badge badge-role-teller';
                }
            }
            filterMemberTable(true);
        }

        function filterMemberTable(resetPage = true) {
            if (resetPage) {
                currentMemberPage = 1;
            }
            const query = (document.getElementById('searchMember')?.value || '').toLowerCase().trim();
            const kelas = (document.getElementById('filterMemberKelas')?.value || '').toLowerCase();
            const angkatan = (document.getElementById('filterMemberAngkatan')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#tableMemberBody tr.member-row');
            
            const matchingRows = [];

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rKelas = row.getAttribute('data-kelas') || '';
                const rAngkatan = row.getAttribute('data-angkatan') || '';
                const rStatus = row.getAttribute('data-status') || '';

                const matchStatus = (currentMemberStatusFilter === 'semua') || (rStatus === 'belum_aktif');
                const matchQuery = !query || text.includes(query);
                const matchKelas = !kelas || rKelas === kelas;
                const matchAngkatan = !angkatan || rAngkatan === angkatan;

                if (matchStatus && matchQuery && matchKelas && matchAngkatan) {
                    matchingRows.push(row);
                } else {
                    row.style.display = 'none';
                }
            });

            const totalMatches = matchingRows.length;
            const totalPages = Math.ceil(totalMatches / MEMBER_PER_PAGE) || 1;

            if (currentMemberPage > totalPages) currentMemberPage = totalPages;
            if (currentMemberPage < 1) currentMemberPage = 1;

            const startIndex = (currentMemberPage - 1) * MEMBER_PER_PAGE;
            const endIndex = Math.min(startIndex + MEMBER_PER_PAGE, totalMatches);

            matchingRows.forEach((row, idx) => {
                if (idx >= startIndex && idx < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update UI Pagination Controls
            const pageInfo = document.getElementById('memberPageInfo');
            const pageInput = document.getElementById('memberPageInput');
            const totalPagesEl = document.getElementById('memberTotalPages');
            const btnPrev = document.getElementById('btnMemberPrev');
            const btnNext = document.getElementById('btnMemberNext');

            if (pageInfo) {
                pageInfo.innerText = totalMatches > 0 
                    ? `${startIndex + 1} - ${endIndex} dari ${totalMatches}` 
                    : `0 dari 0`;
            }
            if (pageInput) {
                pageInput.value = currentMemberPage;
                pageInput.max = totalPages;
            }
            if (totalPagesEl) {
                totalPagesEl.innerText = totalPages;
            }
            if (btnPrev) {
                btnPrev.disabled = (currentMemberPage <= 1);
                btnPrev.style.opacity = (currentMemberPage <= 1) ? '0.4' : '1';
                btnPrev.style.cursor = (currentMemberPage <= 1) ? 'not-allowed' : 'pointer';
            }
            if (btnNext) {
                btnNext.disabled = (currentMemberPage >= totalPages);
                btnNext.style.opacity = (currentMemberPage >= totalPages) ? '0.4' : '1';
                btnNext.style.cursor = (currentMemberPage >= totalPages) ? 'not-allowed' : 'pointer';
            }

            const emptyRow = document.getElementById('memberEmptySearch');
            const emptyText = document.getElementById('memberEmptyText');
            if (emptyRow) {
                emptyRow.style.display = (totalMatches === 0) ? '' : 'none';
                if (emptyText) {
                    if (currentMemberStatusFilter === 'belum_aktif' && !query && !kelas && !angkatan) {
                        emptyText.innerHTML = 'Tidak ada anggota siswa yang belum aktif. Seluruh siswa yang telah menabung otomatis berpindah ke tab <strong>Manajemen User</strong> & <strong>Data Penabung</strong>.';
                    } else {
                        emptyText.innerText = 'Tidak ada data siswa yang sesuai dengan filter atau pencarian.';
                    }
                }
            }
        }

        function prevMemberPage() {
            if (currentMemberPage > 1) {
                currentMemberPage--;
                filterMemberTable(false);
            }
        }

        function nextMemberPage() {
            currentMemberPage++;
            filterMemberTable(false);
        }

        function jumpMemberPage(val) {
            const pageNum = parseInt(val, 10);
            if (!isNaN(pageNum)) {
                currentMemberPage = pageNum;
                filterMemberTable(false);
            }
        }

        let currentPengaduanFilter = 'semua';

        function filterPengaduanStatus(status) {
            currentPengaduanFilter = status;
            const btns = {
                'semua': document.getElementById('btnPengaduanAll'),
                'menunggu': document.getElementById('btnPengaduanMenunggu'),
                'diproses': document.getElementById('btnPengaduanDiproses'),
                'selesai': document.getElementById('btnPengaduanSelesai')
            };

            for (const key in btns) {
                if (btns[key]) {
                    btns[key].className = (key === status) ? 'badge badge-role-teller' : 'badge badge-tidak-aktif';
                }
            }
            filterPengaduanTable();
        }

        function filterPengaduanTable() {
            const query = (document.getElementById('searchPengaduan')?.value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('#tablePengaduanBody tr.pengaduan-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rStatus = row.getAttribute('data-status') || '';

                const matchStatus = (currentPengaduanFilter === 'semua') || (rStatus === currentPengaduanFilter);
                const matchQuery = !query || text.includes(query);

                if (matchStatus && matchQuery) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('pengaduanEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        function openTanggapiModal(id, nama, nis, subjek, pesan, status, tanggapan) {
            document.getElementById('formTanggapiPengaduan').action = '/admin/pengaduan/' + id + '/tanggapi';
            document.getElementById('modalPengaduNama').innerText = nama;
            document.getElementById('modalPengaduNis').innerText = nis;
            document.getElementById('modalPengaduSubjek').innerText = subjek;
            document.getElementById('modalPengaduPesan').innerText = pesan;
            document.getElementById('modalStatusPengaduan').value = (status === 'menunggu') ? 'diproses' : status;
            document.getElementById('modalTanggapanAdmin').value = tanggapan || '';
            const modal = document.getElementById('modalTanggapiPengaduan');
            modal.style.display = 'flex';
        }

        function closeTanggapiModal() {
            const modal = document.getElementById('modalTanggapiPengaduan');
            if (modal) modal.style.display = 'none';
        }

        function filterSaverTable() {
            const query = (document.getElementById('searchSaver')?.value || '').toLowerCase().trim();
            const kelas = (document.getElementById('filterSaverKelas')?.value || '').toLowerCase();
            const angkatan = (document.getElementById('filterSaverAngkatan')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#tableSaverBody tr.saver-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rKelas = row.getAttribute('data-kelas') || '';
                const rAngkatan = row.getAttribute('data-angkatan') || '';

                const matchQuery = !query || text.includes(query);
                const matchKelas = !kelas || rKelas === kelas;
                const matchAngkatan = !angkatan || rAngkatan === angkatan;

                if (matchQuery && matchKelas && matchAngkatan) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('saverEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        function filterKasPendingTable() {
            const query = (document.getElementById('searchKasPending')?.value || '').toLowerCase().trim();
            const tgl = (document.getElementById('filterKasPendingTanggal')?.value || '').trim();
            const thn = (document.getElementById('filterKasPendingTahun')?.value || '').trim();
            const rows = document.querySelectorAll('#tableKasPendingBody tr.kas-pending-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rTgl = row.getAttribute('data-tanggal') || '';
                const rThn = row.getAttribute('data-tahun') || '';

                const matchQuery = !query || text.includes(query);
                const matchTgl = !tgl || rTgl === tgl;
                const matchThn = !thn || rThn === thn;

                if (matchQuery && matchTgl && matchThn) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('kasPendingEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        function filterKasApprovedTable() {
            const query = (document.getElementById('searchKasApproved')?.value || '').toLowerCase().trim();
            const tgl = (document.getElementById('filterKasApprovedTanggal')?.value || '').trim();
            const thn = (document.getElementById('filterKasApprovedTahun')?.value || '').trim();
            const rows = document.querySelectorAll('#tableKasApprovedBody tr.kas-approved-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rTgl = row.getAttribute('data-tanggal') || '';
                const rThn = row.getAttribute('data-tahun') || '';

                const matchQuery = !query || text.includes(query);
                const matchTgl = !tgl || rTgl === tgl;
                const matchThn = !thn || rThn === thn;

                if (matchQuery && matchTgl && matchThn) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('kasApprovedEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        function filterJurnalTable() {
            const query = (document.getElementById('searchJurnal')?.value || '').toLowerCase().trim();
            const tgl = (document.getElementById('filterJurnalTanggal')?.value || '').trim();
            const thn = (document.getElementById('filterJurnalTahun')?.value || '').trim();
            const rows = document.querySelectorAll('#tableJurnalBody tr.jurnal-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rTgl = row.getAttribute('data-tanggal') || '';
                const rThn = row.getAttribute('data-tahun') || '';

                const matchQuery = !query || text.includes(query);
                const matchTgl = !tgl || rTgl === tgl;
                const matchThn = !thn || rThn === thn;

                if (matchQuery && matchTgl && matchThn) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('jurnalEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        function filterAuditTable() {
            const query = (document.getElementById('searchAudit')?.value || '').toLowerCase().trim();
            const tgl = (document.getElementById('filterAuditTanggal')?.value || '').trim();
            const thn = (document.getElementById('filterAuditTahun')?.value || '').trim();
            const rows = document.querySelectorAll('#tableAuditBody tr.audit-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rTgl = row.getAttribute('data-tanggal') || '';
                const rThn = row.getAttribute('data-tahun') || '';

                const matchQuery = !query || text.includes(query);
                const matchTgl = !tgl || rTgl === tgl;
                const matchThn = !thn || rThn === thn;

                if (matchQuery && matchTgl && matchThn) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('auditEmptySearch');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }
    
        function handleGlobalSearch(val) {
            const activeTab = document.querySelector('.tab-content.active');
            if (!activeTab) return;
            if (activeTab.id === 'tab-users') {
                const input = document.getElementById('searchUser');
                if (input) { input.value = val; filterUserTable(); }
            } else if (activeTab.id === 'tab-members') {
                const input = document.getElementById('searchMember');
                if (input) { input.value = val; filterMemberTable(true); }
            } else if (activeTab.id === 'tab-savers') {
                const input = document.getElementById('searchSaver');
                if (input) { input.value = val; filterSaverTable(); }
            } else if (activeTab.id === 'tab-pengaduan') {
                const input = document.getElementById('searchPengaduan');
                if (input) { input.value = val; filterPengaduanTable(); }
            } else if (activeTab.id === 'tab-jurnal') {
                const input = document.getElementById('searchJurnal');
                if (input) { input.value = val; filterJurnalTable(); }
            } else if (activeTab.id === 'tab-audit') {
                const input = document.getElementById('searchAudit');
                if (input) { input.value = val; filterAuditTable(); }
            }
        }

    </script>

    <!-- MODAL POPUP RIWAYAT TRANSAKSI TELLER SEBELUM APPROVE -->
    @foreach($laporanPending as $lp)
    <div class="modal-overlay" id="modalRiwayat-{{ $lp->id }}">
        <div class="modal-card" style="max-width: 880px; width: 95%; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Riwayat Mutasi Transaksi Kas Teller</h3>
                    <p style="margin: 4px 0 0; font-size: 0.85rem; color: #64748b;">
                        Teller: <strong>{{ $lp->teller->nama_petugas ?? 'Teller' }}</strong> | Tanggal: <strong>{{ date('d F Y', strtotime($lp->tanggal)) }}</strong>
                    </p>
                </div>
                <button type="button" onclick="closeRiwayatModal({{ $lp->id }})" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <!-- Summary Cards inside Modal -->
            @php
                $modalActualSetoran = $lp->transaksis->where('jenis_trans', 'setoran')->sum('nominal');
                $modalActualPenarikan = $lp->transaksis->where('jenis_trans', 'penarikan')->sum('nominal');
                $modalDisplaySetoran = $modalActualSetoran > 0 ? $modalActualSetoran : (int)$lp->total_setoran;
                $modalDisplayPenarikan = $modalActualPenarikan > 0 ? $modalActualPenarikan : (int)$lp->total_penarikan;
            @endphp
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem 1rem; border-radius: 12px;">
                    <div style="font-size: 0.75rem; color: #166534; font-weight: 700; text-transform: uppercase;">Total Setoran</div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: #16a34a; margin-top: 2px;">+ Rp {{ number_format($modalDisplaySetoran) }}</div>
                </div>
                <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 0.75rem 1rem; border-radius: 12px;">
                    <div style="font-size: 0.75rem; color: #991b1b; font-weight: 700; text-transform: uppercase;">Total Penarikan</div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: #dc2626; margin-top: 2px;">- Rp {{ number_format($modalDisplayPenarikan) }}</div>
                </div>
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.75rem 1rem; border-radius: 12px;">
                    <div style="font-size: 0.75rem; color: #1e40af; font-weight: 700; text-transform: uppercase;">Uang Fisik Kas Laci</div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: #2563eb; margin-top: 2px;">Rp {{ number_format($lp->uang_fisik) }}</div>
                </div>
                <div style="background: {{ $lp->selisih == 0 ? '#f0fdf4' : '#fff1f2' }}; border: 1px solid {{ $lp->selisih == 0 ? '#bbf7d0' : '#fecdd3' }}; padding: 0.75rem 1rem; border-radius: 12px;">
                    <div style="font-size: 0.75rem; color: {{ $lp->selisih == 0 ? '#166534' : '#9f1239' }}; font-weight: 700; text-transform: uppercase;">Selisih Kas</div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: {{ $lp->selisih == 0 ? '#16a34a' : '#e11d48' }}; margin-top: 2px;">Rp {{ number_format($lp->selisih) }}</div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f1f5f9;">
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 5%;">No</th>
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 12%;">Waktu</th>
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 22%;">No. Transaksi</th>
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 25%;">Nama Penabung</th>
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 12%;">Jenis</th>
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 14%; text-align: right;">Nominal</th>
                            <th style="padding: 8px 10px; font-size: 0.85rem; width: 10%;">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lp->transaksis as $idx => $trx)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 8px 10px; font-size: 0.85rem; text-align: center;">{{ $idx + 1 }}</td>
                            <td style="padding: 8px 10px; font-size: 0.85rem;">{{ date('H:i:s', strtotime($trx->tanggal)) }}</td>
                            <td style="padding: 8px 10px;"><code style="font-size: 0.85rem;">{{ $trx->no_transaksi }}</code></td>
                            <td style="padding: 8px 10px; font-size: 0.85rem;">
                                <strong>{{ $trx->student_name_display }}</strong>
                                <br><small style="color: #64748b;">Rek: {{ $trx->account_number_display }}</small>
                            </td>
                            <td style="padding: 8px 10px;">
                                <strong style="text-transform: uppercase; font-size: 0.85rem; color: {{ $trx->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                    {{ $trx->jenis_trans }}
                                </strong>
                            </td>
                            <td style="padding: 8px 10px; text-align: right; font-weight: 700; color: {{ $trx->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }}; font-size: 0.9rem;">
                                Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td style="padding: 8px 10px; font-size: 0.8rem; color: #64748b;">{{ $trx->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 1.5rem; color: #64748b;">Tidak ada data transaksi teller pada tanggal ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.25rem; border-top: 1px solid #e2e8f0; padding-top: 0.75rem;">
                <button type="button" onclick="closeRiwayatModal({{ $lp->id }})" class="btn-logout" style="background: #94a3b8;">Tutup</button>
            </div>
        </div>
    </div>
    @endforeach

    <!-- MODAL PENOLAKAN LAPORAN KAS DENGAN INPUT TEKS ALASAN -->
    <div class="modal-overlay" id="rejectLaporanModal">
        <div class="modal-card" style="max-width: 440px; width: 95%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #dc2626; margin:0;">Tolak Laporan</h3>
                <button type="button" onclick="closeRejectModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <div style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem; line-height: 1.5;">
                <span id="rejectTellerNama">-</span> &bull; Tanggal: <strong id="rejectTanggal">-</strong> &bull; Selisih: <strong id="rejectSelisih" style="color: #dc2626;">-</strong>
            </div>

            <form id="rejectLaporanForm" method="POST" onsubmit="return confirm('Konfirmasi Penolakan:\n\nApakah Anda yakin ingin MENOLAK laporan penutupan kas harian ini?');">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="font-weight: 600; font-size: 0.85rem;">Alasan Penolakan <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="catatan_penolakan" id="rejectCatatanInput" class="form-control" required placeholder="Tulis alasan...">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                    <button type="button" onclick="closeRejectModal()" class="btn-logout" style="background: #94a3b8; flex: 1;">Batal</button>
                    <button type="submit" class="btn-danger" style="flex: 1.5;">Tolak Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PENOLAKAN PENGAJUAN TAMBAHAN KAS -->
    <div class="modal-overlay" id="rejectPengajuanModal">
        <div class="modal-card" style="max-width: 440px; width: 95%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #dc2626; margin:0;">Tolak Pengajuan Kas</h3>
                <button type="button" onclick="closeRejectPengajuanModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <div style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem; line-height: 1.5;">
                Teller: <strong id="rejectPengajuanTeller">-</strong> &bull; Nominal: <strong id="rejectPengajuanNominal" style="color: #dc2626;">-</strong>
            </div>

            <form id="rejectPengajuanForm" method="POST" onsubmit="return confirm('Konfirmasi Penolakan:\n\nApakah Anda yakin ingin MENOLAK pengajuan tambahan modal kas ini?');">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="font-weight: 600; font-size: 0.85rem;">Alasan Penolakan <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="catatan_admin" id="rejectPengajuanCatatanInput" class="form-control" required placeholder="Contoh: Saldo kas utama belum mencukupi / salah input...">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                    <button type="button" onclick="closeRejectPengajuanModal()" class="btn-logout" style="background: #94a3b8; flex: 1;">Batal</button>
                    <button type="submit" class="btn-danger" style="flex: 1.5;">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TANGGAPI PENGADUAN SISWA -->
    <div class="modal-overlay" id="modalTanggapiPengaduan">
        <div class="modal-card" style="max-width: 520px; width: 95%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Tanggapi Pengaduan Siswa</h3>
                <button type="button" onclick="closeTanggapiModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <form id="formTanggapiPengaduan" method="POST" action="" onsubmit="return confirm('Konfirmasi: Kirimkan tanggapan resmi ini ke nasabah?');">
                @csrf
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.85rem; margin-bottom: 1.25rem; font-size: 0.85rem;">
                    <div>Pengadu: <strong id="modalPengaduNama">-</strong> (<span id="modalPengaduNis">-</span>)</div>
                    <div style="margin-top: 4px;">Subjek: <strong id="modalPengaduSubjek">-</strong></div>
                    <div style="margin-top: 6px; padding-top: 6px; border-top: 1px dashed #cbd5e1; color: #334155; line-height: 1.4;" id="modalPengaduPesan">-</div>
                </div>

                <div class="form-group">
                    <label>Tanggapan / Solusi Admin <span style="color: #dc2626;">*</span></label>
                    <textarea name="tanggapan_admin" id="modalTanggapanAdmin" rows="4" class="form-control" required placeholder="Tuliskan balasan resmi untuk siswa..." style="resize: vertical;"></textarea>
                </div>

                <div class="form-group">
                    <label>Status Pengaduan <span style="color: #dc2626;">*</span></label>
                    <select name="status" id="modalStatusPengaduan" class="form-control" required>
                        <option value="diproses">Sedang Diproses</option>
                        <option value="selesai">Selesai / Terjawab</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeTanggapiModal()" class="btn-logout" style="background: #94a3b8; flex: 1;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2;">Kirim Tanggapan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
