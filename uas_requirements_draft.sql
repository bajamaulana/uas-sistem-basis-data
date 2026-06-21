-- ==========================================
-- SCRIPT PELENGKAP UNTUK UAS BASIS DATA LANJUT
-- Berdasarkan Panduan Proyek Akhir
-- ==========================================

-- --------------------------------------------------------
-- BAGIAN 1: PERANCANGAN BASIS DATA (Data Dictionary)
-- --------------------------------------------------------
-- Menampilkan Data Dictionary dari tabel-tabel yang ada.
-- Jalankan query ini untuk melihat struktur database.

SELECT 
    TABLE_NAME AS 'Nama Tabel',
    COLUMN_NAME AS 'Nama Kolom',
    COLUMN_TYPE AS 'Tipe Data',
    IS_NULLABLE AS 'Boleh Null?',
    COLUMN_KEY AS 'Key',
    COLUMN_DEFAULT AS 'Default Value',
    EXTRA AS 'Extra',
    COLUMN_COMMENT AS 'Keterangan'
FROM 
    information_schema.COLUMNS
WHERE 
    TABLE_SCHEMA = 'coffeeidea'
ORDER BY 
    TABLE_NAME, ORDINAL_POSITION;

-- --------------------------------------------------------
-- BAGIAN 2: IMPLEMENTASI DATABASE (DDL, DML, Constraint)
-- --------------------------------------------------------
-- *Catatan: Pastikan Anda sudah menjalankan schema_dump.sql terlebih dahulu.*
-- Berikut adalah contoh penambahan tabel untuk memenuhi minimal 20 entitas
-- (Asumsi schema saat ini memiliki < 20 tabel. Silakan sesuaikan/tambahkan jika kurang).

-- Contoh Penambahan Tabel Master (jika belum ada)
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
);

-- Contoh Penambahan Kolom dan Constraint (jika diperlukan)
-- ALTER TABLE `products` ADD COLUMN `category_id` INT;
-- ALTER TABLE `products` ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`);

-- --------------------------------------------------------
-- BAGIAN 3: AGGREGATE FUNCTION (Minimal 5 Query)
-- --------------------------------------------------------
-- Panduan meminta penggunaan MIN, MAX, SUM, AVG, COUNT

-- 1. SUM: Total pendapatan dari semua pesanan yang 'Completed'
SELECT SUM(total_amount) AS TotalPendapatan
FROM orders
WHERE status = 'Completed';

-- 2. AVG: Rata-rata nilai pesanan per transaksi
SELECT AVG(total_amount) AS RataRataPesanan
FROM orders
WHERE status = 'Completed';

-- 3. MAX: Nilai pesanan tertinggi
SELECT MAX(total_amount) AS PesananTertinggi
FROM orders;

-- 4. MIN: Harga produk termurah di menu
SELECT MIN(price) AS HargaTermurah
FROM products;

-- 5. COUNT: Jumlah pesanan per status
SELECT status, COUNT(*) AS JumlahPesanan
FROM orders
GROUP BY status;

-- --------------------------------------------------------
-- BAGIAN 4: REPORTING DAN DASHBOARD (Minimal 5 Laporan)
-- --------------------------------------------------------

-- Laporan 1: Laporan Penjualan Harian
CREATE OR REPLACE VIEW view_laporan_penjualan_harian AS
SELECT 
    DATE(order_date) AS Tanggal,
    COUNT(id) AS TotalTransaksi,
    SUM(total_amount) AS TotalPendapatan
FROM orders
WHERE status = 'Completed'
GROUP BY DATE(order_date)
ORDER BY Tanggal DESC;

-- Laporan 2: Produk Terlaris (Berdasarkan jumlah yang dipesan)
CREATE OR REPLACE VIEW view_produk_terlaris AS
SELECT 
    p.name AS NamaProduk,
    SUM(od.quantity) AS TotalTerjual
FROM order_details od
JOIN products p ON od.product_id = p.id
JOIN orders o ON od.order_id = o.id
WHERE o.status = 'Completed'
GROUP BY p.id, p.name
ORDER BY TotalTerjual DESC;

-- Laporan 3: Kinerja Karyawan (Jumlah pesanan yang ditangani)
CREATE OR REPLACE VIEW view_kinerja_karyawan AS
SELECT 
    e.name AS NamaKaryawan,
    COUNT(o.id) AS JumlahPesananDitangani,
    SUM(o.total_amount) AS TotalPendapatanDihasilkan
FROM orders o
JOIN employees e ON o.employee_id = e.id
WHERE o.status = 'Completed'
GROUP BY e.id, e.name
ORDER BY TotalPendapatanDihasilkan DESC;

-- Laporan 4: Stok Minimum (Bahan baku yang perlu segera di-restock)
CREATE OR REPLACE VIEW view_stok_minimum AS
SELECT 
    name AS NamaBahan,
    stock_quantity AS StokSaatIni,
    unit AS Satuan
FROM ingredients
WHERE stock_quantity < 10 -- Ambang batas stok minimum, sesuaikan dengan kebutuhan
ORDER BY stock_quantity ASC;

-- Laporan 5: Analisis Metode Pembayaran
CREATE OR REPLACE VIEW view_analisis_pembayaran AS
SELECT 
    pm.name AS MetodePembayaran,
    COUNT(o.id) AS JumlahPenggunaan,
    SUM(o.total_amount) AS TotalNominal
FROM orders o
JOIN payment_methods pm ON o.payment_method_id = pm.id
WHERE o.status = 'Completed'
GROUP BY pm.id, pm.name
ORDER BY JumlahPenggunaan DESC;

-- Cara menampilkan laporan (Dashboard queries):
-- SELECT * FROM view_laporan_penjualan_harian;
-- SELECT * FROM view_produk_terlaris LIMIT 5;
-- SELECT * FROM view_kinerja_karyawan;
-- SELECT * FROM view_stok_minimum;
-- SELECT * FROM view_analisis_pembayaran;

-- --------------------------------------------------------
-- BAGIAN 5: BACKUP DAN RESTORE DATABASE
-- --------------------------------------------------------
-- Perintah ini dijalankan di Command Prompt / Terminal, BUKAN di dalam GUI SQL.

/*
1. Perintah Backup (mysqldump):
   mysqldump -u root -p coffeeidea > backup_coffeeidea_20260620.sql

   Penjelasan: 
   Perintah ini akan mencadangkan seluruh struktur dan data dari database 'coffeeidea' 
   ke dalam file 'backup_coffeeidea_20260620.sql'.

2. Perintah Restore:
   mysql -u root -p coffeeidea < backup_coffeeidea_20260620.sql

   Penjelasan:
   Perintah ini akan memulihkan data dari file .sql kembali ke database 'coffeeidea'. 
   Pastikan database 'coffeeidea' sudah dibuat sebelumnya menggunakan `CREATE DATABASE coffeeidea;`.
*/
