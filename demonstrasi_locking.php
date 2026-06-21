<?php
// demonstrasi_locking.php
// Skrip ini dibuat untuk mendemonstrasikan fitur Table Locking (Pessimistic Locking)
// yang merupakan salah satu syarat dalam panduan UAS.
//
// Cara kerja:
// Skrip ini akan melakukan START TRANSACTION, lalu membaca row dari tabel 'ingredients' dengan 'FOR UPDATE'.
// Jika skrip ini dijalankan di dua tab browser yang berbeda secara bersamaan,
// eksekusi pada tab kedua akan terhenti (menunggu) sampai tab pertama melakukan COMMIT atau ROLLBACK.

require_once 'includes/db.php';

echo "<h1>Demonstrasi Table Locking (Pessimistic Locking)</h1>";

try {
    // 1. Memulai transaksi
    $conn->begin_transaction();
    echo "<p>[" . date('H:i:s') . "] Transaksi dimulai.</p>";

    // ID bahan baku yang akan dikunci (misal ID 1: Biji Kopi)
    $ingredient_id = 1;

    echo "<p>[" . date('H:i:s') . "] Mengambil data stok untuk bahan baku ID $ingredient_id dengan mode <strong>FOR UPDATE</strong>...</p>";
    
    // 2. Mengunci baris data (Pessimistic Locking)
    // Query ini memastikan tidak ada transaksi lain yang bisa mengubah (atau melakukan FOR UPDATE) 
    // pada baris ini sebelum transaksi ini selesai.
    $stmt = $conn->prepare("SELECT ingredient_name, stock_quantity FROM ingredients WHERE id = ? FOR UPDATE");
    $stmt->bind_param("i", $ingredient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $current_stock = $row['stock_quantity'];
        echo "<p>[" . date('H:i:s') . "] Data berhasil dikunci. Stok saat ini untuk <strong>{$row['ingredient_name']}</strong> adalah <strong>$current_stock</strong>.</p>";
        
        // Simulasi proses bisnis yang memakan waktu (misal: komunikasi dengan API payment atau validasi kompleks)
        echo "<p>[" . date('H:i:s') . "] Mensimulasikan pemrosesan yang lambat (jeda 5 detik)...</p>";
        flush(); // Memaksa output ke browser (jika didukung)
        sleep(5);
        
        // 3. Mengubah data
        $new_stock = $current_stock - 1; // Simulasi pengurangan stok
        $update_stmt = $conn->prepare("UPDATE ingredients SET stock_quantity = ? WHERE id = ?");
        $update_stmt->bind_param("di", $new_stock, $ingredient_id);
        $update_stmt->execute();
        
        echo "<p>[" . date('H:i:s') . "] Stok berhasil dikurangi menjadi $new_stock.</p>";
    } else {
        echo "<p>Bahan baku tidak ditemukan.</p>";
    }

    // 4. Menyelesaikan transaksi (COMMIT)
    // Pada titik ini, kunci (lock) akan dilepas, dan transaksi lain yang sedang menunggu dapat melanjutkan prosesnya.
    $conn->commit();
    echo "<p style='color: green;'>[" . date('H:i:s') . "] Transaksi berhasil (COMMIT). Lock dilepaskan.</p>";

} catch (Exception $e) {
    // Jika terjadi error, batalkan semua perubahan dan lepaskan kunci
    $conn->rollback();
    echo "<p style='color: red;'>[" . date('H:i:s') . "] Transaksi dibatalkan (ROLLBACK). Error: " . $e->getMessage() . "</p>";
}

echo "<hr><p><em>Instruksi pengujian: Buka skrip ini di dua tab browser yang berbeda. Refresh tab pertama, dan segera refresh tab kedua. Anda akan melihat tab kedua tertunda loading-nya (spinning) selama 5 detik hingga tab pertama menyelesaikan COMMIT.</em></p>";
?>
