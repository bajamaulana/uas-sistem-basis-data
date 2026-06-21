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

$ingredients_result = $conn->query("SELECT * FROM ingredients");
$ingredients = [];
$low_stock_count = 0;
while ($row = $ingredients_result->fetch_assoc()) {
    $ingredients[] = $row;
    if ($row['stock_quantity'] <= $row['min_stock_level']) {
        $low_stock_count++;
    }
}
$total_sku = count($ingredients);
?>
<!DOCTYPE html><html class="light" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Ngopidea Admin - Inventory Management</title>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<!-- Google Fonts: Playfair Display, Merriweather, Plus Jakarta Sans -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&amp;family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      vertical-align: middle;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #e0c0b0;
      border-radius: 10px;
    }
    .status-pulse {
      animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
  </style>
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
                  "headline-lg": ["Playfair Display"],
                  "body-lg": ["Merriweather"],
                  "headline-sm": ["Playfair Display"],
                  "headline-md": ["Playfair Display"]
          },
          "fontSize": {
                  "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}],
                  "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                  "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                  "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}],
                  "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                  "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                  "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}]
          }
        },
      },
    }
  </script>
</head>
<body class="bg-background text-on-surface font-body-md min-h-screen">
<!-- SideNavBar Anchor -->
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
<!-- Active: Inventory -->
<a class="flex items-center gap-3 px-4 py-3 bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary rounded-lg transition-all scale-95 duration-150 active:scale-90" href="inven.php">
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
<!-- Main Content Area -->
<main class="ml-64 mt-16 p-8 flex-grow">
<!-- TopNavBar Anchor -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 bg-surface/95 dark:bg-surface-dim/95 backdrop-blur-md flex items-center h-16 px-8 shadow-sm border-b border-outline-variant/30 justify-end">
<div class="flex items-center gap-6">
<button class="relative text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full border-2 border-surface"></span>
</button>
<div class="relative group flex items-center gap-3 pl-4 border-l border-outline-variant/30 h-10 cursor-pointer">
<div class="text-right">
<p class="font-label-md text-on-surface">Staff Profile</p>
<p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Store Manager</p>
</div>
<img class="w-8 h-8 rounded-full object-cover border-2 border-primary-fixed shadow-sm" data-alt="A professional studio portrait of a cafe manager with a warm and welcoming expression. The background is a blurred high-end coffee shop interior with soft golden lighting and rich wooden textures. The aesthetic is clean and modern, matching a luxury artisanal brand." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZlVDS0zWYeeHCBEkQZTED4pbtTPxw7ePQtTG8Aq14g3AS60CX3HF7cM4AB7VXvphm6CTEpgKniazy5LA9ZtAbMJrnAw-pSC5hyHUeUgm0hUg3DI7cB6O3lHRcXG9beBTcC4DgmsQfFlnp7HrQA9lirbTtbsxf1TDk1O1dJNfutcoBibCxvK1gZT-PDR12fzJYqrRR_MD0LDNbt4gUa75sUcMwbU12YF9bLHfbQSwjSsrwkciXjIToInPeI68thC97oVstk9uO_g8">

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
<!-- Page Body -->
<div class="p-8 max-w-container-max-lg mx-auto">
<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
<div class="bg-surface-container-low p-6 rounded-xl shadow-sm border border-outline-variant/30 transition-transform duration-300 hover:-translate-y-1">
<p class="text-on-surface-variant font-label-md mb-2">Total SKU</p>
<h3 class="font-headline-sm text-primary"><?= $total_sku ?> Items</h3>
</div>
<div class="bg-surface-container-low p-6 rounded-xl shadow-sm border border-outline-variant/30 transition-transform duration-300 hover:-translate-y-1">
<p class="text-on-surface-variant font-label-md mb-2">Low Stock Alert</p>
<h3 class="font-headline-sm text-error"><?= $low_stock_count ?> Items</h3>
</div>
<div class="bg-surface-container-low p-6 rounded-xl shadow-sm border border-outline-variant/30 transition-transform duration-300 hover:-translate-y-1">
<p class="text-on-surface-variant font-label-md mb-2">Arrivals (Today)</p>
<h3 class="font-headline-sm text-secondary">4 Shipments</h3>
</div>
<div class="bg-surface-container-low p-6 rounded-xl shadow-sm border border-outline-variant/30 transition-transform duration-300 hover:-translate-y-1">
<p class="text-on-surface-variant font-label-md mb-2">Inventory Value</p>
<h3 class="font-headline-sm text-on-surface">$12,450.00</h3>
</div>
</div>
<!-- Action Row -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
<div>
<h4 class="font-headline-sm text-on-surface">Supply Catalog</h4>
<p class="text-body-md text-on-surface-variant">Manage your artisanal coffee beans, liquids, and fresh pastries.</p>
</div>
<div class="flex gap-4">
<button class="bg-surface-container-highest text-on-surface font-label-md px-6 py-3 rounded-full flex items-center gap-2 hover:bg-surface-variant transition-colors border border-outline-variant">
<span class="material-symbols-outlined">history</span>
            Inventory Log
          </button>
<button class="bg-secondary text-on-primary font-label-md px-6 py-3 rounded-full flex items-center gap-2 shadow-sm hover:brightness-110 transition-all active:scale-95">
<span class="material-symbols-outlined">local_shipping</span>
            Record Stock Arrival
          </button>
<button class="bg-primary text-on-primary font-label-md px-6 py-3 rounded-full flex items-center gap-2 shadow-sm hover:brightness-110 transition-all active:scale-95">
<span class="material-symbols-outlined">sync</span>
            Update Inventory
          </button>
</div>
</div>
<!-- Inventory Table Container -->
<div class="bg-surface-container-lowest rounded-2xl shadow-md border border-outline-variant/50 overflow-hidden">
<div class="overflow-x-auto custom-scrollbar">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
<th class="px-6 py-5 font-label-md text-on-surface-variant">Item Description</th>
<th class="px-6 py-5 font-label-md text-on-surface-variant">Category</th>
<th class="px-6 py-5 font-label-md text-on-surface-variant text-center">Stock Level</th>
<th class="px-6 py-5 font-label-md text-on-surface-variant text-center">Reorder Point</th>
<th class="px-6 py-5 font-label-md text-on-surface-variant">Supplier</th>
<th class="px-6 py-5 font-label-md text-on-surface-variant text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/20">

<?php foreach ($ingredients as $item): ?>
    <?php $is_low = $item['stock_quantity'] <= $item['min_stock_level']; ?>
    <tr class="hover:bg-surface/50 transition-colors group <?= $is_low ? 'bg-error-container/10' : '' ?>">
        <td class="px-6 py-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-surface-variant rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-label-md text-on-surface"><?= htmlspecialchars($item['ingredient_name']) ?></span>
                        <?php if ($is_low): ?>
                            <span class="bg-error text-on-error text-[10px] uppercase px-2 py-0.5 rounded-full font-bold status-pulse">Low Stock</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-on-surface-variant">Unit: <?= htmlspecialchars($item['unit']) ?></p>
                </div>
            </div>
        </td>
        <td class="px-6 py-5">
            <span class="px-3 py-1 bg-surface-variant text-on-surface-variant rounded-full text-xs font-bold uppercase tracking-wider">Raw Material</span>
        </td>
        <td class="px-6 py-5 text-center">
            <div class="flex flex-col items-center">
                <span class="font-bold <?= $is_low ? 'text-error' : 'text-secondary' ?>"><?= number_format($item['stock_quantity'], 2) ?> <?= htmlspecialchars($item['unit']) ?></span>
                <div class="w-24 h-1.5 bg-outline-variant rounded-full mt-2 overflow-hidden">
                    <?php 
                        $pct = ($item['min_stock_level'] > 0) ? min(100, ($item['stock_quantity'] / ($item['min_stock_level'] * 3)) * 100) : 100; 
                    ?>
                    <div class="h-full <?= $is_low ? 'bg-error' : 'bg-secondary' ?>" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-5 text-center font-label-md text-on-surface-variant"><?= number_format($item['min_stock_level'], 2) ?> <?= htmlspecialchars($item['unit']) ?></td>
        <td class="px-6 py-5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-on-surface-variant scale-75">business</span>
                <span class="font-label-md text-on-surface">Registered Supplier</span>
            </div>
        </td>
        <td class="px-6 py-5 text-right">
            <button class="p-2 text-primary hover:bg-primary-container/20 rounded-full transition-colors">
                <span class="material-symbols-outlined">edit</span>
            </button>
            <button class="p-2 text-on-surface-variant hover:text-error rounded-full transition-colors">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </td>
    </tr>
<?php endforeach; ?>

</tbody>
</table>
</div>
<!-- Table Footer/Pagination -->
<div class="px-6 py-4 bg-surface-container-low flex justify-between items-center border-t border-outline-variant">
<p class="text-label-md text-on-surface-variant">Showing <?= $total_sku ?> products</p>
<div class="flex items-center gap-2">
<button class="p-2 text-on-surface-variant hover:bg-surface-variant rounded-lg transition-colors">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<span class="px-4 font-label-md text-primary">1</span>
<button class="p-2 text-on-surface-variant hover:bg-surface-variant rounded-lg transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</div>
</div>
<!-- Quick Tips / Help -->
<div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="glass-card p-8 rounded-2xl border border-primary/10 flex items-start gap-6">
<div class="bg-primary-fixed p-4 rounded-xl text-primary">
<span class="material-symbols-outlined scale-125">auto_graph</span>
</div>
<div>
<h5 class="font-headline-sm text-on-surface mb-2">Demand Forecasting</h5>
<p class="text-body-md text-on-surface-variant">Based on last week's sales, we recommend reordering Sumatra Beans by Thursday to avoid outages.</p>
</div>
</div>
<div class="glass-card p-8 rounded-2xl border border-secondary/10 flex items-start gap-6">
<div class="bg-secondary-fixed p-4 rounded-xl text-secondary">
<span class="material-symbols-outlined scale-125">info</span>
</div>
<div>
<h5 class="font-headline-sm text-on-surface mb-2">Inventory Hygiene</h5>
<p class="text-body-md text-on-surface-variant">Don't forget to conduct a weekly physical count every Sunday evening to maintain accuracy.</p>
</div>
</div>
</div>
</div>
</main>
<script>
    // Subtle interactivity
    document.querySelectorAll('tr').forEach(row => {
      row.addEventListener('mouseenter', () => {
        row.querySelector('.material-symbols-outlined')?.classList.add('scale-110');
      });
      row.addEventListener('mouseleave', () => {
        row.querySelector('.material-symbols-outlined')?.classList.remove('scale-110');
      });
    });

    // Simulated action feedback
    const actionButtons = document.querySelectorAll('button');
    actionButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const text = this.innerText;
        if (text.includes('Update') || text.includes('Record')) {
          const originalContent = this.innerHTML;
          this.innerHTML = `<span class="material-symbols-outlined animate-spin">sync</span> Processing...`;
          this.disabled = true;
          setTimeout(() => {
            this.innerHTML = originalContent;
            this.disabled = false;
          }, 1500);
        }
      });
    });
  </script>




</body></html>