<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Redirect to signin if not logged in
if (!isLoggedIn()) {
    header("Location: signin.php");
    exit();
}

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
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Your Ritual | Ngopidea Artisanal Coffee</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Merriweather:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@100..900&family=Playfair+Display:wght@100..900&family=Plus+Jakarta+Sans:wght@100..900&display=swap" rel="stylesheet"/>
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
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-nav {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .cart-item-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s ease;
        }
        .cart-item-transition:hover {
            transform: translateY(-4px);
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">
<!-- TopNavBar -->
<?php include 'includes/navbar.php'; ?>
<main class="flex-grow w-full max-w-container-max-lg mx-auto px-gutter py-section-sm md:py-section-md">
<!-- Breadcrumb / Back Navigation -->
<div class="mb-8 flex items-center gap-2">
<a class="group flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors" href="menu.php">
<span class="material-symbols-outlined text-[20px]">arrow_back</span>
<span class="font-label-md text-label-md">Back to Menu</span>
</a>
</div>
<h1 class="font-headline-md text-headline-md mb-10 text-on-surface">Your Selection</h1>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
<!-- Cart Items List -->
<div class="lg:col-span-8 space-y-6">
  <?php if (empty($cart_items)): ?>
    <div class="text-center py-12 bg-surface-container-low rounded-xl border border-outline-variant/10">
      <span class="material-symbols-outlined text-[48px] text-outline mb-4 block">shopping_cart</span>
      <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Your cart is empty</h3>
      <p class="text-on-surface-variant font-body-md mb-6">Looks like you haven't added any artisanal brews yet.</p>
      <a href="menu.php" class="inline-block px-8 py-3 bg-primary text-on-primary rounded-full font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-all shadow-lg hover:scale-105">Browse Menu</a>
    </div>
  <?php else: ?>
    <?php foreach ($cart_items as $item): ?>
    <div class="flex flex-col sm:flex-row items-center sm:items-stretch gap-6 p-6 bg-surface-container-low rounded-xl cart-item-transition border border-outline-variant/10">
      <div class="w-32 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-surface-variant">
        <img class="w-full h-full object-cover" src="assets/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>"/>
      </div>
      <div class="flex-grow flex flex-col justify-between text-center sm:text-left py-1">
        <div>
          <h3 class="font-headline-sm text-headline-sm text-on-surface"><?= htmlspecialchars($item['product_name']) ?></h3>
          <p class="font-label-md text-label-md text-on-surface-variant mt-1 line-clamp-2"><?= htmlspecialchars($item['description']) ?></p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center justify-center sm:justify-start gap-8">
          
          <form action="cart_action.php" method="POST" class="flex items-center bg-surface border border-outline-variant rounded-full px-2 py-1">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
            <button type="submit" name="quantity" value="<?= $item['cart_qty'] - 1 ?>" class="p-2 hover:text-primary transition-colors flex items-center"><span class="material-symbols-outlined text-[18px]">remove</span></button>
            <input class="w-10 text-center bg-transparent border-none focus:ring-0 font-label-md text-on-surface" type="number" readonly value="<?= $item['cart_qty'] ?>"/>
            <button type="submit" name="quantity" value="<?= $item['cart_qty'] + 1 ?>" class="p-2 hover:text-primary transition-colors flex items-center"><span class="material-symbols-outlined text-[18px]">add</span></button>
          </form>
          
          <span class="font-headline-sm text-[20px] text-primary">$<?= number_format($item['price'] * $item['cart_qty'], 2) ?></span>
        </div>
      </div>
      <form action="cart_action.php" method="POST">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
        <button type="submit" class="sm:self-start p-2 text-on-surface-variant hover:text-error transition-colors">
          <span class="material-symbols-outlined">delete_outline</span>
        </button>
      </form>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<!-- Order Summary Section -->
<div class="lg:col-span-4">
<div class="sticky top-28 bg-surface-container p-8 rounded-2xl shadow-[0_2px_10px_rgba(62,51,43,0.05)]">
<h2 class="font-headline-sm text-headline-sm text-on-surface mb-6">Summary</h2>
<div class="space-y-4 mb-8">
<div class="flex justify-between items-center">
<span class="font-body-md text-on-surface-variant">Subtotal</span>
<span class="font-label-md text-on-surface text-lg">$<?= number_format($subtotal, 2) ?></span>
</div>
<div class="flex justify-between items-center">
<span class="font-body-md text-on-surface-variant">Tax (8%)</span>
<span class="font-label-md text-on-surface text-lg">$<?= number_format($tax, 2) ?></span>
</div>
<div class="flex justify-between items-center">
<span class="font-body-md text-on-surface-variant">Delivery</span>
<span class="font-label-md text-primary text-lg">Free</span>
</div>
<div class="h-px bg-outline-variant/30 my-2"></div>
<div class="flex justify-between items-center pt-2">
<span class="font-headline-sm text-[20px] text-on-surface">Total</span>
<span class="font-headline-sm text-[28px] text-primary">$<?= number_format($total, 2) ?></span>
</div>
</div>
<div class="space-y-4">

    <?php if (empty($cart_items)): ?>
        <a class="w-full bg-surface-variant text-on-surface-variant py-5 rounded-full font-label-md text-label-md uppercase tracking-wider flex items-center justify-center gap-3 cursor-not-allowed opacity-70" 
           href="javascript:void(0)" 
           onclick="alert('Your cart is empty. Please add items to your cart before proceeding to checkout.');">
            Proceed to Checkout
            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
        </a>
    <?php else: ?>
        <a class="w-full bg-primary text-on-primary py-5 rounded-full font-label-md text-label-md uppercase tracking-wider flex items-center justify-center gap-3 hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 shadow-lg hover:shadow-primary/20 active:scale-95" 
           href="checkout.php">
            Proceed to Checkout
            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
        </a>
    <?php endif; ?>
    
<div class="bg-surface-container-high p-4 rounded-xl flex items-start gap-3">
<span class="material-symbols-outlined text-primary mt-0.5">info</span>
<p class="font-label-md text-[12px] text-on-surface-variant leading-relaxed">
                                Subscription members get 15% off and priority beans. <a class="text-primary underline" href="#">Join the ritual.</a>
</p>
</div>
</div>
<!-- Secure Payment Indicators -->
<div class="mt-8 flex justify-center items-center gap-4 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
<span class="material-symbols-outlined text-[20px]">security</span>
<span class="font-label-md text-[11px] uppercase tracking-widest text-on-surface-variant">Encrypted Transaction</span>
</div>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-surface-container-low full-width mt-20">
<div class="w-full py-section-sm px-gutter max-w-container-max-lg mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
<div class="flex flex-col items-center md:items-start gap-2">
<span class="font-headline-sm text-headline-sm text-primary">Ngopidea</span>
<p class="font-label-md text-label-md text-on-surface-variant text-center md:text-left max-w-xs">
                    © 2024 Ngopidea Artisanal Coffee. Crafted for Clarity.
                </p>
</div>
<nav class="flex flex-wrap justify-center gap-x-8 gap-y-4">
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Privacy Policy</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Terms of Service</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Shipping & Returns</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Wholesale</a>
</nav>
<div class="flex gap-4">
<a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">public</span>
</a>
<a class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container transition-all" href="#">
<span class="material-symbols-outlined text-[20px]">chat</span>
</a>
</div>
</div>
</footer>
<script>

      // AJAX cart updates to prevent scroll jump
      document.querySelectorAll('form[action="cart_action.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(this);
          
          // Capture which button submitted the form for quantity updates
          if (e.submitter && e.submitter.name) {
              formData.append(e.submitter.name, e.submitter.value);
          }
          
          formData.append('ajax', '1');
          
          // Visual feedback on the container
          this.style.opacity = '0.5';
          
          fetch('cart_action.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.text())
          .then(text => {
            let data;
            try {
              const start = text.indexOf('{');
              const end = text.lastIndexOf('}');
              if (start !== -1 && end !== -1) {
                  data = JSON.parse(text.substring(start, end + 1));
              } else {
                  data = JSON.parse(text);
              }
            } catch(e) {
              console.error('Failed to parse JSON', text);
              throw e;
            }
            if(data.status === 'success') {
              // Reload page to reflect new totals and items, 
              // location.reload() preserves the scroll position!
              window.location.reload();
            }
          })
          .catch(err => {
            console.error('Error updating cart:', err);
            this.style.opacity = '1';
          });
        });
      });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 20) {
                header.classList.add('py-2');
                header.classList.remove('h-20');
                header.style.height = '70px';
            } else {
                header.classList.remove('py-2');
                header.classList.add('h-20');
                header.style.height = '80px';
            }
        });
    </script>
</body></html>