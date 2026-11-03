<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrator | E-Teller Bank Mini</title>
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
            --amber-600: #d97706;
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
            max-width: 1380px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .grid-split {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) minmax(360px, 2fr);
            gap: 1.75rem;
            width: 100%;
        }
        @media (max-width: 960px) {
            .grid-split {
                grid-template-columns: 1fr;
            }
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
        .btn-success:hover { background: #15803d; }
        .btn-warning {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-danger {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        .nav-tabs {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 0.35rem;
            margin-bottom: 1.75rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
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
        .sub-badge-danger {
            background: #ef4444;
            color: #ffffff;
        }
        .sub-badge-warning {
            background: #f59e0b;
            color: #ffffff;
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
            padding: 0.85rem 1.15rem;
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
        .badge { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
        .badge-aktif { background: #dcfce7; color: #15803d; }
        .badge-tidak-aktif { background: #fee2e2; color: #b91c1c; }
        .badge-terblokir { background: #fef2f2; color: #b91c1c; }
        .badge-role-admin { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .badge-role-teller { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-role-nasabah { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
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
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 520px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            position: relative;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <h2 style="margin:0; font-size: 1.25rem; font-weight: 800;">Administrator</h2>
            <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah</p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span>Halo, <strong>{{ $user->nama_petugas }}</strong> <span class="badge badge-aktif">ADMIN</span></span>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout" onclick="try{sessionStorage.removeItem('admin_active_tab');localStorage.removeItem('admin_active_tab');}catch(e){}">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="container">
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

        <!-- BANNER PERMINTAAN TAMBAHAN KAS TELLER MENUNGGU APPROVAL (LANGSUNG TAMPIL DI PALING ATAS) -->
        @php $pendingKasTop = $pengajuanKasList->where('status', 'pending'); @endphp
        @if($pendingKasTop->count() > 0)
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #f59e0b; border-radius: 16px; padding: 1.15rem 1.35rem; margin-bottom: 1.75rem; box-shadow: 0 4px 16px rgba(245, 158, 11, 0.12);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <span style="background: #f59e0b; color: #fff; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">!</span>
                        <h4 style="margin: 0; color: #92400e; font-size: 1.05rem; font-weight: 800;">
                            {{ $pendingKasTop->count() }} Pengajuan Tambahan Kas Teller Menunggu Persetujuan
                        </h4>
                    </div>
                    <span style="font-size: 0.75rem; background: #fef3c7; color: #92400e; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 999px; border: 1px solid #fcd34d;">
                        AKSI CEPAT ADMINISTRATOR
                    </span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
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
                                <button type="submit" class="btn-success" style="padding: 0.45rem 1rem; font-size: 0.85rem; border-radius: 8px;">
                                    ✓ ACC Kas
                                </button>
                            </form>
                            <button type="button" class="btn-danger" style="padding: 0.45rem 0.85rem; font-size: 0.85rem; border-radius: 8px;" onclick="openRejectPengajuanModal({{ $pkTop->id }}, '{{ addslashes($pkTop->teller->nama_petugas ?? 'Teller') }}', '{{ number_format($pkTop->nominal, 0, ',', '.') }}')">
                                Tolak
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid">
            <div class="card">
                <h4>Total Pengguna</h4>
                <div class="value">{{ $totalUsers }}</div>
            </div>
            <div class="card">
                <h4>Total Anggota Siswa</h4>
                <div class="value" style="color: #6366f1;">{{ $totalMembers }}</div>
            </div>
            <div class="card">
                <h4>Total Penabung Aktif</h4>
                <div class="value" style="color: #10b981;">{{ $totalSavers }}</div>
            </div>
            <div class="card">
                <h4>Total Saldo Penabung</h4>
                <div class="value">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- NAVIGATION TABS -->
        <div class="nav-tabs">
            <button class="tab-btn active" onclick="switchTab('tab-users')">Manajemen User</button>
            <button class="tab-btn" onclick="switchTab('tab-members')">Data Anggota Siswa (CRUD)</button>
            <button class="tab-btn" onclick="switchTab('tab-savers')">Data Penabung (Saver)</button>
            <button class="tab-btn" onclick="switchTab('tab-supervisor')">
                Approval Kas & Tutup Kas
                @php $pendingKasCount = $pengajuanKasList->where('status', 'pending')->count(); @endphp
                @if($pendingKasCount > 0)
                    <span style="background: #dc2626; color: white; padding: 2px 7px; border-radius: 10px; font-size: 0.75rem; margin-left: 4px;">{{ $pendingKasCount }}</span>
                @endif
            </button>
            <button class="tab-btn" onclick="switchTab('tab-laporan-bulanan')">Laporan Bulanan & PDF</button>
            <button class="tab-btn" onclick="switchTab('tab-jurnal')">Jurnal Akuntansi</button>
            <button class="tab-btn" onclick="switchTab('tab-audit')">Audit Logs</button>
        </div>

        <!-- TAB 1: MANAJEMEN USER -->
        <div id="tab-users" class="tab-content active">
            <div class="grid-split">
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom: 1.25rem;">Tambah User Petugas</h3>
                    <form action="{{ route('admin.users.store') }}" method="POST">
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
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
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
                                    <th>Nama Petugas</th>
                                    <th>Username</th>
                                    <th>Email / Gmail</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableUserBody">
                                @foreach($users as $index => $u)
                                <tr class="user-row" data-role="{{ strtolower($u->role) }}" data-status="{{ strtolower($u->status) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $u->nama_petugas }}</td>
                                    <td><code>{{ $u->username }}</code></td>
                                    <td><small>{{ $u->email ?: '-' }}</small></td>
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
                                            <form action="{{ route('admin.users.toggle-status', $u->id) }}" method="POST" style="margin:0;">
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
                                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada pengguna sistem yang sesuai dengan pencarian atau filter role.</td>
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
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom: 0.5rem; color: #2563eb;">Tambah Anggota Siswa Baru</h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Tabel Anggota digunakan untuk menambah anggota</p>
                    
                    <form action="{{ route('admin.members.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" name="student_name" class="form-control" required placeholder="Contoh: Budi Pratama">
                        </div>
                        <div class="form-group">
                            <label>NIS (Nomor Induk Siswa)</label>
                            <input type="text" name="nis" class="form-control" required placeholder="Contoh: 202611005">
                        </div>
                        <div class="form-group">
                            <label>Alamat Gmail Siswa</label>
                            <input type="email" name="email" class="form-control" required placeholder="Contoh: budi.pratama@gmail.com">
                        </div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <input type="text" name="class_grade" class="form-control" required placeholder="Contoh: X RPL 1">
                        </div>
                        <div class="form-group">
                            <label>Angkatan Keluar (Tahun Kelulusan)</label>
                            <input type="text" name="graduation_year" class="form-control" required placeholder="Contoh: 2028">
                        </div>
                        <div class="form-group">
                            <label>Password Akun Login Siswa <span style="color: #dc2626;">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Masukkan kata sandi login (min. 6 karakter)">
                           
                        </div>
                        <div class="form-group">
                            <label>PIN Keamanan Transaksi Penarikan (Wajib 6-Digit) <span style="color: #dc2626;">*</span></label>
                            <input type="password" name="pin" class="form-control" required maxlength="6" minlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="Wajib tepat 6 angka (contoh: 123456)">
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 0.5rem;">Simpan Data Anggota</button>
                    </form>
                </div>

                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div>
                            <h3 style="margin:0;">Daftar Anggota Siswa (Members)</h3>
                        </div>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
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
                                <option value="">Semua Kelas</option>
                                @foreach($uniqueMemberKelas as $uk)
                                    <option value="{{ strtolower($uk) }}">{{ $uk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width: 150px;">
                            <select id="filterMemberAngkatan" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterMemberTable()">
                                <option value="">Semua Angkatan</option>
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
                                    <th>Kelas</th>
                                    <th>Angkatan Keluar</th>
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
                </div>
            </div>
        </div>

        <!-- TAB 3: DATA PENABUNG (SAVER) -->
        <div id="tab-savers" class="tab-content">
            <div class="card">


                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin:0;">Daftar Rekening Penabung (Savers)</h3>
                        <p style="margin:0; font-size: 0.85rem; color: var(--text-muted);">Siswa yang telah mengaktifkan rekening dan memiliki catatan saldo tabungan.</p>
                    </div>
                    <div>
                        <span class="badge badge-aktif" style="font-size: 0.85rem; padding: 0.5rem 1rem;">Total Saldo: Rp {{ number_format($totalSaldo, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- PENCARIAN & FILTER PENABUNG -->
                @php
                    $uniqueSaverKelas = \App\Models\Saver::whereNotNull('class_grade')->where('class_grade', '!=', '')->distinct()->pluck('class_grade')->sort()->values();
                @endphp
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: center;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" id="searchSaver" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" placeholder="Cari Penabung (Nama, No Rekening, NIS)..." oninput="filterSaverTable()">
                    </div>
                    <div style="width: 160px;">
                        <select id="filterSaverKelas" class="form-control" style="padding: 0.45rem 0.75rem; font-size: 0.85rem;" onchange="filterSaverTable()">
                            <option value="">Semua Kelas</option>
                            @foreach($uniqueSaverKelas as $sk)
                                <option value="{{ strtolower($sk) }}">{{ $sk }}</option>
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
                                <th>Kelas</th>
                                <th>Saldo Real-Time</th>
                                <th>Status Rekening</th>
                                <th>Aksi Penabung</th>
                            </tr>
                        </thead>
                        <tbody id="tableSaverBody">
                            @forelse($savers as $s)
                            <tr class="saver-row" data-kelas="{{ strtolower($s->class_grade) }}">
                                <td><code>{{ $s->account_number }}</code></td>
                                <td><strong>{{ $s->student_name }}</strong></td>
                                <td><code>{{ $s->nis }}</code></td>
                                <td>{{ $s->class_grade }}</td>
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
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada penabung aktif. Aktivasi penabung dilakukan oleh Teller melalui setoran awal minimal Rp 10.000.</td>
                            </tr>
                            @endforelse
                            <tr id="saverEmptySearch" style="display: none;">
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada penabung yang sesuai dengan pencarian atau filter kelas.</td>
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
                                            <form action="{{ route('admin.pengajuan-kas.approve', $pk->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('ACC tambahan kas Rp {{ number_format($pk->nominal, 0, ',', '.') }}?')">
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
                                        <form action="{{ route('admin.laporan.approve', $lp->id) }}" method="POST" style="margin:0;">
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
    </div>

    <!-- MODAL EDIT MEMBER -->
    <div class="modal-overlay" id="editMemberModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Edit Data Anggota Siswa</h3>
                <button type="button" onclick="closeEditMemberModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <form id="editMemberForm" method="POST">
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
                    <label>Kelas</label>
                    <input type="text" name="class_grade" id="edit_class_grade" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Angkatan Keluar</label>
                    <input type="text" name="graduation_year" id="edit_graduation_year" class="form-control" required>
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

            <form id="resetPinForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>PIN 6-Digit Baru</label>
                    <input type="password" name="pin" class="form-control" required maxlength="6" minlength="6" placeholder="Masukkan 6 angka PIN baru">
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

        function switchTab(tabId) {
            if (!tabId) return;
            const targetContent = document.getElementById(tabId);
            if (!targetContent) return;

            document.querySelectorAll('.nav-tabs > .tab-btn').forEach(btn => {
                btn.classList.remove('active');
                const oc = btn.getAttribute('onclick');
                if (oc && oc.includes("'" + tabId + "'")) {
                    btn.classList.add('active');
                }
            });
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            targetContent.classList.add('active');

            try {
                sessionStorage.setItem('admin_active_tab', tabId);
                localStorage.setItem('admin_active_tab', tabId);
            } catch (e) {}

            try {
                if (window.history && window.history.replaceState && window.location.hash) {
                    window.history.replaceState(null, null, window.location.pathname + window.location.search);
                }
            } catch (e) {}
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
                const urlParams = new URLSearchParams(window.location.search);
                const queryTab = urlParams.get('tab');
                const sessionTab = sessionStorage.getItem('admin_active_tab');
                const localTab = localStorage.getItem('admin_active_tab');

                const tabToActivate = (queryTab && document.getElementById(queryTab)) ? queryTab
                                    : (sessionTab && document.getElementById(sessionTab)) ? sessionTab
                                    : (localTab && document.getElementById(localTab)) ? localTab
                                    : null;

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

        // Restore tab segera saat skrip dieksekusi agar bebas flicker
        restoreAdminActiveTab();

        // Restore tab kembali saat DOM selesai dimuat dan pasang listener submit form
        document.addEventListener('DOMContentLoaded', () => {
            restoreAdminActiveTab();
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
            filterMemberTable();
        }

        function filterMemberTable() {
            const query = (document.getElementById('searchMember')?.value || '').toLowerCase().trim();
            const kelas = (document.getElementById('filterMemberKelas')?.value || '').toLowerCase();
            const angkatan = (document.getElementById('filterMemberAngkatan')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#tableMemberBody tr.member-row');
            let visibleCount = 0;

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
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('memberEmptySearch');
            const emptyText = document.getElementById('memberEmptyText');
            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0) ? '' : 'none';
                if (emptyText) {
                    if (currentMemberStatusFilter === 'belum_aktif' && !query && !kelas && !angkatan) {
                        emptyText.innerHTML = 'Tidak ada anggota siswa yang belum aktif. Seluruh siswa yang telah menabung otomatis berpindah ke tab <strong>Manajemen User</strong> & <strong>Data Penabung</strong>.';
                    } else {
                        emptyText.innerText = 'Tidak ada data siswa yang sesuai dengan filter atau pencarian.';
                    }
                }
            }
        }

        function filterSaverTable() {
            const query = (document.getElementById('searchSaver')?.value || '').toLowerCase().trim();
            const kelas = (document.getElementById('filterSaverKelas')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#tableSaverBody tr.saver-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rKelas = row.getAttribute('data-kelas') || '';

                const matchQuery = !query || text.includes(query);
                const matchKelas = !kelas || rKelas === kelas;

                if (matchQuery && matchKelas) {
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
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem;">
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem 1rem; border-radius: 12px;">
                    <div style="font-size: 0.75rem; color: #166534; font-weight: 700; text-transform: uppercase;">Total Setoran</div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: #16a34a; margin-top: 2px;">+ Rp {{ number_format($lp->total_setoran) }}</div>
                </div>
                <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 0.75rem 1rem; border-radius: 12px;">
                    <div style="font-size: 0.75rem; color: #991b1b; font-weight: 700; text-transform: uppercase;">Total Penarikan</div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: #dc2626; margin-top: 2px;">- Rp {{ number_format($lp->total_penarikan) }}</div>
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

            <form id="rejectLaporanForm" method="POST">
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

            <form id="rejectPengajuanForm" method="POST">
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
</body>
</html>
