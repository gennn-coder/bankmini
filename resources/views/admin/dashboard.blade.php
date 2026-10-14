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
            max-width: 1320px;
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
            <h2 style="margin:0; font-size: 1.25rem; font-weight: 800;">Administrator System</h2>
            <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah &bull; Role Gabungan Admin & Supervisor</p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span>Halo, <strong>{{ $user->nama_petugas }}</strong> <span class="badge badge-aktif">ADMIN & SPV</span></span>
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
            <div class="card">
                <h4>Tutup Kas Pending</h4>
                <div class="value" style="color: #f59e0b;">{{ count($laporanPending) }}</div>
            </div>
        </div>

        <!-- NAVIGATION TABS -->
        <div class="nav-tabs">
            <button class="tab-btn active" onclick="switchTab('tab-users')">Manajemen User</button>
            <button class="tab-btn" onclick="switchTab('tab-members')">Data Anggota Siswa (CRUD)</button>
            <button class="tab-btn" onclick="switchTab('tab-savers')">Data Penabung (Saver)</button>
            <button class="tab-btn" onclick="switchTab('tab-supervisor')">Approval Tutup Kas (Supervisor)</button>
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
                    <h3 style="margin-top:0;">Daftar Pengguna Sistem</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Petugas</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $u)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $u->nama_petugas }}</td>
                                    <td><code>{{ $u->username }}</code></td>
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
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Tabel Anggota digunakan untuk data siswa yang belum menabung atau belum mengaktifkan rekening sama sekali.</p>
                    
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
                            <label>Kelas</label>
                            <input type="text" name="class_grade" class="form-control" required placeholder="Contoh: X RPL 1">
                        </div>
                        <div class="form-group">
                            <label>Angkatan Keluar (Tahun Kelulusan)</label>
                            <input type="text" name="graduation_year" class="form-control" required placeholder="Contoh: 2028">
                        </div>
                        <div class="form-group">
                            <label>Password Awal Akun (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Default: 123456">
                        </div>
                        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 0.5rem;">Simpan Data Anggota</button>
                    </form>
                </div>

                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                        <h3 style="margin:0;">Daftar Anggota Siswa (Members)</h3>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Total: <strong>{{ count($members) }}</strong> siswa terdaftar</span>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th>Angkatan Keluar</th>
                                    <th>Status Rekening</th>
                                    <th>Aksi CRUD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $index => $m)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $m->student_name }}</strong></td>
                                    <td><code>{{ $m->nis }}</code></td>
                                    <td>{{ $m->class_grade }}</td>
                                    <td>{{ $m->graduation_year }}</td>
                                    <td>
                                        <span class="badge {{ $m->status === 'rekening aktif' ? 'badge-aktif' : 'badge-tidak-aktif' }}">
                                            {{ strtoupper($m->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.35rem;">
                                            <button type="button" class="btn-warning" onclick="openEditMemberModal('{{ $m->user_id }}', '{{ addslashes($m->student_name) }}', '{{ $m->nis }}', '{{ $m->class_grade }}', '{{ $m->graduation_year }}', '{{ $m->status }}')">
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
                                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Belum ada data anggota siswa yang terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: DATA PENABUNG (SAVER) -->
        <div id="tab-savers" class="tab-content">
            <div class="card">
                <div class="notice-box">
                    <strong>Pemberitahuan Sistem Otomatisasi Penabung:</strong><br>
                    Tombol Tambah Manual (+) untuk Penabung telah ditiadakan sesuai hasil revisi. Data penabung otomatis dibuat (dikloning) saat siswa anggota mendatangi Teller dan melakukan setoran awal pertama minimal <strong>Rp 10.000</strong>.
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="margin:0;">Daftar Rekening Penabung (Savers)</h3>
                        <p style="margin:0; font-size: 0.85rem; color: var(--text-muted);">Siswa yang telah mengaktifkan rekening dan memiliki catatan saldo tabungan.</p>
                    </div>
                    <div>
                        <span class="badge badge-aktif" style="font-size: 0.85rem; padding: 0.5rem 1rem;">Total Saldo: Rp {{ number_format($totalSaldo, 0, ',', '.') }}</span>
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
                        <tbody>
                            @forelse($savers as $s)
                            <tr>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: APPROVAL TUTUP KAS TELLER (FITUR SUPERVISOR YANG DISATUKAN KE ADMIN) -->
        <div id="tab-supervisor" class="tab-content">
            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="margin-top:0; color: #d97706; margin-bottom: 0.5rem;">Laporan Penutupan Kas Teller Menunggu Approval</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Administrator memvalidasi saldo akhir kas sistem dengan uang fisik kas laci teller sebelum menyetujui pemindahan ke brankas utama.</p>

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
                        <tbody>
                            @forelse($laporanPending as $lp)
                            <tr>
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
                                    <div style="display: flex; gap: 0.5rem;">
                                        <form action="{{ route('admin.laporan.approve', $lp->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="btn-success">Approve & Vault</button>
                                        </form>
                                        <form action="{{ route('admin.laporan.reject', $lp->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="btn-danger">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">Tidak ada laporan harian kas teller yang menunggu approval saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top:0; color: #16a34a; margin-bottom: 0.5rem;">Arsip Laporan Kas Disetujui (Cetak PDF)</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Laporan penutupan kas yang telah di-ACC. Transaksi akun yang dihapus tetap tersaji lengkap dan tidak terhapus pada PDF.</p>

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
                        <tbody>
                            @forelse($laporanApproved as $la)
                            <tr>
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
                        </tbody>
                    </table>
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
                <h3 style="margin-top:0; margin-bottom: 0.5rem;">Audit Jurnal Akuntansi Double-Entry</h3>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.25rem;">Pencatatan otomatis Debit & Kredit setiap transaksi setoran (Debit 101 Kas / Kredit 201 Tabungan) & penarikan (Debit 201 Tabungan / Kredit 101 Kas).</p>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>No Transaksi</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Posisi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jurnals as $j)
                            <tr>
                                <td>{{ $j->tanggal }}</td>
                                <td><code>{{ $j->transaksi->no_transaksi ?? '-' }}</code></td>
                                <td><code>{{ $j->kode_akun }}</code></td>
                                <td>{{ $j->akun->nama_akun ?? '-' }}</td>
                                <td><strong style="text-transform: uppercase; color: {{ $j->posisi === 'debit' ? '#2563eb' : '#dc2626' }};">{{ $j->posisi }}</strong></td>
                                <td>Rp {{ number_format($j->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 7: AUDIT LOGS -->
        <div id="tab-audit" class="tab-content">
            <div class="card">
                <h3 style="margin-top:0;">Audit Trail Logs Aktivitas</h3>
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
                        <tbody>
                            @foreach($auditLogs as $log)
                            <tr>
                                <td>{{ $log->created_at }}</td>
                                <td>{{ $log->user->nama_petugas ?? 'System' }}</td>
                                <td><strong>{{ $log->aktivitas }}</strong></td>
                                <td>{{ $log->detail }}</td>
                                <td><code>{{ $log->ip_address }}</code></td>
                            </tr>
                            @endforeach
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
                    <label>Kelas</label>
                    <input type="text" name="class_grade" id="edit_class_grade" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Angkatan Keluar</label>
                    <input type="text" name="graduation_year" id="edit_graduation_year" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Status Rekening</label>
                    <select name="status" id="edit_status" class="form-control" required>
                        <option value="rekening tidak aktif">rekening tidak aktif</option>
                        <option value="rekening aktif">rekening aktif</option>
                    </select>
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
        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            const activeBtn = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.getAttribute('onclick').includes(tabId));
            if (activeBtn) activeBtn.classList.add('active');
            
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.classList.add('active');
        }

        // Auto activate tab from URL query if present
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && document.getElementById(tab)) {
                switchTab(tab);
            }
        });

        function openEditMemberModal(userId, name, nis, grade, gradYear, status) {
            document.getElementById('editMemberForm').action = '/admin/members/' + userId + '/update';
            document.getElementById('edit_student_name').value = name;
            document.getElementById('edit_nis').value = nis;
            document.getElementById('edit_class_grade').value = grade;
            document.getElementById('edit_graduation_year').value = gradYear;
            document.getElementById('edit_status').value = status;
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
    </script>
</body>
</html>
