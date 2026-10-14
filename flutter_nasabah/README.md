# Aplikasi Mobile Nasabah — E-Teller Bank Mini Sekolah (UKK RPL)

Aplikasi mobile berbasis Flutter yang dirancang khusus untuk **Role Nasabah Siswa** sesuai dengan spesifikasi resmi **Soal UKK Rekayasa Perangkat Lunak: E-Teller Bank Mini Sekolah**.

---

## 1. Fitur Utama (Sesuai Soal UKK)
1. **Autentikasi Login Mandiri**:
   - Login menggunakan **Nama Siswa / Username** dan Password.
   - Terhubung langsung ke database MySQL Laravel secara real-time.
2. **Dashboard Nasabah Real-Time**:
   - Informasi Nomor Rekening, NIS, Kelas, dan Status Rekening Aktif.
   - Tampilan Saldo Tabungan Real-Time (Format Rupiah `Rp`).
3. **Kode QR Kartu Digital Nasabah**:
   - Menampilkan QR Code digital rekening nasabah dengan resolusi tajam.
   - Siap dipindai secara instan (<30ms) oleh webcam loket Teller.
4. **Riwayat Mutasi Rekening Siswa**:
   - Menampilkan daftar mutasi transaksi lengkap.
   - Indikator visual hijau (`+`) untuk Setoran dan merah (`-`) untuk Penarikan.
   - Dilengkapi Nomor Transaksi unik, waktu, dan keterangan.
5. **Pull-to-Refresh**:
   - Tarik layar ke bawah untuk memperbarui saldo dan mutasi seketika saat baru saja melakukan transaksi di loket Teller.
6. **Desain UI Formal & Bersih**:
   - Menggunakan Material Design 3 standar perbankan.
   - **Bebas dari emotikon / icon AI**, sangat formal untuk ujian UKK SMK.

---

## 2. Cara Menjalankan Aplikasi Flutter

Buka terminal di folder `flutter_nasabah`:

```bash
cd flutter_nasabah
flutter pub get
```

### Opsi A — Jalankan di Browser / Chrome (Paling Cepat & Mudah):
```bash
flutter run -d chrome
```

### Opsi B — Jalankan di Emulator Android Studio:
```bash
flutter run
```
*(Di emulator Android, Base URL otomatis menggunakan `http://10.0.2.2:8000/api/nasabah`)*

### Opsi C — Jalankan di HP Android Fisik via Wi-Fi:
1. Pastikan HP dan laptop terhubung ke jaringan Wi-Fi / Hotspot yang sama.
2. Cek IP laptop Anda (misal `192.168.1.10`).
3. Jalankan Laravel: `php artisan serve --host=0.0.0.0 --port=8000`
4. Pada halaman Login aplikasi Flutter, klik tombol **"Konfigurasi Server API"** di bagian bawah, lalu masukkan `http://192.168.1.10:8000/api/nasabah`.
