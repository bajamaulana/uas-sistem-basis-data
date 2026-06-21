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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['res_id'], $_POST['status'])) {
    $res_id = intval($_POST['res_id']);
    $new_status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE reservations SET status = '$new_status' WHERE id = $res_id");
}

// Fetch Reservations
$filter = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : 'Pending';
$sql = "SELECT r.id, r.reservation_time, r.guest_count, r.status, c.full_name, t.table_number 
        FROM reservations r 
        JOIN customers c ON r.customer_id = c.id 
        JOIN tables t ON r.table_id = t.id 
        WHERE r.status = '$filter'
        ORDER BY r.reservation_time DESC";
$reservations = $conn->query($sql);
?>
<!DOCTYPE html><html class="light" lang="en" style="width: 1280px; height: 1043px; overflow: hidden; position: relative;"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Ngopidea Admin - Table Reservations</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@400;700&amp;family=Plus+Jakarta+Sans:wght@400;600;700&amp;display=swap" rel="stylesheet">
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary-fixed": "#2b1700",
                    "background": "#fff8f5",
                    "inverse-primary": "#ffb68d",
                    "outline": "#8c7264",
                    "secondary-fixed-dim": "#edbe89",
                    "secondary-container": "#ffcf99",
                    "on-secondary-fixed-variant": "#604016",
                    "on-surface": "#231a13",
                    "on-background": "#231a13",
                    "surface-container-lowest": "#ffffff",
                    "on-tertiary-container": "#263849",
                    "tertiary": "#4d6073",
                    "surface-tint": "#9a4600",
                    "surface-container-highest": "#f2dfd3",
                    "error": "#ba1a1a",
                    "on-tertiary-fixed": "#081d2d",
                    "on-tertiary": "#ffffff",
                    "on-secondary-container": "#7a562a",
                    "surface": "#fff8f5",
                    "on-surface-variant": "#584236",
                    "on-primary-fixed": "#321200",
                    "inverse-on-surface": "#ffede3",
                    "tertiary-container": "#8ea2b6",
                    "surface-container": "#fdeade",
                    "surface-bright": "#fff8f5",
                    "inverse-surface": "#392e26",
                    "surface-variant": "#f2dfd3",
                    "on-secondary": "#ffffff",
                    "surface-container-low": "#fff1e9",
                    "on-error": "#ffffff",
                    "error-container": "#ffdad6",
                    "primary-container": "#ff790b",
                    "secondary-fixed": "#ffddb9",
                    "primary-fixed-dim": "#ffb68d",
                    "on-primary-fixed-variant": "#763300",
                    "tertiary-fixed-dim": "#b5c9de",
                    "tertiary-fixed": "#d1e5fb",
                    "surface-container-high": "#f7e5d9",
                    "surface-dim": "#e9d7cb",
                    "primary-fixed": "#ffdbc9",
                    "on-error-container": "#93000a",
                    "primary": "#9a4600",
                    "on-primary": "#ffffff",
                    "outline-variant": "#e0c0b0",
                    "secondary": "#7b572b",
                    "on-tertiary-fixed-variant": "#36495a",
                    "on-primary-container": "#5d2700"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "container-max-md": "1100px",
                    "section-sm": "40px",
                    "section-md": "80px",
                    "section-lg": "100px",
                    "gutter": "20px",
                    "container-max-lg": "1200px",
                    "unit": "8px"
            },
            "fontFamily": {
                    "headline-md": ["Playfair Display"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "body-lg": ["Merriweather"],
                    "headline-sm": ["Playfair Display"],
                    "display-hero-mobile": ["Playfair Display"],
                    "label-md": ["Plus Jakarta Sans"],
                    "headline-lg": ["Playfair Display"],
                    "display-hero": ["Playfair Display"],
                    "body-md": ["Merriweather"]
            },
            "fontSize": {
                    "headline-md": ["40px", { "lineHeight": "1.3", "fontWeight": "700" }],
                    "headline-lg-mobile": ["32px", { "lineHeight": "1.2", "letterSpacing": "0px", "fontWeight": "700" }],
                    "body-lg": ["18px", { "lineHeight": "1.8", "fontWeight": "400" }],
                    "headline-sm": ["24px", { "lineHeight": "1.4", "fontWeight": "700" }],
                    "display-hero-mobile": ["35px", { "lineHeight": "1.2", "letterSpacing": "1px", "fontWeight": "700" }],
                    "label-md": ["14px", { "lineHeight": "1.2", "fontWeight": "600" }],
                    "headline-lg": ["56px", { "lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700" }],
                    "display-hero": ["72px", { "lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700" }],
                    "body-md": ["16px", { "lineHeight": "1.6", "fontWeight": "400" }]
            }
          },
        },
      }
    </script>
<style>
        body { background-color: #fff8f5; color: #231a13; }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(140, 114, 100, 0.1); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .active-tab-indicator { position: absolute; bottom: -2px; left: 0; right: 0; height: 3px; background-color: #9a4600; border-radius: 999px; }
        .bento-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #fff1e9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0c0b0; border-radius: 10px; }
    </style>
</head>
<body class="font-body-md text-body-md overflow-x-hidden">
<!-- SideNavBar (Authority: JSON & Context) -->
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
<a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant dark:text-on-surface hover:bg-surface-container-high dark:hover:bg-surface-container-highest rounded-lg transition-all hover:text-primary" href="orders.php">
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
<a class="flex items-center gap-3 px-4 py-3 bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary rounded-lg transition-all scale-95 duration-150 active:scale-90" href="table_reserve.php">
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
<!-- TopNavBar (Authority: JSON) -->
<header class="h-16 w-full fixed top-0 z-40 bg-white/95 dark:bg-surface-container-highest/95 backdrop-blur-md shadow-sm flex justify-between items-center px-8 ml-64 w-[calc(100%-16rem)]">
<div class="flex items-center gap-4">
<div class="relative flex items-center">
<span class="material-symbols-outlined absolute left-3 text-outline text-xl">search</span>
<input class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full text-body-md focus:ring-2 focus:ring-primary-container w-64 transition-all outline-none" placeholder="Search reservations..." type="text">
</div>
</div>
<div class="flex items-center gap-6">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-outline-variant hover:text-primary transition-colors cursor-pointer">notifications</span>
<div class="w-2 h-2 bg-primary rounded-full -ml-3 mb-3"></div>
</div>
<div class="flex items-center gap-3 group cursor-pointer">
<div class="text-right">
<p class="font-label-md text-on-surface leading-none">Adriano M.</p>
<p class="text-[11px] text-outline leading-none mt-1">Floor Manager</p>
</div>
<div class="w-9 h-9 rounded-full overflow-hidden border-2 border-primary-container">
<img class="w-full h-full object-cover" data-alt="A professional portrait of a stylish floor manager for a luxury coffee boutique. He has a groomed appearance and wears a minimalist dark linen apron over a crisp shirt. The background is a soft-focus bokeh of a warm, wooden-toned cafe interior with amber lighting and organic textures." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBYM2O-zBaBIFFBqVYSET7dsIZQ7es454YrSFh8_miW8HxiY-qxtN_uXrdMO0MXPnwoSNobekeAa47xDdLXu4l_j6BuMZuS_DBAWgzYx8ByVRGtWKZEKBrSzyYfme57mPumGW3yYmaivz2Vfg-rp3YYFRXsY0vLewfCmsTJXwpLOaXi0n93Dk7B2GyuohYpkSZPMW9KAy_npK4QSbc6oZjxdWwR9d2xUs2tK9ie2FkjyANCg-ethzBSvEWAUl_i58TFU9ml280SBM">
</div>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-64 pt-24 pb-16 px-8 min-h-screen">
<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
<div>
<h2 class="font-headline-md text-headline-md text-primary mb-2">Reservations Management</h2>
<p class="font-body-md text-body-md text-outline">Oversee table bookings and seating ritual at <span class="font-bold text-secondary">The Heritage Attic</span>.</p>
</div>
<!-- Insight Card (Seating Optimization) -->
<div class="glass-card p-4 rounded-xl flex items-center gap-4 shadow-sm max-w-sm">
<div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">insights</span>
</div>
<div>
<p class="font-label-md text-on-surface">Seating Optimization</p>
<p class="text-[13px] text-outline-variant">Next peak hour: <span class="font-bold text-primary">2:00 PM</span>. <br>85% occupancy projected.</p>
</div>
</div>
</div>
<!-- Controls & Navigation -->
<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6 border-b border-outline-variant/20 pb-4">
<div class="flex items-center gap-8">
<?php $current_filter = $_GET['filter'] ?? 'Pending'; ?>
<a href="?filter=Pending" class="relative py-2 font-label-md <?= $current_filter === 'Pending' ? 'text-primary' : 'text-outline hover:text-primary transition-colors' ?>">
    Upcoming
    <?= $current_filter === 'Pending' ? '<div class="active-tab-indicator"></div>' : '' ?>
</a>
<a href="?filter=Confirmed" class="relative py-2 font-label-md <?= $current_filter === 'Confirmed' ? 'text-primary' : 'text-outline hover:text-primary transition-colors' ?>">
    Active
    <?= $current_filter === 'Confirmed' ? '<div class="active-tab-indicator"></div>' : '' ?>
</a>
<a href="?filter=Completed" class="relative py-2 font-label-md <?= $current_filter === 'Completed' ? 'text-primary' : 'text-outline hover:text-primary transition-colors' ?>">
    Completed
    <?= $current_filter === 'Completed' ? '<div class="active-tab-indicator"></div>' : '' ?>
</a>
<a href="?filter=Cancelled" class="relative py-2 font-label-md <?= $current_filter === 'Cancelled' ? 'text-primary' : 'text-outline hover:text-primary transition-colors' ?>">
    Cancelled
    <?= $current_filter === 'Cancelled' ? '<div class="active-tab-indicator"></div>' : '' ?>
</a>
</div>

</div>
<!-- Bento Grid Layout for Content -->
<div class="bento-grid">
<!-- Reservation Table (Main List View) -->
<div class="col-span-12 lg:col-span-12 glass-card rounded-2xl overflow-hidden shadow-sm flex flex-col">
<div class="px-6 py-4 bg-surface-container-low flex justify-between items-center border-b border-outline-variant/20">
<h3 class="font-headline-sm text-[20px] text-on-surface">Upcoming Bookings</h3>
<div class="flex items-center gap-2">
<span class="text-[12px] text-outline font-label-md uppercase tracking-wider">Sort by:</span>
<select class="bg-transparent border-none font-label-md text-primary focus:ring-0 cursor-pointer">
<option>Time (Ascending)</option>
<option>Party Size</option>
</select>
</div>
</div>
<div class="overflow-x-auto custom-scrollbar">
<table class="w-full text-left border-collapse">
<thead>
<tr class="text-outline font-label-md text-[13px] border-b border-outline-variant/10">
<th class="px-6 py-4 font-semibold uppercase tracking-tight">Customer</th>
<th class="px-6 py-4 font-semibold uppercase tracking-tight">Party</th>
<th class="px-6 py-4 font-semibold uppercase tracking-tight">Time Slot</th>
<th class="px-6 py-4 font-semibold uppercase tracking-tight">Area</th>
<th class="px-6 py-4 font-semibold uppercase tracking-tight">Status</th>
<th class="px-6 py-4"></th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<?php if($reservations && $reservations->num_rows > 0): ?>
    <?php while($row = $reservations->fetch_assoc()): ?>
        <tr class="hover:bg-surface-container/50 transition-colors group">
            <td class="px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-tertiary-container flex items-center justify-center text-[12px] font-bold text-on-tertiary-container"><?= substr(strtoupper($row['full_name']), 0, 2) ?></div>
                    <div>
                        <p class="font-label-md text-on-surface"><?= htmlspecialchars($row['full_name']) ?></p>
                        <p class="text-[12px] text-outline">Customer</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-5">
                <div class="flex items-center gap-1.5 font-label-md">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    <?= $row['guest_count'] ?> Guests
                </div>
            </td>
            <td class="px-6 py-5">
                <div class="font-label-md text-primary"><?= date('M j, g:i A', strtotime($row['reservation_time'])) ?></div>
            </td>
            <td class="px-6 py-5">
                <span class="px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[12px] font-bold">Table <?= htmlspecialchars($row['table_number']) ?></span>
            </td>
            <td class="px-6 py-5">
                <?php 
                    $badgeClass = '';
                    switch($row['status']) {
                        case 'Pending': $badgeClass = 'bg-orange-100 text-orange-700'; break;
                        case 'Confirmed': $badgeClass = 'bg-blue-100 text-blue-700'; break;
                        case 'Completed': $badgeClass = 'bg-green-100 text-green-700'; break;
                        case 'Cancelled': $badgeClass = 'bg-red-100 text-red-700'; break;
                    }
                ?>
                <div class="flex items-center gap-2 font-bold text-[13px]">
                    <span class="px-3 py-1 rounded-full <?= $badgeClass ?>"><?= $row['status'] ?></span>
                </div>
            </td>
            <td class="px-6 py-5 text-right">
                <form method="POST" action="table_reserve.php">
                    <input type="hidden" name="res_id" value="<?= $row['id'] ?>">
                    <select name="status" onchange="this.form.submit()" class="bg-surface border border-outline-variant rounded-md text-[12px] px-2 py-1 outline-none text-on-surface focus:ring-1 focus:ring-primary">
                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="6" class="text-center py-6 text-on-surface-variant">No reservations found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<div class="p-4 border-t border-outline-variant/10 flex justify-center">
<button class="text-primary font-label-md hover:underline decoration-2 underline-offset-4">View All 24 Reservations</button>
</div>
</div>


</div>
</main>
<!-- Interactive Layer / Floating Action Button (Limited context) -->
<!-- (Suppressed as per logic: Navigation and secondary controls are already prominent) -->



</body></html>