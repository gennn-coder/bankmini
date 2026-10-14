<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Supervisor | E-Teller Bank Mini</title>
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
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        .btn-approve {
            background: #16a34a; color: #fff; border: none; padding: 0.5rem 0.9rem; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-approve:hover { background: #15803d; transform: translateY(-1px); }
        .btn-reject {
            background: #dc2626; color: #fff; border: none; padding: 0.5rem 0.9rem; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;
        }
        .btn-reject:hover { background: #b91c1c; transform: translateY(-1px); }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <h2 style="margin:0; font-size: 1.25rem; font-weight: 800;">Supervisor System</h2>
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
                <h4>Laporan Menunggu Approval</h4>
                <div class="value" style="color: #d97706;">{{ count($laporanPending) }}</div>
            </div>
            <div class="card">
                <h4>Laporan Disetujui (Vault Transfer)</h4>
                <div class="value" style="color: #16a34a;">{{ count($laporanApproved) }}</div>
            </div>
        </div>

        <!-- LAPORAN PENDING APPROVAL -->
        <div class="card" style="margin-bottom: 2rem;">
            <h3 style="margin-top:0; color: #d97706; margin-bottom: 0.5rem;">Laporan Harian Teller Menunggu Approval</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Supervisor harus memvalidasi saldo akhir sistem dengan uang fisik kas sebelum menyetujui pemindahan ke brankas utama.</p>
            
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
                            <th>Aksi Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanPending as $lp)
                        <tr>
                            <td>{{ $lp->tanggal }}</td>
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
                                    <form action="{{ route('supervisor.laporan.approve', $lp->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-approve">Approve & Pindah Brankas</button>
                                    </form>
                                    <form action="{{ route('supervisor.laporan.reject', $lp->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-reject">Tolak Laporan</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: var(--text-muted);">Tidak ada laporan harian teller yang menunggu approval saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- LAPORAN APPROVED ARSIP -->
        <div class="card" style="margin-bottom: 2rem;">
            <h3 style="margin-top:0; color: #16a34a; margin-bottom: 1rem;">Arsip Laporan Disetujui (Kas Terpindah Ke Brankas)</h3>
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
                            <th>Supervisor Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanApproved as $la)
                        <tr>
                            <td>{{ $la->tanggal }}</td>
                            <td>{{ $la->teller->nama_petugas ?? 'Teller' }}</td>
                            <td>Rp {{ number_format($la->total_setoran) }}</td>
                            <td>Rp {{ number_format($la->total_penarikan) }}</td>
                            <td>Rp {{ number_format($la->saldo_akhir_sistem) }}</td>
                            <td><strong style="color: #16a34a;">APPROVED</strong></td>
                            <td>{{ $la->supervisor->nama_petugas ?? 'Supervisor' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AUDIT TRAIL LOGS -->
        <div class="card">
            <h3 style="margin-top:0; margin-bottom: 1rem;">Real-time Log Audit Trail Aktivitas</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auditLogs as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->user->nama_petugas ?? 'System' }}</td>
                            <td><strong>{{ $log->aktivitas }}</strong></td>
                            <td>{{ $log->detail }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
