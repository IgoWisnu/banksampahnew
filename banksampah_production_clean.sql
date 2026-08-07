-- ========================================================
-- Bank Sampah Production Database Dump (Clean Version)
-- Superadmin: superadmin@mankadibalirecycling.com
-- Password  : 123456
-- Banjar    : mankadibalirecycling (id: 1)
-- Generated Date: 2026-08-07 07:44:37
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Drop Views, Procedures, & Tables
--

DROP VIEW IF EXISTS `v_saldo_dinamis_nasabah`;
DROP PROCEDURE IF EXISTS `sp_get_profile`;

DROP TABLE IF EXISTS `banjar`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `jenis_sampah`;
DROP TABLE IF EXISTS `artikel`;
DROP TABLE IF EXISTS `antrian_email`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `tabungan`;
DROP TABLE IF EXISTS `transaksi_sampah`;
DROP TABLE IF EXISTS `transaksi_sampahdetail`;
DROP TABLE IF EXISTS `tabungan_transaksi`;
DROP TABLE IF EXISTS `penarikan_detail`;
DROP TABLE IF EXISTS `harga_sampah_history`;

-- Table structure for `banjar`
CREATE TABLE `banjar` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `alamat` text,
  `email` varchar(255) DEFAULT NULL,
  `no_telp` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `margin_value` decimal(5,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `user`
CREATE TABLE `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `banjar_id` int unsigned DEFAULT NULL,
  `username` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notelp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `profile` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_verif` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isVerif` int NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `fk_user_banjar` (`banjar_id`),
  CONSTRAINT `fk_user_banjar` FOREIGN KEY (`banjar_id`) REFERENCES `banjar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `jenis_sampah`
CREATE TABLE `jenis_sampah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `banjar_id` int unsigned DEFAULT NULL,
  `jenis_sampah` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori_sampah` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sub_kategori_sampah` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga_sampah` int NOT NULL,
  `stok_tersisa` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_jenis_sampah_banjar` (`banjar_id`),
  CONSTRAINT `fk_jenis_sampah_banjar` FOREIGN KEY (`banjar_id`) REFERENCES `banjar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `artikel`
CREATE TABLE `artikel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `banjar_id` int unsigned DEFAULT NULL,
  `judul` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_artikel_banjar` (`banjar_id`),
  CONSTRAINT `fk_artikel_banjar` FOREIGN KEY (`banjar_id`) REFERENCES `banjar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `antrian_email`
CREATE TABLE `antrian_email` (
  `id_antrian` int NOT NULL AUTO_INCREMENT,
  `email_tujuan` varchar(80) NOT NULL,
  `subjek` varchar(150) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('antri','terkirim','gagal') DEFAULT 'antri',
  `tgl_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `tgl_terkirim` datetime DEFAULT NULL,
  PRIMARY KEY (`id_antrian`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COLLATE=utf8mb4_general_ci;

-- Table structure for `migrations`
CREATE TABLE `migrations` (
  `version` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `tabungan`
CREATE TABLE `tabungan` (
  `id_tabungan` int NOT NULL AUTO_INCREMENT,
  `id_user_nasabah` int DEFAULT NULL,
  `saldo` int NOT NULL,
  `tgl_buka_rekening` date DEFAULT NULL,
  PRIMARY KEY (`id_tabungan`),
  KEY `fk_tabungan_user` (`id_user_nasabah`),
  CONSTRAINT `fk_tabungan_user` FOREIGN KEY (`id_user_nasabah`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `transaksi_sampah`
CREATE TABLE `transaksi_sampah` (
  `id_transaksi_sampah` int NOT NULL AUTO_INCREMENT,
  `no_invoice` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe_transaksi` enum('beli','jual') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'beli',
  `id_user_staff` int DEFAULT NULL,
  `id_user_nasabah` int DEFAULT NULL,
  `nama_pihak_luar` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_transaksi` int DEFAULT NULL,
  `status_pembayaran` enum('Pending','Lunas') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Lunas',
  `biaya_tambahan` int DEFAULT '0',
  `keterangan_biaya` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `grand_total` int DEFAULT '0',
  `tgl_transaksi` datetime DEFAULT NULL,
  `tgl_pelunasan` datetime DEFAULT NULL,
  `banjar_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id_transaksi_sampah`),
  KEY `fk_transaksi_sampah_user_staff` (`id_user_staff`),
  KEY `fk_transaksi_sampah_user_nasabah` (`id_user_nasabah`),
  KEY `idx_id_user_nasabah` (`id_user_nasabah`),
  KEY `fk_transaksi_sampah_banjar` (`banjar_id`),
  CONSTRAINT `fk_transaksi_sampah_banjar` FOREIGN KEY (`banjar_id`) REFERENCES `banjar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_transaksi_sampah_user_nasabah` FOREIGN KEY (`id_user_nasabah`) REFERENCES `user` (`id_user`),
  CONSTRAINT `fk_transaksi_sampah_user_staff` FOREIGN KEY (`id_user_staff`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `transaksi_sampahdetail`
CREATE TABLE `transaksi_sampahdetail` (
  `id_transaksi_sampahDetail` int NOT NULL AUTO_INCREMENT,
  `id_transaksi_sampah` int DEFAULT NULL,
  `id_jenis_sampah` int DEFAULT NULL,
  `harga_satuan` int NOT NULL DEFAULT '0',
  `total_harga` int DEFAULT NULL,
  `berat_sampah` float DEFAULT NULL,
  `berat_sisa` float DEFAULT NULL COMMENT 'Sisa berat sampah di kantong ini setelah dikurangi penarikan FIFO',
  `harga_saat_setor` int DEFAULT NULL COMMENT 'Snapshot harga per kg pada saat setor (audit purpose)',
  PRIMARY KEY (`id_transaksi_sampahDetail`),
  KEY `fk_transaksi_sampahDetail_transaksi_sampah` (`id_transaksi_sampah`),
  KEY `fk_transaksi_sampahDetail_jenis_sampah` (`id_jenis_sampah`),
  KEY `idx_berat_sisa` (`berat_sisa`),
  KEY `idx_id_transaksi_sampah` (`id_transaksi_sampah`),
  KEY `idx_id_jenis_sampah` (`id_jenis_sampah`),
  CONSTRAINT `fk_transaksi_sampahDetail_jenis_sampah` FOREIGN KEY (`id_jenis_sampah`) REFERENCES `jenis_sampah` (`id`),
  CONSTRAINT `fk_transaksi_sampahDetail_transaksi_sampah` FOREIGN KEY (`id_transaksi_sampah`) REFERENCES `transaksi_sampah` (`id_transaksi_sampah`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `tabungan_transaksi`
CREATE TABLE `tabungan_transaksi` (
  `id_tabungan_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_tabungan` int NOT NULL,
  `id_transaksi_sampah` int DEFAULT NULL,
  `id_user_staff` int DEFAULT NULL,
  `kredit` int DEFAULT NULL,
  `debit` int DEFAULT NULL,
  `margin` float(11,2) DEFAULT '0.00',
  `debit_final` int DEFAULT '0',
  `tgl_tabungan_transaksi` datetime DEFAULT NULL,
  `banjar_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id_tabungan_transaksi`),
  KEY `fk_tabungan_transaksi_transaksi_sampah` (`id_transaksi_sampah`),
  KEY `fk_tabungan_transaksi_user_staff` (`id_user_staff`),
  KEY `fk_id_tabungan` (`id_tabungan`),
  KEY `fk_tabungan_transaksi_banjar` (`banjar_id`),
  CONSTRAINT `fk_id_tabungan` FOREIGN KEY (`id_tabungan`) REFERENCES `tabungan` (`id_tabungan`),
  CONSTRAINT `fk_tabungan_transaksi_banjar` FOREIGN KEY (`banjar_id`) REFERENCES `banjar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_tabungan_transaksi_transaksi_sampah` FOREIGN KEY (`id_transaksi_sampah`) REFERENCES `transaksi_sampah` (`id_transaksi_sampah`),
  CONSTRAINT `fk_tabungan_transaksi_user_staff` FOREIGN KEY (`id_user_staff`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for `penarikan_detail`
CREATE TABLE `penarikan_detail` (
  `id_penarikan_detail` int NOT NULL AUTO_INCREMENT,
  `id_tabungan_transaksi` int NOT NULL COMMENT 'FK ke transaksi tarik di tabungan_transaksi',
  `id_transaksi_sampahdetail` int NOT NULL COMMENT 'FK ke kantong setoran yang digerus',
  `berat_terambil` float NOT NULL COMMENT 'Berapa kg yg diambil dari kantong ini',
  `harga_saat_tarik` int NOT NULL COMMENT 'Snapshot harga per kg saat tarik (untuk audit)',
  `nilai_terambil` int NOT NULL COMMENT 'Rupiah dari kantong ini (berat_terambil * harga_saat_tarik)',
  `tgl_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penarikan_detail`),
  KEY `fk_pd_tabungan_transaksi` (`id_tabungan_transaksi`),
  KEY `fk_pd_sampahdetail` (`id_transaksi_sampahdetail`),
  CONSTRAINT `fk_pd_sampahdetail` FOREIGN KEY (`id_transaksi_sampahdetail`) REFERENCES `transaksi_sampahdetail` (`id_transaksi_sampahDetail`),
  CONSTRAINT `fk_pd_tabungan_transaksi` FOREIGN KEY (`id_tabungan_transaksi`) REFERENCES `tabungan_transaksi` (`id_tabungan_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Detail kantong mana saja yang digerus pada setiap penarikan (FIFO trail)';

-- Table structure for `harga_sampah_history`
CREATE TABLE `harga_sampah_history` (
  `id_history` int NOT NULL AUTO_INCREMENT,
  `id_jenis_sampah` int NOT NULL,
  `harga_lama` int DEFAULT NULL,
  `harga_baru` int NOT NULL,
  `id_user_admin` int DEFAULT NULL COMMENT 'Admin yg melakukan perubahan',
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Alasan perubahan harga (opsional)',
  `tgl_perubahan` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_history`),
  KEY `fk_hsh_jenis_sampah` (`id_jenis_sampah`),
  KEY `fk_hsh_user_admin` (`id_user_admin`),
  KEY `idx_tgl_perubahan` (`tgl_perubahan`),
  CONSTRAINT `fk_hsh_jenis_sampah` FOREIGN KEY (`id_jenis_sampah`) REFERENCES `jenis_sampah` (`id`),
  CONSTRAINT `fk_hsh_user_admin` FOREIGN KEY (`id_user_admin`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Log historis perubahan harga jenis sampah';

-- View structure for `v_saldo_dinamis_nasabah`
CREATE VIEW `v_saldo_dinamis_nasabah` AS select `t`.`id_tabungan` AS `id_tabungan`,`t`.`id_user_nasabah` AS `id_user_nasabah`,`u`.`username` AS `username`,coalesce(sum((`tsd`.`berat_sisa` * `js`.`harga_sampah`)),0) AS `saldo_dinamis`,count(distinct (case when (`tsd`.`berat_sisa` > 0) then `tsd`.`id_transaksi_sampahDetail` end)) AS `jumlah_kantong_aktif` from ((((`tabungan` `t` left join `user` `u` on((`t`.`id_user_nasabah` = `u`.`id_user`))) left join `transaksi_sampah` `ts` on((`ts`.`id_user_nasabah` = `t`.`id_user_nasabah`))) left join `transaksi_sampahdetail` `tsd` on(((`tsd`.`id_transaksi_sampah` = `ts`.`id_transaksi_sampah`) and (`tsd`.`berat_sisa` > 0)))) left join `jenis_sampah` `js` on((`tsd`.`id_jenis_sampah` = `js`.`id`))) group by `t`.`id_tabungan`,`t`.`id_user_nasabah`,`u`.`username`;

-- Stored Procedure structure for `sp_get_profile`
DELIMITER $$
CREATE PROCEDURE `sp_get_profile`(IN `in_id_user` INT)
BEGIN
    SELECT * FROM user
    JOIN tabungan ON user.id_user = tabungan.id_user_nasabah
    WHERE user.id_user = in_id_user;
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Initial Data for `banjar` (mankadibalirecycling)
-- --------------------------------------------------------

INSERT INTO `banjar` (`id`, `nama`, `alamat`, `email`, `no_telp`, `status`, `margin_value`) VALUES
(1, 'mankadibalirecycling', '-', NULL, NULL, 'active', 0.00);

-- --------------------------------------------------------
-- Initial Data for `user` (Superadmin Account)
-- --------------------------------------------------------

INSERT INTO `user` (`id_user`, `banjar_id`, `username`, `password`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `email`, `notelp`, `role`, `profile`, `kode_verif`, `isVerif`) VALUES
(1, 1, 'superadmin@mankadibalirecycling.com', '$2y$10$6fUzpFA.ZxxXNYkOUVVNNu7P/D5O8HYxFSlMog0Lea4pi.VlYbHfO', 'Superadmin', NULL, NULL, NULL, 'superadmin@mankadibalirecycling.com', NULL, 'superadmin', NULL, NULL, 1);

-- --------------------------------------------------------
-- Initial Data for `jenis_sampah` (Banjar ID 1: mankadibalirecycling)
-- --------------------------------------------------------

INSERT INTO `jenis_sampah` (`banjar_id`, `kategori_sampah`, `jenis_sampah`, `sub_kategori_sampah`, `harga_sampah`) VALUES
-- PLASTIK (ANORGANIK)
(1, 'ANORGANIK', 'PET B Clear/CW', 'PLASTIK', 7200),
(1, 'ANORGANIK', 'PET B Blue/BM', 'PLASTIK', 5800),
(1, 'ANORGANIK', 'PET Mix', 'PLASTIK', 6500),
(1, 'ANORGANIK', 'Ember', 'PLASTIK', 1000),
(1, 'ANORGANIK', 'Putihan', 'PLASTIK', 1800),
(1, 'ANORGANIK', 'Jerigen/ Aq 1 ( bening)', 'PLASTIK', 5500),
(1, 'ANORGANIK', 'Jerigen Kecap/ Saos', 'PLASTIK', 2500),
(1, 'ANORGANIK', 'Jerigen Putih', 'PLASTIK', 2500),
(1, 'ANORGANIK', 'Jerigen Warna', 'PLASTIK', 2000),
(1, 'ANORGANIK', 'Cup/ Gelas', 'PLASTIK', 2000),
(1, 'ANORGANIK', 'Plastik Kresek', 'PLASTIK', 400),
(1, 'ANORGANIK', 'Tutup HD', 'PLASTIK', 7200),

-- BESI (ANORGANIK)
(1, 'ANORGANIK', 'Besi 1 (Tebal)', 'BESI', 4500),
(1, 'ANORGANIK', 'Besi 2 (Tipis)', 'BESI', 1800),
(1, 'ANORGANIK', 'Aluminum Can', 'BESI', 20000),
(1, 'ANORGANIK', 'Omplong', 'BESI', 1800),

-- KERTAS (ANORGANIK)
(1, 'ANORGANIK', 'Kertas', 'KERTAS', 1500),
(1, 'ANORGANIK', 'Kardus', 'KERTAS', 1500),
(1, 'ANORGANIK', 'Duplek', 'KERTAS', 400),

-- BOTOL (ANORGANIK)
(1, 'ANORGANIK', 'Botol Kaca Mix/Beling', 'BOTOL', 150),
(1, 'ANORGANIK', 'Botol Bintang Besar', 'BOTOL', 1000),
(1, 'ANORGANIK', 'Botol Draft Besar', 'BOTOL', 1000),
(1, 'ANORGANIK', 'Botol Bintang Kecil', 'BOTOL', 500),
(1, 'ANORGANIK', 'Botol Heineken Kecil', 'BOTOL', 500),
(1, 'ANORGANIK', 'Botol Kecap Besar', 'BOTOL', 500),
(1, 'ANORGANIK', 'Botol Kecap Kecil', 'BOTOL', 300);

SET FOREIGN_KEY_CHECKS=1;
