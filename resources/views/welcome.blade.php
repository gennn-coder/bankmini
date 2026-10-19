<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Modal Reset Password via OTP Gmail (Tanpa Icon) */
        .modal-reset-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
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
            max-width: 440px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
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

        .alert-box.alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
        }

        .btn-action-primary {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            border: none;
            background: #2563eb;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-action-primary:hover:not(:disabled) {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-action-primary:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-action-success {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: none;
            background: #059669;
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .btn-action-success:hover:not(:disabled) {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
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
            font-size: 0.75rem;
            font-weight: 700;
            color: #166534;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .account-info-card .info-name {
            font-size: 1.2rem;
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
            letter-spacing: 4px;
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
        }

        .btn-text-back:hover {
            color: #1e293b;
        }
    </style>
</head>
<body>

    <div class="login-outer-frame">
        <!-- Brand Header -->
        <div class="header-brand">
            <h1>E-TELLER BANK MINI SEKOLAH</h1>
        </div>

        <!-- Inner White Card -->
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

            <div id="loginCardSuccessAlert" class="alert-success" style="display: {{ session('success_lupa') ? 'block' : 'none' }}; line-height: 1.5;">
                {{ session('success_lupa') }}
            </div>

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

    <!-- MODAL LUPA SANDI VIA KODE OTP GMAIL (TANPA MENGGUNAKAN ICON) -->
    <div class="modal-reset-overlay" id="resetModal">
        <div class="modal-reset-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Pemulihan Sandi Akun</h3>
                <button type="button" onclick="closeResetModal()" class="btn-text-close">Tutup</button>
            </div>

            <!-- TAHAP 1: INPUT NISN/USERNAME & GMAIL -> KIRIM OTP -> MASUKKAN OTP -> SELANJUTNYA -->
            <div id="resetStep1">
                <p class="modal-instruction">
                    Masukkan NISN atau Username akun Anda dan alamat Gmail. Sistem akan mengirimkan kode OTP untuk proses verifikasi kata sandi baru.
                </p>

                <div id="step1Alert" class="alert-box" style="display: none;"></div>

                <div class="form-group">
                    <label for="resetIdentifier">NISN atau Username</label>
                    <input type="text" id="resetIdentifier" class="form-control" placeholder="Contoh: 1029384756 atau username" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="resetEmail">Alamat Gmail</label>
                    <input type="email" id="resetEmail" class="form-control" placeholder="Contoh: ahmad.rizky@gmail.com" autocomplete="off">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <button type="button" id="btnKirimOtp" class="btn-action-primary" onclick="handleKirimOtp()">
                        Kirim Kode OTP
                    </button>
                </div>

                <div class="form-group">
                    <label for="resetOtp">Masukkan Kode OTP</label>
                    <input type="text" id="resetOtp" class="form-control text-center-otp" placeholder="6 Digit OTP" maxlength="6" autocomplete="off">
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
                    <label for="resetNewPassword">Sandi Baru</label>
                    <input type="password" id="resetNewPassword" class="form-control" placeholder="Masukkan kata sandi baru (min. 6 karakter)">
                </div>

                <div class="form-group">
                    <label for="resetNewPasswordConfirm">Konfirmasi Sandi Baru</label>
                    <input type="password" id="resetNewPasswordConfirm" class="form-control" placeholder="Ulangi kata sandi baru">
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

                    // Otomatis pasang nama siapa dan username di Tahap 2
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
