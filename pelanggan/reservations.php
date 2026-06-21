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

$total_reservations = 0;
$reservations = [];

if ($customer_id) {
    // Total reservations
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM reservations WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $total_reservations = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    // Reservation history
    $stmt = $conn->prepare("
        SELECT r.id, r.reservation_time, r.guest_count, r.status, t.table_number
        FROM reservations r
        JOIN tables t ON r.table_id = t.id
        WHERE r.customer_id = ?
        ORDER BY r.reservation_time DESC
    ");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html><html class="light" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>My Reservations | Ngopidea Artisanal Cafe</title>
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
<a class="rounded-lg flex items-center gap-3 px-4 py-3 hover:translate-x-1 transition-all font-label-md text-label-md text-on-surface-variant hover:bg-surface-variant" href="profile.php">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 0;">person</span>
                    Overview
                </a>
<a class="rounded-lg flex items-center gap-3 px-4 py-3 hover:translate-x-1 transition-all font-label-md text-label-md text-on-surface-variant hover:bg-surface-variant" href="history.php">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 0;">history</span>
                    History
                </a>
<a class="rounded-lg flex items-center gap-3 px-4 py-3 transition-all font-label-md text-label-md bg-primary-container text-on-primary-container font-bold" href="reservations.php">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">event_seat</span>
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
<section class="flex-grow p-gutter md:p-10 space-y-12">
<div class="space-y-6" id="overview">
<h1 class="font-headline-md text-headline-md text-on-surface">My Reservations</h1>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="bg-white p-6 rounded-3xl border border-outline-variant/20 flex flex-col justify-between card-hover">
<span class="material-symbols-outlined text-primary">event_seat</span>
<div class="mt-4">
<p class="text-headline-sm font-headline-sm"><?= $total_reservations ?></p>
<p class="text-on-surface-variant font-label-md text-[12px]">Total Reservations</p>
</div>
</div>
</div>
</div>

<div class="space-y-6" id="history">
<div class="flex flex-col md:flex-row gap-4 mb-8 items-end md:items-center">
<div class="relative flex-1 group w-full">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">search</span>
<input class="w-full pl-12 pr-4 py-3 bg-white border border-outline-variant/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" placeholder="Search by status or table..." type="text">
</div>
<div class="flex gap-4 w-full md:w-auto">
<select id="status-filter" class="flex-1 md:flex-none px-4 py-3 bg-white border border-outline-variant/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 font-label-md text-label-md appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke%3D%22%239a4600%22%3E%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%222%22%20d%3D%22M19%209l-7%207-7-7%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat pr-12 cursor-pointer">
<option value="All">All Status</option>
<option value="Confirmed">Confirmed</option>
<option value="Completed">Completed</option>
<option value="Pending">Pending</option>
<option value="Cancelled">Cancelled</option>
</select>
<button class="flex items-center gap-2 px-6 py-3 bg-surface-container-high text-on-surface-variant rounded-lg font-label-md text-label-md hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined">filter_list</span>
                        Filter
                    </button>
</div>
</div>

<div class="overflow-x-auto no-scrollbar">
<table class="w-full text-left border-separate border-spacing-y-3">
<thead>
<tr class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
<th class="pb-2 px-4">Reservation ID</th>
<th class="pb-2 px-4">Date & Time</th>
<th class="pb-2 px-4">Guests</th>
<th class="pb-2 px-4">Table</th>
<th class="pb-2 px-4">Status</th>
</tr>
</thead>
<tbody id="transactions-body">
<?php if (empty($reservations)): ?>
<tr><td colspan="5" class="py-4 px-4 text-center text-on-surface-variant">No reservations found.</td></tr>
<?php else: ?>
<?php foreach ($reservations as $index => $res): ?>
<tr class="order-row bg-white rounded-2xl shadow-sm border border-outline-variant/10 hover:shadow-md transition-shadow" data-status="<?= $res['status'] ?>">
<td class="py-4 px-4 font-bold rounded-l-2xl">#RES-<?= str_pad($res['id'], 4, '0', STR_PAD_LEFT) ?></td>
<td class="py-4 px-4 text-on-surface-variant"><?= date('M d, Y g:i A', strtotime($res['reservation_time'])) ?></td>
<td class="py-4 px-4 font-body-md text-on-surface"><?= htmlspecialchars($res['guest_count']) ?> Guests</td>
<td class="py-4 px-4 font-bold text-primary">Table <?= htmlspecialchars($res['table_number']) ?></td>
<td class="py-4 px-4 rounded-r-2xl">
<?php
    $status_color = 'bg-gray-100 text-gray-700';
    if ($res['status'] == 'Completed') $status_color = 'bg-green-100 text-green-700';
    elseif ($res['status'] == 'Pending') $status_color = 'bg-amber-100 text-amber-700';
    elseif ($res['status'] == 'Confirmed') $status_color = 'bg-blue-100 text-blue-700';
    elseif ($res['status'] == 'Cancelled') $status_color = 'bg-red-100 text-red-700';
?>
<span class="<?= $status_color ?> px-3 py-1 rounded-full text-[10px] font-bold uppercase"><?= $res['status'] ?></span>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</section>
</main>
<!-- BottomNavBar (The Shell - Mobile Only) -->
<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center px-4 py-3 pb-safe bg-surface-container-highest dark:bg-inverse-surface shadow-lg lg:hidden z-50">
<a href="../index.php" class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-variant transition-transform active:scale-90">
<span class="material-symbols-outlined">home</span>
<span class="font-label-md text-[10px]">Home</span>
</a>
<a href="history.php" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-xl p-2 transition-transform active:scale-90">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">receipt_long</span>
<span class="font-label-md text-[10px]">History</span>
</a>
<a href="#" class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-variant transition-transform active:scale-90">
<span class="material-symbols-outlined">card_membership</span>
<span class="font-label-md text-[10px]">Loyalty</span>
</a>
<a href="profile.php" class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:bg-surface-variant transition-transform active:scale-90">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 0;">person</span>
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

        
        // Search and Filter logic
        const searchInput = document.querySelector('input[type="text"]');
        const filterSelect = document.getElementById('status-filter');
        
        function filterRows() {
            const query = searchInput.value.toLowerCase();
            const status = filterSelect.value;
            
            document.querySelectorAll('.order-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowStatus = row.getAttribute('data-status');
                const matchesSearch = text.includes(query);
                const matchesStatus = status === 'All' || rowStatus === status;
                
                if (matchesSearch && matchesStatus) {
                    row.style.display = 'table-row';
                    row.style.opacity = '1';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        if (searchInput && filterSelect) {
            searchInput.addEventListener('input', filterRows);
            filterSelect.addEventListener('change', filterRows);
        }

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