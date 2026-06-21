<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

if (!isStaff()) {
    header("Location: ../index.php");
    exit();
}

// 1. Penjualan Bulanan (SUM & COUNT)
$report1 = $conn->query("
    SELECT DATE_FORMAT(order_date, '%Y-%m') as month, 
           COUNT(id) as total_orders, 
           SUM(total_amount) as revenue 
    FROM orders 
    WHERE status='Completed' 
    GROUP BY month 
    ORDER BY month DESC 
    LIMIT 5
");

// 2. Produk Terlaris (SUM)
$report2 = $conn->query("
    SELECT p.product_name, SUM(od.quantity) as total_qty 
    FROM order_details od 
    JOIN products p ON od.product_id=p.id 
    JOIN orders o ON od.order_id=o.id 
    WHERE o.status='Completed' 
    GROUP BY p.id 
    ORDER BY total_qty DESC 
    LIMIT 5
");

// 3. Ringkasan Transaksi (MAX, MIN, AVG)
$report3 = $conn->query("
    SELECT MAX(total_amount) as max_trx, 
           MIN(total_amount) as min_trx, 
           AVG(total_amount) as avg_trx 
    FROM orders 
    WHERE status='Completed'
")->fetch_assoc();

// 4. Stok Kritis (MIN)
// Mengambil item dengan stock terkecil
$report4 = $conn->query("
    SELECT ingredient_name, stock_quantity, unit 
    FROM ingredients 
    ORDER BY stock_quantity ASC 
    LIMIT 5
");

// 5. Pelanggan Teraktif (COUNT, SUM)
$report5 = $conn->query("
    SELECT c.full_name, 
           COUNT(o.id) as total_orders, 
           SUM(o.total_amount) as total_spent 
    FROM orders o 
    JOIN customers c ON o.customer_id = c.id 
    WHERE o.status='Completed' 
    GROUP BY c.id 
    ORDER BY total_orders DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Manajemen | Ngopidea Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fff8f5] text-[#231a13]">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar Placeholder -->
    <aside class="w-64 bg-[#f2dfd3] border-r border-[#e0c0b0] flex flex-col hidden md:flex">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-[#9a4600]">Ngopidea</h2>
            <p class="text-sm text-[#7a562a]">Staff Panel</p>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard_staff.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#584236] hover:bg-[#fff1e9]">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="reports.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-[#9a4600] text-white">
                <span class="material-symbols-outlined">bar_chart</span> Laporan UAS
            </a>
            <a href="../index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#584236] hover:bg-[#fff1e9]">
                <span class="material-symbols-outlined">home</span> Back to Site
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white border-b border-[#e0c0b0] px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Kebutuhan Laporan Manajemen (UAS)</h1>
        </header>

        <main class="p-8 space-y-8">
            
            <!-- Report 3: Aggregates Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-[#e0c0b0]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-[#e9d7cb] rounded-lg text-[#9a4600]"><span class="material-symbols-outlined">trending_up</span></div>
                        <div>
                            <p class="text-sm text-[#584236] font-semibold">Transaksi Terbesar (MAX)</p>
                            <p class="text-2xl font-bold">$<?= number_format($report3['max_trx'] ?? 0, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-[#e0c0b0]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-[#e9d7cb] rounded-lg text-[#9a4600]"><span class="material-symbols-outlined">trending_down</span></div>
                        <div>
                            <p class="text-sm text-[#584236] font-semibold">Transaksi Terkecil (MIN)</p>
                            <p class="text-2xl font-bold">$<?= number_format($report3['min_trx'] ?? 0, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-[#e0c0b0]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-[#e9d7cb] rounded-lg text-[#9a4600]"><span class="material-symbols-outlined">functions</span></div>
                        <div>
                            <p class="text-sm text-[#584236] font-semibold">Rata-rata Transaksi (AVG)</p>
                            <p class="text-2xl font-bold">$<?= number_format($report3['avg_trx'] ?? 0, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Report 1 -->
                <div class="bg-white rounded-xl shadow-sm border border-[#e0c0b0] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e0c0b0] bg-[#fdeade]">
                        <h3 class="font-bold text-lg">1. Laporan Penjualan Bulanan (SUM & COUNT)</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#fff1e9] text-[#584236] text-sm">
                                    <th class="p-4 font-semibold">Bulan</th>
                                    <th class="p-4 font-semibold">Jml Pesanan</th>
                                    <th class="p-4 font-semibold">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $report1->fetch_assoc()): ?>
                                <tr class="border-b border-[#f2dfd3] hover:bg-[#fff8f5]">
                                    <td class="p-4"><?= $row['month'] ?></td>
                                    <td class="p-4"><?= $row['total_orders'] ?></td>
                                    <td class="p-4 font-bold text-[#9a4600]">$<?= number_format($row['revenue'], 2) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Report 2 -->
                <div class="bg-white rounded-xl shadow-sm border border-[#e0c0b0] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e0c0b0] bg-[#fdeade]">
                        <h3 class="font-bold text-lg">2. Produk Terlaris (SUM)</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#fff1e9] text-[#584236] text-sm">
                                    <th class="p-4 font-semibold">Nama Produk</th>
                                    <th class="p-4 font-semibold">Total Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $report2->fetch_assoc()): ?>
                                <tr class="border-b border-[#f2dfd3] hover:bg-[#fff8f5]">
                                    <td class="p-4"><?= htmlspecialchars($row['product_name']) ?></td>
                                    <td class="p-4 font-bold"><?= $row['total_qty'] ?> items</td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Report 4 -->
                <div class="bg-white rounded-xl shadow-sm border border-[#e0c0b0] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e0c0b0] bg-[#fdeade]">
                        <h3 class="font-bold text-lg">3. Stok Kritis / Paling Sedikit (MIN context)</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#fff1e9] text-[#584236] text-sm">
                                    <th class="p-4 font-semibold">Bahan Baku</th>
                                    <th class="p-4 font-semibold">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $report4->fetch_assoc()): ?>
                                <tr class="border-b border-[#f2dfd3] hover:bg-[#fff8f5]">
                                    <td class="p-4"><?= htmlspecialchars($row['ingredient_name']) ?></td>
                                    <td class="p-4 font-bold text-red-600"><?= $row['stock_quantity'] ?> <?= $row['unit'] ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Report 5 -->
                <div class="bg-white rounded-xl shadow-sm border border-[#e0c0b0] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e0c0b0] bg-[#fdeade]">
                        <h3 class="font-bold text-lg">4. Pelanggan Teraktif (COUNT & SUM)</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#fff1e9] text-[#584236] text-sm">
                                    <th class="p-4 font-semibold">Nama Pelanggan</th>
                                    <th class="p-4 font-semibold">Jml Transaksi</th>
                                    <th class="p-4 font-semibold">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $report5->fetch_assoc()): ?>
                                <tr class="border-b border-[#f2dfd3] hover:bg-[#fff8f5]">
                                    <td class="p-4"><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td class="p-4"><?= $row['total_orders'] ?> trx</td>
                                    <td class="p-4 font-bold text-[#9a4600]">$<?= number_format($row['total_spent'], 2) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

</body>
</html>
