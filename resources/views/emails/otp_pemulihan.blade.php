<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Pemulihan Kata Sandi</title>
</head>
<body style="margin: 0; padding: 24px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b;">
    <div style="max-width: 540px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 26px 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.5px;">E-TELLER BANK MINI SEKOLAH</h1>
            <p style="margin: 6px 0 0; font-size: 13px; opacity: 0.9;">Layanan Pemulihan Akses Akun</p>
        </div>

        <!-- Content -->
        <div style="padding: 28px 24px;">
            <h2 style="margin: 0 0 12px; font-size: 18px; color: #0f172a;">Halo, {{ $namaPenerima }}</h2>
            <p style="margin: 0 0 18px; font-size: 14px; line-height: 1.6; color: #475569;">
                Kami menerima permintaan verifikasi untuk mengatur ulang Kata Sandi atau PIN Transaksi akun E-Teller Bank Mini Anda (Username/NIS: <strong>{{ $username }}</strong>). Gunakan kode OTP di bawah ini untuk melanjutkan proses verifikasi:
            </p>

            <!-- Box OTP -->
            <div style="background-color: #f0fdf4; border: 2px dashed #86efac; border-radius: 12px; padding: 22px; text-align: center; margin: 24px 0;">
                <div style="font-size: 12px; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px;">
                    KODE OTP VERIFIKASI
                </div>
                <div style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; color: #15803d; letter-spacing: 6px;">
                    {{ $otp }}
                </div>
                <div style="font-size: 12px; color: #64748b; margin-top: 10px;">
                    Kode ini berlaku selama 15 menit ke depan.
                </div>
            </div>

            <p style="margin: 0 0 16px; font-size: 13px; line-height: 1.6; color: #475569;">
                Masukkan 6 digit kode tersebut pada formulir verifikasi di aplikasi E-Teller Bank Mini. Demi keamanan akun Anda, jangan berikan kode ini kepada pihak manapun.
            </p>

            <p style="margin: 16px 0 0; font-size: 12px; line-height: 1.5; color: #94a3b8;">
                Jika Anda tidak merasa meminta kode ini, Anda dapat mengabaikan email ini dengan aman. Akun dan saldo Anda tetap terlindungi.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center; font-size: 12px; color: #94a3b8;">
            &copy; 2026/2027 E-Teller Bank Mini Sekolah. Email ini dikirim otomatis oleh sistem.
        </div>
    </div>
</body>
</html>
