<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Nasabah | E-Teller Bank Mini</title>
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
            z-index: 900;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.15rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .container {
            max-width: 1100px;
            width: 100%;
            margin: 1.5rem auto 3rem;
            padding: 0 1.5rem;
        }
        .nav-tabs {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.75rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.75rem;
            width: 100%;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 0.65rem 1.25rem;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .tab-btn:hover {
            color: var(--primary-600);
            background: #eff6ff;
        }
        .tab-btn.active {
            color: #ffffff;
            background: var(--primary-600);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.04);
            max-width: 100%;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .card h4 {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            margin: 0 0 0.5rem;
            letter-spacing: 0.5px;
        }
        .card .value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-600);
        }
        .btn-primary {
            background: var(--primary-600);
            color: #ffffff;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
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
        }
        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.4rem;
        }
        .form-control {
            width: 100%;
            padding: 0.65rem 0.95rem;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--primary-600);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
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
        .badge {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            text-align: center;
        }
        .badge-aktif { background: #dcfce7; color: #15803d; }
        .badge-tidak-aktif { background: #fee2e2; color: #b91c1c; }
        .badge-menunggu { background: #fef3c7; color: #b45309; }
        .badge-diproses { background: #dbeafe; color: #1d4ed8; }
        .badge-selesai { background: #dcfce7; color: #15803d; }
        
        .status-badge-buka {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 800;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .status-badge-istirahat {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 800;
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .status-badge-tutup {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 800;
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .alert {
            padding: 0.9rem 1.25rem;
            border-radius: 12px;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 480px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            position: relative;
        }

        /* RESPONSIVE STYLES UNTUK PORTAL NASABAH */
        .tab-label-short {
            display: none;
        }

        .filter-mutasi-bar {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .filter-mutasi-buttons {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }
        .filter-mutasi-search {
            flex: 1 1 240px;
            max-width: 320px;
            min-width: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem 1.25rem;
            }
            .container {
                padding: 0 1rem;
                margin: 1rem auto 1.5rem;
                padding-bottom: 5.5rem; /* Ruang bawah agar konten tidak tertutup bottom navigation bar */
            }
            .card {
                padding: 1.25rem;
                border-radius: 14px;
            }
            /* BOTTOM NAVIGATION BAR PERMANEN DI MOBILE */
            .nav-tabs {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                margin: 0;
                padding: 0.55rem 0.75rem calc(0.55rem + env(safe-area-inset-bottom, 0px));
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-top: 1px solid var(--border-color);
                border-bottom: none;
                box-shadow: 0 -4px 20px rgba(15, 23, 42, 0.08);
                display: flex;
                justify-content: space-around;
                align-items: center;
                gap: 0.35rem;
                flex-wrap: nowrap;
            }
            .tab-btn {
                flex: 1 1 0;
                padding: 0.65rem 0.35rem;
                font-size: 0.8rem;
                text-align: center;
                border-radius: 12px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .tab-btn.active {
                box-shadow: 0 2px 10px rgba(37, 99, 235, 0.25);
            }
            .filter-mutasi-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.65rem;
            }
            .filter-mutasi-buttons {
                width: 100%;
                justify-content: flex-start;
            }
            .filter-mutasi-search {
                max-width: 100%;
                width: 100%;
            }
            .grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 0.85rem;
            }
            .container {
                padding: 0 0.75rem;
                padding-bottom: 5.5rem;
            }
            .card {
                padding: 1rem;
            }
            .tab-label-full {
                display: none;
            }
            .tab-label-short {
                display: inline;
            }
            .nav-tabs {
                padding: 0.45rem 0.4rem calc(0.45rem + env(safe-area-inset-bottom, 0px));
                gap: 0.25rem;
            }
            .tab-btn {
                padding: 0.55rem 0.2rem;
                font-size: 0.72rem;
                letter-spacing: -0.2px;
            }
            .btn-filter-mutasi {
                flex: 1;
                text-align: center;
                padding: 0.45rem 0.5rem !important;
                font-size: 0.78rem !important;
            }
            .filter-mutasi-buttons {
                display: flex;
                width: 100%;
                gap: 0.35rem;
            }
            .modal-card {
                padding: 1.25rem;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <img src="{{ asset('images/logo_bank_mini.png') }}" alt="Logo Bank Mini" style="height: 44px; width: 44px; object-fit: contain; border-radius: 10px; border: 1px solid var(--border-color); background: #fff; padding: 2px; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);">
            <div>
                <h2 style="margin:0; font-size: 1.25rem; font-weight: 800; line-height: 1.2;">Nasabah</h2>
                <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap;">
            <div style="display: inline-flex; align-items: center; gap: 0.45rem;">
                <span style="font-weight: 700; color: #475569; font-size: 0.85rem;">Teller =</span>
                <div id="badgeStatusContainer" class="status-badge-{{ $statusLayanan }}">
                    <span id="labelStatusLayanan">{{ ucfirst($statusLayanan) }}</span>
                </div>
            </div>
            <span>Halo, <strong>{{ $member->student_name ?? $nasabah->nama_siswa ?? $user->nama_petugas }}</strong></span>
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
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @php
            $isAktif = ($member->status ?? '') === 'rekening aktif' && !empty($nasabah) && !empty($nasabah->no_rekening);
            $currentSaldo = (int)($nasabah->rekening->saldo ?? 0);
        @endphp

        <!-- NAVIGATION 4 TAB UTAMA (PERMANEN DI BAWAH PADA TAMPILAN MOBILE) -->
        <div class="nav-tabs">
            <button type="button" class="tab-btn active" id="btn-tab-dashboard" onclick="switchNasabahTab('tab-dashboard')">Dashboard</button>
            <button type="button" class="tab-btn" id="btn-tab-perjanjian" onclick="switchNasabahTab('tab-perjanjian')">
                <span class="tab-label-full">Perjanjian</span>
                <span class="tab-label-short">Perjanjian</span>
            </button>
            <button type="button" class="tab-btn" id="btn-tab-mutasi" onclick="switchNasabahTab('tab-mutasi')">
                <span class="tab-label-full">Laporan Bulanan & Target</span>
                <span class="tab-label-short">Laporan</span>
            </button>
            <button type="button" class="tab-btn" id="btn-tab-profil" onclick="switchNasabahTab('tab-profil')">
                <span class="tab-label-full">Profil & Pengaduan</span>
                <span class="tab-label-short">Profil</span>
            </button>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: DASHBOARD (Saldo, Antrean & Mutasi) -->
        <!-- ========================================== -->
        <div id="tab-dashboard" class="tab-content active">
            <!-- GRID IDENTITAS, REKENING & SALDO -->
            <div class="grid">
                <div class="card">
                    <h4>Identitas Penabung</h4>
                    <div class="value" style="font-size: 1.4rem; color: #0f172a; margin-bottom: 0.35rem;">
                        {{ $member->student_name ?? $nasabah->nama_siswa ?? $user->nama_petugas }}
                    </div>
                    <div style="font-size: 0.88rem; color: var(--text-muted); font-weight: 600;">
                        Jurusan: {{ $member->class_grade ?? $nasabah->kelas ?? '-' }} &bull; NIS: {{ $member->nis ?? $nasabah->nis ?? $user->username }}
                    </div>
                </div>
                <div class="card" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                    <div>
                        <h4>Nomor Rekening Nasabah</h4>
                        <div class="value" style="font-size: 1.4rem;">
                            @if($isAktif)
                                <code>{{ $nasabah->no_rekening }}</code>
                            @else
                                <span style="color: #ef4444; font-size: 1.15rem; font-weight: 700;">Belum Aktif</span>
                            @endif
                        </div>
                        @if($isAktif)
                            <button onclick="openQrModal()" style="margin-top: 0.75rem; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">Lihat Kode QR</button>
                        @else
                            <span style="font-size: 0.78rem; color: #64748b; display: block; margin-top: 0.5rem;">Menunggu aktivasi rekening</span>
                        @endif
                    </div>
                    @if($isAktif)
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=5&data={{ $nasabah->no_rekening }}" alt="QR Code" style="width: 70px; height: 70px; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer;" onclick="openQrModal()" title="Klik untuk memperbesar QR">
                    @else
                        <div style="width: 70px; height: 70px; border-radius: 8px; border: 2px dashed #cbd5e1; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.72rem; text-align: center; line-height: 1.2; padding: 4px;">
                            QR Belum Ada
                        </div>
                    @endif
                </div>
                <div class="card">
                    <h4>Saldo Tabungan Real-Time</h4>
                    <div class="value" style="color: {{ $isAktif ? '#16a34a' : '#94a3b8' }}; font-size: 1.6rem;">
                        Rp {{ number_format($currentSaldo, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 0.82rem; font-weight: 700; margin-top: 0.35rem;">
                        @if($isAktif)
                            <span style="color: #16a34a;">Status: Rekening Aktif</span>
                        @else
                            <span style="color: #ef4444;">Status: Rekening Belum Aktif</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- BANNER INFO KETENTUAN PENARIKAN & JANJI TARIK (> 1 JT) -->
            <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border: 1px solid #bfdbfe; border-left: 5px solid #2563eb;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #2563eb; color: #fff; font-size: 0.72rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 6px;">INFO PENARIKAN</span>
                            <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">Aturan Kas Teller</span>
                        </div>
                        <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-top: 0.3rem;">
                            Tarik s/d Rp 1.000.000 Langsung di Loket &bull; Di atas Rp 1 Juta Wajib Perjanjian H-1
                        </div>
                        <div style="font-size: 0.84rem; color: #475569; margin-top: 0.25rem;">
                            Penarikan &le; Rp 1.000.000 tidak butuh izin admin/perjanjian. Sisa saldo tabungan wajib mengendap minimal Rp 10.000.
                        </div>
                    </div>
                    <div>
                        <button type="button" onclick="switchNasabahTab('tab-perjanjian')" class="btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.88rem; font-weight: 700; white-space: nowrap;">
                            Ajukan Perjanjian &rarr;
                        </button>
                    </div>
                </div>
            </div>

            <!-- LIVE STATUS & SISTEM ANTREAN BANK MINI -->
            <div class="card" style="border-left: 5px solid var(--primary-600); margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h4 style="margin:0; font-size: 0.8rem; color: var(--text-muted);">Sistem Antrean Loket Bank Mini</h4>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #1e293b; margin-top: 0.25rem;">
                            Sedang Dilayani: <span style="color: var(--primary-600);" id="liveSedangDilayani">{{ $antreanSedangDilayani ? $antreanSedangDilayani->nomor_antrean : 'Belum Ada Panggilan' }}</span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;">
                            Total antrean yang sedang menunggu hari ini: <strong id="liveTotalMenunggu">{{ $totalAntreanMenunggu }} orang</strong>
                        </div>
                    </div>
                    <div>
                        @if($antreanSaya)
                            <div style="text-align: right; background: #eff6ff; padding: 0.75rem 1.25rem; border-radius: 12px; border: 1px solid #bfdbfe;">
                                <div style="font-size: 0.78rem; font-weight: 700; color: var(--primary-600); text-transform: uppercase;">Nomor Antrean Anda</div>
                                <div style="font-size: 1.6rem; font-weight: 800; color: #1e293b;">{{ $antreanSaya->nomor_antrean }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                                    @if($antreanSaya->status === 'dilayani')
                                        <span style="color: #16a34a; font-weight: 800;">Silakan menuju loket Teller sekarang</span>
                                    @else
                                        Menunggu (Sisa: {{ $antreanSebelumSaya }} orang sebelum Anda)
                                    @endif
                                </div>
                            </div>
                        @else
                            <form action="{{ route('nasabah.ambil-antrean') }}" method="POST" style="margin:0;" onsubmit="return confirm('Konfirmasi: Ambil nomor antrean loket Bank Mini hari ini?');">
                                @csrf
                                <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.95rem;">Ambil Nomor Antrean Hari Ini</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TABEL RIWAYAT MUTASI REKENING (PINDAH KE DASHBOARD) -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin:0; font-size: 1.15rem; color: #1e293b;">Riwayat Mutasi Rekening</h3>
                        <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-muted);">Daftar seluruh transaksi setoran dan penarikan yang pernah dilakukan.</p>
                    </div>
                </div>

                @if(!$isAktif)
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem; color: #b91c1c; font-size: 0.9rem; margin-bottom: 1rem;">
                        <strong>Pemberitahuan:</strong> Rekening tabungan Anda saat ini belum aktif. Silakan lakukan aktivasi di loket Teller untuk mendapatkan nomor rekening dan mulai menabung.
                    </div>
                @endif

                <div class="filter-mutasi-bar">
                    <div class="filter-mutasi-buttons">
                        <button type="button" class="btn-filter-mutasi active" onclick="filterMutasi('semua')" id="btn-mutasi-semua" style="border: 1px solid var(--border-color); background: #0f172a; color: #fff; padding: 0.4rem 0.85rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                            Semua ({{ $transaksi->count() }})
                        </button>
                        <button type="button" class="btn-filter-mutasi" onclick="filterMutasi('setoran')" id="btn-mutasi-setoran" style="border: 1px solid var(--border-color); background: #fff; color: #334155; padding: 0.4rem 0.85rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                            Setoran ({{ $transaksi->where('jenis_trans', 'setoran')->count() }})
                        </button>
                        <button type="button" class="btn-filter-mutasi" onclick="filterMutasi('penarikan')" id="btn-mutasi-penarikan" style="border: 1px solid var(--border-color); background: #fff; color: #334155; padding: 0.4rem 0.85rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                            Penarikan ({{ $transaksi->where('jenis_trans', 'penarikan')->count() }})
                        </button>
                    </div>
                    <div class="filter-mutasi-search">
                        <input type="text" id="searchMutasi" class="form-control" style="padding: 0.45rem 0.85rem; font-size: 0.85rem; width: 100%;" placeholder="Cari No Transaksi, Tanggal..." oninput="searchMutasiTable()">
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu Transaksi</th>
                                <th>No Transaksi</th>
                                <th>Jenis Transaksi</th>
                                <th>Nominal Transaksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tableMutasiBody">
                            @forelse($transaksi as $t)
                                <tr class="mutasi-row" data-jenis="{{ strtolower($t->jenis_trans) }}">
                                    <td>{{ $t->tanggal ? \Carbon\Carbon::parse($t->tanggal)->format('Y-m-d H:i:s') : '-' }}</td>
                                    <td><code>{{ $t->no_transaksi }}</code></td>
                                    <td>
                                        @if($t->jenis_trans === 'setoran')
                                            <span style="color: #16a34a; font-weight: 700;">SETORAN</span>
                                        @else
                                            <span style="color: #dc2626; font-weight: 700;">PENARIKAN</span>
                                        @endif
                                    </td>
                                    <td style="font-weight: 700; color: {{ $t->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                        {{ $t->jenis_trans === 'setoran' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $t->keterangan ?: ($t->jenis_trans === 'setoran' ? 'Setoran Tunai Teller' : 'Penarikan Tunai Teller') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                        Belum ada riwayat transaksi mutasi.
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="emptyMutasiSearch" style="display:none;">
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                    Tidak ada transaksi yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: PERJANJIAN PENARIKAN                              -->
        <!-- ======================================================== -->
        <div id="tab-perjanjian" class="tab-content">
            <!-- FORM AJUKAN PERJANJIAN & DAFTAR STATUS PERJANJIAN -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; align-items: start;">
                <!-- FORM PENGAJUAN -->
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom: 0.5rem; font-size: 1.15rem; color: #1e293b;">
                        Form Pengajuan Perjanjian
                    </h3>
                    <p style="margin: 0 0 1.25rem; font-size: 0.85rem; color: var(--text-muted);">
                        Isi formulir ini jika Anda berencana menarik saldo lebih dari Rp 1.000.000 di loket Teller.
                    </p>

                    @if(!$isAktif)
                        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem; color: #b91c1c; font-size: 0.88rem; margin-bottom: 1rem;">
                            Rekening Anda belum aktif. Aktivasi rekening terlebih dahulu di loket Teller untuk dapat mengajukan penarikan.
                        </div>
                    @else
                        @php
                            $maxTarikNasabah = max(0, $currentSaldo - 10000);
                        @endphp
                        
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.25rem;">
                            <div style="font-size: 0.8rem; color: #166534; font-weight: 700;">INFORMASI SALDO ANDA</div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.35rem; flex-wrap: wrap; gap: 0.5rem;">
                                <div>Saldo Saat Ini: <strong style="color: #15803d; font-size: 1.05rem;">Rp {{ number_format($currentSaldo, 0, ',', '.') }}</strong></div>
                                <div>Maksimal Bisa Ditarik: <strong style="color: #0f172a; font-size: 1.05rem;">Rp {{ number_format($maxTarikNasabah, 0, ',', '.') }}</strong></div>
                            </div>
                            <div style="font-size: 0.75rem; color: #166534; margin-top: 0.35rem;">* Sudah memperhitungkan sisa saldo wajib mengendap Rp 10.000.</div>
                        </div>

                        <form action="{{ route('nasabah.perjanjian-penarikan.store') }}" method="POST" onsubmit="try{ sessionStorage.setItem('nasabah_active_tab', 'tab-perjanjian'); }catch(e){} return confirm('Konfirmasi: Ajukan permohonan perjanjian penarikan saldo ini ke pihak Teller Bank Mini?');">
                            @csrf
                            <div class="form-group">
                                <label>Nominal Penarikan (Rp) <span style="color: #dc2626;">*</span></label>
                                <input type="number" name="nominal" id="inputPjNominal" class="form-control" required min="1000001" placeholder="Contoh: 1500000 (Min. Rp 1.000.001)" value="{{ old('nominal') }}">
                                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.35rem;">
                                    Wajib di atas Rp 1.000.000 (minimal Rp 1.000.001).
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Rencana Pengambilan di Loket <span style="color: #dc2626;">*</span></label>
                                <input type="date" name="tanggal_rencana" id="inputPjTanggal" class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('tanggal_rencana', date('Y-m-d', strtotime('+1 day'))) }}">
                                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.35rem;">
                                    Terkunci otomatis minimal besok (H-1) sesuai kebijakan ketersediaan kas fisik.
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Keperluan / Alasan Penarikan <span style="color: #dc2626;">*</span></label>
                                <textarea name="alasan" id="inputPjAlasan" class="form-control" rows="3" required placeholder="Contoh: Biaya sertifikasi kompetensi, pembelian laptop praktik, keperluan sekolah...">{{ old('alasan') }}</textarea>
                            </div>

                            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; font-weight: 700;">
                                Ajukan Perjanjian ke Teller
                            </button>
                        </form>
                    @endif
                </div>

                <!-- DAFTAR RIWAYAT PERJANJIAN PENARIKAN SAYA -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h3 style="margin:0; font-size: 1.15rem; color: #1e293b;">Daftar Perjanjian Penarikan</h3>
                            <p style="margin: 0.25rem 0 0; font-size: 0.82rem; color: var(--text-muted);">Status permohonan penarikan dana di atas 1 juta rupiah.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal Rencana</th>
                                    <th>Nominal</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Catatan / Arahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perjanjianSaya as $pj)
                                    <tr>
                                        <td>
                                            <strong>{{ date('d M Y', strtotime($pj->tanggal_rencana)) }}</strong>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">Diajukan: {{ $pj->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td>
                                            <strong style="color: #dc2626; font-size: 0.95rem;">Rp {{ number_format($pj->nominal, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.85rem; max-width: 180px; word-break: break-word;">{{ $pj->alasan }}</div>
                                        </td>
                                        <td>
                                            @if($pj->status === 'disetujui')
                                                <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 800;">DISETUJUI</span>
                                                <div style="font-size: 0.72rem; color: #16a34a; margin-top: 0.25rem;">Siap cair di loket</div>
                                            @elseif($pj->status === 'selesai')
                                                <span class="badge" style="background: #e0e7ff; color: #3730a3; font-weight: 800;">SELESAI</span>
                                                <div style="font-size: 0.72rem; color: #4338ca; margin-top: 0.25rem;">Telah ditarik</div>
                                            @elseif($pj->status === 'ditolak')
                                                <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 800;">DITOLAK</span>
                                            @else
                                                <span class="badge" style="background: #fef3c7; color: #92400e; font-weight: 800;">MENUNGGU</span>
                                                <div style="font-size: 0.72rem; color: #b45309; margin-top: 0.25rem;">Menunggu teller</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pj->catatan_teller)
                                                <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 0.35rem 0.6rem; border-radius: 8px; font-size: 0.82rem; color: #334155;">
                                                    {{ $pj->catatan_teller }}
                                                </div>
                                            @elseif($pj->status === 'disetujui')
                                                <span style="font-size: 0.8rem; color: #16a34a; font-weight: 600;">Silakan datang ke loket pada {{ date('d M Y', strtotime($pj->tanggal_rencana)) }}</span>
                                            @else
                                                <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                            Belum ada pengajuan perjanjian penarikan dana.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: LAPORAN BULANAN & TARGET TABUNGAN   -->
        <!-- ========================================== -->
        <div id="tab-mutasi" class="tab-content">
            <!-- LAPORAN KEUANGAN BULANAN (RINGKASAN AKHIR BULAN) -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                    <div>
                        <h3 style="margin:0; font-size: 1.15rem; color: #1e293b;">Ringkasan Keuangan Bulanan</h3>
                        <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-muted);">Rangkuman mutasi keuangan tabungan Anda per periode bulan.</p>
                    </div>
                    <form method="GET" action="{{ route('nasabah.dashboard') }}" style="display: flex; gap: 0.5rem; align-items: center; margin:0;">
                        <select name="bulan" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem; width: auto;" onchange="this.form.submit()">
                            @for($b = 1; $b <= 12; $b++)
                                <option value="{{ $b }}" {{ $selectedBulan === $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $b, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <select name="tahun" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem; width: auto;" onchange="this.form.submit()">
                            @for($t = date('Y'); $t >= date('Y') - 3; $t--)
                                <option value="{{ $t }}" {{ $selectedTahun === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </form>
                </div>

                <div class="grid" style="margin-bottom: 0;">
                    <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Saldo Awal Bulan</div>
                        <div style="font-size: 1.3rem; font-weight: 800; color: #1e293b; margin-top: 0.35rem;">
                            Rp {{ number_format($saldoAwalBulan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 1.25rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #166534; text-transform: uppercase;">Total Setoran Masuk (+)</div>
                        <div style="font-size: 1.3rem; font-weight: 800; color: #15803d; margin-top: 0.35rem;">
                            + Rp {{ number_format($totalSetoranBulanIni, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 14px; padding: 1.25rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #991b1b; text-transform: uppercase;">Total Penarikan Keluar (-)</div>
                        <div style="font-size: 1.3rem; font-weight: 800; color: #b91c1c; margin-top: 0.35rem;">
                            - Rp {{ number_format($totalPenarikanBulanIni, 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px; padding: 1.25rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary-600); text-transform: uppercase;">Saldo Akhir Bulan</div>
                        <div style="font-size: 1.3rem; font-weight: 800; color: var(--primary-600); margin-top: 0.35rem;">
                            Rp {{ number_format($saldoAkhirBulan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- FITUR: TARGET TABUNGAN (PINDAH KE LAPORAN BULAN) -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin:0; font-size: 1.15rem; color: #1e293b;">Target Tabungan Saya</h3>
                        <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-muted);">Kelola target tabungan untuk memotivasi perencanaan keuangan Anda.</p>
                    </div>
                    <button type="button" onclick="openTargetModal()" class="btn-primary">
                        {{ $targetTabungan ? 'Perbarui Target' : 'Pasang Target Baru' }}
                    </button>
                </div>

                @if($targetTabungan)
                    @php
                        $nominalTarget = $targetTabungan->nominal_target > 0 ? $targetTabungan->nominal_target : 1;
                        $persen = min(100, round(($currentSaldo / $nominalTarget) * 100, 1));
                        $sisa = max(0, $targetTabungan->nominal_target - $currentSaldo);
                    @endphp
                    <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <span style="font-size: 1.1rem; font-weight: 800; color: #1e293b;">{{ $targetTabungan->judul_target }}</span>
                            <span class="badge {{ $persen >= 100 ? 'badge-aktif' : 'badge-diproses' }}">
                                {{ $persen >= 100 ? 'Target Tercapai' : 'Sedang Berjalan (' . $persen . '%)' }}
                            </span>
                        </div>

                        <!-- PROGRESS BAR SEDERHANA & ELEGAN -->
                        <div style="background: #e2e8f0; height: 12px; border-radius: 999px; overflow: hidden; margin: 0.75rem 0;">
                            <div style="background: {{ $persen >= 100 ? '#16a34a' : 'var(--primary-600)' }}; width: {{ $persen }}%; height: 100%; transition: width 0.5s ease;"></div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; font-size: 0.88rem; color: #475569;">
                            <div>
                                Saldo Saat Ini: <strong>Rp {{ number_format($currentSaldo, 0, ',', '.') }}</strong> dari Target: <strong>Rp {{ number_format($targetTabungan->nominal_target, 0, ',', '.') }}</strong>
                            </div>
                            <div>
                                @if($sisa > 0)
                                    Sisa yang dibutuhkan: <strong style="color: #b91c1c;">Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
                                @else
                                    <strong style="color: #16a34a;">Selamat! Target tabungan Anda sudah tercapai.</strong>
                                @endif
                            </div>
                        </div>

                        @if($targetTabungan->keterangan)
                            <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.65rem;">
                                Catatan: {{ $targetTabungan->keterangan }}
                            </div>
                        @endif

                        <div style="margin-top: 1rem; text-align: right;">
                            <form action="{{ route('nasabah.target-tabungan.delete', $targetTabungan->id) }}" method="POST" onsubmit="return confirm('Hapus target tabungan ini?')" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-secondary" style="color: #dc2626; padding: 0.35rem 0.75rem; font-size: 0.8rem;">Hapus Target</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem 1rem; background: #f8fafc; border: 1px dashed var(--border-color); border-radius: 14px;">
                        <p style="margin: 0; font-size: 0.9rem; color: var(--text-muted);">Belum ada target tabungan yang dipasang. Pasang target tabungan untuk memantau perkembangan impian Anda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: PROFIL & PENGADUAN KE ADMIN          -->
        <!-- ========================================== -->
        <div id="tab-profil" class="tab-content">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <!-- BIODATA RESMI SISWA -->
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom: 1.25rem; font-size: 1.15rem; color: #1e293b;">Data Resmi Siswa</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.9rem;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">Nama Lengkap:</span>
                            <strong>{{ $member->student_name ?? $user->nama_petugas }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">NIS (Nomor Induk):</span>
                            <code>{{ $member->nis ?? $user->username }}</code>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">Jurusan / Kelas:</span>
                            <strong>{{ $member->class_grade ?? '-' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">Tahun Kelulusan:</span>
                            <strong>{{ $member->graduation_year ?? '-' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">Alamat Gmail:</span>
                            <span>{{ $member->email ?? $user->email ?? '-' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">Nomor Rekening:</span>
                            <strong>{{ $nasabah->no_rekening ?? 'Belum Aktif' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 0.25rem;">
                            <span style="color: var(--text-muted);">Status Rekening:</span>
                            <span class="badge {{ $isAktif ? 'badge-aktif' : 'badge-tidak-aktif' }}">
                                {{ $isAktif ? 'Aktif' : 'Belum Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- FORM GANTI KATA SANDI MANDIRI -->
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom: 0.5rem; font-size: 1.15rem; color: #1e293b;">Keamanan Akun</h3>
                    <p style="margin: 0 0 1.25rem; font-size: 0.85rem; color: var(--text-muted);">Perbarui kata sandi akun login Anda secara berkala.</p>

                    @if(session('success_password'))
                        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.88rem; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                            <span style="background: #10b981; color: white; width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">✓</span>
                            <span>{{ session('success_password') }}</span>
                        </div>
                    @endif

                    @if(session('error_password'))
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.88rem; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                            <span style="background: #ef4444; color: white; width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">!</span>
                            <span>{{ session('error_password') }}</span>
                        </div>
                    @endif

                    @if($errors->any() && (old('sandi_lama') || old('sandi_baru') || session()->has('error_password')))
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.85rem; margin-bottom: 1.25rem;">
                            <strong>Gagal memperbarui kata sandi:</strong>
                            <ul style="margin: 4px 0 0; padding-left: 1.25rem;">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('nasabah.ganti-password') }}" method="POST" onsubmit="try{ sessionStorage.setItem('nasabah_active_tab', 'tab-profil'); }catch(e){} return confirm('Konfirmasi: Apakah Anda yakin ingin memperbarui kata sandi akun?');">
                        @csrf
                        <div class="form-group">
                            <label>Kata Sandi Saat Ini</label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_lama" id="inputSandiLama" class="form-control" required placeholder="Masukkan kata sandi lama" style="padding-right: 2.75rem; {{ session('error_password') ? 'border: 2px solid #dc2626; background: #fff5f5;' : '' }}">
                                <button type="button" onclick="togglePasswordVisibility('inputSandiLama', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Kata Sandi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            @if(session('error_password'))
                                <div style="margin-top: 6px; color: #dc2626; font-size: 0.82rem; font-weight: 700;">
                                    ⚠️ Kata sandi saat ini yang Anda masukkan salah.
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Kata Sandi Baru (Min. 6 Karakter)</label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_baru" id="inputSandiBaru" class="form-control" required minlength="6" placeholder="Masukkan kata sandi baru" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('inputSandiBaru', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Kata Sandi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Kata Sandi Baru</label>
                            <div style="position: relative;">
                                <input type="password" name="sandi_baru_confirmation" id="inputSandiBaruConfirm" class="form-control" required minlength="6" placeholder="Ulangi kata sandi baru" style="padding-right: 2.75rem;">
                                <button type="button" onclick="togglePasswordVisibility('inputSandiBaruConfirm', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #64748b; transition: color 0.2s;" title="Lihat Kata Sandi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%;">Simpan Kata Sandi Baru</button>
                    </form>
                </div>
            </div>

            <!-- FITUR: LAYANAN PENGADUAN NASABAH KE ADMIN -->
            <div class="card" style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin:0; font-size: 1.15rem; color: #1e293b;">Pengaduan & Bantuan Nasabah</h3>
                        <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-muted);">Sampaikan pertanyaan, kendala transaksi, atau koreksi data langsung ke Administrator.</p>
                    </div>
                    <button type="button" onclick="openPengaduanModal()" class="btn-primary">
                        Buat Pengaduan Baru
                    </button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Judul Pengaduan</th>
                                <th>Status</th>
                                <th>Tanggapan Administrator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengaduanList as $p)
                                <tr>
                                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                    <td><span style="font-weight: 600;">{{ $p->kategori }}</span></td>
                                    <td>
                                        <strong>{{ $p->judul }}</strong>
                                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            {{ \Illuminate\Support\Str::limit($p->isi_pesan, 70) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($p->status === 'selesai')
                                            <span class="badge badge-selesai">Selesai</span>
                                        @elseif($p->status === 'diproses')
                                            <span class="badge badge-diproses">Diproses</span>
                                        @else
                                            <span class="badge badge-menunggu">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->tanggapan_admin)
                                            <div style="background: #f8fafc; border: 1px solid var(--border-color); padding: 0.5rem 0.75rem; border-radius: 8px; font-size: 0.85rem;">
                                                {{ $p->tanggapan_admin }}
                                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">
                                                    {{ $p->ditanggapi_pada ? $p->ditanggapi_pada->format('d/m/Y H:i') : '' }}
                                                </div>
                                            </div>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.85rem;">Belum ada tanggapan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">
                                        Belum ada pengaduan yang diajukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PASANG / EDIT TARGET TABUNGAN -->
    <div class="modal-overlay" id="targetModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin:0;">
                    {{ $targetTabungan ? 'Perbarui Target Tabungan' : 'Pasang Target Tabungan' }}
                </h3>
                <button type="button" onclick="closeTargetModal()" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <form action="{{ route('nasabah.target-tabungan.store') }}" method="POST" onsubmit="return confirm('Konfirmasi: Simpan target tabungan impian baru?');">
                @csrf
                <div class="form-group">
                    <label>Nama Target / Impian *</label>
                    <input type="text" name="judul_target" class="form-control" required placeholder="Contoh: Beli Laptop PKL" value="{{ $targetTabungan->judul_target ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Nominal Target Tabungan (Rp) *</label>
                    <input type="number" name="nominal_target" class="form-control" required min="10000" placeholder="Contoh: 2000000" value="{{ $targetTabungan->nominal_target ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Target Tanggal Selesai (Opsional)</label>
                    <input type="date" name="target_tanggal" class="form-control" value="{{ $targetTabungan && $targetTabungan->target_tanggal ? $targetTabungan->target_tanggal->format('Y-m-d') : '' }}">
                </div>
                <div class="form-group">
                    <label>Catatan Tambahan (Opsional)</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Disisihkan dari uang saku mingguan" value="{{ $targetTabungan->keterangan ?? '' }}">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeTargetModal()" class="btn-secondary" style="flex: 1;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2;">Simpan Target</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL BUAT PENGADUAN BARU -->
    <div class="modal-overlay" id="pengaduanModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin:0;">Form Pengaduan Nasabah</h3>
                <button type="button" onclick="closePengaduanModal()" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <form action="{{ route('nasabah.pengaduan.store') }}" method="POST" onsubmit="return confirm('Konfirmasi: Kirimkan pesan pengaduan ini ke pihak Bank Mini?');">
                @csrf
                <div class="form-group">
                    <label>Kategori Masalah *</label>
                    <select name="kategori" class="form-control" required>
                        <option value="Kendala Saldo & Mutasi">Kendala Saldo & Mutasi Transaksi</option>
                        <option value="Layanan Loket Teller">Layanan Loket Teller</option>
                        <option value="Pembaruan Biodata Siswa">Pembaruan / Koreksi Data Siswa</option>
                        <option value="Kendala Login / Sandi">Kendala Login / Akun</option>
                        <option value="Saran & Masukan">Saran & Masukan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Judul Pengaduan *</label>
                    <input type="text" name="judul" class="form-control" required placeholder="Contoh: Saldo setoran belum tercatat di mutasi">
                </div>
                <div class="form-group">
                    <label>Isi Pesan Detail *</label>
                    <textarea name="isi_pesan" rows="4" class="form-control" required placeholder="Tuliskan detail permasalahan atau pertanyaan Anda secara jelas..."></textarea>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closePengaduanModal()" class="btn-secondary" style="flex: 1;">Batal</button>
                    <button type="submit" class="btn-primary" style="flex: 2;">Kirim Pengaduan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL QR CODE NASABAH -->
    <div class="modal-overlay" id="qrModal">
        <div class="modal-card" style="text-align: center;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin:0;">Kode QR Kartu Digital Nasabah</h3>
                <button type="button" onclick="closeQrModal()" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>
            @if($isAktif)
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Tunjukkan Kode QR ini kepada Petugas Teller untuk melakukan transaksi setoran atau penarikan di loket.
                </p>
                <div style="display: inline-block; padding: 12px; background: #ffffff; border: 2px solid var(--border-color); border-radius: 16px;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=10&data={{ $nasabah->no_rekening }}" alt="QR Code" style="width: 220px; height: 220px; display: block;">
                </div>
                <div style="margin-top: 1.25rem;">
                    <div style="font-size: 1.1rem; font-weight: 800; color: #1e293b;">{{ $member->student_name ?? $nasabah->nama_siswa ?? $user->nama_petugas }}</div>
                    <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">No Rek: <code>{{ $nasabah->no_rekening }}</code> &bull; NIS: {{ $member->nis ?? $nasabah->nis ?? $user->username }}</div>
                </div>
            @else
                <p style="color: #ef4444; font-weight: 700;">Rekening belum aktif. Silakan aktivasi rekening di loket Teller terlebih dahulu.</p>
            @endif
            <div style="margin-top: 1.5rem;">
                <button type="button" onclick="closeQrModal()" class="btn-secondary" style="width: 100%;">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function switchNasabahTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            const targetBtn = document.getElementById('btn-' + tabId);
            const targetContent = document.getElementById(tabId);

            if (targetBtn) targetBtn.classList.add('active');
            if (targetContent) targetContent.classList.add('active');

            try {
                sessionStorage.setItem('nasabah_active_tab', tabId);
            } catch(e) {}
        }

        document.addEventListener('DOMContentLoaded', () => {
            try {
                @if(session('success_password') || session('error_password'))
                    switchNasabahTab('tab-profil');
                    return;
                @endif
                @if(old('nominal') || (session('success') && str_contains(session('success'), 'perjanjian')))
                    switchNasabahTab('tab-perjanjian');
                    return;
                @endif
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('bulan') || urlParams.has('tahun')) {
                    switchNasabahTab('tab-mutasi');
                    return;
                }
                const savedTab = sessionStorage.getItem('nasabah_active_tab');
                if (savedTab && document.getElementById(savedTab)) {
                    switchNasabahTab(savedTab);
                }
            } catch(e) {}
        });

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

        function openQrModal() {
            document.getElementById('qrModal').style.display = 'flex';
        }
        function closeQrModal() {
            document.getElementById('qrModal').style.display = 'none';
        }

        function openTargetModal() {
            document.getElementById('targetModal').style.display = 'flex';
        }
        function closeTargetModal() {
            document.getElementById('targetModal').style.display = 'none';
        }

        function openPengaduanModal() {
            document.getElementById('pengaduanModal').style.display = 'flex';
        }
        function closePengaduanModal() {
            document.getElementById('pengaduanModal').style.display = 'none';
        }

        // Filter & Pencarian Mutasi
        let currentMutasiJenis = 'semua';

        function filterMutasi(jenis) {
            currentMutasiJenis = jenis;

            document.querySelectorAll('.btn-filter-mutasi').forEach(btn => {
                btn.style.background = '#fff';
                btn.style.color = '#334155';
            });
            const activeBtn = document.getElementById('btn-mutasi-' + jenis);
            if (activeBtn) {
                activeBtn.style.background = '#0f172a';
                activeBtn.style.color = '#fff';
            }

            applyMutasiFilter();
        }

        function searchMutasiTable() {
            applyMutasiFilter();
        }

        function applyMutasiFilter() {
            const query = (document.getElementById('searchMutasi')?.value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('#tableMutasiBody tr.mutasi-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const rJenis = (row.getAttribute('data-jenis') || '').toLowerCase().trim();
                const text = row.innerText.toLowerCase();

                const matchJenis = (currentMutasiJenis === 'semua' || rJenis === currentMutasiJenis);
                const matchQuery = (!query || text.includes(query));

                if (matchJenis && matchQuery) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptySearch = document.getElementById('emptyMutasiSearch');
            if (emptySearch) {
                emptySearch.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        // Auto-polling status antrean dan teller secara berkala
        setInterval(() => {
            fetch('/nasabah/status-antrean')
                .then(res => res.json())
                .then(data => {
                    const badgeContainer = document.getElementById('badgeStatusContainer');
                    const badgeLabel = document.getElementById('labelStatusLayanan');
                    if (badgeContainer && badgeLabel && data.status_layanan) {
                        badgeContainer.className = 'status-badge-' + data.status_layanan;
                        badgeLabel.innerText = data.status_layanan.charAt(0).toUpperCase() + data.status_layanan.slice(1);
                    }

                    const liveSedang = document.getElementById('liveSedangDilayani');
                    if (liveSedang) {
                        liveSedang.innerText = data.sedang_dilayani || 'Belum Ada Panggilan';
                    }

                    const liveTotal = document.getElementById('liveTotalMenunggu');
                    if (liveTotal) {
                        liveTotal.innerText = (data.total_menunggu || 0) + ' orang';
                    }
                })
                .catch(() => {});
        }, 5000);
    </script>
</body>
</html>
