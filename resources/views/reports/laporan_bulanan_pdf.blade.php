<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Bulanan - Periode {{ $namaBulan }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
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
        .grid-summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grid-summary th, .grid-summary td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-size: 9.5pt;
        }
        .grid-summary th {
            background-color: #f1f5f9;
            text-align: left;
        }
        .tx-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .tx-table th, .tx-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 8.5pt;
        }
        .tx-table th {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .signature-space {
            height: 60px;
        }
        .deleted-notice {
            color: #dc2626;
            font-size: 7.5pt;
            font-style: italic;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-title">
                <h2>E-TELLER BANK MINI SEKOLAH</h2>
                <p>Laporan Mutasi & Rekapitulasi Keuangan Bulanan</p>
                <p style="font-size: 8pt; color: #94a3b8;">Periode Bulan: <strong>{{ $namaBulan }} {{ $year }}</strong></p>
            </td>
        </tr>
    </table>

    <table class="meta-box">
        <tr>
            <td width="20%"><strong>Periode Laporan</strong></td>
            <td width="30%">: {{ $namaBulan }} {{ $year }}</td>
            <td width="20%"><strong>Dicetak Pada</strong></td>
            <td width="30%">: {{ date('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Total Transaksi</strong></td>
            <td>: {{ count($transaksis) }} Transaksi</td>
            <td><strong>Dicetak Oleh</strong></td>
            <td>: {{ auth()->user()->nama_petugas ?? 'Administrator' }}</td>
        </tr>
    </table>

    <h4 style="margin: 15px 0 8px; font-size: 10.5pt; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Ringkasan Arus Kas Bulanan</h4>
    <table class="grid-summary">
        <tr>
            <th width="35%">Total Setoran Masuk (Kredit Nasabah)</th>
            <td width="65%" style="color: #16a34a; font-weight: bold;">+ Rp {{ number_format($totalSetoran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Penarikan Keluar (Debit Nasabah)</th>
            <td style="color: #dc2626; font-weight: bold;">- Rp {{ number_format($totalPenarikan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Arus Kas Bersih (Net Cash Flow)</th>
            <td style="font-weight: bold; color: {{ ($totalSetoran - $totalPenarikan) >= 0 ? '#16a34a' : '#dc2626' }};">
                Rp {{ number_format($totalSetoran - $totalPenarikan, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <th>Rata-rata Transaksi per Transaksi</th>
            <td>Rp {{ count($transaksis) > 0 ? number_format(($totalSetoran + $totalPenarikan) / count($transaksis), 0, ',', '.') : 0 }}</td>
        </tr>
    </table>

    <h4 style="margin: 15px 0 8px; font-size: 10.5pt; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Daftar Transaksi Bulanan (Lengkap Termasuk Akun yang Terhapus)</h4>
    <table class="tx-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">No Transaksi</th>
                <th width="25%">Penabung / Rekening</th>
                <th width="10%">Jenis</th>
                <th width="14%">Nominal</th>
                <th width="12%">Teller</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $t)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ date('d/m/Y H:i', strtotime($t->tanggal)) }}</td>
                <td class="text-center"><code>{{ $t->no_transaksi }}</code></td>
                <td>
                    <strong>{{ $t->student_name_display }}</strong>
                    <br><small style="color: #64748b;">No Rek: {{ $t->account_number_display }}</small>
                    @if($t->saver && $t->saver->trashed())
                        <br><span class="deleted-notice">(Akun Telah Dihapus)</span>
                    @endif
                </td>
                <td class="text-center" style="font-weight: bold; color: {{ $t->jenis_trans === 'setoran' ? '#16a34a' : '#dc2626' }};">
                    {{ strtoupper($t->jenis_trans) }}
                </td>
                <td class="text-right">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                <td class="text-center">{{ $t->teller->nama_petugas ?? 'Teller' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="color: #64748b; padding: 15px;">Tidak ada catatan transaksi pada periode bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">Total Setoran:</td>
                <td class="text-right" style="color: #16a34a;">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">Total Penarikan:</td>
                <td class="text-right" style="color: #dc2626;">Rp {{ number_format($totalPenarikan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Staff Teller Operasional
                <div class="signature-space"></div>
                <strong>( .................................................. )</strong>
            </td>
            <td>
                Disahkan Oleh,<br>
                Administrator & Kepala Bank Mini
                <div class="signature-space"></div>
                <strong>{{ auth()->user()->nama_petugas ?? 'Genta Nilam Prana (Admin)' }}</strong><br>
                <small style="color: #64748b;">NIP. Bank Mini Sekolah</small>
            </td>
        </tr>
    </table>
</body>
</html>
