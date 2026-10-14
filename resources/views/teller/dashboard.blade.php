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
        .nav-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.75rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            max-width: 100%;
        }
        .tab-btn {
            background: transparent;
            border: none;
            padding: 0.65rem 1.25rem;
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
            position: relative;
            background: #0f172a;
            border-radius: 14px;
            overflow: hidden;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #reader {
            width: 100% !important;
            border: none !important;
            background: #0f172a !important;
        }
        #reader video {
            width: 100% !important;
            height: auto !important;
            border-radius: 12px;
            object-fit: cover;
        }
        #reader__scan_region {
            background: transparent !important;
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
            width: 230px;
            height: 230px;
            border: 2px dashed rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            pointer-events: none;
            z-index: 9;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.3);
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
            <h2 style="margin:0; font-size: 1.25rem; font-weight: 800;">Teller Operational System</h2>
            <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah</p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span>Halo, <strong>{{ $user->nama_petugas }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="grid">
            <div class="card">
                <h4>Total Transaksi Hari Ini</h4>
                <div class="value">{{ count($transaksiHariIni) }}</div>
            </div>
            <div class="card">
                <h4>Total Setoran Tunai</h4>
                <div class="value" style="color: #16a34a;">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</div>
            </div>
            <div class="card">
                <h4>Total Penarikan Tunai</h4>
                <div class="value" style="color: #dc2626;">Rp {{ number_format($totalPenarikan, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- SEARCH BOX: PENCARIAN PENABUNG -->
        <div class="search-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="margin: 0; font-size: 1.15rem; color: var(--primary-600); font-weight: 800;">
                    Pencarian Rekening Penabung (Scan Barcode &amp; QR / No Rekening / NIS)
                </h3>
                <span style="font-size: 0.78rem; background: #e0f2fe; color: #0284c7; padding: 0.25rem 0.65rem; border-radius: 999px; font-weight: 700;">
                    Kamera &amp; USB Barcode Scanner Ready
                </span>
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
                    <div>NIS / Kelas: <strong id="infoNisKelas">-</strong></div>
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
            <button class="tab-btn active" onclick="switchTab('tab-aktivasi')">Aktivasi Anggota (Otomatis)</button>
            <button class="tab-btn" onclick="switchTab('tab-setoran')">Setoran Tunai</button>
            <button class="tab-btn" onclick="switchTab('tab-penarikan')">Penarikan Tunai</button>
            <button class="tab-btn" onclick="switchTab('tab-reset-pin')">Lupa / Reset PIN Penabung</button>
            <button class="tab-btn" onclick="switchTab('tab-laporan')">Penutupan Kas & Laporan</button>
            <button class="tab-btn" onclick="switchTab('tab-riwayat')">Riwayat Transaksi Harian</button>
            <button class="tab-btn" onclick="switchTab('tab-bulanan')">Laporan Bulanan</button>
        </div>

        <!-- TAB 1: SISTEM OTOMATIS: AKTIVASI REKENING ANGGOTA -->
        <div id="tab-aktivasi" class="tab-content active">
            <div class="card" style="max-width: 650px; margin: 0 auto;">
                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.25rem;">
                    <h3 style="margin:0; color: #2563eb; font-size: 1.25rem;">Sistem Otomatis Aktivasi Rekening Anggota</h3>
                    <p style="margin: 0.35rem 0 0; font-size: 0.85rem; color: var(--text-muted); line-height: 1.4;">
                        Jika siswa anggota menuju teller dan menabung minimal <strong>Rp 10.000</strong>, data otomatis berpindah/diklon ke tabel <strong>Penabung (Saver)</strong> dan rekening langsung aktif.
                    </p>
                </div>

                <form action="{{ route('teller.aktivasi-anggota') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Siswa Anggota (Status: Rekening Belum Aktif)</label>
                        <select name="user_id" id="aktivasiMemberSelect" class="form-control" required onchange="onMemberSelectChange()">
                            <option value="">-- Pilih Siswa Anggota --</option>
                            @foreach($membersUnactivated as $mu)
                                <option value="{{ $mu->user_id }}" data-name="{{ $mu->student_name }}" data-nis="{{ $mu->nis }}" data-class="{{ $mu->class_grade }}" data-year="{{ $mu->graduation_year }}">
                                    {{ $mu->student_name }} (NIS: {{ $mu->nis }} &bull; {{ $mu->class_grade }} &bull; Angkatan: {{ $mu->graduation_year }})
                                </option>
                            @endforeach
                        </select>
                        <small style="font-size: 0.78rem; color: var(--text-muted); display: block; margin-top: 0.3rem;">Hanya menampilkan siswa anggota yang belum memiliki rekening tabungan.</small>
                    </div>

                    <div id="memberDetailBox" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 1rem; margin-bottom: 1.25rem; font-size: 0.85rem; line-height: 1.5;">
                        <div>Nama Siswa: <strong id="detNama">-</strong></div>
                        <div>NIS / Kelas: <strong id="detNisKelas">-</strong></div>
                        <div>Angkatan Keluar: <strong id="detAngkatan">-</strong></div>
                    </div>

                    <div class="form-group">
                        <label>Nominal Setoran Awal (Minimal Rp 10.000)</label>
                        <input type="number" name="nominal" class="form-control" required min="10000" step="1000" value="10000" placeholder="Minimal Rp 10.000">
                    </div>

                    <div class="form-group">
                        <label>Buat PIN 6-Digit Keamanan Penabung Baru</label>
                        <input type="password" name="pin" class="form-control" required maxlength="6" minlength="6" placeholder="Contoh: 123456">
                        <small style="font-size: 0.78rem; color: var(--text-muted); display: block; margin-top: 0.3rem;">PIN 6 angka ini akan digunakan siswa setiap kali melakukan penarikan uang tabungan.</small>
                    </div>

                    <div class="form-group">
                        <label>Email Siswa (Opsional untuk Pemulihan Sandi)</label>
                        <input type="email" name="email" class="form-control" placeholder="Contoh: siswa@gmail.com">
                    </div>

                    <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #2563eb, #3b82f6); width: 100%; margin-top: 0.5rem; padding: 0.85rem;">
                        Aktifkan Rekening & Kloning Data ke Penabung
                    </button>
                </form>
            </div>
        </div>

        <!-- TAB 2: SETORAN TUNAI -->
        <div id="tab-setoran" class="tab-content">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <h3 style="margin-top:0; color: #16a34a; margin-bottom: 1.25rem;">Form Transaksi Setoran Tunai Penabung</h3>
                <form action="{{ route('teller.setoran') }}" method="POST">
                    @csrf
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
                <h3 style="margin-top:0; color: #dc2626; margin-bottom: 0.5rem;">Form Transaksi Penarikan Tunai Penabung</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem; line-height: 1.4;">
                    Aturan Wajib: Saldo setelah penarikan tidak boleh kurang dari <strong>Rp 10.000</strong> dan wajib verifikasi <strong>PIN 6-Digit Keamanan</strong> siswa.
                </p>
                
                <form action="{{ route('teller.penarikan') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Penabung</label>
                        <select name="id_nasabah" id="penarikanNasabahSelect" class="form-control" required>
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->account_number }} - {{ $s->student_name }} (Saldo: Rp {{ number_format($s->rekening->saldo ?? 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nominal Penarikan (Rp)</label>
                        <input type="number" name="nominal" id="penarikanNominal" class="form-control" required min="1000" step="1000" placeholder="Contoh: 10000">
                    </div>
                    <div class="form-group">
                        <label>Verifikasi PIN 6-Digit Keamanan Penabung</label>
                        <input type="password" name="pin" class="form-control" required maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN penabung">
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

        <!-- TAB 4: LUPA / RESET PIN PENABUNG -->
        <div id="tab-reset-pin" class="tab-content">
            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <h3 style="margin-top:0; color: #d97706; margin-bottom: 0.5rem;">Layanan Lupa / Reset PIN 6-Digit Penabung</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem; line-height: 1.4;">
                    Jika siswa lupa PIN keamanan transaksinya, Teller dapat mereset PIN menjadi PIN baru setelah memverifikasi kartu identitas siswa.
                </p>

                <form action="{{ route('teller.reset-pin') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Penabung</label>
                        <select name="id_nasabah" id="resetPinSelect" class="form-control" required>
                            <option value="">-- Pilih Penabung --</option>
                            @foreach($savers as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->account_number }} - {{ $s->student_name }} (NIS: {{ $s->nis }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Masukkan PIN 6-Digit Baru</label>
                        <input type="password" name="pin_baru" class="form-control" required maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN baru">
                    </div>
                    <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #d97706, #f59e0b); width: 100%; margin-top: 0.5rem; padding: 0.85rem;">
                        Simpan PIN Baru Penabung
                    </button>
                </form>
            </div>
        </div>

        <!-- TAB 5: PENUTUPAN KAS & LAPORAN HARIAN -->
        <div id="tab-laporan" class="tab-content">
            <div class="card" style="max-width: 680px; margin: 0 auto;">
                <h3 style="margin-top:0; margin-bottom: 0.5rem;">Penutupan Kas & Laporan Harian Teller</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Hitung dan masukkan jumlah uang fisik di laci kas. Laporan akan dikirim ke Administrator untuk approval dan pemindahan kas ke brankas utama.
                </p>

                <div style="background: #eff6ff; padding: 1.25rem; border-radius: 14px; border: 1px solid #bfdbfe; margin-bottom: 1.5rem; font-size: 0.9rem; line-height: 1.7;">
                    <div>Tanggal Operasional: <strong>{{ date('d F Y') }}</strong></div>
                    <div>Total Setoran Hari Ini: <strong style="color: #16a34a;">+ Rp {{ number_format($totalSetoran) }}</strong></div>
                    <div>Total Penarikan Hari Ini: <strong style="color: #dc2626;">- Rp {{ number_format($totalPenarikan) }}</strong></div>
                    <div>Saldo Akhir Sistem Kas: <strong>Rp {{ number_format($totalSetoran - $totalPenarikan) }}</strong></div>
                    <div>Status Approval Administrator: 
                        <strong style="text-transform: uppercase; color: {{ ($laporanHariIni->status_approval ?? 'pending') === 'approved' ? '#16a34a' : '#d97706' }};">
                            {{ $laporanHariIni->status_approval ?? 'BELUM DISUBMIT' }}
                        </strong>
                    </div>
                </div>

                @if($laporanHariIni && $laporanHariIni->status_approval === 'approved')
                    <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 14px; padding: 1.25rem; margin-bottom: 1.5rem; text-align: center;">
                        <h4 style="margin:0 0 0.5rem; color: #15803d;">Laporan Hari Ini Telah Disetujui (ACC) oleh Administrator!</h4>
                        <p style="margin:0 0 1rem; font-size: 0.85rem; color: #166534;">Anda dapat mengunduh dokumen resmi laporan harian kas dalam format PDF:</p>
                        <a href="{{ route('admin.laporan.pdf', $laporanHariIni->id) }}" class="btn-success">
                            Download Dokumen PDF Laporan Harian
                        </a>
                    </div>
                @endif

                <form action="{{ route('teller.laporan-harian') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Jumlah Uang Fisik Kas di Laci Teller (Rp)</label>
                        <input type="number" name="uang_fisik" class="form-control" required min="0" value="{{ $laporanHariIni->uang_fisik ?? ($totalSetoran - $totalPenarikan) }}" placeholder="Input hitungan uang fisik kas">
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; margin-top: 0.5rem; padding: 0.85rem;">
                        {{ $laporanHariIni ? 'Perbarui & Kirim Ulang ke Administrator' : 'Kirim Laporan Kas ke Administrator' }}
                    </button>
                </form>
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
        let selectedSaverData = null;

        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            const activeBtn = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.getAttribute('onclick').includes(tabId));
            if (activeBtn) activeBtn.classList.add('active');
            
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.classList.add('active');
        }

        function onMemberSelectChange() {
            const select = document.getElementById('aktivasiMemberSelect');
            const selectedOption = select.options[select.selectedIndex];
            const detailBox = document.getElementById('memberDetailBox');

            if (select.value) {
                document.getElementById('detNama').innerText = selectedOption.getAttribute('data-name');
                document.getElementById('detNisKelas').innerText = selectedOption.getAttribute('data-nis') + ' (' + selectedOption.getAttribute('data-class') + ')';
                document.getElementById('detAngkatan').innerText = selectedOption.getAttribute('data-year');
                detailBox.style.display = 'block';
            } else {
                detailBox.style.display = 'none';
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
            switchTab('tab-setoran');
            const sel = document.getElementById('setoranNasabahSelect');
            sel.value = selectedSaverData.id;
            document.getElementById('setoranNominal').focus();
        }

        function selectForPenarikan() {
            if (!selectedSaverData) return;
            switchTab('tab-penarikan');
            const sel = document.getElementById('penarikanNasabahSelect');
            sel.value = selectedSaverData.id;
            document.getElementById('penarikanNominal').focus();
        }

        function selectForResetPin() {
            if (!selectedSaverData) return;
            switchTab('tab-reset-pin');
            const sel = document.getElementById('resetPinSelect');
            sel.value = selectedSaverData.id;
        }

        function cetakStruk(noTrans, nama, noRek, jenis, nominal, waktu) {
            const printWindow = window.open('', '', 'width=450,height=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Struk Transaksi - \${noTrans}</title>
                    <style>
                        body { font-family: monospace; font-size: 11pt; padding: 20px; line-height: 1.5; }
                        .text-center { text-align: center; }
                        .hr { border-bottom: 1px dashed #000; margin: 10px 0; }
                    </style>
                </head>
                <body>
                    <div class="text-center">
                        <strong style="font-size: 13pt;">BANK MINI SEKOLAH</strong><br>
                        STRUK BUKTI TRANSAKSI TELLER<br>
                        ================================
                    </div>
                    <div class="hr"></div>
                    <div>Waktu      : \${waktu}</div>
                    <div>No Trx     : \${noTrans}</div>
                    <div>No Rek     : \${noRek}</div>
                    <div>Penabung   : \${nama}</div>
                    <div>Jenis Trx  : \${jenis.toUpperCase()}</div>
                    <div>Nominal    : Rp \${nominal}</div>
                    <div class="hr"></div>
                    <div class="text-center">
                        Simpan struk ini sebagai bukti transaksi yang sah.<br>
                        Terima kasih telah menabung di Bank Mini!
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); }, 250);
        }

        // ==========================================
        // FITUR SCANNER BARCODE & QR CODE
        // ==========================================
        let html5QrScanner = null;
        let isScannerRunning = false;

        async function openCameraModal() {
            const modal = document.getElementById('cameraModal');
            modal.classList.add('active');
            modal.style.display = 'flex';
            document.getElementById('scannerLaser').style.display = 'block';
            document.getElementById('scannerCorners').style.display = 'block';
            document.getElementById('scannerStatusMsg').innerText = 'Menghubungkan ke kamera...';

            await startCameraScanner();
        }

        async function closeCameraModal() {
            const modal = document.getElementById('cameraModal');
            modal.classList.remove('active');
            modal.style.display = 'none';
            await stopCameraScanner();
        }

        async function startCameraScanner(specificCameraId = null) {
            try {
                if (typeof Html5Qrcode === 'undefined') {
                    document.getElementById('scannerStatusMsg').innerText = 'Library pemindai sedang dimuat, mohon tunggu...';
                    setTimeout(() => startCameraScanner(specificCameraId), 400);
                    return;
                }

                if (html5QrScanner && isScannerRunning) {
                    await html5QrScanner.stop();
                    isScannerRunning = false;
                }

                if (!html5QrScanner) {
                    html5QrScanner = new Html5Qrcode("reader");
                }

                const select = document.getElementById('cameraSelect');
                let devices = [];
                try {
                    devices = await Html5Qrcode.getCameras();
                } catch (e) {
                    console.warn("Gagal mendapatkan daftar kamera:", e);
                }

                if (!devices || devices.length === 0) {
                    document.getElementById('scannerStatusMsg').innerHTML = '<span style="color: #ef4444;">Kamera tidak terdeteksi. Silakan gunakan opsi unggah foto barcode/QR atau tembakkan alat USB barcode scanner.</span>';
                    const btn = document.getElementById('btnToggleCamera');
                    if (btn) {
                        btn.innerText = 'Coba Lagi';
                        btn.style.background = 'var(--primary-gradient)';
                    }
                    return;
                }

                if (select.options.length <= 1) {
                    select.innerHTML = '';
                    devices.forEach((dev, idx) => {
                        const opt = document.createElement('option');
                        opt.value = dev.id;
                        opt.text = dev.label || `Kamera ${idx + 1}`;
                        select.appendChild(opt);
                    });
                }

                const selectedDevId = specificCameraId || (select.value || devices[0].id);

                const config = {
                    fps: 15,
                    qrbox: { width: 240, height: 240 },
                    aspectRatio: 1.0
                };

                await html5QrScanner.start(
                    selectedDevId,
                    config,
                    (decodedText) => {
                        onScanSuccess(decodedText);
                    },
                    (errorMessage) => {
                        // ignore unparsed frames
                    }
                );

                isScannerRunning = true;
                if (isCameraMirrored) {
                    document.getElementById('reader')?.classList.add('camera-mirrored');
                }
                const toggleBtn = document.getElementById('btnToggleCamera');
                if (toggleBtn) {
                    toggleBtn.innerText = 'Matikan Kamera';
                    toggleBtn.style.background = '#dc2626';
                }
                document.getElementById('scannerStatusMsg').innerText = 'Kamera aktif. Posisikan barcode atau QR siswa tepat di dalam kotak.';
            } catch (err) {
                console.error("Gagal start kamera:", err);
                isScannerRunning = false;
                document.getElementById('scannerStatusMsg').innerHTML = `<span style="color: #ef4444;">Gagal mengakses kamera: ${err.message || 'Izin kamera ditolak atau sedang digunakan aplikasi lain'}.</span>`;
                const toggleBtn = document.getElementById('btnToggleCamera');
                if (toggleBtn) {
                    toggleBtn.innerText = 'Nyalakan Kamera';
                    toggleBtn.style.background = 'var(--primary-gradient)';
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
                toggleBtn.style.background = 'var(--primary-gradient)';
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
                await startCameraScanner(select ? select.value : null);
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

        // Restore tab saat refresh / navigasi
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('month')) {
                switchTab('tab-bulanan');
            } else if (window.location.hash) {
                const hashTab = window.location.hash.substring(1);
                if (document.getElementById(hashTab)) {
                    switchTab(hashTab);
                }
            }
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
                <!-- Camera Select & Action -->
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                    <select id="cameraSelect" class="form-control" style="flex: 1; min-width: 180px; padding: 0.55rem 0.75rem; font-size: 0.85rem;">
                        <option value="">Memuat kamera perangkat...</option>
                    </select>
                    <button type="button" id="btnToggleCamera" onclick="toggleCameraScanner()" class="btn-primary" style="padding: 0.55rem 0.95rem; font-size: 0.85rem; background: #dc2626;">
                        Matikan Kamera
                    </button>
                    <button type="button" id="btnMirrorCamera" onclick="toggleCameraMirror()" class="btn-primary" style="padding: 0.55rem 0.95rem; font-size: 0.85rem; background: #475569;">
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
</body>
</html>
