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

// --- AJAX HANDLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');
    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'error' => 'No action specified']);
        exit;
    }
    
    try {
        if ($data['action'] === 'add') {
            $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, description, price, image_url, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issdsi", $data['category_id'], $data['product_name'], $data['description'], $data['price'], $data['image_url'], $data['is_active']);
            $stmt->execute();
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } elseif ($data['action'] === 'edit') {
            $stmt = $conn->prepare("UPDATE products SET category_id=?, product_name=?, description=?, price=?, image_url=?, is_active=? WHERE id=?");
            $stmt->bind_param("issdsii", $data['category_id'], $data['product_name'], $data['description'], $data['price'], $data['image_url'], $data['is_active'], $data['id']);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } elseif ($data['action'] === 'delete') {
            $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
            $stmt->bind_param("i", $data['id']);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } elseif ($data['action'] === 'toggle_status') {
            $stmt = $conn->prepare("UPDATE products SET is_active = NOT is_active WHERE id=?");
            $stmt->bind_param("i", $data['id']);
            $stmt->execute();
            echo json_encode(['success' => true]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
// --- END AJAX HANDLER ---

$categories_result = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

$products_by_category = [];
$products_result = $conn->query("SELECT * FROM products");
$total_products = $products_result->num_rows;
while ($row = $products_result->fetch_assoc()) {
    $products_by_category[$row['category_id']][] = $row;
}


?>
<!DOCTYPE html>

<html class="light" lang="en" style=""><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Menu Management | Artisan Café Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Plus+Jakarta+Sans:wght@400;600;700&amp;family=Merriweather:wght@400;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet"/>
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
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        
        .amber-glow:hover {
            box-shadow: 0 10px 30px rgba(154, 70, 0, 0.2);
            transform: translateY(-4px);
        }

        .glass-nav {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Toggle Switch Styling */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #e0c0b0;
            transition: .4s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider { background-color: #9a4600; }
        input:checked + .slider:before { transform: translateX(20px); }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden">
<!-- SideNavBar (Fixed) -->
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
<a class="flex items-center gap-3 px-4 py-3 bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary rounded-lg transition-all scale-95 duration-150 active:scale-90" href="menu-manage.php">
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
<!-- Main Content Shell -->
<main class="ml-64 min-h-screen mt-16">
<!-- TopNavBar -->
<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 bg-surface/95 dark:bg-surface-dim/95 backdrop-blur-md flex items-center h-16 px-6 shadow-sm border-b border-outline-variant/30 justify-end">
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
<img class="w-8 h-8 rounded-full object-cover border-2 border-primary-fixed shadow-sm" data-alt="A professional studio portrait of a cafe manager with a warm and welcoming expression. The background is a blurred high-end coffee shop interior with soft golden lighting and rich wooden textures. The aesthetic is clean and modern, matching a luxury artisanal brand." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZlVDS0zWYeeHCBEkQZTED4pbtTPxw7ePQtTG8Aq14g3AS60CX3HF7cM4AB7VXvphm6CTEpgKniazy5LA9ZtAbMJrnAw-pSC5hyHUeUgm0hUg3DI7cB6O3lHRcXG9beBTcC4DgmsQfFlnp7HrQA9lirbTtbsxf1TDk1O1dJNfutcoBibCxvK1gZT-PDR12fzJYqrRR_MD0LDNbt4gUa75sUcMwbU12YF9bLHfbQSwjSsrwkciXjIToInPeI68thC97oVstk9uO_g8"/>

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
<!-- Page Content -->
<section class="p-8 lg:p-12 max-w-container-max-lg mx-auto">
<!-- Hero Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
<div>
<h2 class="font-headline-md text-headline-md text-primary mb-2">Menu Management</h2>
<p class="text-body-lg text-on-surface-variant max-w-xl">Curate the Ngopidea experience. Update your artisanal offerings, seasonal specials, and manage stock availability in real-time.</p>
</div>
<button onclick="openModal('add')" class="flex items-center gap-2 bg-primary text-on-primary px-8 py-4 rounded-full font-label-md text-label-md amber-glow transition-all active:scale-95">
<span class="material-symbols-outlined">add</span>
                    Add New Item
                </button>
</div>

<?php foreach ($categories as $category): ?>
    <?php 
        $cat_products = $products_by_category[$category['id']] ?? []; 
        if (empty($cat_products)) continue;
    ?>
    <div class="mb-16">
        <div class="flex items-center gap-4 mb-8">
            <h3 class="font-headline-sm text-headline-sm text-on-surface"><?= htmlspecialchars($category['category_name']) ?></h3>
            <div class="h-[2px] flex-1 bg-gradient-to-r from-outline-variant/50 to-transparent"></div>
            <span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full"><?= count($cat_products) ?> Items</span>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/20 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-surface-container/50 border-b border-outline-variant/20">
                    <tr>
                        <th class="px-6 py-4 font-label-md text-xs uppercase tracking-widest text-on-surface-variant">Product</th>
                        <th class="px-6 py-4 font-label-md text-xs uppercase tracking-widest text-on-surface-variant">Base Price</th>
                        <th class="px-6 py-4 font-label-md text-xs uppercase tracking-widest text-on-surface-variant">Status</th>
                        <th class="px-6 py-4 font-label-md text-xs uppercase tracking-widest text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php foreach ($cat_products as $product): ?>
                        <tr class="hover:bg-surface transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4 <?= !$product['is_active'] ? 'opacity-70' : '' ?>">
                                    <?php if (!empty($product['image_url'])): ?>
                                        <div class="w-12 h-12 rounded-lg bg-cover bg-center <?= !$product['is_active'] ? 'grayscale' : '' ?>" style="background-image: url('<?= htmlspecialchars($product['image_url']) ?>')"></div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary <?= !$product['is_active'] ? 'grayscale' : '' ?>">
                                            <span class="material-symbols-outlined text-sm">coffee</span>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-bold text-on-surface <?= !$product['is_active'] ? 'italic' : '' ?>"><?= htmlspecialchars($product['product_name']) ?></p>
                                        <?php if (!empty($product['description'])): ?>
                                            <p class="text-xs text-on-surface-variant italic"><?= htmlspecialchars(substr($product['description'], 0, 30)) ?>...</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-body-md <?= !$product['is_active'] ? 'text-on-surface-variant' : '' ?>">$<?= number_format($product['price'], 2) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <label class="switch">
                                        <input type="checkbox" <?= $product['is_active'] ? 'checked' : '' ?> onchange="toggleStatus(<?= $product['id'] ?>)">
                                        <span class="slider"></span>
                                    </label>
                                    <span class="text-xs font-semibold text-on-surface-variant uppercase"><?= $product['is_active'] ? 'Available' : 'Unavailable' ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick='openModal("edit", <?= json_encode($product) ?>)' class="w-8 h-8 rounded-full flex items-center justify-center text-primary bg-primary/5 hover:bg-primary hover:text-on-primary transition-all">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                    <button onclick="deleteItem(<?= $product['id'] ?>)" class="w-8 h-8 rounded-full flex items-center justify-center text-error bg-error/5 hover:bg-error hover:text-on-error transition-all">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>

<!-- Footer Pagination/Navigation -->
<div class="flex items-center justify-between border-t border-outline-variant/30 pt-8 pb-16">
<p class="text-sm text-on-surface-variant">Showing <strong>12</strong> of <strong>34</strong> menu items</p>
<div class="flex gap-2">
<button class="w-10 h-10 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-primary/5 text-on-surface-variant transition-colors">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-md">1</button>
<button class="w-10 h-10 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-primary/5 text-on-surface-variant transition-colors">2</button>
<button class="w-10 h-10 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-primary/5 text-on-surface-variant transition-colors">3</button>
<button class="w-10 h-10 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-primary/5 text-on-surface-variant transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</div>
</section>
</main>
<!-- Floating Action Button for Mobile Context (if needed) -->
<button class="md:hidden fixed bottom-6 right-6 w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center z-50">
<span class="material-symbols-outlined">add</span>
</button>
<!-- Modal Template -->
<div id="itemModal" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 id="modalTitle" class="font-headline-sm text-primary">Add New Item</h3>
            <button onclick="closeModal()" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <form id="itemForm" class="space-y-4">
                <input type="hidden" id="itemId" value="">
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Product Name</label>
                    <input type="text" id="itemName" required class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest">
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Category</label>
                    <select id="itemCategory" required class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Base Price ($)</label>
                    <input type="number" id="itemPrice" step="0.01" required class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest">
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Description</label>
                    <textarea id="itemDesc" rows="2" class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Image URL</label>
                    <input type="url" id="itemImage" class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <label class="switch">
                        <input type="checkbox" id="itemActive" checked>
                        <span class="slider"></span>
                    </label>
                    <span class="text-sm font-bold text-on-surface-variant">Active / Available</span>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2 rounded-lg font-bold text-on-surface-variant hover:bg-surface-variant transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg font-bold bg-primary text-on-primary hover:brightness-110 transition-colors">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        function openModal(mode, data = null) {
            const modal = document.getElementById('itemModal');
            const form = document.getElementById('itemForm');
            const title = document.getElementById('modalTitle');
            
            if (mode === 'add') {
                title.innerText = 'Add New Item';
                form.reset();
                document.getElementById('itemId').value = '';
                document.getElementById('itemActive').checked = true;
            } else if (mode === 'edit' && data) {
                title.innerText = 'Edit Item';
                document.getElementById('itemId').value = data.id;
                document.getElementById('itemName').value = data.product_name;
                document.getElementById('itemCategory').value = data.category_id;
                document.getElementById('itemPrice').value = data.price;
                document.getElementById('itemDesc').value = data.description;
                document.getElementById('itemImage').value = data.image_url;
                document.getElementById('itemActive').checked = data.is_active == 1;
            }
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('itemModal').classList.add('hidden');
        }

        document.getElementById('itemForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = document.getElementById('itemId').value;
            const action = id ? 'edit' : 'add';
            
            const payload = {
                action: action,
                id: id,
                product_name: document.getElementById('itemName').value,
                category_id: document.getElementById('itemCategory').value,
                price: document.getElementById('itemPrice').value,
                description: document.getElementById('itemDesc').value,
                image_url: document.getElementById('itemImage').value,
                is_active: document.getElementById('itemActive').checked ? 1 : 0
            };
            
            try {
                const res = await fetch('menu-manage.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (err) {
                alert('Network error');
            }
        });

        async function toggleStatus(id) {
            try {
                const res = await fetch('menu-manage.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'toggle_status', id: id})
                });
                const data = await res.json();
                if (!data.success) {
                    alert('Error updating status: ' + data.error);
                    window.location.reload(); // revert
                }
            } catch (err) {
                alert('Network error');
                window.location.reload(); // revert
            }
        }

        async function deleteItem(id) {
            if (!confirm('Are you sure you want to delete this item?')) return;
            try {
                const res = await fetch('menu-manage.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'delete', id: id})
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (err) {
                alert('Network error');
            }
        }

        // Micro-interactions and subtle scroll effects
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.querySelector('header');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    header.classList.add('shadow-md');
                    header.classList.remove('shadow-sm');
                } else {
                    header.classList.add('shadow-sm');
                    header.classList.remove('shadow-md');
                }
            });

            // Ripple effect logic for primary buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(btn => {
                btn.addEventListener('mousedown', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.width = '100px';
                    ripple.style.height = '100px';
                    ripple.style.background = 'rgba(255,255,255,0.3)';
                    ripple.style.borderRadius = '50%';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.transform = 'translate(-50%, -50%) scale(0)';
                    ripple.style.transition = 'transform 0.5s ease-out, opacity 0.5s ease-out';
                    ripple.style.pointerEvents = 'none';
                    
                    this.appendChild(ripple);
                    
                    requestAnimationFrame(() => {
                        ripple.style.transform = 'translate(-50%, -50%) scale(4)';
                        ripple.style.opacity = '0';
                    });
                    
                    setTimeout(() => ripple.remove(), 500);
                });
            });
        });
    </script>
</body></html>