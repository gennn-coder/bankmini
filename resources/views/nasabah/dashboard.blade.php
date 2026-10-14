<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Nasabah | E-Teller Bank Mini</title>
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
            max-width: 1100px;
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
        
        .btn-qr-show {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
            margin-bottom: 1rem;
        }
        .btn-qr-show:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }
        .modal-qr-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-qr-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            position: relative;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <h2 style="margin:0; font-size: 1.25rem; font-weight: 800;">Portal Nasabah Siswa</h2>
            <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">E-Teller Bank Mini Sekolah</p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span>Halo, <strong>{{ $nasabah->nama_siswa ?? $user->nama_petugas }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="grid">
            <div class="card">
                <h4>Identitas Penabung</h4>
                <div class="value" style="font-size: 1.4rem; color: #0f172a; margin-bottom: 0.35rem;">
                    {{ $nasabah->nama_siswa ?? $user->nama_petugas }}
                </div>
                <div style="font-size: 0.88rem; color: var(--text-muted); font-weight: 600;">
                    {{ $nasabah->kelas ?? '-' }} &bull; NIS: {{ $nasabah->nis ?? '-' }}
                </div>
            </div>
            <div class="card" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                <div>
                    <h4>Nomor Rekening Nasabah</h4>
                    <div class="value" style="font-size: 1.4rem;"><code>{{ $nasabah->no_rekening ?? '-' }}</code></div>
                    <button onclick="openQrModal()" style="margin-top: 0.75rem; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">Lihat Kode QR</button>
                </div>
                @if(isset($nasabah->no_rekening))
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=5&data={{ $nasabah->no_rekening }}" alt="QR Code" style="width: 70px; height: 70px; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer;" onclick="openQrModal()" title="Klik untuk memperbesar QR">
                @endif
            </div>
            <div class="card">
                <h4>Saldo Tabungan Real-Time</h4>
                <div class="value" style="color: #16a34a; font-size: 1.6rem;">
                    Rp {{ number_format($nasabah->rekening->saldo ?? 0, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.82rem; color: #16a34a; font-weight: 700; margin-top: 0.35rem;">
                    Status: Rekening Aktif
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-top:0; margin-bottom: 0.5rem;">Riwayat Mutasi Rekening Siswa</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">Daftar riwayat transaksi setoran dan penarikan yang pernah dilakukan pada rekening Anda.</p>
            
            <!-- Tombol QR Code di Atas Keterangan / Tabel sesuai Permintaan User -->
            <button onclick="openQrModal()" class="btn-qr-show">
                Tampilkan Kode QR Kartu Digital Nasabah
            </button>
            
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
                    <tbody>
                        @forelse($transaksi as $t)
                        <tr>
                            <td>{{ $t->tanggal }}</td>
                            <td><code>{{ $t->no_transaksi }}</code></td>
                            <td>
                                <strong style="text-transform: uppercase; color: {{ $t->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                    {{ $t->jenis_trans }}
                                </strong>
                            </td>
                            <td>
                                <strong style="color: {{ $t->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                                    {{ $t->jenis_trans === 'setoran' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>{{ $t->keterangan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada riwayat transaksi mutasi pada rekening ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP KODE QR DIGITAL NASABAH -->
    <div class="modal-qr-overlay" id="qrModal">
        <div class="modal-qr-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">Kode QR Kartu Digital</h3>
                <button onclick="closeQrModal()" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; font-weight: 700;">&times;</button>
            </div>

            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem; line-height: 1.4;">Tunjukkan Kode QR ini kepada Teller Loket Bank Mini Sekolah untuk pemindaian otomatis.</p>

            <div style="background: #ffffff; padding: 1.25rem; border-radius: 20px; border: 2px dashed #bfdbfe; display: inline-block; margin-bottom: 1.25rem; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.08);">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=15&data={{ $nasabah->no_rekening ?? '10001001' }}" alt="QR Code Rekening" style="width: 220px; height: 220px; display: block; margin: 0 auto; border-radius: 12px;">
            </div>

            <div style="font-size: 1.2rem; font-weight: 800; color: #2563eb; margin-bottom: 0.25rem;">No. Rek: {{ $nasabah->no_rekening ?? '-' }}</div>
            <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">{{ $nasabah->nama_siswa ?? $user->nama_petugas }}</div>
            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">{{ $nasabah->kelas ?? '-' }} (NIS: {{ $nasabah->nis ?? '-' }})</div>

            <button onclick="closeQrModal()" class="btn-logout" style="background: #2563eb; width: 100%; padding: 0.75rem; border-radius: 12px;">Tutup Kode QR</button>
        </div>
    </div>

    <script>
        function openQrModal() {
            document.getElementById('qrModal').style.display = 'flex';
        }
        function closeQrModal() {
            document.getElementById('qrModal').style.display = 'none';
        }
    </script>
</body>
</html>
