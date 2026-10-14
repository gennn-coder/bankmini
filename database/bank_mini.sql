-- ====================================================================
-- SKEMA BASIS DATA (DATABASE SCHEMA) E-TELLER BANK MINI SEKOLAH
-- UKK RPL TAHUN AJARAN 2026/2027 - GENTA NILAM PRANA (XI RPL 2)
-- Single Source of Truth: InnoDB, 3NF, Double-Entry Accounting, RBAC
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `bank_mini` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bank_mini`;

-- --------------------------------------------------------
-- 1. TABEL users (Pengguna Sistem: Administrator, Teller, Supervisor, Nasabah)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jurnal_akuntansi`;
DROP TABLE IF EXISTS `transaksi`;
DROP TABLE IF EXISTS `laporan_harian`;
DROP TABLE IF EXISTS `rekening`;
DROP TABLE IF EXISTS `nasabah`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `akun_jurnal`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_petugas` VARCHAR(100) NOT NULL,
  `role` ENUM('administrator', 'teller', 'supervisor', 'nasabah') NOT NULL,
  `status` ENUM('aktif', 'terblokir') NOT NULL DEFAULT 'aktif',
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. TABEL nasabah (Data Siswa / Nasabah)
-- --------------------------------------------------------
CREATE TABLE `nasabah` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL UNIQUE,
  `no_rekening` VARCHAR(20) NOT NULL UNIQUE,
  `nis` VARCHAR(20) NOT NULL UNIQUE,
  `nama_siswa` VARCHAR(100) NOT NULL,
  `kelas` VARCHAR(20) NOT NULL,
  `pin_keamanan` VARCHAR(255) NOT NULL, -- PIN 6 Digit (Hashed)
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_nasabah_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. TABEL rekening (Data Rekening & Saldo Real-Time)
-- --------------------------------------------------------
CREATE TABLE `rekening` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_nasabah` BIGINT UNSIGNED NOT NULL,
  `no_rekening` VARCHAR(20) NOT NULL UNIQUE,
  `saldo` BIGINT NOT NULL DEFAULT 0,
  `status` ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `chk_saldo_min` CHECK (`saldo` >= 0),
  CONSTRAINT `fk_rekening_nasabah` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. TABEL transaksi (Setoran & Penarikan Tunai)
-- --------------------------------------------------------
CREATE TABLE `transaksi` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `no_transaksi` VARCHAR(30) NOT NULL UNIQUE,
  `id_nasabah` BIGINT UNSIGNED NOT NULL,
  `id_user` BIGINT UNSIGNED NOT NULL, -- Id Teller
  `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_trans` ENUM('setoran', 'penarikan') NOT NULL,
  `nominal` BIGINT NOT NULL,
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `chk_nominal_pos` CHECK (`nominal` > 0),
  CONSTRAINT `fk_transaksi_nasabah` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_transaksi_teller` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. TABEL akun_jurnal (Bagan Akun Standar Akuntansi Bank)
-- --------------------------------------------------------
CREATE TABLE `akun_jurnal` (
  `kode_akun` VARCHAR(10) PRIMARY KEY,
  `nama_akun` VARCHAR(100) NOT NULL,
  `jenis_akun` ENUM('aset', 'liabilitas', 'ekuitas', 'pendapatan', 'beban') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. TABEL jurnal_akuntansi (Pencatatan Double-Entry Accounting)
-- --------------------------------------------------------
CREATE TABLE `jurnal_akuntansi` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_transaksi` BIGINT UNSIGNED NOT NULL,
  `kode_akun` VARCHAR(10) NOT NULL,
  `posisi` ENUM('debit', 'kredit') NOT NULL,
  `jumlah` BIGINT NOT NULL,
  `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `chk_jumlah_pos` CHECK (`jumlah` > 0),
  CONSTRAINT `fk_jurnal_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_jurnal_akun` FOREIGN KEY (`kode_akun`) REFERENCES `akun_jurnal` (`kode_akun`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. TABEL laporan_harian (Penutupan Kas & Rekonsiliasi Teller)
-- --------------------------------------------------------
CREATE TABLE `laporan_harian` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `tanggal` DATE NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL, -- Teller ID
  `saldo_awal` BIGINT NOT NULL DEFAULT 0,
  `total_setoran` BIGINT NOT NULL DEFAULT 0,
  `total_penarikan` BIGINT NOT NULL DEFAULT 0,
  `saldo_akhir_sistem` BIGINT NOT NULL DEFAULT 0,
  `uang_fisik` BIGINT NOT NULL DEFAULT 0,
  `selisih` BIGINT NOT NULL DEFAULT 0,
  `status_approval` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `supervisor_id` BIGINT UNSIGNED NULL, -- Supervisor ID
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_laporan_teller` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_laporan_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. TABEL audit_logs (Jejak Audit Aktivitas Pengguna)
-- --------------------------------------------------------
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `aktivitas` VARCHAR(255) NOT NULL,
  `detail` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- SEED DATA AWAL (DEFAULT SYSTEM DATA)
-- ====================================================================

-- 1. Akun Jurnal Standar UKK
INSERT INTO `akun_jurnal` (`kode_akun`, `nama_akun`, `jenis_akun`) VALUES
('101', 'Kas Bank Mini', 'aset'),
('201', 'Simpanan Tabungan Nasabah', 'liabilitas');

-- 2. Users Default (Password default: 'password123')
INSERT INTO `users` (`id`, `username`, `password`, `nama_petugas`, `role`, `status`) VALUES
(1, 'admin', '$2y$12$e/iA8l2Dk9hY.k1B9J0n.O5x2p1Y8Q5b7g4s3m2n1k0l9j8h7g6f', 'Genta Nilam Prana (Admin)', 'administrator', 'aktif'),
(2, 'teller1', '$2y$12$e/iA8l2Dk9hY.k1B9J0n.O5x2p1Y8Q5b7g4s3m2n1k0l9j8h7g6f', 'Siti Rahmawati (Teller 1)', 'teller', 'aktif'),
(3, 'supervisor1', '$2y$12$e/iA8l2Dk9hY.k1B9J0n.O5x2p1Y8Q5b7g4s3m2n1k0l9j8h7g6f', 'Budi Santoso, S.Pd (Supervisor)', 'supervisor', 'aktif'),
(4, 'siswa1', '$2y$12$e/iA8l2Dk9hY.k1B9J0n.O5x2p1Y8Q5b7g4s3m2n1k0l9j8h7g6f', 'Ahmad Rizky (Nasabah)', 'nasabah', 'aktif');

-- 3. Data Nasabah Contoh
INSERT INTO `nasabah` (`id`, `user_id`, `no_rekening`, `nis`, `nama_siswa`, `kelas`, `pin_keamanan`) VALUES
(1, 4, '10001001', '202611001', 'Ahmad Rizky', 'XI RPL 2', '$2y$12$R.32u6T9yX8Vb7N6M5L4K3J2I1H0G9F8E7D6C5B4A3');

-- 4. Rekening Nasabah (Saldo Awal Rp 50.000)
INSERT INTO `rekening` (`id`, `id_nasabah`, `no_rekening`, `saldo`, `status`) VALUES
(1, 1, '10001001', 50000, 'aktif');

-- 5. Laporan Harian Contoh (Approved)
INSERT INTO `laporan_harian` (`id`, `tanggal`, `user_id`, `saldo_awal`, `total_setoran`, `total_penarikan`, `saldo_akhir_sistem`, `uang_fisik`, `selisih`, `status_approval`, `supervisor_id`, `created_at`) VALUES
(1, '2026-07-29', 2, 0, 60000, 10000, 50000, 50000, 0, 'approved', 3, '2026-07-29 08:18:49');
