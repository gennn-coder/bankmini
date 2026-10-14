<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian Kas Teller - {{ $laporan->tanggal }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11pt;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 1px;
        }
        .header-title p {
            margin: 3px 0 0;
            font-size: 9pt;
            color: #64748b;
        }
        .meta-box {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-box td {
            padding: 4px 6px;
            font-size: 10pt;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-size: 10pt;
        }
        .summary-table th {
            background-color: #f1f5f9;
            text-align: left;
            font-weight: bold;
        }
        .tx-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .tx-table th, .tx-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 9pt;
        }
        .tx-table th {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-status {
            font-weight: bold;
            color: #16a34a;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10pt;
        }
        .signature-space {
            height: 60px;
        }
        .deleted-notice {
            color: #dc2626;
            font-size: 8pt;
            font-style: italic;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-title">
                <h2>E-TELLER BANK MINI SEKOLAH</h2>
                <p>Laporan Rekonsiliasi Kas Harian & Penutupan Kas Teller</p>
                <p style="font-size: 8pt; color: #94a3b8;">Sistem Perbankan Mini Digital & Koperasi Sekolah</p>
            </td>
        </tr>
    </table>

    <table class="meta-box">
        <tr>
            <td width="20%"><strong>Tanggal Operasional</strong></td>
            <td width="30%">: {{ date('d F Y', strtotime($laporan->tanggal)) }}</td>
            <td width="20%"><strong>Status Approval</strong></td>
            <td width="30%">: <span class="badge-status">{{ strtoupper($laporan->status_approval) }}</span></td>
        </tr>
        <tr>
            <td><strong>Nama Teller</strong></td>
            <td>: {{ $laporan->teller->nama_petugas ?? 'Teller' }}</td>
            <td><strong>Disetujui Oleh (Admin)</strong></td>
            <td>: {{ $laporan->supervisor->nama_petugas ?? 'Administrator' }}</td>
        </tr>
    </table>

    <h4 style="margin: 15px 0 8px; font-size: 11pt; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Ringkasan Rekonsiliasi Kas</h4>
    <table class="summary-table">
        <tr>
            <th width="35%">Saldo Awal Kas Laci</th>
            <td width="65%">Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Setoran Tunai Masuk</th>
            <td style="color: #16a34a; font-weight: bold;">+ Rp {{ number_format($laporan->total_setoran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Penarikan Tunai Keluar</th>
            <td style="color: #dc2626; font-weight: bold;">- Rp {{ number_format($laporan->total_penarikan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Saldo Akhir Kas Sistem</th>
            <td style="font-weight: bold;">Rp {{ number_format($laporan->saldo_akhir_sistem, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Hasil Hitung Uang Fisik Kas</th>
            <td style="font-weight: bold;">Rp {{ number_format($laporan->uang_fisik, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Selisih Kas (Fisik - Sistem)</th>
            <td style="font-weight: bold; color: {{ $laporan->selisih == 0 ? '#16a34a' : '#dc2626' }};">
                Rp {{ number_format($laporan->selisih, 0, ',', '.') }} ({{ $laporan->selisih == 0 ? 'COCOK / SEIMBANG' : 'ADA SELISIH' }})
            </td>
        </tr>
    </table>

    <h4 style="margin: 15px 0 8px; font-size: 11pt; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Rincian Transaksi Teller Tanggal {{ date('d/m/Y', strtotime($laporan->tanggal)) }}</h4>
    <table class="tx-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="22%">No Transaksi</th>
                <th width="15%">Waktu</th>
                <th width="28%">Nama Penabung / Akun</th>
                <th width="12%">Jenis</th>
                <th width="18%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $t)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><code>{{ $t->no_transaksi }}</code></td>
                <td class="text-center">{{ date('H:i:s', strtotime($t->tanggal)) }}</td>
                <td>
                    <strong>{{ $t->student_name_display }}</strong>
                    <br><small style="color: #64748b;">Rek: {{ $t->account_number_display }}</small>
                    @if($t->saver && $t->saver->trashed())
                        <br><span class="deleted-notice">(Data akun telah dinonaktifkan/dihapus)</span>
                    @endif
                </td>
                <td class="text-center" style="font-weight: bold; color: {{ $t->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                    {{ strtoupper($t->jenis_trans) }}
                </td>
                <td class="text-right">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="color: #64748b; padding: 15px;">Tidak ada transaksi tercatat pada hari ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">Total Transaksi Masuk (Setoran):</td>
                <td class="text-right" style="color: #16a34a;">Rp {{ number_format($laporan->total_setoran, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">Total Transaksi Keluar (Penarikan):</td>
                <td class="text-right" style="color: #dc2626;">Rp {{ number_format($laporan->total_penarikan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Teller Yang Bertugas,
                <div class="signature-space"></div>
                <strong>{{ $laporan->teller->nama_petugas ?? 'Siti Rahmawati' }}</strong><br>
                <small style="color: #64748b;">Petugas Teller Bank Mini</small>
            </td>
            <td>
                Disetujui Oleh Administrator,
                <div class="signature-space"></div>
                <strong>{{ $laporan->supervisor->nama_petugas ?? auth()->user()->nama_petugas ?? 'Administrator' }}</strong><br>
                <small style="color: #64748b;">Kepala / Admin Operasional Bank Mini</small>
            </td>
        </tr>
    </table>
</body>
</html>
