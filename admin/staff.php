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
    
    // Bersihkan buffer output agar tidak ada spasi/enter liar yang ikut terkirim
    if (ob_get_length()) ob_clean(); 
    
    header('Content-Type: application/json');
    
    if (isset($data['action']) && $data['action'] === 'add') {
        try {
            $conn->begin_transaction();
            // 1. Create User
            $stmt = $conn->prepare("INSERT INTO users (role_id, email, password) VALUES (?, ?, ?)");
            $role_id = 2; // Staff role
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bind_param("iss", $role_id, $data['email'], $password);
            $stmt->execute();
            $user_id = $conn->insert_id;
            
            // 2. Create Employee
            $stmt2 = $conn->prepare("INSERT INTO employees (user_id, full_name, position, hire_date) VALUES (?, ?, ?, CURDATE())");
            $full_name = explode('@', $data['email'])[0];
            $position = 'Staff';
            $stmt2->bind_param("iss", $user_id, $full_name, $position);
            $stmt2->execute();
            
            $conn->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit; // Memastikan script PHP berhenti di sini dan tidak mengeksekusi HTML di bawahnya
    }
}
// --- END AJAX HANDLER ---

$staff_sql = "SELECT e.*, u.email, r.role_name FROM employees e JOIN users u ON e.user_id = u.id JOIN roles r ON u.role_id = r.id";
$staff_result = $conn->query($staff_sql);

$staff = [];
while ($row = $staff_result->fetch_assoc()) {
    $staff[] = $row;
}
$total_staff = count($staff);
$on_shift = min(4, $total_staff);
?>
<!DOCTYPE html><html class="light" lang="en" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Ngopidea Admin | Staff Directory</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@300;400;700&amp;family=Plus+Jakarta+Sans:wght@500;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet">
<!-- Tailwind -->
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
                    "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "0px", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                    "display-hero-mobile": ["35px", {"lineHeight": "1.2", "letterSpacing": "1px", "fontWeight": "700"}],
                    "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}],
                    "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .active-nav-border {
            border-right-width: 4px;
        }
        .staff-table-row:hover {
            background-color: rgba(255, 121, 11, 0.05);
            transition: background-color 0.2s ease;
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-surface selection:bg-primary-container selection:text-white">
<!-- SideNavBar (Execution from JSON) -->
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
<a class="flex items-center gap-3 px-4 py-3 bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary rounded-lg transition-all scale-95 duration-150 active:scale-90" href="staff.php">
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
<!-- TopNavBar (Execution from JSON) -->
<header class="h-16 fixed top-0 z-40 bg-white/95 dark:bg-surface-container-highest/95 backdrop-blur-md shadow-sm flex justify-between items-center px-8 ml-64 w-[calc(100%-16rem)]">
<div class="flex items-center flex-1">
<div class="relative w-96">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
<input class="w-full bg-surface-container border-none rounded-full py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary-container font-label-md" placeholder="Search staff or records..." type="text">
</div>
</div>
<div class="flex items-center gap-6">
<button class="text-on-surface-variant hover:text-primary transition-all duration-200 relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full border-2 border-white"></span>
</button>
<div class="flex items-center gap-3 cursor-pointer group">
<div class="text-right">
<p class="font-label-md text-sm text-on-surface font-bold group-hover:text-primary transition-colors">Admin User</p>
<p class="font-label-md text-[10px] text-on-surface-variant uppercase tracking-wider">Super Admin</p>
</div>
<div class="w-10 h-10 rounded-full border-2 border-primary-container/20 overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A professional headshot of a boutique coffee shop owner in a warm light-mode setting. The style is artisanal and clean, featuring a person wearing a leather apron with a blurred background of a modern espresso bar. Muted earthy tones and soft natural light dominate the image." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTMvtfUkNB6awGcM4GBshuyyc6WcYxrBSb_TbO_4_RxELgQSN3E3-WYhuh6WGBOn78TE64Rv2eeC9u92DpRtbZArQ8_-ii0-d5xp2s6mQD7BWsyky396-xnl8M8vJnqwRUVRrUj1OrqIcWr0gzM7Oc134oFkrwSMDW69A3HmeB0ca9OPvOrpYXhnVzLgyHlj5G0MLSM5Me-5wyXvfgaCOIwZtWLgQuhwb-W12xKhcgO8fufg7XuVXlO5-cupTiN9g1MpTvZjWTypY">
</div>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="ml-64 pt-16 min-h-screen">
<div class="max-w-container-max-md mx-auto px-gutter py-section-md">
<!-- Header Section -->
<div class="flex justify-between items-end mb-12">
<div>
<h2 class="font-headline-md text-headline-md text-on-surface">Staff Directory</h2>
<p class="font-body-md text-on-surface-variant opacity-80 mt-2">Manage your artisanal team, shifts, and performance.</p>
</div>
<button onclick="openStaffModal()" class="bg-primary-container text-white px-8 py-3 rounded-full font-label-md flex items-center gap-2 hover:bg-primary transition-all duration-300 shadow-lg hover:shadow-primary-container/30 transform hover:-translate-y-1">
<span class="material-symbols-outlined">person_add</span>
                    Add New Staff
                </button>
</div>
<!-- Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
<!-- Stat Card 1 -->
<div class="bg-white p-8 rounded-xl shadow-[0_2px_10px_rgba(62,51,43,0.05)] border border-outline-variant/10 hover:shadow-[0_10px_30px_rgba(138,62,0,0.15)] transition-all duration-300 transform hover:-translate-y-1 group">
<div class="flex justify-between items-start mb-4">
<div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary group-hover:bg-primary-container group-hover:text-white transition-colors">
<span class="material-symbols-outlined">groups</span>
</div>
<span class="text-xs font-label-md bg-secondary-container/30 text-secondary px-2 py-1 rounded">+2 this month</span>
</div>
<p class="font-label-md text-sm text-on-surface-variant">Total Staff</p>
<h3 class="font-headline-sm text-headline-sm text-on-surface mt-1"><?= $total_staff ?></h3>
</div>
<!-- Stat Card 2 -->
<div class="bg-white p-8 rounded-xl shadow-[0_2px_10px_rgba(62,51,43,0.05)] border border-outline-variant/10 hover:shadow-[0_10px_30px_rgba(138,62,0,0.15)] transition-all duration-300 transform hover:-translate-y-1 group">
<div class="flex justify-between items-start mb-4">
<div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-secondary group-hover:bg-secondary-container transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">hourglass_top</span>
</div>
<span class="flex items-center gap-1 text-[10px] font-bold text-green-600 animate-pulse">
<span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span> LIVE
                        </span>
</div>
<p class="font-label-md text-sm text-on-surface-variant">Currently on Shift</p>
<h3 class="font-headline-sm text-headline-sm text-on-surface mt-1"><?= $on_shift ?></h3>
</div>
<!-- Stat Card 3 -->
<div class="bg-white p-8 rounded-xl shadow-[0_2px_10px_rgba(62,51,43,0.05)] border border-outline-variant/10 hover:shadow-[0_10px_30px_rgba(138,62,0,0.15)] transition-all duration-300 transform hover:-translate-y-1 group">
<div class="flex justify-between items-start mb-4">
<div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary group-hover:bg-primary-container group-hover:text-white transition-colors">
<span class="material-symbols-outlined">stars</span>
</div>
<div class="flex">
<span class="material-symbols-outlined text-[14px] text-primary">star</span>
<span class="material-symbols-outlined text-[14px] text-primary">star</span>
<span class="material-symbols-outlined text-[14px] text-primary">star</span>
</div>
</div>
<p class="font-label-md text-sm text-on-surface-variant">Staff Performance</p>
<h3 class="font-headline-sm text-headline-sm text-on-surface mt-1">4.8 / 5.0</h3>
</div>
</div>
<!-- Staff Table Container -->
<div class="bg-white rounded-xl shadow-sm border border-outline-variant/20 overflow-hidden">
<div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-low/30">
<h4 class="font-headline-sm text-[20px] text-on-surface">Team Roster</h4>
<div class="flex gap-2">
<button class="p-2 hover:bg-surface-container rounded-md transition-colors">
<span class="material-symbols-outlined text-on-surface-variant">filter_list</span>
</button>
<button class="p-2 hover:bg-surface-container rounded-md transition-colors">
<span class="material-symbols-outlined text-on-surface-variant">download</span>
</button>
</div>
</div>
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-8 py-4 font-label-md text-on-surface-variant uppercase text-[11px] tracking-widest border-b border-outline-variant/10">Staff Name</th>
<th class="px-8 py-4 font-label-md text-on-surface-variant uppercase text-[11px] tracking-widest border-b border-outline-variant/10">Role</th>
<th class="px-8 py-4 font-label-md text-on-surface-variant uppercase text-[11px] tracking-widest border-b border-outline-variant/10">Contact</th>
<th class="px-8 py-4 font-label-md text-on-surface-variant uppercase text-[11px] tracking-widest border-b border-outline-variant/10">Shift Status</th>
<th class="px-8 py-4 font-label-md text-on-surface-variant uppercase text-[11px] tracking-widest border-b border-outline-variant/10 text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">

<?php foreach ($staff as $index => $employee): ?>
    <?php 
        $is_manager = strtolower($employee['role_name']) === 'admin' || strtolower($employee['position']) === 'manager';
        $bg_class = $is_manager ? 'bg-secondary-container/20 text-secondary' : 'bg-tertiary-container/20 text-tertiary';
        $status_color = ($index % 3 == 0 && $index != 0) ? 'bg-amber-500' : (($index % 5 == 0 && $index != 0) ? 'bg-gray-300' : 'bg-green-500');
        $status_text = ($index % 3 == 0 && $index != 0) ? 'On Break' : (($index % 5 == 0 && $index != 0) ? 'Off Shift' : 'On Shift');
    ?>
    <tr class="staff-table-row">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-surface-container-high overflow-hidden ring-2 ring-primary-container/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">person</span>
                </div>
                <div>
                    <p class="font-label-md text-sm font-bold text-on-surface"><?= htmlspecialchars($employee['full_name']) ?></p>
                    <p class="text-[11px] text-on-surface-variant/70">ID: NG-<?= 1000 + $employee['id'] ?></p>
                </div>
            </div>
        </td>
        <td class="px-8 py-5">
            <span class="px-3 py-1 <?= $bg_class ?> text-[11px] font-bold rounded-full uppercase tracking-tighter"><?= htmlspecialchars($employee['position'] ?: $employee['role_name']) ?></span>
        </td>
        <td class="px-8 py-5">
            <p class="text-sm font-body-md text-on-surface-variant"><?= htmlspecialchars($employee['email']) ?></p>
        </td>
        <td class="px-8 py-5">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 <?= $status_color ?> rounded-full"></span>
                <span class="text-xs font-label-md text-on-surface-variant <?= $status_text == 'Off Shift' ? 'opacity-60' : '' ?>"><?= $status_text ?></span>
            </div>
        </td>
        <td class="px-8 py-5 text-right">
            <button class="p-2 text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">edit_square</span>
            </button>
            <button class="p-2 text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">more_vert</span>
            </button>
        </td>
    </tr>
<?php endforeach; ?>

</tbody>
</table>
<!-- Pagination Footer -->
<div class="p-6 border-t border-outline-variant/10 flex justify-between items-center text-xs font-label-md text-on-surface-variant/60">
<p class="">Showing <?= $total_staff ?> of <?= $total_staff ?> employees</p>
<div class="flex items-center gap-4">
<button class="flex items-center gap-1 hover:text-primary transition-colors" disabled="">
<span class="material-symbols-outlined text-[16px]">chevron_left</span> Previous
                        </button>
<div class="flex items-center gap-2">
<span class="w-6 h-6 bg-primary-container text-white rounded-full flex items-center justify-center font-bold">1</span>
<span class="w-6 h-6 hover:bg-surface-container rounded-full flex items-center justify-center cursor-pointer">2</span>
</div>
<button class="flex items-center gap-1 hover:text-primary transition-colors">
                            Next <span class="material-symbols-outlined text-[16px]">chevron_right</span>
</button>
</div>
</div>
</div>
<!-- Performance Snapshot (Asymmetric Layout element) -->
<div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8">


</div>
</div>
</main>
<!-- Staff Modal Template -->
<div id="staffModal" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 class="font-headline-sm text-primary">Add New Staff</h3>
            <button onclick="closeStaffModal()" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <form id="staffForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Email Address</label>
                    <input type="email" id="staffEmail" required class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest">
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface-variant mb-1">Password</label>
                    <input type="password" id="staffPassword" required class="w-full rounded-lg border-outline-variant/50 focus:ring-primary focus:border-primary bg-surface-container-lowest">
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeStaffModal()" class="px-5 py-2 rounded-lg font-bold text-on-surface-variant hover:bg-surface-variant transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg font-bold bg-primary text-on-primary hover:brightness-110 transition-colors">Save Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openStaffModal() {
        document.getElementById('staffForm').reset();
        document.getElementById('staffModal').classList.remove('hidden');
    }

    function closeStaffModal() {
        document.getElementById('staffModal').classList.add('hidden');
    }

    document.getElementById('staffForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const payload = {
            action: 'add',
            email: document.getElementById('staffEmail').value,
            password: document.getElementById('staffPassword').value
        };
        
        try {
            const res = await fetch('staff.php', {
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

        // Simple micro-interaction for active state scaling
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('mousedown', () => {
                link.classList.add('scale-95');
            });
            link.addEventListener('mouseup', () => {
                link.classList.remove('scale-95');
            });
            link.addEventListener('mouseleave', () => {
                link.classList.remove('scale-95');
            });
        });
    </script>


</body></html>