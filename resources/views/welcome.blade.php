<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | E-Teller Bank Mini Sekolah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_bank_mini.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Design System CSS -->
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @endif

    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --primary-blue: #4361ee;
            --primary-hover: #3a56d4;
            --primary-dark: #2b45bd;
            --accent-cyan: #38bdf8;
            --bg-page: #edf2f9;
        }

        *, *::before, *::after {
            box-sizing: border-box !important;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef2fa;
            position: relative;
            padding: 1.5rem 1rem;
            margin: 0;
            overflow-x: hidden;
        }

        /* Abstract Soft Circles in Background */
        .bg-deco-circle-1 {
            position: fixed;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.09) 0%, rgba(238, 242, 250, 0) 70%);
            top: -120px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
        }

        .bg-deco-circle-2 {
            position: fixed;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, rgba(238, 242, 250, 0) 70%);
            bottom: -100px;
            left: -80px;
            z-index: 0;
            pointer-events: none;
        }

        /* Main Split Card Layout */
        .login-split-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 960px;
            min-height: 580px;
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 25px 60px -12px rgba(43, 69, 189, 0.18), 0 10px 24px -6px rgba(15, 23, 42, 0.06);
            display: flex;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Left Side: Modern Blue Banner */
        .split-left-panel {
            flex: 1.05;
            background: linear-gradient(145deg, #4866f7 0%, #3e5bf2 40%, #2f4fe6 100%);
            padding: 3.5rem 3rem;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Pure CSS Geometric Ornaments (NO ICONS) */
        .geo-glow-top {
            position: absolute;
            top: -60px;
            left: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            filter: blur(20px);
            pointer-events: none;
        }

        .geo-pill-1 {
            position: absolute;
            top: 28px;
            left: 120px;
            width: 32px;
            height: 110px;
            background: linear-gradient(180deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.02) 100%);
            border-radius: 20px;
            transform: rotate(-10deg);
            pointer-events: none;
        }

        .geo-pill-2 {
            position: absolute;
            top: 40px;
            left: 165px;
            width: 22px;
            height: 85px;
            background: linear-gradient(180deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.02) 100%);
            border-radius: 16px;
            transform: rotate(-10deg);
            pointer-events: none;
        }

        .geo-dots-grid {
            position: absolute;
            top: 40px;
            left: 36px;
            width: 60px;
            height: 60px;
            background-image: radial-gradient(rgba(255, 255, 255, 0.5) 2px, transparent 2px);
            background-size: 12px 12px;
            pointer-events: none;
        }

        .geo-dots-bottom {
            position: absolute;
            bottom: 40px;
            left: 36px;
            width: 70px;
            height: 70px;
            background-image: radial-gradient(rgba(255, 255, 255, 0.45) 2px, transparent 2px);
            background-size: 14px 14px;
            pointer-events: none;
        }

        .geo-orbit-ring {
            position: absolute;
            bottom: -80px;
            right: 40px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.4);
            border-bottom-color: transparent;
            pointer-events: none;
        }

        .geo-sphere-main {
            position: absolute;
            bottom: -90px;
            right: 55px;
            width: 230px;
            height: 230px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8 0%, #2563eb 80%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            pointer-events: none;
        }

        .geo-sphere-small {
            position: absolute;
            bottom: 85px;
            right: 30px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7dd3fc 0%, #38bdf8 100%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.18);
            pointer-events: none;
        }

        .geo-sphere-tiny {
            position: absolute;
            bottom: 120px;
            left: 42px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #bae6fd 0%, #38bdf8 100%);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            pointer-events: none;
        }

        /* Banner Text Typography */
        .left-content {
            position: relative;
            z-index: 2;
            margin: auto 0;
            padding-right: 1.5rem;
        }

        .left-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 999px;
            padding: 0.35rem 1rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
            backdrop-filter: blur(4px);
        }

        .left-headline {
            font-size: 2.35rem;
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 1.25rem;
            text-shadow: 0 2px 10px rgba(15, 23, 42, 0.1);
        }

        .left-description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            max-width: 360px;
        }

        .left-footer {
            position: relative;
            z-index: 2;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        /* Right Side: Clean White Form Panel */
        .split-right-panel {
            flex: 1.15;
            background: #ffffff;
            padding: 3.25rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }



        .right-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .right-header h2 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .right-header p {
            font-size: 0.88rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.45rem;
            letter-spacing: 0.01em;
        }

        .form-input-wrapper {
            position: relative;
            width: 100%;
        }

        .form-control-custom {
            width: 100%;
            height: 48px;
            padding: 0 1rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #0f172a;
            font-family: inherit;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.12);
            background: #ffffff;
        }

        .form-control-custom::placeholder {
            color: #94a3b8;
            font-size: 0.88rem;
        }

        /* Text Toggle Password (NO ICONS) */
        .btn-text-toggle-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            padding: 4px 10px;
            border-radius: 6px;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .btn-text-toggle-pwd:hover {
            color: #1e293b;
            background: #e2e8f0;
        }

        /* Row: Remember Me & Reset Password */
        .form-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.4rem;
            margin-bottom: 1.5rem;
            font-size: 0.825rem;
        }

        .remember-checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: #4361ee;
            cursor: pointer;
        }

        .link-reset-password {
            background: none;
            border: none;
            color: #4361ee;
            font-weight: 700;
            font-size: 0.825rem;
            cursor: pointer;
            font-family: inherit;
            padding: 0;
            transition: color 0.15s;
        }

        .link-reset-password:hover {
            color: #2b45bd;
            text-decoration: underline;
        }

        /* Main Blue Submit Button */
        .btn-main-login {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #4866f7 0%, #3a56d4 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 6px 18px rgba(58, 86, 212, 0.28);
            font-family: inherit;
        }

        .btn-main-login:hover {
            background: linear-gradient(135deg, #3a56d4 0%, #2b45bd 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(58, 86, 212, 0.38);
        }

        .btn-main-login:active {
            transform: translateY(0);
        }

        /* Notice / Info text */
        .login-info-box {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }

        .btn-download-app-link {
            display: inline-block;
            width: 100%;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 0.75rem 1.25rem;
            border-radius: 14px;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.06);
            letter-spacing: 0.2px;
        }

        .btn-download-app-link:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }

        .login-info-box p {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
            font-weight: 500;
        }

        .login-info-box .highlight {
            color: #4361ee;
            font-weight: 700;
        }

        /* Alert Notifications */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.825rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            text-align: center;
            line-height: 1.45;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.825rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            text-align: center;
            line-height: 1.45;
        }

        /* Modal Reset Password via OTP Gmail (TANPA MENGGUNAKAN ICON) */
        .modal-reset-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-reset-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 450px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2.25rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .btn-text-close {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.35rem 0.85rem;
            font-size: 0.825rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-text-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .modal-instruction {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }

        .alert-box {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.825rem;
            line-height: 1.45;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .alert-box.alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-box.alert-success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #15803d;
        }

        .btn-action-primary {
            width: 100%;
            height: 46px;
            border-radius: 12px;
            border: none;
            background: #4361ee;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
            font-family: inherit;
        }

        .btn-action-primary:hover:not(:disabled) {
            background: #3a56d4;
            transform: translateY(-1px);
        }

        .btn-action-primary:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-action-success {
            width: 100%;
            height: 46px;
            border-radius: 12px;
            border: none;
            background: #059669;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            font-family: inherit;
        }

        .btn-action-success:hover:not(:disabled) {
            background: #047857;
            transform: translateY(-1px);
        }

        .btn-action-success:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .account-info-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .account-info-card .info-label {
            font-size: 0.725rem;
            font-weight: 700;
            color: #166534;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .account-info-card .info-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: #14532d;
            margin-top: 0.25rem;
        }

        .account-info-card .info-sub {
            font-size: 0.825rem;
            color: #475569;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .text-center-otp {
            text-align: center;
            letter-spacing: 6px;
            font-size: 1.25rem;
            font-weight: 700;
            font-family: monospace;
        }

        .btn-text-back {
            background: none;
            border: none;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            font-family: inherit;
        }

        .btn-text-back:hover {
            color: #1e293b;
        }

        /* Responsive Layout */
        @media (max-width: 820px) {
            .login-split-card {
                flex-direction: column;
                max-width: 480px;
                border-radius: 20px;
                min-height: auto;
            }

            .split-left-panel {
                padding: 2.5rem 2rem;
                min-height: 240px;
            }

            .left-headline {
                font-size: 1.85rem;
            }

            .split-right-panel {
                padding: 2.25rem 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Soft Background Decors -->
    <div class="bg-deco-circle-1"></div>
    <div class="bg-deco-circle-2"></div>

    <!-- Main Split Card -->
    <div class="login-split-card">

        <!-- SISI KIRI: Blue Geometric Banner (Sesuai Desain Referensi) -->
        <div class="split-left-panel">
            <!-- Pure CSS Geometric Decorations (Tanpa Icon) -->
            <div class="geo-glow-top"></div>
            <div class="geo-dots-grid"></div>
            <div class="geo-pill-1"></div>
            <div class="geo-pill-2"></div>
            <div class="geo-orbit-ring"></div>
            <div class="geo-sphere-main"></div>
            <div class="geo-sphere-small"></div>
            <div class="geo-sphere-tiny"></div>
            <div class="geo-dots-bottom"></div>

            <div>
                <img src="{{ asset('images/logo_bank_mini.png') }}" alt="Logo Bank Mini" style="width: 58px; height: 58px; object-fit: contain; border-radius: 12px; background: rgba(255,255,255,0.95); padding: 4px; box-shadow: 0 4px 16px rgba(0,0,0,0.12);">
            </div>

            <div class="left-content">
                <h1 class="left-headline">E-Teller<br>Bank Mini</h1>
                <p class="left-description">
                    Layanan operasional perbankan sekolah terpadu dan tabungan siswa yang praktis, cepat, dan aman.
                </p>
            </div>

            <div class="left-footer">
                Sistem Informasi Perbankan Mini &copy; 2026/2027
            </div>
        </div>

        <!-- SISI KANAN: Clean White Login Card (Tanpa Icon & Tanpa Logo) -->
        <div class="split-right-panel">
            <div class="right-header">
                <div style="margin-bottom: 0.85rem;">
                    <img src="{{ asset('images/logo_bank_mini.png') }}" alt="Logo Bank Mini" style="width: 52px; height: 52px; object-fit: contain; border-radius: 10px; background: #fff; padding: 3px; box-shadow: 0 2px 8px rgba(15,23,42,0.06); border: 1px solid #e2e8f0;">
                </div>
                <h2>Hello ! Welcome back</h2>
                <p>Silakan masuk ke akun perbankan Anda</p>
            </div>

            <!-- Pesan Kesalahan / Sukses -->
            @if($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('error_lupa'))
                <div class="alert-error">
                    {{ session('error_lupa') }}
                </div>
            @endif

            <div id="loginCardSuccessAlert" class="alert-success" style="display: {{ session('success_lupa') ? 'block' : 'none' }};">
                {{ session('success_lupa') }}
            </div>

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="usernameInput">Email / NISN / Username</label>
                    <div class="form-input-wrapper">
                        <input type="text" name="username" id="usernameInput" class="form-control-custom" required placeholder="Masukkan email, NISN, atau username" value="{{ old('username') }}" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="passwordInput">Password</label>
                    <div class="form-input-wrapper">
                        <input type="password" name="password" id="passwordInput" class="form-control-custom" required placeholder="Masukkan kata sandi" style="padding-right: 5rem;">
                        <button type="button" class="btn-text-toggle-pwd" onclick="togglePasswordVisibility('passwordInput', this)">Lihat</button>
                    </div>
                </div>

                <!-- Checkbox Ingat Saya & Link Lupa Kata Sandi -->
                <div class="form-meta-row">
                    <label class="remember-checkbox-label" for="rememberMe">
                        <input type="checkbox" name="remember" id="rememberMe">
                        <span>Remember me</span>
                    </label>

                    <button type="button" class="link-reset-password" onclick="openResetModal()">
                        Reset Password!
                    </button>
                </div>

                <button type="submit" class="btn-main-login">Login</button>
            </form>

            <div class="login-info-box">
                <a href="{{ route('landing.apk') }}" class="btn-download-app-link">
                    Unduh Aplikasi Nasabah (APK)
                </a>
            </div>
        </div>

    </div>

    <!-- MODAL LUPA SANDI VIA KODE OTP GMAIL (TANPA MENGGUNAKAN ICON) -->
    <div class="modal-reset-overlay" id="resetModal">
        <div class="modal-reset-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;">Pemulihan Sandi Akun</h3>
                <button type="button" onclick="closeResetModal()" class="btn-text-close">Tutup</button>
            </div>

            <!-- TAHAP 1: INPUT NISN/USERNAME & GMAIL -> KIRIM OTP -> MASUKKAN OTP -> SELANJUTNYA -->
            <div id="resetStep1">
                <p class="modal-instruction">
                    Masukkan NISN atau Username akun Anda dan alamat Gmail. Sistem akan mengirimkan kode OTP untuk proses verifikasi kata sandi baru.
                </p>

                <div id="step1Alert" class="alert-box" style="display: none;"></div>

                <div class="form-group">
                    <label class="form-label" for="resetIdentifier">NISN atau Username</label>
                    <input type="text" id="resetIdentifier" class="form-control-custom" placeholder="Contoh: 1029384756 atau username" autocomplete="off">
                </div>

                <div class="form-group">
                    <label class="form-label" for="resetEmail">Alamat Gmail Terdaftar</label>
                    <input type="email" id="resetEmail" class="form-control-custom" placeholder="Contoh: ahmad.rizky@gmail.com" autocomplete="off">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <button type="button" id="btnKirimOtp" class="btn-action-primary" onclick="handleKirimOtp()">
                        Kirim Kode OTP
                    </button>
                </div>

                <div class="form-group">
                    <label class="form-label" for="resetOtp">Masukkan Kode OTP</label>
                    <input type="text" id="resetOtp" class="form-control-custom text-center-otp" placeholder="6 Digit OTP" maxlength="6" autocomplete="off">
                </div>

                <button type="button" id="btnSelanjutnya" class="btn-action-success" onclick="handleVerifikasiOtp()">
                    Selanjutnya
                </button>
            </div>

            <!-- TAHAP 2: OTOMATIS TAMPILKAN NAMA SIAPA & MASUKKAN SANDI BARU -->
            <div id="resetStep2" style="display: none;">
                <p class="modal-instruction">
                    Kode OTP terverifikasi. Berikut nama pemilik akun Anda. Silakan tentukan kata sandi baru untuk akun ini di bawah.
                </p>

                <div id="step2Alert" class="alert-box" style="display: none;"></div>

                <!-- Tampilan Otomatis Nama Akun -->
                <div class="account-info-card">
                    <div class="info-label">Nama Pemilik Akun</div>
                    <div class="info-name" id="displayNamaUser">-</div>
                    <div class="info-sub" id="displayUsernameUser">Username / NISN: -</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="resetNewPassword">Sandi Baru</label>
                    <div class="form-input-wrapper">
                        <input type="password" id="resetNewPassword" class="form-control-custom" placeholder="Masukkan kata sandi baru (min. 6 karakter)" style="padding-right: 5rem;">
                        <button type="button" class="btn-text-toggle-pwd" onclick="togglePasswordVisibility('resetNewPassword', this)">Lihat</button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="resetNewPasswordConfirm">Konfirmasi Sandi Baru</label>
                    <div class="form-input-wrapper">
                        <input type="password" id="resetNewPasswordConfirm" class="form-control-custom" placeholder="Ulangi kata sandi baru" style="padding-right: 5rem;">
                        <button type="button" class="btn-text-toggle-pwd" onclick="togglePasswordVisibility('resetNewPasswordConfirm', this)">Lihat</button>
                    </div>
                </div>

                <button type="button" id="btnSimpanSandi" class="btn-action-primary" onclick="handleSimpanSandi()">
                    Simpan Sandi Baru
                </button>

                <div style="text-align: center; margin-top: 1rem;">
                    <button type="button" class="btn-text-back" onclick="goToStep1()">Kembali ke Tahap Sebelumnya</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentResetToken = null;
        let otpCountdownTimer = null;

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.textContent = isPassword ? 'Sembunyikan' : 'Lihat';
            btn.style.color = isPassword ? '#4361ee' : '#475569';
        }

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function showAlert(elementId, message, type = 'error') {
            const el = document.getElementById(elementId);
            if (!el) return;
            el.className = 'alert-box alert-' + type;
            el.textContent = message;
            el.style.display = 'block';
        }

        function hideAlert(elementId) {
            const el = document.getElementById(elementId);
            if (el) el.style.display = 'none';
        }

        function openResetModal() {
            document.getElementById('resetModal').style.display = 'flex';
            goToStep1();
            setTimeout(() => {
                const idEl = document.getElementById('resetIdentifier');
                if (idEl) idEl.focus();
            }, 100);
        }

        function closeResetModal() {
            document.getElementById('resetModal').style.display = 'none';
            if (otpCountdownTimer) {
                clearInterval(otpCountdownTimer);
                otpCountdownTimer = null;
            }
        }

        // Tutup modal jika klik di area overlay luar kartu
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('resetModal');
            if (e.target === modal) {
                closeResetModal();
            }
        });

        function goToStep1() {
            document.getElementById('resetStep2').style.display = 'none';
            document.getElementById('resetStep1').style.display = 'block';
            hideAlert('step1Alert');
            hideAlert('step2Alert');
        }

        async function handleKirimOtp() {
            const identifier = document.getElementById('resetIdentifier').value.trim();
            const email = document.getElementById('resetEmail').value.trim();
            const btn = document.getElementById('btnKirimOtp');

            hideAlert('step1Alert');

            if (!identifier) {
                showAlert('step1Alert', 'Silakan masukkan NISN atau Username terlebih dahulu.', 'error');
                document.getElementById('resetIdentifier').focus();
                return;
            }

            if (!email) {
                showAlert('step1Alert', 'Silakan masukkan alamat Gmail terlebih dahulu.', 'error');
                document.getElementById('resetEmail').focus();
                return;
            }

            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = 'Mengirim Kode OTP...';

            try {
                const response = await fetch('{{ route("lupa-sandi.kirim-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier, email })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert('step1Alert', data.message, 'success');
                    document.getElementById('resetOtp').focus();

                    // Hitung mundur kirim ulang OTP 60 detik
                    let timeLeft = 60;
                    btn.textContent = 'Kirim Ulang OTP (' + timeLeft + 's)';
                    if (otpCountdownTimer) clearInterval(otpCountdownTimer);
                    otpCountdownTimer = setInterval(() => {
                        timeLeft--;
                        if (timeLeft <= 0) {
                            clearInterval(otpCountdownTimer);
                            otpCountdownTimer = null;
                            btn.disabled = false;
                            btn.textContent = 'Kirim Ulang Kode OTP';
                        } else {
                            btn.textContent = 'Kirim Ulang OTP (' + timeLeft + 's)';
                        }
                    }, 1000);
                } else {
                    showAlert('step1Alert', data.message || 'Gagal mengirim kode OTP.', 'error');
                    btn.disabled = false;
                    btn.textContent = originalText;
                }
            } catch (err) {
                showAlert('step1Alert', 'Terjadi kesalahan sistem saat mengirim kode OTP.', 'error');
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }

        async function handleVerifikasiOtp() {
            const identifier = document.getElementById('resetIdentifier').value.trim();
            const email = document.getElementById('resetEmail').value.trim();
            const otp = document.getElementById('resetOtp').value.trim();
            const btn = document.getElementById('btnSelanjutnya');

            hideAlert('step1Alert');

            if (!identifier || !email) {
                showAlert('step1Alert', 'Mohon lengkapi NISN/Username dan Alamat Gmail.', 'error');
                return;
            }

            if (!otp || otp.length < 6) {
                showAlert('step1Alert', 'Silakan masukkan 6 digit kode OTP yang telah dikirim ke Gmail Anda.', 'error');
                document.getElementById('resetOtp').focus();
                return;
            }

            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = 'Memverifikasi...';

            try {
                const response = await fetch('{{ route("lupa-sandi.verifikasi-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ identifier, email, otp })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    currentResetToken = data.reset_token;

                    // Otomatis pasang nama pemilik akun dan username di Tahap 2
                    document.getElementById('displayNamaUser').textContent = data.nama;
                    document.getElementById('displayUsernameUser').textContent = 'NISN / Username: ' + (data.username || identifier);

                    // Beralih ke Tahap 2
                    document.getElementById('resetStep1').style.display = 'none';
                    document.getElementById('resetStep2').style.display = 'block';
                    hideAlert('step2Alert');
                    document.getElementById('resetNewPassword').focus();
                } else {
                    showAlert('step1Alert', data.message || 'Kode OTP tidak valid atau telah kadaluarsa.', 'error');
                }
            } catch (err) {
                showAlert('step1Alert', 'Terjadi kesalahan sistem saat memverifikasi kode OTP.', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }

        async function handleSimpanSandi() {
            const password = document.getElementById('resetNewPassword').value;
            const passwordConfirmation = document.getElementById('resetNewPasswordConfirm').value;
            const btn = document.getElementById('btnSimpanSandi');

            hideAlert('step2Alert');

            if (!password) {
                showAlert('step2Alert', 'Silakan masukkan kata sandi baru Anda.', 'error');
                document.getElementById('resetNewPassword').focus();
                return;
            }

            if (password.length < 6) {
                showAlert('step2Alert', 'Kata sandi baru minimal harus 6 karakter.', 'error');
                document.getElementById('resetNewPassword').focus();
                return;
            }

            if (password !== passwordConfirmation) {
                showAlert('step2Alert', 'Konfirmasi kata sandi baru tidak cocok. Silakan periksa kembali.', 'error');
                document.getElementById('resetNewPasswordConfirm').focus();
                return;
            }

            if (!currentResetToken) {
                showAlert('step2Alert', 'Token verifikasi tidak valid. Silakan ulangi tahap verifikasi OTP.', 'error');
                return;
            }

            btn.disabled = true;
            const originalText = btn.textContent;
            btn.textContent = 'Menyimpan Sandi Baru...';

            try {
                const response = await fetch('{{ route("lupa-sandi.simpan-sandi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        reset_token: currentResetToken,
                        password: password,
                        password_confirmation: passwordConfirmation
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showAlert('step2Alert', data.message, 'success');

                    // Isi username di form login utama secara otomatis
                    const loginUsernameInput = document.getElementById('usernameInput');
                    if (loginUsernameInput && data.username) {
                        loginUsernameInput.value = data.username;
                    }

                    // Tampilkan notifikasi di login card
                    const cardAlert = document.getElementById('loginCardSuccessAlert');
                    if (cardAlert) {
                        cardAlert.textContent = data.message;
                        cardAlert.style.display = 'block';
                    }

                    // Bersihkan input modal
                    document.getElementById('resetIdentifier').value = '';
                    document.getElementById('resetEmail').value = '';
                    document.getElementById('resetOtp').value = '';
                    document.getElementById('resetNewPassword').value = '';
                    document.getElementById('resetNewPasswordConfirm').value = '';
                    currentResetToken = null;

                    // Tutup modal dan arahkan ke password input di login card
                    setTimeout(() => {
                        closeResetModal();
                        goToStep1();
                        const passInput = document.getElementById('passwordInput');
                        if (passInput) passInput.focus();
                    }, 1800);
                } else {
                    showAlert('step2Alert', data.message || 'Gagal menyimpan kata sandi baru.', 'error');
                    btn.disabled = false;
                    btn.textContent = originalText;
                }
            } catch (err) {
                showAlert('step2Alert', 'Terjadi kesalahan sistem saat menyimpan kata sandi baru.', 'error');
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
    </script>
</body>
</html>
