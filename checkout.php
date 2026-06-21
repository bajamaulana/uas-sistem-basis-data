<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Redirect to signin if not logged in
if (!isLoggedIn()) {
    header("Location: signin.php");
    exit();
}

// Redirect to menu if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: menu.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get Customer ID
$stmt = $conn->prepare("SELECT id FROM customers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $customer_id = $result->fetch_assoc()['id'];
} else {
    // Should exist from signup, but fallback just in case
    $full_name = $_SESSION['user_name'] ?? 'Online Customer';
    $conn->query("INSERT INTO customers (user_id, full_name) VALUES ($user_id, '$full_name')");
    $customer_id = $conn->insert_id;
}

// Process POST Checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for default employee (System Online)
    $emp_result = $conn->query("SELECT id FROM employees WHERE full_name = 'System Online'");
    if ($emp_result->num_rows > 0) {
        $employee_id = $emp_result->fetch_assoc()['id'];
    } else {
        $conn->query("INSERT INTO employees (user_id, full_name, position) VALUES ($user_id, 'System Online', 'System')");
        $employee_id = $conn->insert_id;
    }

    // Check for default payment method
    $pm_result = $conn->query("SELECT id FROM payment_methods WHERE method_name = 'Credit Card'");
    if ($pm_result->num_rows > 0) {
        $payment_method_id = $pm_result->fetch_assoc()['id'];
    } else {
        $conn->query("INSERT INTO payment_methods (method_name, is_active) VALUES ('Credit Card', 1)");
        $payment_method_id = $conn->insert_id;
    }
    
    // Calculate Total
    $subtotal = 0;
    $product_ids = array_keys($_SESSION['cart']);
    $safe_ids = array_map('intval', $product_ids);
    $in_clause = implode(',', $safe_ids);
    
    $cart_products = [];
    if (!empty($safe_ids)) {
        $res = $conn->query("SELECT * FROM products WHERE id IN ($in_clause)");
        while ($row = $res->fetch_assoc()) {
            $qty = $_SESSION['cart'][$row['id']];
            $row['cart_qty'] = $qty;
            $cart_products[$row['id']] = $row;
            $subtotal += ($row['price'] * $qty);
        }
    }
    
    $tax = $subtotal * 0.08;
    $total_amount = $subtotal + $tax;
    
    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, employee_id, payment_method_id, total_amount, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iiid", $customer_id, $employee_id, $payment_method_id, $total_amount);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        
        // Insert order details
        $detail_stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($cart_products as $id => $prod) {
            $qty = $prod['cart_qty'];
            $price = $prod['price'];
            $item_subtotal = $price * $qty;
            $detail_stmt->bind_param("iiidd", $order_id, $id, $qty, $price, $item_subtotal);
            $detail_stmt->execute();
        }
        
        // Clear Cart
        $_SESSION['cart'] = [];
        
        // Redirect to success
        header("Location: checkout_success.php?order_id=" . $order_id);
        exit();
    } else {
        $error = "Error placing order: " . $conn->error;
    }
}

// Fetch Cart for Display
$cart_items = [];
$subtotal = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $safe_ids = array_map('intval', $product_ids);
    $in_clause = implode(',', $safe_ids);
    
    $result = $conn->query("SELECT * FROM products WHERE id IN ($in_clause)");
    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $row['cart_qty'] = $qty;
        $cart_items[] = $row;
        $subtotal += ($row['price'] * $qty);
    }
}

$tax = $subtotal * 0.08; // 8% tax
$total = $subtotal + $tax;
?>
<!DOCTYPE html><html class="light" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Checkout | Ngopidea Artisanal Coffee</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&amp;family=Merriweather:wght@400;700&amp;family=Plus+Jakarta+Sans:wght@400;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&amp;family=Playfair+Display:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-error-container": "#93000a",
                    "on-tertiary-fixed-variant": "#36495a",
                    "on-secondary-fixed": "#2b1700",
                    "outline-variant": "#e0c0b0",
                    "secondary-fixed-dim": "#edbe89",
                    "primary-fixed": "#ffdbc9",
                    "on-error": "#ffffff",
                    "on-background": "#231a13",
                    "error-container": "#ffdad6",
                    "tertiary-fixed-dim": "#b5c9de",
                    "surface-container-low": "#fff1e9",
                    "on-secondary-fixed-variant": "#604016",
                    "secondary-container": "#ffcf99",
                    "error": "#ba1a1a",
                    "surface-variant": "#f2dfd3",
                    "surface": "#fff8f5",
                    "tertiary-fixed": "#d1e5fb",
                    "on-primary-container": "#5d2700",
                    "surface-container": "#fdeade",
                    "secondary": "#7b572b",
                    "surface-container-high": "#f7e5d9",
                    "surface-dim": "#e9d7cb",
                    "on-secondary": "#ffffff",
                    "outline": "#8c7264",
                    "on-primary-fixed-variant": "#763300",
                    "secondary-fixed": "#ffddb9",
                    "on-secondary-container": "#7a562a",
                    "on-tertiary": "#ffffff",
                    "primary-fixed-dim": "#ffb68d",
                    "surface-container-lowest": "#ffffff",
                    "on-primary": "#ffffff",
                    "surface-tint": "#9a4600",
                    "on-tertiary-container": "#263849",
                    "on-primary-fixed": "#321200",
                    "primary-container": "#ff790b",
                    "on-surface-variant": "#584236",
                    "inverse-primary": "#ffb68d",
                    "background": "#fff8f5",
                    "tertiary": "#4d6073",
                    "on-surface": "#231a13",
                    "surface-container-highest": "#f2dfd3",
                    "inverse-surface": "#392e26",
                    "primary": "#9a4600",
                    "on-tertiary-fixed": "#081d2d",
                    "tertiary-container": "#8ea2b6",
                    "surface-bright": "#fff8f5",
                    "inverse-on-surface": "#ffede3"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "section-md": "80px",
                    "unit": "8px",
                    "container-max-lg": "1200px",
                    "gutter": "20px",
                    "section-sm": "40px",
                    "section-lg": "100px",
                    "container-max-md": "1100px"
            },
            "fontFamily": {
                    "body-md": ["Merriweather"],
                    "label-md": ["Plus Jakarta Sans"],
                    "headline-sm": ["Playfair Display"],
                    "headline-md": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"],
                    "display-hero": ["Playfair Display"],
                    "body-lg": ["Merriweather"]
            },
            "fontSize": {
                    "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "700"}],
                    "headline-md": ["40px", {"lineHeight": "1.3", "fontWeight": "700"}],
                    "headline-lg": ["56px", {"lineHeight": "1.2", "letterSpacing": "-1px", "fontWeight": "700"}],
                    "display-hero": ["72px", {"lineHeight": "1.2", "letterSpacing": "2px", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.8", "fontWeight": "400"}]
            }
          }
        },
      }
    </script>
<style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        display: inline-block;
        vertical-align: middle;
      }
      .glass-nav {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
      .form-input-focus:focus {
        border-color: #9a4600;
        box-shadow: 0 0 0 2px rgba(154, 70, 0, 0.1);
        outline: none;
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
    </style>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-fixed selection:text-on-primary-fixed">
<!-- TopNavBar (Suppressed for Checkout as per Navigation Shell Rule for Transactional pages) -->
<header class="w-full h-20 flex items-center px-gutter max-w-container-max-lg mx-auto bg-surface/95 z-50">
<div class="flex items-center gap-4">
<a class="text-primary hover:opacity-70 transition-opacity" href="#">
<span class="material-symbols-outlined">arrow_back</span>
</a>
<h1 class="font-headline-sm text-headline-sm font-bold text-primary">Ngopidea</h1>
</div>
<div class="ml-auto flex items-center gap-2 text-on-surface-variant font-label-md">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">lock</span>
<span class="">Secure Checkout</span>
</div>
</header>
<main class="max-w-container-max-lg mx-auto px-gutter py-section-md">
<?php if (isset($error)): ?>
  <div class="mb-8 p-4 bg-error-container text-on-error-container rounded-lg font-body-md border border-error/20">
      <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>
<form action="checkout.php" method="POST">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
<!-- Left Column: Details & Payment -->
<div class="lg:col-span-7 space-y-10">
<!-- Section: Fulfillment -->
<section class="space-y-6">
<div class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-label-md">1</span>
<h2 class="font-headline-sm text-headline-sm">Fulfillment Details</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-xl border border-outline-variant bg-surface-container-lowest shadow-[0_2px_10px_rgba(62,51,43,0.05)]">
<div class="space-y-2 col-span-full">
<label class="block font-label-md text-on-surface-variant">Order Type</label>
<div class="flex gap-4">
<button class="flex-1 py-3 px-4 rounded-lg border-2 border-primary bg-primary-fixed/20 text-primary font-bold flex items-center justify-center gap-2">
<span class="material-symbols-outlined">store</span> Pickup
                                </button>
<button class="flex-1 py-3 px-4 rounded-lg border-2 border-outline-variant text-on-surface-variant flex items-center justify-center gap-2 hover:border-primary/50 transition-colors">
<span class="material-symbols-outlined">delivery_dining</span> Delivery
                                </button>
</div>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface-variant" for="pickup-location">Select Store</label>
<select class="w-full p-4 rounded-lg border border-outline-variant bg-surface-bright form-input-focus font-body-md" id="pickup-location">
<option>The Ritual House - Downtown</option>
<option>The Beanery - North District</option>
<option>Arisanal Corner - Westside</option>
</select>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface-variant" for="pickup-time">Pickup Time</label>
<select class="w-full p-4 rounded-lg border border-outline-variant bg-surface-bright form-input-focus font-body-md" id="pickup-time">
<option>As soon as possible (15-20 mins)</option>
<option>10:30 AM</option>
<option>11:00 AM</option>
<option>11:30 AM</option>
<option>12:00 PM</option>
</select>
</div>
</div>
</section>
<!-- Section: Payment Information -->
<section class="space-y-6">
<div class="flex items-center gap-3">
<span class="w-8 h-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-label-md">2</span>
<h2 class="font-headline-sm text-headline-sm">Payment Method</h2>
</div>
<div class="p-6 rounded-xl border border-outline-variant bg-surface-container-lowest shadow-[0_2px_10px_rgba(62,51,43,0.05)] space-y-6">
<div class="flex flex-wrap gap-3">
<button class="flex-1 min-w-[140px] p-4 rounded-lg border-2 flex flex-col items-center gap-2 border-outline-variant">
<span class="material-symbols-outlined text-primary">credit_card</span>
<span class="font-label-md">Credit Card</span>
</button>
<button class="flex-1 min-w-[140px] p-4 rounded-lg border-2 hover:border-primary/50 flex flex-col items-center gap-2 transition-all border-primary bg-primary-fixed/10">
<span class="material-symbols-outlined">account_balance_wallet</span>
<span class="font-label-md">Paypal</span>
</button>
<button class="flex-1 min-w-[140px] p-4 rounded-lg border-2 hover:border-primary/50 flex flex-col items-center gap-2 transition-all border-outline-variant">
<span class="material-symbols-outlined">payments</span>
<span class="font-label-md">Google Pay</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="space-y-2 col-span-full">
<label class="block font-label-md text-on-surface-variant">Cardholder Name</label>
<input class="w-full p-4 rounded-lg border border-outline-variant bg-surface-bright form-input-focus" placeholder="Julian V. Espresso" type="text">
</div>
<div class="space-y-2 col-span-full">
<label class="block font-label-md text-on-surface-variant">Card Number</label>
<div class="relative">
<input class="w-full p-4 rounded-lg border border-outline-variant bg-surface-bright form-input-focus" placeholder="**** **** **** 4421" type="text">
<span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">credit_card</span>
</div>
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface-variant">Expiry Date</label>
<input class="w-full p-4 rounded-lg border border-outline-variant bg-surface-bright form-input-focus" placeholder="MM / YY" type="text">
</div>
<div class="space-y-2">
<label class="block font-label-md text-on-surface-variant">CVV</label>
<input class="w-full p-4 rounded-lg border border-outline-variant bg-surface-bright form-input-focus" placeholder="***" type="text">
</div>
</div>
<div class="flex items-center gap-3 py-2">
<input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary" id="save-card" type="checkbox">
<label class="text-body-md text-on-surface-variant" for="save-card">Save payment info for my next ritual.</label>
</div>
</div>
</section>
</div>
<!-- Right Column: Order Summary -->
<aside class="lg:col-span-5 sticky top-24">
<div class="p-8 rounded-2xl bg-surface-container-high border border-outline-variant shadow-lg space-y-8">
<h2 class="font-headline-sm text-headline-sm text-primary">Your Order</h2>
<!-- Item List -->
<div class="space-y-6 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
<?php foreach ($cart_items as $item): ?>
<div class="flex gap-4">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" src="assets/<?= htmlspecialchars($item['image_url']) ?>">
</div>
<div class="flex-1">
<div class="flex justify-between">
<h3 class="font-bold text-on-surface"><?= htmlspecialchars($item['product_name']) ?></h3>
<span class="font-label-md text-primary">$<?= number_format($item['price'] * $item['cart_qty'], 2) ?></span>
</div>
<p class="text-label-md text-on-surface-variant mt-1 line-clamp-1"><?= htmlspecialchars($item['description']) ?></p>
<div class="flex items-center gap-3 mt-2">
<span class="font-label-md">Qty: <?= $item['cart_qty'] ?></span>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<!-- Discount Code -->
<div class="flex gap-2">
<input class="flex-1 p-3 rounded-lg border border-outline-variant bg-surface-bright form-input-focus text-label-md" placeholder="Promo Code" type="text">
<button class="px-6 py-3 rounded-lg bg-secondary text-on-secondary font-label-md hover:brightness-110 transition-all">Apply</button>
</div>
<!-- Totals -->
<div class="pt-6 border-t border-outline-variant space-y-3">
<div class="flex justify-between text-body-md text-on-surface-variant">
<span class="">Subtotal</span>
<span class="">$<?= number_format($subtotal, 2) ?></span>
</div>
<div class="flex justify-between text-body-md text-on-surface-variant">
<span class="">Tax (8%)</span>
<span class="">$<?= number_format($tax, 2) ?></span>
</div>
<div class="flex justify-between pt-4 border-t border-outline-variant">
<span class="font-headline-sm text-headline-sm">Total</span>
<span class="font-headline-sm text-headline-sm text-primary">$<?= number_format($total_amount ?? $total, 2) ?></span>
</div>
</div>
<button type="submit" class="w-full py-5 px-6 rounded-full bg-primary text-on-primary font-label-md text-lg shadow-[0_10px_30px_rgba(154,70,0,0.3)] hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                        Place Order
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
<p class="text-center text-label-md text-outline italic">
                        Estimated ready time: 10:45 AM
                    </p>
</div>
<!-- Trust Badges -->
<div class="mt-6 flex justify-center gap-6 opacity-60 grayscale hover:grayscale-0 transition-all">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm">verified_user</span>
<span class="text-[10px] font-bold uppercase tracking-widest">PCI Compliant</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm">encrypted</span>
<span class="text-[10px] font-bold uppercase tracking-widest">256-Bit SSL</span>
</div>
</div>
</aside>
</div>
</form>
</main>
<!-- Simple Transactional Footer -->
<footer class="w-full py-12 bg-surface-container-low mt-section-lg border-t border-outline-variant/10">
<div class="max-w-container-max-lg mx-auto px-gutter flex flex-col md:flex-row justify-between items-center gap-6">
<div class="flex flex-col items-center md:items-start gap-2">
<span class="font-headline-sm text-headline-sm text-primary">Ngopidea</span>
<p class="font-label-md text-on-surface-variant">© 2024 Ngopidea Artisanal Coffee. Crafted for Clarity.</p>
</div>
<div class="flex gap-8 font-label-md text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
<a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
<a class="hover:text-primary transition-colors" href="#">Support</a>
</div>
</div>
</footer>
<script>
        // Micro-interactions for form fields
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('focus', () => {
                element.parentElement.querySelector('label')?.classList.add('text-primary');
            });
            element.addEventListener('blur', () => {
                element.parentElement.querySelector('label')?.classList.remove('text-primary');
            });
        });

        // Toggle Payment Methods (UI only)
        const paymentButtons = document.querySelectorAll('button[class*="min-w-[140px]"]');
        paymentButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                paymentButtons.forEach(b => {
                    b.classList.remove('border-primary', 'bg-primary-fixed/10');
                    b.classList.add('border-outline-variant');
                });
                btn.classList.add('border-primary', 'bg-primary-fixed/10');
                btn.classList.remove('border-outline-variant');
            });
        });
    </script>


</body></html>