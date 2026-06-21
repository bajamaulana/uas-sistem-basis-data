<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isLoggedIn()) {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$customer_name = $_SESSION['user_name'] ?? 'Customer';

// Fetch customer ID
$stmt = $conn->prepare("SELECT id FROM customers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cust_res = $stmt->get_result();
$customer_id = null;
if ($cust_row = $cust_res->fetch_assoc()) {
    $customer_id = $cust_row['id'];
}

// Stats
$recent_orders_count = 0;
$top_drink = "N/A";

if ($customer_id) {
    // Recent orders count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $recent_orders_count = $stmt->get_result()->fetch_assoc()['count'];

    // Top drink
    $stmt = $conn->prepare("
        SELECT p.product_name 
        FROM order_details od 
        JOIN orders o ON od.order_id = o.id 
        JOIN products p ON od.product_id = p.id 
        WHERE o.customer_id = ? 
        GROUP BY p.id 
        ORDER BY SUM(od.quantity) DESC LIMIT 1
    ");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $top_drink = $row['product_name'];
    }
}

// Password change logic
$msg = '';
$msg_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    
    if (!empty($current_pass) && !empty($new_pass)) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_data = $stmt->get_result()->fetch_assoc();
        
        if ($user_data && password_verify($current_pass, $user_data['password'])) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed, $user_id);
            if ($update->execute()) {
                $msg = "Password updated successfully.";
                $msg_type = "success";
            } else {
                $msg = "Failed to update password.";
                $msg_type = "error";
            }
        } else {
            $msg = "Incorrect current password.";
            $msg_type = "error";
        }
    } else {
        $msg = "Please fill in all fields.";
        $msg_type = "error";
    }
}

// Recent transactions (last 3)
$recent_txs = [];
if ($customer_id) {
    $stmt = $conn->prepare("SELECT id, order_date, total_amount, status FROM orders WHERE customer_id = ? ORDER BY order_date DESC LIMIT 3");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $recent_txs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html><html class="light" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>My Profile | Ngopidea Artisanal Cafe</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@400&amp;family=Plus+Jakarta+Sans:wght@600&amp;display=swap" rel="stylesheet">
<!-- Material Symbols -->
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
                    "surface-variant": "#f2dfd3",
                    "surface-container": "#fdeade",
                    "inverse-primary": "#ffb68d",
                    "surface-container-highest": "#f2dfd3",
                    "tertiary-fixed-dim": "#b5c9de",
                    "on-surface": "#231a13",
                    "primary-fixed": "#ffdbc9",
                    "on-surface-variant": "#584236",
                    "tertiary-fixed": "#d1e5fb",
                    "on-error-container": "#93000a",
                    "surface-tint": "#9a4600",
                    "surface-container-low": "#fff1e9",
                    "outline-variant": "#e0c0b0",
                    "surface-container-lowest": "#ffffff",
                    "on-secondary-fixed-variant": "#604016",
                    "primary-container": "#ff790b",
                    "inverse-on-surface": "#ffede3",
                    "tertiary": "#4d6073",
                    "primary-fixed-dim": "#ffb68d",
                    "on-secondary-container": "#7a562a",
                    "on-primary-container": "#5d2700",
                    "secondary": "#7b572b",
                    "surface-bright": "#fff8f5",
                    "on-primary-fixed": "#321200",
                    "on-tertiary": "#ffffff",
                    "on-tertiary-fixed": "#081d2d",
                    "on-background": "#231a13",
                    "on-tertiary-fixed-variant": "#36495a",
                    "error-container": "#ffdad6",
                    "secondary-container": "#ffcf99",
                    "outline": "#8c7264",
                    "tertiary-container": "#8ea2b6",
                    "surface-container-high": "#f7e5d9",
                    "primary": "#9a4600",
                    "on-tertiary-container": "#263849",
                    "on-primary": "#ffffff",
                    "background": "#fff8f5",
                    "secondary-fixed": "#ffddb9",
                    "surface": "#fff8f5",
                    "on-secondary-fixed": "#2b1700",
                    "on-primary-fixed-variant": "#763300",
                    "secondary-fixed-dim": "#edbe89",
                    "error": "#ba1a1a",
                    "on-secondary": "#ffffff",
                    "surface-dim": "#e9d7cb",
                    "on-error": "#ffffff",
                    "inverse-surface": "#392e26"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "section-md": "80px",
                    "section-lg": "100px",
                    "gutter": "20px",
                    "container-max-lg": "1200px",
                    "container-max-md": "1100px",
                    "unit": "8px",
                    "section-sm": "40px"
            },
            "fontFamily": {
                    "display-hero-mobile": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "body-lg": ["Merriweather"],
                    "label-md": ["Plus Jakarta Sans"],
                    "headline-sm": ["Playfair Display"],
                    "body-md": ["Merriweather"],
                    "headline-md": ["Playfair Display"],
                    "display-hero": ["Playfair Display"]
            },
            "fontSize": {
                    "display-hero-mobile": ["35px", {"lineHeight": "1.2", "letterSpacing": "1px", "fontWeight": "700"}],
                    "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "0px", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}],
                    "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-nav-link { font-variation-settings: 'FILL' 1; }
        
        /* Smooth transitions for interactive elements */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-10px);
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(138, 62, 0, 0.15);
        }
        
        .progress-glow {
            box-shadow: 0 0 15px rgba(255, 121, 11, 0.4);
        }
        
        /* Hide scrollbars but keep functionality */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col font-body-md text-body-md">
<?php $base_url = '../'; include '../includes/navbar.php'; ?>
<div class="pt-24"></div>
<main class="flex-grow w-full max-w-container-max-lg mx-auto flex flex-col lg:flex-row">
<!-- SideNavBar (The Blueprint) -->
<aside class="bg-surface-container-low dark:bg-surface-dim h-screen w-64 hidden lg:flex flex-col p-4 gap-2 sticky top-20 shadow-none">
<div class="flex items-center gap-3 mb-6 p-2">
<div class="h-12 w-12 rounded-xl bg-primary-container flex items-center justify-center text-on-primary-container">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">person</span>
</div>
<div>
<p class="font-label-md text-label-md font-bold text-primary"><?= htmlspecialchars($customer_name) ?></p>

</div>
</div>
<nav class="flex flex-col gap-1 flex-grow">
<a class="rounded-lg flex items-center gap-3 px-4 py-3 transition-all font-label-md text-label-md bg-primary-container text-on-primary-container font-bold" href="profile.php">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">person</span>
                    Overview
                </a>
<a class="rounded-lg flex items-center gap-3 px-4 py-3 hover:translate-x-1 transition-all font-label-md text-label-md text-on-surface-variant hover:bg-surface-variant" href="history.php">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 0;">history</span>
                    History
                </a>
<a class="rounded-lg flex items-center gap-3 px-4 py-3 hover:translate-x-1 transition-all font-label-md text-label-md text-on-surface-variant hover:bg-surface-variant" href="reservations.php">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 0;">event_seat</span>
                    Reservations
                </a>
</nav>
<div class="mt-auto border-t border-outline-variant pt-4 flex flex-col gap-1">
<a class="text-error hover:bg-error-container/20 rounded-lg flex items-center gap-3 px-4 py-3 hover:translate-x-1 transition-all font-label-md text-label-md" href="../logout.php">
<span class="material-symbols-outlined">logout</span>
                    Sign Out
                </a>
</div>
</aside>
<!-- Main Content Area -->
<section class="flex-grow p-gutter md:p-10 space-y-12"><!-- Section: Profile Overview -->
<div class="space-y-6" id="overview">
<h1 class="font-headline-md text-headline-md text-on-surface">Member Overview</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="bg-white p-6 rounded-3xl border border-outline-variant/20 flex flex-col justify-between card-hover">
<span class="material-symbols-outlined text-primary">receipt_long</span>
<div class="mt-4">
<p class="text-headline-sm font-headline-sm"><?= $recent_orders_count ?></p>
<p class="text-on-surface-variant font-label-md text-[12px]">Recent Orders</p>
</div>
</div>
<div class="bg-white p-6 rounded-3xl border border-outline-variant/20 flex flex-col justify-between card-hover">
<span class="material-symbols-outlined text-primary">local_cafe</span>
<div class="mt-4">
<p class="text-headline-sm font-headline-sm truncate" title="<?= htmlspecialchars($top_drink) ?>"><?= htmlspecialchars($top_drink) ?></p>
<p class="text-on-surface-variant font-label-md text-[12px]">Top Drink</p>
</div>
</div>

</div>
</div>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
<!-- Section: Transaction History -->
<div class="xl:col-span-2 space-y-6" id="history">
<div class="flex items-center justify-between">
<h2 class="font-headline-sm text-headline-sm">Transaction History</h2>
<a href="history.php" class="text-primary font-label-md hover:underline">View All</a>
</div>
<div class="overflow-x-auto no-scrollbar">
<table class="w-full text-left border-separate border-spacing-y-3">
<thead>
<tr class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
<th class="pb-2 px-4">Order</th>
<th class="pb-2 px-4">Date</th>
<th class="pb-2 px-4">Total</th>
<th class="pb-2 px-4">Status</th>
</tr>
</thead>
<tbody>
<?php if (empty($recent_txs)): ?>
<tr><td colspan="4" class="py-4 px-4 text-center text-on-surface-variant">No recent orders found.</td></tr>
<?php else: ?>
<?php foreach ($recent_txs as $tx): ?>
<tr class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-shadow">
<td class="py-4 px-4 font-bold rounded-l-2xl">#NG-<?= str_pad($tx['id'], 4, '0', STR_PAD_LEFT) ?></td>
<td class="py-4 px-4 text-on-surface-variant"><?= date('M d, Y', strtotime($tx['order_date'])) ?></td>
<td class="py-4 px-4 font-bold text-primary">$<?= number_format($tx['total_amount'], 2) ?></td>
<td class="py-4 px-4 rounded-r-2xl">
<?php
    $status_color = 'bg-gray-100 text-gray-700';
    if ($tx['status'] == 'Completed') $status_color = 'bg-green-100 text-green-700';
    elseif ($tx['status'] == 'Pending') $status_color = 'bg-amber-100 text-amber-700';
    elseif ($tx['status'] == 'Processing') $status_color = 'bg-blue-100 text-blue-700';
    elseif ($tx['status'] == 'Cancelled') $status_color = 'bg-red-100 text-red-700';
?>
<span class="<?= $status_color ?> px-3 py-1 rounded-full text-[10px] font-bold uppercase"><?= $tx['status'] ?></span>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
<!-- Section: Security -->
<div class="space-y-6" id="security">
<h2 class="font-headline-sm text-headline-sm">Security</h2>
<div class="bg-surface-container-high p-6 rounded-3xl space-y-6 border border-outline-variant/20">
<div class="flex items-start gap-4">
<div class="p-3 bg-white rounded-xl text-primary">
<span class="material-symbols-outlined">lock_reset</span>
</div>
<div>
<p class="font-bold font-label-md">Change Password</p>
<p class="text-[12px] text-on-surface-variant">Keep your account secure.</p>
</div>
</div>
</div>
<?php if (!empty($msg)): ?>
<div class="p-3 rounded-xl <?= $msg_type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> text-sm">
    <?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>
<form method="POST" action="profile.php" class="space-y-3">
<input type="hidden" name="action" value="change_password">
<input name="current_password" required class="w-full px-4 py-3 rounded-xl border border-outline-variant/30 bg-white focus:ring-2 focus:ring-primary outline-none text-sm" placeholder="Current Password" type="password">
<input name="new_password" required class="w-full px-4 py-3 rounded-xl border border-outline-variant/30 bg-white focus:ring-2 focus:ring-primary outline-none text-sm" placeholder="New Password" type="password">
<button type="submit" class="w-full py-3 border-2 border-primary text-primary rounded-full font-label-md hover:bg-primary hover:text-on-primary transition-all">
          Save Changes
        </button>
</form>
</div>
</div>
</div></section>
</main>
<!-- BottomNavBar (The Shell - Mobile Only) -->
<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center px-4 py-3 pb-safe bg-surface-container-highest dark:bg-inverse-surface shadow-lg lg:hidden z-50">
<a href="../index.php" class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-variant transition-transform active:scale-90">
<span class="material-symbols-outlined">home</span>
<span class="font-label-md text-[10px]">Home</span>
</a>
<a href="history.php" class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-variant transition-transform active:scale-90">
<span class="material-symbols-outlined">receipt_long</span>
<span class="font-label-md text-[10px]">History</span>
</a>
<a href="#" class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-variant transition-transform active:scale-90">
<span class="material-symbols-outlined">card_membership</span>
<span class="font-label-md text-[10px]">Loyalty</span>
</a>
<a href="profile.php" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-xl p-2 transition-transform active:scale-90">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">person</span>
<span class="font-label-md text-[10px]">Profile</span>
</a>
</nav>
<!-- Footer (The Final Anchor) -->
<footer class="bg-surface-dim dark:bg-on-surface w-full py-section-sm">
<div class="w-full max-w-container-max-lg mx-auto px-gutter flex flex-col md:flex-row justify-between items-center gap-6">
<span class="font-display-hero text-headline-sm text-primary">Ngopidea</span>
<p class="font-body-md text-body-md text-on-surface-variant dark:text-outline-variant text-center md:text-left max-w-md">
                © 2024 Ngopidea Artisanal Cafe. Crafted for clarity.
            </p>
<div class="flex gap-6">
<a class="text-on-surface-variant dark:text-outline-variant hover:text-primary transition-colors font-body-md text-body-md" href="#">Privacy Policy</a>
<a class="text-on-surface-variant dark:text-outline-variant hover:text-primary transition-colors font-body-md text-body-md" href="#">Terms of Service</a>
<a class="text-on-surface-variant dark:text-outline-variant hover:text-primary transition-colors font-body-md text-body-md" href="#">Sustainability</a>
</div>
</div>
</footer>
<!-- Micro-interaction Scripts -->
<script>
        // Sidebar logic (No e.preventDefault so it navigates)
        const navLinks = document.querySelectorAll('aside nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Let it navigate
            });
        });

        // Form submission animation mock
        const updateBtn = document.querySelector('button[type="submit"]');
        if(updateBtn) {
            updateBtn.addEventListener('click', (e) => {
                e.preventDefault();
                updateBtn.innerText = "Updating...";
                setTimeout(() => {
                    updateBtn.innerText = "Profile Updated!";
                    updateBtn.classList.replace('bg-primary', 'bg-green-600');
                    setTimeout(() => {
                        updateBtn.innerText = "Update Profile";
                        updateBtn.classList.replace('bg-green-600', 'bg-primary');
                    }, 2000);
                }, 800);
            });
        }
    </script>




<div id="snapdom-sandbox" data-snapdom-sandbox="true" aria-hidden="true" style="position: absolute; left: -9999px; top: -9999px; width: 0px; height: 0px; overflow: hidden;"></div></body></html>