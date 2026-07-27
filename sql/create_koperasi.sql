-- Modul Koperasi
-- Jalankan file ini di database aplikasi SPP.
-- Tabel ini memisahkan barang koperasi dari tabel jenis_pembayaran.

CREATE TABLE IF NOT EXISTS `koperasi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sekolah_id` bigint(20) UNSIGNED NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('buku','seragam','alat_tulis','atribut','makanan_minuman','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lainnya',
  `satuan` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `harga_beli` decimal(12,2) NOT NULL DEFAULT 0.00,
  `harga_jual` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0,
  `stok_minimum` int(11) NOT NULL DEFAULT 5,
  `deskripsi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `koperasi_kode_barang_unique` (`kode_barang`),
  KEY `idx_koperasi_sekolah_id` (`sekolah_id`),
  KEY `idx_koperasi_kategori` (`kategori`),
  KEY `idx_koperasi_status` (`status`),
  KEY `idx_koperasi_stok` (`stok`),
  CONSTRAINT `fk_koperasi_sekolah` FOREIGN KEY (`sekolah_id`) REFERENCES `sekolah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contoh data awal.
-- Data ini akan masuk ke sekolah pertama yang tersedia.
-- Jika tabel sekolah masih kosong, bagian INSERT ini tidak akan menambahkan data apa pun.

INSERT INTO `koperasi`
  (`sekolah_id`, `kode_barang`, `nama_barang`, `kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok`, `stok_minimum`, `deskripsi`, `status`, `created_at`, `updated_at`)
SELECT
  s.`id`, 'BUK-001', 'Buku Cetak Tema 1', 'buku', 'buku', 45000.00, 50000.00, 30, 5, 'Contoh barang koperasi kategori buku.', 'aktif', NOW(), NOW()
FROM `sekolah` s
ORDER BY s.`id`
LIMIT 1
ON DUPLICATE KEY UPDATE
  `nama_barang` = VALUES(`nama_barang`),
  `updated_at` = NOW();

INSERT INTO `koperasi`
  (`sekolah_id`, `kode_barang`, `nama_barang`, `kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok`, `stok_minimum`, `deskripsi`, `status`, `created_at`, `updated_at`)
SELECT
  s.`id`, 'SRG-001', 'Seragam Putih Merah', 'seragam', 'set', 115000.00, 125000.00, 20, 5, 'Contoh barang koperasi kategori seragam.', 'aktif', NOW(), NOW()
FROM `sekolah` s
ORDER BY s.`id`
LIMIT 1
ON DUPLICATE KEY UPDATE
  `nama_barang` = VALUES(`nama_barang`),
  `updated_at` = NOW();

INSERT INTO `koperasi`
  (`sekolah_id`, `kode_barang`, `nama_barang`, `kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok`, `stok_minimum`, `deskripsi`, `status`, `created_at`, `updated_at`)
SELECT
  s.`id`, 'ATK-001', 'Pensil 2B', 'alat_tulis', 'pcs', 2000.00, 3000.00, 100, 10, 'Contoh barang koperasi kategori alat tulis.', 'aktif', NOW(), NOW()
FROM `sekolah` s
ORDER BY s.`id`
LIMIT 1
ON DUPLICATE KEY UPDATE
  `nama_barang` = VALUES(`nama_barang`),
  `updated_at` = NOW();

-- Opsional: salin data barang yang sebelumnya terlanjur masuk ke jenis_pembayaran.
-- Query ini hanya MENYALIN ke koperasi, tidak menghapus data lama dari jenis_pembayaran.
-- Setelah dicek aman, admin bisa menghapus data barang lama dari menu Jenis Pembayaran secara manual.

INSERT INTO `koperasi`
  (`sekolah_id`, `kode_barang`, `nama_barang`, `kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok`, `stok_minimum`, `deskripsi`, `status`, `created_at`, `updated_at`)
SELECT
  jp.`sekolah_id`,
  CONCAT('JP-', jp.`id`) AS `kode_barang`,
  jp.`nama_pembayaran` AS `nama_barang`,
  CASE
    WHEN LOWER(jp.`nama_pembayaran`) LIKE '%buku%' THEN 'buku'
    WHEN LOWER(jp.`nama_pembayaran`) LIKE '%seragam%' THEN 'seragam'
    WHEN LOWER(jp.`nama_pembayaran`) LIKE '%pensil%'
      OR LOWER(jp.`nama_pembayaran`) LIKE '%pulpen%'
      OR LOWER(jp.`nama_pembayaran`) LIKE '%alat tulis%' THEN 'alat_tulis'
    WHEN LOWER(jp.`nama_pembayaran`) LIKE '%atribut%'
      OR LOWER(jp.`nama_pembayaran`) LIKE '%topi%'
      OR LOWER(jp.`nama_pembayaran`) LIKE '%dasi%' THEN 'atribut'
    ELSE 'lainnya'
  END AS `kategori`,
  'pcs' AS `satuan`,
  0.00 AS `harga_beli`,
  jp.`nominal` AS `harga_jual`,
  0 AS `stok`,
  5 AS `stok_minimum`,
  CONCAT('Disalin dari jenis pembayaran ID ', jp.`id`, '. Periksa kembali stok dan satuan barang.') AS `deskripsi`,
  'aktif' AS `status`,
  NOW() AS `created_at`,
  NOW() AS `updated_at`
FROM `jenis_pembayaran` jp
WHERE LOWER(jp.`nama_pembayaran`) LIKE '%buku%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%seragam%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%pensil%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%pulpen%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%alat tulis%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%atribut%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%topi%'
   OR LOWER(jp.`nama_pembayaran`) LIKE '%dasi%'
ON DUPLICATE KEY UPDATE
  `nama_barang` = VALUES(`nama_barang`),
  `harga_jual` = VALUES(`harga_jual`),
  `deskripsi` = VALUES(`deskripsi`),
  `updated_at` = NOW();

-- Tabel transaksi penjualan koperasi.
-- Data ini terpisah dari pembayaran SPP/tagihan.
CREATE TABLE IF NOT EXISTS `koperasi_penjualan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sekolah_id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `kode_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `catatan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `koperasi_penjualan_kode_transaksi_unique` (`kode_transaksi`),
  KEY `idx_koperasi_penjualan_sekolah_id` (`sekolah_id`),
  KEY `idx_koperasi_penjualan_siswa_id` (`siswa_id`),
  KEY `idx_koperasi_penjualan_tanggal` (`tanggal`),
  CONSTRAINT `fk_koperasi_penjualan_sekolah` FOREIGN KEY (`sekolah_id`) REFERENCES `sekolah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_koperasi_penjualan_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `koperasi_penjualan_detail` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `koperasi_penjualan_id` bigint(20) UNSIGNED NOT NULL,
  `koperasi_id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_koperasi_penjualan_detail_penjualan_id` (`koperasi_penjualan_id`),
  KEY `idx_koperasi_penjualan_detail_koperasi_id` (`koperasi_id`),
  CONSTRAINT `fk_koperasi_penjualan_detail_penjualan` FOREIGN KEY (`koperasi_penjualan_id`) REFERENCES `koperasi_penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_koperasi_penjualan_detail_koperasi` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
