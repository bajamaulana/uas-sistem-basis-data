<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

if (!isStaff()) {
    header("Location: ../index.php");
    exit();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $conn->real_escape_string($_POST['status']);
    
    // Check current status first to prevent double-deduction
    $status_check = $conn->query("SELECT status FROM orders WHERE id = $order_id");
    if ($status_check && $status_check->num_rows > 0) {
        $current_status = $status_check->fetch_assoc()['status'];
        
        $conn->query("UPDATE orders SET status = '$new_status' WHERE id = $order_id");
        
        // Phase 1: Auto-Deduction on Order Completion
        if ($new_status === 'Completed' && $current_status !== 'Completed') {
            // Deduct ingredients based on product recipes
            $recipe_sql = "
                SELECT pr.ingredient_id, (pr.quantity_needed * od.quantity) as total_needed 
                FROM order_details od
                JOIN product_recipes pr ON od.product_id = pr.product_id
                WHERE od.order_id = $order_id
            ";
            $recipe_res = $conn->query($recipe_sql);
            
            if ($recipe_res && $recipe_res->num_rows > 0) {
                $deduct_stmt = $conn->prepare("UPDATE ingredients SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $log_stmt = $conn->prepare("INSERT INTO inventory_transactions (ingredient_id, transaction_type, quantity, remarks) VALUES (?, 'Out', ?, ?)");
                
                $remarks = "Deducted from Order #$order_id";
                
                while ($row = $recipe_res->fetch_assoc()) {
                    $ing_id = $row['ingredient_id'];
                    $qty_needed = $row['total_needed'];
                    
                    // Update stock
                    $deduct_stmt->bind_param("di", $qty_needed, $ing_id);
                    $deduct_stmt->execute();
                    
                    // Log transaction
                    $log_stmt->bind_param("ids", $ing_id, $qty_needed, $remarks);
                    $log_stmt->execute();
                }
            }
        }
    }
}

// Get metrics
$pending_count = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE status IN ('Pending', 'Processing')")->fetch_assoc()['cnt'];
$completed_count = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE status = 'Completed'")->fetch_assoc()['cnt'];
$today_count = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE DATE(order_date) = CURDATE()")->fetch_assoc()['cnt'];

// Get orders list
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$status_condition = "";
if ($filter === 'processing') {
    $status_condition = " WHERE o.status IN ('Pending', 'Processing') ";
} elseif ($filter === 'completed') {
    $status_condition = " WHERE o.status = 'Completed' ";
}

$sql = "SELECT o.id, o.order_date, o.total_amount, o.status, c.full_name,
               (SELECT GROUP_CONCAT(CONCAT(od.quantity, 'x ', p.product_name) SEPARATOR ', ') 
                FROM order_details od JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = o.id) as items
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        $status_condition
        ORDER BY o.order_date DESC";
$orders = $conn->query($sql);
?>
<!DOCTYPE html><html class="light" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Artisan Café Admin - Orders Dashboard</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;family=Merriweather:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet">
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "primary-fixed-dim": "#ffb68d",
                    "surface-bright": "#fff8f5",
                    "primary-fixed": "#ffdbc9",
                    "secondary": "#7b572b",
                    "inverse-on-surface": "#ffede3",
                    "outline-variant": "#e0c0b0",
                    "on-error": "#ffffff",
                    "primary-container": "#ff790b",
                    "error-container": "#ffdad6",
                    "outline": "#8c7264",
                    "inverse-primary": "#ffb68d",
                    "on-primary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "surface": "#fff8f5",
                    "on-secondary-fixed-variant": "#604016",
                    "tertiary": "#4d6073",
                    "secondary-fixed": "#ffddb9",
                    "primary": "#9a4600",
                    "tertiary-container": "#8ea2b6",
                    "surface-container": "#fdeade",
                    "on-primary-fixed-variant": "#763300",
                    "on-tertiary-container": "#263849",
                    "on-secondary-fixed": "#2b1700",
                    "on-surface": "#231a13",
                    "on-error-container": "#93000a",
                    "surface-dim": "#e9d7cb",
                    "surface-container-high": "#f7e5d9",
                    "on-tertiary-fixed-variant": "#36495a",
                    "on-primary-fixed": "#321200",
                    "secondary-fixed-dim": "#edbe89",
                    "inverse-surface": "#392e26",
                    "surface-container-highest": "#f2dfd3",
                    "tertiary-fixed": "#d1e5fb",
                    "surface-container-low": "#fff1e9",
                    "background": "#fff8f5",
                    "surface-variant": "#f2dfd3",
                    "on-tertiary-fixed": "#081d2d",
                    "on-secondary-container": "#7a562a",
                    "on-background": "#231a13",
                    "error": "#ba1a1a",
                    "tertiary-fixed-dim": "#b5c9de",
                    "on-surface-variant": "#584236",
                    "surface-tint": "#9a4600",
                    "secondary-container": "#ffcf99",
                    "on-secondary": "#ffffff",
                    "on-primary-container": "#5d2700",
                    "on-tertiary": "#ffffff"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "section-md": "80px",
                    "section-sm": "40px",
                    "container-max-md": "1100px",
                    "unit": "8px",
                    "gutter": "20px",
                    "section-lg": "100px",
                    "container-max-lg": "1200px"
            },
            "fontFamily": {
                    "display-hero": ["Playfair Display"],
                    "label-md": ["Plus Jakarta Sans"],
                    "body-md": ["Merriweather"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "display-hero-mobile": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"],
                    "body-lg": ["Merriweather"],
                    "headline-sm": ["Playfair Display"],
                    "headline-md": ["Playfair Display"]
            },
            "fontSize": {
                    "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}],
                    "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "0px", "fontWeight": "700"}],
                    "display-hero-mobile": ["35px", {"lineHeight": "1.2", "letterSpacing": "1px", "fontWeight": "700"}],
                    "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                    "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-nav-bg {
            background-color: rgba(255, 121, 11, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e0c0b0;
            border-radius: 10px;
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-container selection:text-white">
<!-- Sidebar (Shared Component Logic) -->
<aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container dark:bg-surface-container-low shadow-sm flex flex-col p-4 space-y-2 z-50">
<div class="mb-8 px-4 py-2">
<h1 class="font-headline-sm text-headline-sm font-bold text-primary dark:text-primary-fixed-dim">Ngopidea</h1>
<p class="font-label-md text-label-md text-on-surface-variant opacity-70">Artisanal Cafe Admin</p>
</div>
<nav class="flex-grow space-y-1">
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="dashboard_staff.php">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md">Dashboard</span>
</a>
<!-- Active: Orders -->
<a class="flex items-center gap-3 px-4 py-3 bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary rounded-lg transition-all scale-95 duration-150 active:scale-90" href="orders.php">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
<span class="font-label-md">Orders</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="menu-manage.php">
<span class="material-symbols-outlined" data-icon="restaurant_menu">restaurant_menu</span>
<span class="font-label-md">Menu Management</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="inven.php">
<span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
<span class="font-label-md">Inventory</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="staff.php">
<span class="material-symbols-outlined" data-icon="group">group</span>
<span class="font-label-md">Staff</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="table_reserve.php">
<span class="material-symbols-outlined" data-icon="event_seat">event_seat</span>
<span class="font-label-md">Table Reservations</span>
</a></nav>
<div class="mt-auto border-t border-outline-variant/20 pt-4 space-y-1">
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="#">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-label-md">Settings</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="../logout.php">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-md">Logout</span>
</a>
</div>
</aside>
<!-- Main Content Canvas -->
<main class="ml-64 min-h-screen mt-16">
<!-- Top Bar (Shared Component Logic) -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 bg-surface/95 dark:bg-surface-dim/95 backdrop-blur-md flex items-center h-16 px-8 shadow-sm border-b border-outline-variant/30 justify-end">
<div class="flex items-center gap-6">
<button class="relative text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full border-2 border-surface"></span>
</button>
<div class="relative group flex items-center gap-3 pl-4 border-l border-outline-variant/30 cursor-pointer">
<div class="text-right">
<p class="font-label-md text-on-surface">Staff Profile</p>
<p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Store Manager</p>
</div>
<img class="w-10 h-10 rounded-full object-cover border-2 border-primary-fixed shadow-sm" data-alt="A professional studio portrait of a cafe manager with a warm and welcoming expression. The background is a blurred high-end coffee shop interior with soft golden lighting and rich wooden textures. The aesthetic is clean and modern, matching a luxury artisanal brand." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZlVDS0zWYeeHCBEkQZTED4pbtTPxw7ePQtTG8Aq14g3AS60CX3HF7cM4AB7VXvphm6CTEpgKniazy5LA9ZtAbMJrnAw-pSC5hyHUeUgm0hUg3DI7cB6O3lHRcXG9beBTcC4DgmsQfFlnp7HrQA9lirbTtbsxf1TDk1O1dJNfutcoBibCxvK1gZT-PDR12fzJYqrRR_MD0LDNbt4gUa75sUcMwbU12YF9bLHfbQSwjSsrwkciXjIToInPeI68thC97oVstk9uO_g8">

<!-- Dropdown Menu -->
<div class="absolute right-0 top-full mt-2 w-48 bg-surface border border-outline-variant rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right z-50">
<div class="p-2 space-y-1">
<a href="../logout.php" class="block px-4 py-2 text-sm text-error hover:bg-error-container hover:text-on-error-container rounded-lg transition-colors flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">logout</span> Logout
</a>
</div>
</div>
</div>
</div>
</header>
<!-- Dashboard Content -->
<div class="p-8 max-w-[1400px] mx-auto">
<!-- Summary Section: Bento Style Cards -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
<!-- Orders to Fulfill -->
<div class="group relative overflow-hidden bg-surface-container-low rounded-xl p-6 shadow-sm border border-outline-variant/20 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
<div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
<span class="material-symbols-outlined text-[80px]">pending_actions</span>
</div>
<p class="font-label-md text-outline mb-1 uppercase tracking-tight">Orders to Fulfill</p>
<h2 class="font-headline-md text-primary mb-2"><?= $pending_count ?></h2>
<div class="flex items-center gap-2 text-[12px] text-tertiary">
<span class="flex items-center bg-orange-100 text-primary-container px-2 py-0.5 rounded-full font-bold">Needs attention</span>
</div>
</div>
<!-- Recently Completed -->
<div class="group relative overflow-hidden bg-surface-container-low rounded-xl p-6 shadow-sm border border-outline-variant/20 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
<div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
<span class="material-symbols-outlined text-[80px]">check_circle</span>
</div>
<p class="font-label-md text-outline mb-1 uppercase tracking-tight">Completed</p>
<h2 class="font-headline-md text-on-surface mb-2"><?= $completed_count ?></h2>
<div class="flex items-center gap-2 text-[12px] text-on-surface-variant">
<span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
<span class="">Total all time</span>
</div>
</div>
<!-- Total Orders Today -->
<div class="group relative overflow-hidden bg-primary-container rounded-xl p-6 shadow-lg hover:shadow-primary-container/30 hover:-translate-y-1 transition-all duration-300">
<div class="absolute top-0 right-0 p-4 opacity-20">
<span class="material-symbols-outlined text-[80px] text-white">receipt_long</span>
</div>
<p class="font-label-md text-primary-fixed mb-1 uppercase tracking-tight">Total Orders Today</p>
<h2 class="font-headline-md text-white mb-2"><?= $today_count ?></h2>
<div class="flex items-center gap-2 text-[12px] text-white/90">
<span class="material-symbols-outlined text-[16px]">trending_up</span>
<span class="">Today's activity</span>
</div>
</div>
</section>
<!-- Order Management Area -->
<section class="bg-white rounded-2xl shadow-sm border border-outline-variant/20 overflow-hidden">
<!-- Filters & Header -->
<div class="p-6 border-b border-outline-variant/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
<div>
<h3 class="font-headline-sm text-on-surface">Order History</h3>
<p class="text-on-surface-variant font-body-md text-[14px]">Monitor and manage your café's daily transactions.</p>
</div>
<div class="flex items-center gap-3">
<div class="flex bg-surface-container p-1 rounded-lg">
<?php
    $all_class = $filter === 'all' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface';
    $proc_class = $filter === 'processing' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface';
    $comp_class = $filter === 'completed' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface';
?>
<a href="orders.php?filter=all" class="px-4 py-1.5 rounded-md text-label-md transition-all <?= $all_class ?>">All Orders</a>
<a href="orders.php?filter=processing" class="px-4 py-1.5 rounded-md text-label-md transition-all <?= $proc_class ?>">Processing</a>
<a href="orders.php?filter=completed" class="px-4 py-1.5 rounded-md text-label-md transition-all <?= $comp_class ?>">Completed</a>
</div>
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-label-md hover:bg-surface-container transition-all">
<span class="material-symbols-outlined text-[18px]">filter_list</span>
                            Filters
                        </button>
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-label-md hover:bg-surface-container transition-all">
<span class="material-symbols-outlined text-[18px]">download</span>
                            Export
                        </button>
</div>
</div>
<!-- Orders Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-lowest/50 text-outline font-label-md text-[13px] uppercase tracking-wider">
<th class="px-6 py-4 font-semibold">Order ID</th>
<th class="px-6 py-4 font-semibold">Date</th>
<th class="px-6 py-4 font-semibold">Customer</th>
<th class="px-6 py-4 font-semibold">Items</th>
<th class="px-6 py-4 font-semibold">Total</th>
<th class="px-6 py-4 font-semibold">Status</th>
<th class="px-6 py-4 font-semibold text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<?php if($orders && $orders->num_rows > 0): ?>
    <?php while($row = $orders->fetch_assoc()): ?>
        <tr class="hover:bg-surface-bright transition-colors group">
            <td class="px-6 py-4 font-label-md text-primary">#ORD-<?= $row['id'] ?></td>
            <td class="px-6 py-4 font-body-md text-on-surface-variant text-[14px]"><?= date('M j, g:i A', strtotime($row['order_date'])) ?></td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-[12px] font-bold text-on-secondary-container"><?= substr(strtoupper($row['full_name']), 0, 2) ?></div>
                    <span class="font-label-md text-on-surface"><?= htmlspecialchars($row['full_name']) ?></span>
                </div>
            </td>
            <td class="px-6 py-4 font-body-md text-on-surface-variant text-[14px]"><?= htmlspecialchars($row['items']) ?></td>
            <td class="px-6 py-4 font-bold text-on-surface">$<?= number_format($row['total_amount'], 2) ?></td>
            <td class="px-6 py-4">
                <?php 
                    $badgeClass = '';
                    switch($row['status']) {
                        case 'Pending': $badgeClass = 'bg-orange-100 text-orange-700 border border-orange-200'; break;
                        case 'Processing': $badgeClass = 'bg-blue-100 text-blue-700 border border-blue-200'; break;
                        case 'Completed': $badgeClass = 'bg-green-100 text-green-700 border border-green-200'; break;
                        case 'Cancelled': $badgeClass = 'bg-red-100 text-red-700 border border-red-200'; break;
                    }
                ?>
                <span class="px-3 py-1 rounded-full text-[12px] font-bold <?= $badgeClass ?>"><?= $row['status'] ?></span>
            </td>
            <td class="px-6 py-4 text-right">
                <form method="POST" action="orders.php">
                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                    <select name="status" onchange="this.form.submit()" class="bg-surface border border-outline-variant rounded-md text-[12px] px-2 py-1 outline-none text-on-surface focus:ring-1 focus:ring-primary">
                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="7" class="text-center py-6 text-on-surface-variant">No orders found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Table Footer/Pagination -->
<div class="p-6 border-t border-outline-variant/10 flex items-center justify-between">
<p class="text-on-surface-variant text-[14px] font-body-md">Showing 1 to 5 of 156 orders</p>
<div class="flex items-center gap-2">
<button class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container transition-all active:scale-90">
<span class="material-symbols-outlined text-[18px]">chevron_left</span>
</button>
<button class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold text-[14px] shadow-md">1</button>
<button class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container transition-all text-on-surface text-[14px]">2</button>
<button class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container transition-all text-on-surface text-[14px]">3</button>
<button class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant hover:bg-surface-container transition-all active:scale-90">
<span class="material-symbols-outlined text-[18px]">chevron_right</span>
</button>
</div>
</div>
</section>
</div>
<!-- Floating Quick Add Button -->
<button class="fixed bottom-8 right-8 w-14 h-14 bg-primary-container text-white rounded-full flex items-center justify-center shadow-xl hover:scale-110 active:scale-95 transition-all group z-40">
<span class="material-symbols-outlined text-[32px]">add</span>
<span class="absolute right-16 bg-on-surface text-white px-3 py-1.5 rounded-lg text-label-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">New Order</span>
</button>
</main>
<script>
        // Simple micro-interaction for active state on table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                // Remove active class from all rows
                document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('bg-surface-container-low'));
                // Add to current
                row.classList.add('bg-surface-container-low');
            });
        });

        // Search bar focus effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.classList.add('w-96');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.classList.remove('w-96');
        });
    </script>




</body></html>