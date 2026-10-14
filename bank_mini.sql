-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 23 Agu 2026 pada 05.18
-- Versi server: 8.0.30
-- Versi PHP: 8.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `bank_mini`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun_jurnal`
--

CREATE TABLE `akun_jurnal` (
  `kode_akun` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_akun` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_akun` enum('aset','liabilitas','ekuitas','pendapatan','beban') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `akun_jurnal`
--

INSERT INTO `akun_jurnal` (`kode_akun`, `nama_akun`, `jenis_akun`) VALUES
('101', 'Kas Bank Mini', 'aset'),
('201', 'Simpanan Tabungan Nasabah', 'liabilitas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `aktivitas`, `detail`, `ip_address`, `created_at`) VALUES
(1, 1, 'Ubah Status User', 'Mengubah status user teller1 menjadi terblokir', '127.0.0.1', '2026-08-15 06:56:54'),
(2, 1, 'Ubah Status User', 'Mengubah status user teller1 menjadi aktif', '127.0.0.1', '2026-08-15 06:57:19'),
(3, 1, 'Ubah Status User', 'Mengubah status user supervisor1 menjadi terblokir', '127.0.0.1', '2026-08-15 10:14:21'),
(4, 1, 'Ubah Status User', 'Mengubah status user supervisor1 menjadi aktif', '127.0.0.1', '2026-08-15 10:15:21'),
(5, 2, 'Setoran Tunai', 'Setoran Rp 30,000 ke No Rek 10001001', '127.0.0.1', '2026-08-19 04:18:01'),
(6, 2, 'Submit Laporan Harian Teller', 'Mengirim laporan harian tanggal 2026-08-19 (Selisih: Rp 0)', '127.0.0.1', '2026-08-19 04:19:02'),
(7, 3, 'Approval Laporan Harian Teller', 'Menyetujui laporan harian Teller ID 2 tanggal 2026-08-19', '127.0.0.1', '2026-08-19 04:28:00'),
(8, 2, 'Setoran Tunai', 'Setoran Rp 300,000 ke No Rek 10001001', '127.0.0.1', '2026-08-19 06:36:37'),
(9, 2, 'Submit Laporan Harian Teller', 'Mengirim laporan harian tanggal 2026-08-19 (Selisih: Rp -300,000)', '127.0.0.1', '2026-08-19 06:37:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_akuntansi`
--

CREATE TABLE `jurnal_akuntansi` (
  `id` bigint UNSIGNED NOT NULL,
  `id_transaksi` bigint UNSIGNED NOT NULL,
  `kode_akun` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posisi` enum('debit','kredit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` bigint NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurnal_akuntansi`
--

INSERT INTO `jurnal_akuntansi` (`id`, `id_transaksi`, `kode_akun`, `posisi`, `jumlah`, `tanggal`, `created_at`) VALUES
(1, 1, '101', 'debit', 30000, '2026-08-19 04:18:01', '2026-08-19 04:18:01'),
(2, 1, '201', 'kredit', 30000, '2026-08-19 04:18:01', '2026-08-19 04:18:01'),
(3, 2, '101', 'debit', 300000, '2026-08-19 06:36:37', '2026-08-19 06:36:37'),
(4, 2, '201', 'kredit', 300000, '2026-08-19 06:36:37', '2026-08-19 06:36:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_harian`
--

CREATE TABLE `laporan_harian` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'ID Teller',
  `saldo_awal` bigint NOT NULL DEFAULT '0',
  `total_setoran` bigint NOT NULL DEFAULT '0',
  `total_penarikan` bigint NOT NULL DEFAULT '0',
  `saldo_akhir_sistem` bigint NOT NULL DEFAULT '0',
  `uang_fisik` bigint NOT NULL DEFAULT '0',
  `selisih` bigint NOT NULL DEFAULT '0',
  `status_approval` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `laporan_harian`
--

INSERT INTO `laporan_harian` (`id`, `tanggal`, `user_id`, `saldo_awal`, `total_setoran`, `total_penarikan`, `saldo_akhir_sistem`, `uang_fisik`, `selisih`, `status_approval`, `supervisor_id`, `created_at`, `updated_at`) VALUES
(1, '2026-07-29', 2, 0, 60000, 10000, 50000, 50000, 0, 'approved', 3, '2026-08-01 04:01:32', '2026-08-01 04:01:32'),
(2, '2026-08-19', 2, 0, 330000, 0, 330000, 30000, -300000, 'pending', NULL, '2026-08-18 21:19:02', '2026-08-18 23:37:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_01_000001_create_bank_mini_tables', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nasabah`
--

CREATE TABLE `nasabah` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `no_rekening` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_siswa` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin_keamanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `nasabah`
--

INSERT INTO `nasabah` (`id`, `user_id`, `no_rekening`, `nis`, `nama_siswa`, `kelas`, `email`, `pin_keamanan`, `created_at`, `updated_at`) VALUES
(1, 4, '10001001', '202611001', 'Ahmad Rizky', 'XI RPL 2', 'ahmad.rizky@gmail.com', '$2y$12$lxQmtO6x67HjcSvG9VvkneJZ/ySESq2yWAkORzHLwDHM.IJdGAvVO', '2026-08-01 04:01:32', '2026-08-01 04:01:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekening`
--

CREATE TABLE `rekening` (
  `id` bigint UNSIGNED NOT NULL,
  `id_nasabah` bigint UNSIGNED NOT NULL,
  `no_rekening` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` bigint NOT NULL DEFAULT '0',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rekening`
--

INSERT INTO `rekening` (`id`, `id_nasabah`, `no_rekening`, `saldo`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '10001001', 380000, 'aktif', '2026-08-01 04:01:32', '2026-08-18 23:36:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BCu6pYVU58FJydsqAONJba5ahK7OPoaMMei3WYAg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJLQzFrdnp3dHpXWmwydTRCb2ZDTVFQZ0k2SEg3Y2N5eTFDcFdvRFRMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1787206344),
('DErpHYR7TgwrccAaARks3kJKuVy5Bw8pd8P2J9GT', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqQnpOQmo4OEpXaFpLOWFuaWVjWkM3RjFHczZ1akpqang4VXd6bjk4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90ZWxsZXJcL2Rhc2hib2FyZCIsInJvdXRlIjoidGVsbGVyLmRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', 1787272503),
('IAcQRZsWHvzAg4iLKghtZSAIjvIO4aEMcchwBMzM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJBRlFEWXdqVjE4UlJkY1lyblFvaDR3YWwza1J6VklPT1NSOHU5UmxYIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90ZWxsZXJcL2Rhc2hib2FyZCIsInJvdXRlIjoidGVsbGVyLmRhc2hib2FyZCJ9fQ==', 1787121447),
('ozoyz6nzSfAZWjcAJwq35kASX9eoBItckQFC21jE', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJxek9VT3dFaE5yeXZJaVhtYjNYMzlDMmNyZzlHenpsckhVbkVMekd4IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1787121338),
('vM82U2Yx9VuIeBAJ5IPUlsqgnDA4XXdfIw22xTfl', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI5WDJETUxqOU9ZdWhkcWVlMnNJQmdTUDJMR1F0U2trRHR5bHlFOEFUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9uYXNhYmFoXC9kYXNoYm9hcmQiLCJyb3V0ZSI6Im5hc2FiYWguZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjR9', 1787121580),
('XFSsyrPQ5d67YEr0rMlNWub3tV2NegGdQ5gvSohT', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJncEtHMHhmR0d0ZTB2UzRsN1hOcHZ1aU5pR3AzeHBmZkE3NUdJUk5GIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1787206363),
('z3cuTFaATQ6EZlNUl1Nm7GhpMTfRdvwxx1WKWgfC', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJGdUlTM3IwbExBNkJ1bDZCZXpNa1Q1TVR2a1VpRWg5NDBWT0xYU2pVIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90ZWxsZXJcL2Rhc2hib2FyZCIsInJvdXRlIjoidGVsbGVyLmRhc2hib2FyZCJ9fQ==', 1787207558);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `no_transaksi` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_nasabah` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL COMMENT 'ID Teller',
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_trans` enum('setoran','penarikan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` bigint NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `no_transaksi`, `id_nasabah`, `id_user`, `tanggal`, `jenis_trans`, `nominal`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'STR-20260819041801-961', 1, 2, '2026-08-19 04:18:01', 'setoran', 30000, 'test', '2026-08-18 21:18:01', '2026-08-18 21:18:01'),
(2, 'STR-20260819063637-732', 1, 2, '2026-08-19 06:36:37', 'setoran', 300000, 'anaknya', '2026-08-18 23:36:37', '2026-08-18 23:36:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_petugas` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('administrator','teller','supervisor','nasabah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','terblokir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_petugas`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$RIOvbp2K3yttLRcFoPUXLuXAp8Qxcd1dmgcitvOMniScCp.ZSY9yS', 'Genta Nilam Prana (Admin)', 'administrator', 'aktif', NULL, '2026-08-01 04:01:31', '2026-08-01 04:01:31'),
(2, 'teller1', '$2y$12$7CgwsJocZc1yP7WFee3unOzMvG52RGyFHJY/ZHTgdIhqJ3saCV26O', 'Siti Rahmawati (Teller 1)', 'teller', 'aktif', NULL, '2026-08-01 04:01:31', '2026-08-14 23:57:19'),
(3, 'supervisor1', '$2y$12$51M7/6GPxftKyn4c2I7mv.Ve.e3ZGA1EJOsNvhM7zezufJYejG/Ei', 'Budi Santoso, S.Pd (Supervisor)', 'supervisor', 'aktif', NULL, '2026-08-01 04:01:31', '2026-08-15 03:15:21'),
(4, 'siswa1', '$2y$12$yImRoPWlwQzcVF7VG81WquCRJoGr5xhuHlFo8b3jTgBRR/9PR3YZ2', 'Ahmad Rizky (Nasabah)', 'nasabah', 'aktif', NULL, '2026-08-01 04:01:31', '2026-08-01 04:01:31');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `akun_jurnal`
--
ALTER TABLE `akun_jurnal`
  ADD PRIMARY KEY (`kode_akun`);

--
-- Indeks untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurnal_akuntansi`
--
ALTER TABLE `jurnal_akuntansi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_akuntansi_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `jurnal_akuntansi_kode_akun_foreign` (`kode_akun`);

--
-- Indeks untuk tabel `laporan_harian`
--
ALTER TABLE `laporan_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_harian_user_id_foreign` (`user_id`),
  ADD KEY `laporan_harian_supervisor_id_foreign` (`supervisor_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nasabah_no_rekening_unique` (`no_rekening`),
  ADD UNIQUE KEY `nasabah_nis_unique` (`nis`),
  ADD UNIQUE KEY `nasabah_user_id_unique` (`user_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `rekening`
--
ALTER TABLE `rekening`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rekening_no_rekening_unique` (`no_rekening`),
  ADD KEY `rekening_id_nasabah_foreign` (`id_nasabah`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaksi_no_transaksi_unique` (`no_transaksi`),
  ADD KEY `transaksi_id_nasabah_foreign` (`id_nasabah`),
  ADD KEY `transaksi_id_user_foreign` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurnal_akuntansi`
--
ALTER TABLE `jurnal_akuntansi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `laporan_harian`
--
ALTER TABLE `laporan_harian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `rekening`
--
ALTER TABLE `rekening`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `jurnal_akuntansi`
--
ALTER TABLE `jurnal_akuntansi`
  ADD CONSTRAINT `jurnal_akuntansi_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `jurnal_akuntansi_kode_akun_foreign` FOREIGN KEY (`kode_akun`) REFERENCES `akun_jurnal` (`kode_akun`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `laporan_harian`
--
ALTER TABLE `laporan_harian`
  ADD CONSTRAINT `laporan_harian_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `laporan_harian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD CONSTRAINT `nasabah_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekening`
--
ALTER TABLE `rekening`
  ADD CONSTRAINT `rekening_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_id_nasabah_foreign` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `transaksi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
