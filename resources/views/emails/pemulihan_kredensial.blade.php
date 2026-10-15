<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectTitle ?? 'Pemulihan Akun Bank Mini' }}</title>
</head>
<body style="margin: 0; padding: 20px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b;">
    <div style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 28px 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.5px;">E-TELLER BANK MINI SEKOLAH</h1>
            <p style="margin: 6px 0 0; font-size: 13px; opacity: 0.9;">Sistem Layanan Keuangan Digital Nasabah Siswa</p>
        </div>

        <!-- Content -->
        <div style="padding: 28px 24px;">
            <h2 style="margin: 0 0 12px; font-size: 18px; color: #0f172a;">Halo, {{ $namaSiswa }}!</h2>
            <p style="margin: 0 0 20px; font-size: 14px; line-height: 1.6; color: #475569;">
                Kami menerima permintaan pemulihan <strong>{{ $jenisKredensial }}</strong> untuk akun perbankan mini Anda. Kredensial baru Anda telah berhasil diperbarui di sistem.
            </p>

            <!-- Box Kredensial Baru -->
            <div style="background-color: #eff6ff; border: 2px dashed #93c5fd; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0;">
                <div style="font-size: 12px; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">
                    {{ $labelKredensial }}
                </div>
                <div style="font-family: 'Courier New', Courier, monospace; font-size: 24px; font-weight: 800; color: #1e3a8a; letter-spacing: 2px;">
                    {{ $kredensialBaru }}
                </div>
                <div style="font-size: 11px; color: #64748b; margin-top: 8px;">
                    No. Rekening: <strong>{{ $noRekening }}</strong> | NIS: <strong>{{ $nis }}</strong>
                </div>
            </div>

            <p style="margin: 0 0 16px; font-size: 13px; line-height: 1.6; color: #475569;">
                Silakan gunakan kredensial baru di atas untuk {{ $instruksiPenggunaan }}. Demi keamanan dana Anda, mohon jangan membagikan informasi ini kepada siapapun.
            </p>

            <div style="text-align: center; margin-top: 24px;">
                <a href="{{ $appUrl ?? url('/') }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-weight: 700; font-size: 14px;">
                    Buka Halaman Login Bank Mini
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center; font-size: 12px; color: #94a3b8;">
            &copy; 2026/2027 E-Teller Bank Mini Sekolah. Email ini dikirim secara otomatis oleh sistem, mohon jangan membalas email ini.
        </div>
    </div>
</body>
</html>
