<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Teller | E-Teller Bank Mini</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2rem;
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .container {
            max-width: 1280px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
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
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.04);
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
        .nav-tabs {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.75rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.75rem;
            width: 100%;
        }
        .tab-btn {
            background: transparent;
            border: none;
            padding: 0.55rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 10px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .tab-btn:hover {
            color: var(--primary-600);
            background: rgba(59, 130, 246, 0.08);
        }
        .tab-btn.active {
            background: var(--primary-600);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
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
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <h2 style="margin:0; font-size: 1.25rem; font-weight: 800;">Teller</h2>
            <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah</p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div class="status-toggle-wrapper">
                <button type="button" onclick="ubahStatusLayanan('buka')" id="btn-status-buka" class="btn-status-option {{ $statusLayanan === 'buka' ? 'active-buka' : '' }}">Buka</button>
                <button type="button" onclick="ubahStatusLayanan('istirahat')" id="btn-status-istirahat" class="btn-status-option {{ $statusLayanan === 'istirahat' ? 'active-istirahat' : '' }}">Istirahat</button>
                <button type="button" onclick="ubahStatusLayanan('tutup')" id="btn-status-tutup" class="btn-status-option {{ $statusLayanan === 'tutup' ? 'active-tutup' : '' }}">Tutup</button>
            </div>
            <span>Halo, <strong>{{ $user->nama_petugas }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout" onclick="try{sessionStorage.removeItem('teller_active_tab');localStorage.removeItem('teller_active_tab');}catch(e){}">Keluar</button>
            </form>
        </div>
    </nav>

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
                            <button type="button" onclick="document.getElementById('modalTransaksiGagal').style.display='none'; switchTab('tab-reset-pin');" style="flex: 1; background: #d97706; color: #ffffff; border: none; padding: 0.85rem 1rem; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer;">
                                Reset PIN Nasabah
                            </button>
                        @endif
                        <button type="button" onclick="document.getElementById('modalTransaksiGagal').style.display='none'" style="flex: 1; background: #2563eb; color: #ffffff; border: none; padding: 0.85rem 1rem; border-radius: 12px; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
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
                        <form action="{{ route('teller.modal-awal') }}" method="POST" class="prevent-double-submit" style="display: flex; gap: 0.4rem;">
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

        <!-- SEARCH BOX: PENCARIAN PENABUNG -->
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

        <!-- NAVIGATION TABS -->
        <div class="nav-tabs">
            <button class="tab-btn active" onclick="switchTab('tab-aktivasi')">Aktivasi Anggota</button>
            <button class="tab-btn" onclick="switchTab('tab-setoran')">Setoran Tunai</button>
            <button class="tab-btn" onclick="switchTab('tab-penarikan')">Penarikan Tunai</button>
            <button class="tab-btn" onclick="switchTab('tab-reset-pin')">Reset PIN Nasabah</button>
            <button class="tab-btn" onclick="switchTab('tab-laporan')">
                Penutupan Kas & Laporan
                @if($laporanHariIni && $laporanHariIni->status_approval === 'rejected')
                    <span style="background: #dc2626; color: #fff; padding: 2px 7px; border-radius: 999px; font-size: 0.72rem; margin-left: 5px; font-weight: 700;">Revisi</span>
                @endif
            </button>
            <button class="tab-btn" onclick="switchTab('tab-riwayat')">Riwayat Transaksi Harian</button>
            <button class="tab-btn" onclick="switchTab('tab-bulanan')">Laporan Bulanan</button>
        </div>

        <!-- TAB 1: AKTIVASI REKENING ANGGOTA SISWA -->
        <div id="tab-aktivasi" class="tab-content active">
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
            </div>
        </div>

        <!-- TAB 2: SETORAN TUNAI -->
        <div id="tab-setoran" class="tab-content">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <h3 style="margin-top:0; color: #16a34a; margin-bottom: 1.25rem;">Form Transaksi Setoran Tunai Penabung</h3>
                <form action="{{ route('teller.setoran') }}" method="POST" class="prevent-double-submit">
                    @csrf
                    <input type="hidden" name="_token_transaksi" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                    <div class="form-group">
                        <label>Pilih Penabung</label>
                        <select name="id_nasabah" id="setoranNasabahSelect" class="form-control" required>
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->account_number }} - {{ $s->student_name }} (Saldo: Rp {{ number_format($s->rekening->saldo ?? 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nominal Setoran (Rp)</label>
                        <input type="number" name="nominal" id="setoranNominal" class="form-control" required min="1000" step="1000" placeholder="Contoh: 50000">
                    </div>
                    <div class="form-group">
                        <label>Keterangan Slip</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Setoran tabungan mingguan">
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
                
                <form action="{{ route('teller.penarikan') }}" method="POST" class="prevent-double-submit">
                    @csrf
                    <input type="hidden" name="_token_transaksi" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                    <div class="form-group">
                        <label>Pilih Penabung</label>
                        <select name="id_nasabah" id="penarikanNasabahSelect" class="form-control" required onchange="onPenarikanSelectChange()">
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                <option value="{{ $s->id }}" data-saldo="{{ $s->rekening->saldo ?? 0 }}" data-name="{{ $s->student_name }}" data-norek="{{ $s->account_number }}" data-nis="{{ $s->nis }}" data-email="{{ $s->email ?? '' }}">
                                    {{ $s->account_number }} - {{ $s->student_name }} (Saldo: Rp {{ number_format($s->rekening->saldo ?? 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="penarikanInfoBox" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span style="color: #64748b;">Saldo Tabungan Saat Ini:</span>
                            <strong id="penarikanInfoSaldo" style="color: #16a34a;">Rp 0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #64748b;">Batas Penarikan Maksimal (Sisa Rp 10.000):</span>
                            <strong id="penarikanInfoMaksimal" style="color: #dc2626;">Rp 0</strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nominal Penarikan (Rp)</label>
                        <input type="number" name="nominal" id="penarikanNominal" class="form-control" required min="10000" step="1000" placeholder="Minimal Rp 10.000">
                    </div>
                    <div class="form-group">
                        <label>Verifikasi PIN 6-Digit Keamanan Penabung</label>
                        <div style="position: relative;">
                            <input type="password" name="pin" id="penarikanPin" class="form-control" required maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN penabung" style="padding-right: 2.75rem;">
                            <button type="button" onclick="togglePasswordVisibility('penarikanPin', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat PIN">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px; flex-wrap: wrap; gap: 0.25rem;">
                            <small style="color: #64748b; font-size: 0.78rem;">Wajib 6 angka PIN keamanan penabung.</small>
                            <a href="javascript:void(0)" onclick="openLupaPinOtpModal()" style="font-size: 0.82rem; color: #2563eb; font-weight: 700; text-decoration: underline;">
                                Lupa PIN Nasabah?
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan Slip</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Penarikan kebutuhan praktek">
                    </div>
                    <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #dc2626, #ef4444); width: 100%; margin-top: 0.5rem; padding: 0.85rem;">
                        Proses Penarikan & Simpan Jurnal
                    </button>
                </form>
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
                        <input type="password" id="tabResetPinBaru" class="form-control" maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN baru" required>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi PIN 6-Digit Baru</label>
                        <input type="password" id="tabResetPinBaruConfirm" class="form-control" maxlength="6" minlength="6" placeholder="Ulangi 6 angka PIN baru" required>
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
                    <form action="{{ route('teller.laporan-harian') }}" method="POST" class="prevent-double-submit">
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
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.7';
                        submitBtn.style.cursor = 'not-allowed';
                        submitBtn.style.pointerEvents = 'none';
                        submitBtn.innerHTML = `
                            <span style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                                <svg style="animation: spinDouble 0.9s linear infinite; width:18px; height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"></path></svg>
                                Sedang Memproses... Harap Tunggu
                            </span>
                        `;
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
                    const navTabs = document.querySelector('.nav-tabs');
                    if (navTabs) {
                        const yOffset = -20;
                        const y = navTabs.getBoundingClientRect().top + window.pageYOffset + yOffset;
                        window.scrollTo({ top: y, behavior: 'smooth' });
                    } else {
                        targetContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 60);
            }

            try {
                sessionStorage.setItem('teller_active_tab', tabId);
                localStorage.setItem('teller_active_tab', tabId);
            } catch (e) {}

            try {
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, null, '#' + tabId);
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

            const btnToggle1 = document.getElementById('btnTogglePinAktivasi');
            if (btnToggle1) {
                btnToggle1.textContent = 'Lihat';
                btnToggle1.style.color = '#64748b';
            }
            const btnToggle2 = document.getElementById('btnTogglePinConfirmAktivasi');
            if (btnToggle2) {
                btnToggle2.textContent = 'Lihat';
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

            return true;
        }

        function setModalAktNominal(val) {
            const input = document.getElementById('modalAktNominal');
            if (input) input.value = val;
        }

        function filterAktivasiTable() {
            const search = (document.getElementById('searchAktivasiTable')?.value || '').toLowerCase().trim();
            const kelas = (document.getElementById('filterAktivasiKelas')?.value || '').toLowerCase().trim();
            const angkatan = (document.getElementById('filterAktivasiAngkatan')?.value || '').toLowerCase().trim();

            const rows = document.querySelectorAll('.aktivasi-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.getAttribute('data-search') || '';
                const rowKelas = row.getAttribute('data-kelas') || '';
                const rowAngkatan = row.getAttribute('data-angkatan') || '';

                const matchSearch = !search || text.includes(search);
                const matchKelas = !kelas || rowKelas === kelas;
                const matchAngkatan = !angkatan || rowAngkatan === angkatan;

                if (matchSearch && matchKelas && matchAngkatan) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('emptyAktivasiRow');
            if (emptyRow) {
                emptyRow.style.display = visibleCount === 0 ? '' : 'none';
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

        function selectForSetoran() {
            if (!selectedSaverData) return;
            switchTab('tab-setoran', true);
            const sel = document.getElementById('setoranNasabahSelect');
            sel.value = selectedSaverData.id;
            setTimeout(() => document.getElementById('setoranNominal')?.focus(), 250);
        }

        function onPenarikanSelectChange() {
            const sel = document.getElementById('penarikanNasabahSelect');
            const box = document.getElementById('penarikanInfoBox');
            if (!box || !sel) return;
            const opt = sel.options[sel.selectedIndex];
            if (sel.value && opt) {
                const saldo = parseInt(opt.getAttribute('data-saldo') || '0', 10);
                const maxTarik = Math.max(0, saldo - 10000);
                document.getElementById('penarikanInfoSaldo').innerText = 'Rp ' + saldo.toLocaleString('id-ID');
                document.getElementById('penarikanInfoMaksimal').innerText = 'Rp ' + maxTarik.toLocaleString('id-ID');
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }

        function selectForPenarikan() {
            if (!selectedSaverData) return;
            switchTab('tab-penarikan', true);
            const sel = document.getElementById('penarikanNasabahSelect');
            sel.value = selectedSaverData.id;
            onPenarikanSelectChange();
            setTimeout(() => document.getElementById('penarikanNominal')?.focus(), 250);
        }

        function selectForResetPin() {
            if (!selectedSaverData) return;
            switchTab('tab-reset-pin');
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
            goToTabResetPinStep1();
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
                                    : null;

                if (tabToActivate && document.getElementById(tabToActivate)) {
                    switchTab(tabToActivate);
                }
            } catch (e) {}
        }

        // Jalankan segera saat skrip dieksekusi agar bebas flicker
        restoreTellerActiveTab();

        // Restore tab saat refresh / navigasi & daftarkan listener form submit
        window.addEventListener('DOMContentLoaded', () => {
            restoreTellerActiveTab();

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
                    <input type="email" name="email" id="modalAktEmail" class="form-control" required placeholder="Contoh: siswa@gmail.com">
                    <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Digunakan untuk pengiriman informasi dan pemulihan PIN nasabah.</small>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">
                        Buat PIN 6-Digit Keamanan Penabung Baru <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" name="pin" id="modalAktPin" class="form-control" required maxlength="6" minlength="6" placeholder="Contoh: 123456" pattern="[0-9]{6}" inputmode="numeric" style="padding-right: 4.8rem; letter-spacing: 2px; font-weight: 700;">
                        <button type="button" id="btnTogglePinAktivasi" onclick="togglePasswordVisibility('modalAktPin', this)" style="position: absolute; right: 8px; background: none; border: none; font-size: 0.8rem; font-weight: 700; color: #64748b; cursor: pointer; padding: 4px 8px;">Lihat</button>
                    </div>
                    <small style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">PIN 6 angka ini digunakan nasabah saat verifikasi penarikan uang tabungan.</small>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #1e293b;">
                        Konfirmasi PIN 6-Digit <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" name="pin_confirmation" id="modalAktPinConfirm" class="form-control" required maxlength="6" minlength="6" placeholder="Ketik ulang 6 angka PIN" pattern="[0-9]{6}" inputmode="numeric" style="padding-right: 4.8rem; letter-spacing: 2px; font-weight: 700;">
                        <button type="button" id="btnTogglePinConfirmAktivasi" onclick="togglePasswordVisibility('modalAktPinConfirm', this)" style="position: absolute; right: 8px; background: none; border: none; font-size: 0.8rem; font-weight: 700; color: #64748b; cursor: pointer; padding: 4px 8px;">Lihat</button>
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

            <form action="{{ route('teller.ajukan-kas') }}" method="POST" id="formAjukanKas" class="prevent-double-submit" autocomplete="off">
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

        // Pastikan saat ada konfirmasi transaksi/laporan, layar langsung fokus di atas
        @if(session('success') || session('error') || $errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        @endif
    </script>
</body>
</html>
