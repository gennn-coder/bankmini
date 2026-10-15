<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System | E-Teller Bank Mini Sekolah</title>
    
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
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --primary-blue: #3b82f6;
            --primary-hover: #2563eb;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
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
            background: linear-gradient(135deg, #93c5fd 0%, #3b82f6 50%, #60a5fa 100%);
            padding: 1.5rem 1rem;
            overflow-x: hidden;
        }

        .login-outer-frame {
            width: 100%;
            max-width: 860px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 28px;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            position: relative;
        }

        .header-brand {
            text-align: center;
            margin-bottom: 1.25rem;
            color: #ffffff;
        }

        .header-brand h1 {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-brand p {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 600;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            padding: 2rem 1.75rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .card-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .card-header h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .card-header p {
            font-size: 0.875rem;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #0f172a;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            border-radius: 12px;
            border: none;
            background: #4f46e5;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            text-align: center;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            text-align: center;
        }

        .lupa-sandi-container {
            margin-top: 1.5rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 1.25rem;
        }

        .btn-lupa-link {
            background: none;
            border: none;
            color: #3b82f6;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: underline;
        }

        .btn-lupa-link:hover {
            color: #1d4ed8;
        }

        .footer-credits {
            margin-top: 2rem;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            opacity: 0.9;
        }

        /* Modal Reset Password via Gmail Nasabah */
        .modal-reset-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-reset-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            position: relative;
        }
    </style>
</head>
<body>

    <div class="login-outer-frame">
        <!-- Brand Header -->
        <div class="header-brand">
            <h1>E-TELLER BANK MINI SEKOLAH</h1>
        </div>

        <!-- Inner White Card (Exact layout matching Screenshot 2) -->
        <div class="login-card">
            <div class="card-header">
                <h2>Welcome Back!</h2>
                <p>Silakan masukkan kredensial akun Anda untuk masuk.</p>
            </div>

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

            @if(session('success_lupa'))
                <div class="alert-success" style="line-height: 1.5;">
                    {{ session('success_lupa') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="usernameInput">Username / Nama</label>
                    <input type="text" name="username" id="usernameInput" class="form-control" required placeholder="Masukkan Username atau Nama Anda" autofocus>
                </div>

                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Masukkan Password Anda">
                </div>

                <button type="submit" class="btn-submit">Masuk Sistem</button>
            </form>

            <div class="lupa-sandi-container">
                <button type="button" class="btn-lupa-link" onclick="openResetModal()">Lupa Sandi??</button>
            </div>
        </div>

        <div class="footer-credits">
            &copy; 2026/2027 Sistem Informasi E-Teller Bank Mini Sekolah
        </div>
    </div>

    <!-- MODAL LUPA SANDI VIA GMAIL NASABAH -->
    <div class="modal-reset-overlay" id="resetModal">
        <div class="modal-reset-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Pemulihan Lupa Sandi</h3>
                <button type="button" onclick="closeResetModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem; line-height: 1.5;">
                Masukkan alamat Gmail Nasabah Anda. Kata sandi baru akan otomatis dibuat dan dikirimkan langsung ke alamat Gmail yang Anda tulis di bawah ini.
            </p>

            <form action="{{ route('lupa-sandi') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="emailInput">Alamat Gmail Nasabah</label>
                    <input type="email" name="email" id="emailInput" class="form-control" required placeholder="Contoh: ahmad.rizky@gmail.com">
                </div>

                <button type="submit" class="btn-submit" style="background: #2563eb;">Kirim Kata Sandi ke Gmail</button>
            </form>
        </div>
    </div>

    <script>
        function openResetModal() {
            document.getElementById('resetModal').style.display = 'flex';
            document.getElementById('emailInput').focus();
        }

        function closeResetModal() {
            document.getElementById('resetModal').style.display = 'none';
        }
    </script>
</body>
</html>
