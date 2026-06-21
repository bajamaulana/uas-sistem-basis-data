# Laporan Proyek Akhir Sistem Basis Data
## Studi Kasus: Sistem Manajemen "Ngopidea Artisanal Cafe"

---

### BAB I Pendahuluan
**Latar Belakang:**  
Ngopidea Artisanal Cafe membutuhkan sebuah sistem basis data yang terpusat dan terstruktur untuk mengelola operasional sehari-hari mulai dari manajemen inventori, transaksi pesanan pelanggan, manajemen reservasi meja, hingga pelaporan kinerja bisnis. 

**Rumusan Masalah:**  
Bagaimana merancang dan mengimplementasikan basis data relasional yang komprehensif, aman, serta memiliki fitur manajemen transaksi (TCL) dan trigger otomatis untuk menjaga konsistensi stok inventori?

**Tujuan:**  
Mengembangkan solusi sistem basis data menggunakan DBMS MySQL yang mencakup minimal 20 entitas, mengintegrasikan fitur *stored procedure*, *trigger*, dan *cursor*, serta mendemonstrasikan kapabilitas reporting menggunakan aggregate functions.

**Ruang Lingkup:**  
- Sistem Point of Sales (Pesanan dan Detail Pesanan).
- Sistem Inventori (Bahan baku, Stok, Transaksi Keluar Masuk).
- Sistem Manajemen Pengguna (Pelanggan, Pegawai).

---

### BAB II Analisis Proses Bisnis
**Aktor Sistem:**
1. Pelanggan: Melakukan pemesanan, melihat menu, membuat reservasi meja.
2. Staff (Barista/Kasir): Mengelola pesanan, mengonfirmasi reservasi, menyesuaikan stok inventori.

**Rancangan Kebutuhan Data:**
Sistem membutuhkan data pengguna, menu produk, kategori, data bahan baku (ingredients), pencatatan transaksi masuk-keluar stok, pesanan pelanggan, detail pesanan, dan log aktivitas.

**Aturan Bisnis:**
1. Stok bahan baku tidak boleh bernilai negatif (validasi menggunakan trigger).
2. Ketika status pesanan berubah, sistem harus mencatat riwayat perubahan (audit log).
3. Transaksi inventory 'Out' otomatis memotong stok bahan baku terkait (trigger).

---

### BAB III Perancangan Basis Data
**ERD (Entity Relationship Diagram):**
*(Masukkan gambar ERD di sini)*
Entitas yang digunakan berjumlah 20 tabel, di antaranya: `users`, `roles`, `customers`, `employees`, `categories`, `products`, `ingredients`, `product_recipes`, `tables`, `reservations`, `payment_methods`, `promotions`, `orders`, `order_details`, `order_promotions`, `suppliers`, `purchase_orders`, `purchase_order_details`, `inventory_transactions`, `audit_logs`.

**Normalisasi:**
Database ini sudah mencapai tahap 3NF di mana tidak ada dependensi transitif. Contoh: Detail pesanan memisahkan relasi `order_id` dan `product_id`.

**Data Dictionary:**
*(Sertakan screenshot atau tabel struktur database)*

---

### BAB IV Implementasi Database
**DDL (Data Definition Language):**
Sertakan cuplikan kode `CREATE TABLE` utama seperti pembuatan tabel `orders`, `products`, dan `inventory_transactions`.

**DML (Data Manipulation Language):**
Sertakan cuplikan kode `INSERT` atau skrip seeder yang digunakan untuk memasukkan 100+ baris data ke dalam database.

---

### BAB V Implementasi Fitur Lanjutan DBMS
**Trigger:**
1. `trg_audit_orders_update`: Melakukan audit log setiap status order berubah.
2. `trg_validate_stock_before_out`: Mencegah stok menjadi negatif saat transaksi *Out*.
3. `trg_update_stock_after_trx`: Mengubah total tabel `ingredients` setelah ada transaksi.

**Function:**
1. `fn_calculate_tax`: Menghitung pajak standar 10%.
2. `fn_get_customer_loyalty`: Mendapatkan total uang yang dihabiskan pelanggan (menggunakan fungsi agregat `SUM`).

**Aggregate Function Queries:**
5 query yang digunakan di modul laporan meliputi penggunaan fungsi `SUM()`, `AVG()`, `MAX()`, `MIN()`, dan `COUNT()`. (Bisa dilihat pada `admin/reports.php`).

**TCL (Transaction Control Language) & Locking:**
Diimplementasikan pada Procedure `sp_checkout_order` yang menggunakan `START TRANSACTION`, `COMMIT`, `ROLLBACK` dan `FOR UPDATE` (table locking) untuk menghindari *race condition* saat checkout.

**Stored Procedure:**
1. `sp_restock_ingredient`: Mencatat restock inventori.
2. `sp_checkout_order`: Proses checkout pesanan aman menggunakan lock.
3. `sp_generate_daily_report`: Menghasilkan laporan harian dengan menggunakan **CURSOR**.

---

### BAB VI Backup dan Restore
**Backup Database:**
Metode backup menggunakan `mysqldump`. Perintah yang digunakan terlampir pada file `backup.bat`.
```bash
mysqldump -h 172.22.148.251 -u root -p coffeeidea > backup.sql
```

**Restore Database:**
Langkah restore menggunakan CLI `mysql`. Perintah terlampir pada file `restore.bat`.
```bash
mysql -h 172.22.148.251 -u root -p coffeeidea < backup.sql
```

---

### BAB VII Reporting dan Dashboard
**Kebutuhan Informasi Manajemen:**
Laporan diperlukan oleh Staff dan Manajemen untuk memonitor kesehatan bisnis.
Daftar 5 Laporan yang telah dibuat di halaman **Laporan UAS**:
1. Laporan Penjualan Bulanan (`SUM`, `COUNT`)
2. Produk Terlaris (`SUM` quantity)
3. Ringkasan Transaksi Tertinggi, Terendah, Rata-rata (`MAX`, `MIN`, `AVG`)
4. Stok Kritis (`MIN` logically ordered by quantity)
5. Pelanggan Teraktif (`COUNT`, `SUM`)

*(Lampirkan screenshot halaman admin/reports.php di sini)*

---

### BAB VIII Kesimpulan dan Saran
Sistem Basis Data Ngopidea telah berhasil memenuhi seluruh spesifikasi kompleksitas yang disyaratkan. Penggunaan *stored procedures* dan *triggers* mampu menangani *business logic* di level database sehingga meningkatkan integritas dan keamanan data. Ke depannya, sistem dapat dikembangkan dengan partisi tabel untuk data riwayat transaksi jika ukurannya membesar.

---
**Lampiran Pembagian Tugas:**
| Nama Anggota | NIM | Tugas | Kontribusi (%) |
|---|---|---|---|
| (Nama 1) | (NIM) | Membuat ERD & Struktur Tabel | 25% |
| (Nama 2) | (NIM) | Membuat Trigger & Function | 25% |
| (Nama 3) | (NIM) | Stored Procedure & Cursors | 25% |
| (Nama 4) | (NIM) | Seeder Data & Reporting Dashboard | 25% |
