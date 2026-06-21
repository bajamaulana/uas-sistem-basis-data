<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Fetch all categories
$categories_result = $conn->query("SELECT * FROM categories ORDER BY id ASC");
$categories = [];
while ($cat = $categories_result->fetch_assoc()) {
    $categories[] = $cat;
}

// Check selected categories from GET
$selected_categories = isset($_GET['categories']) ? $_GET['categories'] : [];
if (!is_array($selected_categories)) {
    $selected_categories = [];
}

// Build WHERE clause for products
$where_clause = "WHERE is_active = 1";
if (!empty($selected_categories)) {
    // Sanitize inputs
    $safe_cats = array_map('intval', $selected_categories);
    $in_cats = implode(',', $safe_cats);
    $where_clause .= " AND category_id IN ($in_cats)";
}

// Fetch all active products
$products_result = $conn->query("SELECT * FROM products $where_clause ORDER BY category_id ASC, id ASC");
$products = [];
while ($prod = $products_result->fetch_assoc()) {
    $products[$prod['category_id']][] = $prod;
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en" style="">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Menu | Ngopidea Artisanal Cafe</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&amp;family=Plus+Jakarta+Sans:wght@400;600;700&amp;family=Merriweather:wght@300;400;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              surface: "#fff8f5",
              "secondary-container": "#ffcf99",
              "on-secondary-container": "#7a562a",
              tertiary: "#4d6073",
              "on-tertiary": "#ffffff",
              "on-secondary-fixed-variant": "#604016",
              "primary-fixed": "#ffdbc9",
              "surface-dim": "#e9d7cb",
              "on-surface": "#231a13",
              "on-secondary": "#ffffff",
              "surface-container-highest": "#f2dfd3",
              "primary-container": "#ff790b",
              "outline-variant": "#e0c0b0",
              "on-secondary-fixed": "#2b1700",
              "on-primary-fixed-variant": "#763300",
              "surface-container-high": "#f7e5d9",
              "on-primary-container": "#5d2700",
              "tertiary-fixed-dim": "#b5c9de",
              "tertiary-fixed": "#d1e5fb",
              outline: "#8c7264",
              "surface-variant": "#f2dfd3",
              "inverse-surface": "#392e26",
              "on-error-container": "#93000a",
              "on-background": "#231a13",
              "secondary-fixed-dim": "#edbe89",
              "on-tertiary-container": "#263849",
              "on-primary": "#ffffff",
              background: "#fff8f5",
              "tertiary-container": "#8ea2b6",
              "inverse-on-surface": "#ffede3",
              "secondary-fixed": "#ffddb9",
              "inverse-primary": "#ffb68d",
              primary: "#9a4600",
              "error-container": "#ffdad6",
              "on-error": "#ffffff",
              "surface-container": "#fdeade",
              "surface-tint": "#9a4600",
              "surface-container-low": "#fff1e9",
              secondary: "#7b572b",
              "on-tertiary-fixed-variant": "#36495a",
              "on-surface-variant": "#584236",
              error: "#ba1a1a",
              "primary-fixed-dim": "#ffb68d",
              "on-primary-fixed": "#321200",
              "surface-bright": "#fff8f5",
              "on-tertiary-fixed": "#081d2d",
              "surface-container-lowest": "#ffffff",
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg: "0.5rem",
              xl: "0.75rem",
              full: "9999px",
            },
            spacing: {
              unit: "8px",
              "section-md": "80px",
              gutter: "20px",
              "section-sm": "40px",
              "container-max-md": "1100px",
              "container-max-lg": "1200px",
              "section-lg": "100px",
            },
            fontFamily: {
              "label-md": ["Plus Jakarta Sans"],
              "headline-sm": ["Playfair Display"],
              "display-hero": ["Playfair Display"],
              "headline-md": ["Playfair Display"],
              "body-lg": ["Merriweather"],
              "body-md": ["Merriweather"],
              "headline-lg-mobile": ["Playfair Display"],
              "display-hero-mobile": ["Playfair Display"],
              "headline-lg": ["Playfair Display"],
            },
            fontSize: {
              "label-md": ["14px", { lineHeight: "1.2", fontWeight: "600" }],
              "headline-sm": ["24px", { lineHeight: "1.4", fontWeight: "700" }],
              "display-hero": ["72px", { lineHeight: "1.2", letterSpacing: "2px", fontWeight: "700" }],
              "headline-md": ["40px", { lineHeight: "1.3", fontWeight: "700" }],
              "body-lg": ["18px", { lineHeight: "1.8", fontWeight: "400" }],
              "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
              "headline-lg-mobile": ["32px", { lineHeight: "1.2", letterSpacing: "0px", fontWeight: "700" }],
              "display-hero-mobile": ["35px", { lineHeight: "1.2", letterSpacing: "1px", fontWeight: "700" }],
              "headline-lg": ["56px", { lineHeight: "1.2", letterSpacing: "-1px", fontWeight: "700" }],
            },
          },
        },
      };
    </script>
    <style>
      .material-symbols-outlined {
        font-variation-settings:
          "FILL" 0,
          "wght" 400,
          "GRAD" 0,
          "opsz" 24;
      }
      .glass-nav {
        background: rgba(255, 248, 245, 0.95);
        backdrop-filter: blur(10px);
      }
      .shadow-amber-glow:hover {
        box-shadow: 0 10px 30px rgba(138, 62, 0, 0.15);
      }
      .drink-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.3s ease;
      }
      .drink-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(138, 62, 0, 0.1);
      }
    </style>
  </head>
  <body class="bg-surface text-on-surface font-body-md selection:bg-secondary-container selection:text-on-secondary-container">
    <!-- Top Navigation Bar -->
    <?php include 'includes/navbar.php'; ?>
    <main class="pt-32 pb-section-lg max-w-container-max-lg mx-auto px-gutter">
      <!-- Hero Section -->
      <section class="mb-section-md">
        <h1 class="font-headline-lg text-headline-lg mb-4">Our Signature Brews</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Hand-crafted by our master baristas for your perfect moment. From precision manual brews to our indulgent signatures.</p>
      </section>
      <!-- Menu Navigation / Filters -->
      <div class="flex flex-col lg:flex-row gap-12">
        <aside class="w-full lg:w-64 flex-shrink-0 space-y-10">
          <div>
            <h3 class="font-label-md text-label-md uppercase tracking-widest text-primary mb-6">Beverage Categories</h3>
            <form action="menu.php" method="GET" id="filterForm">
              <div class="space-y-3">
                <?php foreach ($categories as $index => $cat): ?>
                <?php 
                    $isChecked = in_array($cat['id'], $selected_categories);
                    // Jika baru pertama kali load (tidak ada filter), bisa biarkan kosong, 
                    // atau anggap semuanya ter-filter jika diinginkan. Di sini kita biarkan kosong = tampil semua.
                ?>
                <label class="flex items-center group cursor-pointer">
                  <input name="categories[]" value="<?= $cat['id'] ?>" <?= $isChecked ? 'checked' : '' ?> onchange="document.getElementById('filterForm').submit();" class="w-5 h-5 rounded border-outline text-primary focus:ring-primary/20 transition-all" type="checkbox" />
                  <span class="ml-3 font-body-md text-on-surface-variant group-hover:text-on-surface transition-colors"><?= htmlspecialchars($cat['category_name']) ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </form>
          </div>
        </aside>
        <div class="flex-grow">
          <?php foreach ($categories as $cat): ?>
            <?php if (!empty($products[$cat['id']])): ?>
            <div class="mb-section-sm">
              <h2 class="font-headline-sm text-headline-sm mb-8 border-b border-outline-variant pb-2"><?= htmlspecialchars($cat['category_name']) ?></h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($products[$cat['id']] as $prod): ?>
                <div class="drink-card flex flex-col">
                  <div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden mb-4 bg-surface-container-highest">
                    <img
                      alt="<?= htmlspecialchars($prod['product_name']) ?>"
                      class="w-full h-full object-cover"
                      src="assets/<?= htmlspecialchars($prod['image_url']) ?>"
                    />
                  </div>
                  <div class="flex justify-between items-baseline mb-2">
                    <h3 class="font-headline-sm text-[20px]"><?= htmlspecialchars($prod['product_name']) ?></h3>
                    <span class="text-primary font-bold">$<?= number_format($prod['price'], 2) ?></span>
                  </div>
                  <p class="text-on-surface-variant text-sm mb-6 flex-grow"><?= htmlspecialchars($prod['description']) ?></p>
                  <form action="cart_action.php" method="POST" class="mt-auto w-full">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full py-2.5 border border-primary text-primary rounded font-label-md hover:bg-primary hover:text-white transition-all flex items-center justify-center gap-2">
                      <span class="material-symbols-outlined text-[18px]">shopping_cart</span> Order for Pickup
                    </button>
                  </form>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- Barista Essentials Section -->
      <section class="mt-section-lg">
        <div class="flex justify-between items-end mb-10">
          <div>
            <h2 class="font-headline-md text-headline-md mb-2">Barista Essentials</h2>
            <p class="font-body-md text-on-surface-variant">Take the Ngopidea experience home with our curated cafe gear.</p>
          </div>
          <a class="font-label-md text-label-md text-primary border-b-2 border-primary pb-1 group flex items-center gap-2" href="#">
            View All Merchandise
            <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
          </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Large Feature -->
          <div class="md:col-span-2 group relative overflow-hidden rounded-2xl bg-surface-container h-[500px]">
            <div class="absolute inset-0 z-0">
              <img
                alt="Travel Tumblers"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDGabaVMBCS5U3OdayeaDi6mS1xbi-UGW0FuNn3ZSt4ZGWxtA0EFuC1JkFDtgzF76Lt5hLai7H_byHZXOwTVjCCStVwpwJge_JXRzg4qOGAy9J261s46s7oF-VgIGtNXYaO0fT0BX_ogAKxieRJcEs25Nirx-YYwSjXuSFPj1rkKSSklRjZ2pINimY45-xloOYqF-t-8Ae5xR852xEHCwXWajn47ukW_OelXDBlyKp__HqHmg47rhrXqfXJ8a9HtLmmNFZQKRhlHoo"
              />
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>
            <div class="absolute bottom-10 left-10 z-20 text-white">
              <span class="font-label-md text-label-md uppercase tracking-[0.2em] mb-3 block opacity-80">Eco Series</span>
              <h3 class="font-headline-lg-mobile text-headline-lg-mobile mb-4">Hand-Glazed Travel Tumblers</h3>
              <p class="font-body-md mb-6 max-w-md opacity-90">Sustainable sipping without compromising on style. Each ceramic cup is hand-thrown and double-walled.</p>
              <button class="px-8 py-3 bg-white text-primary rounded-full font-label-md text-label-md uppercase tracking-wider hover:bg-primary hover:text-white transition-all shadow-xl">
                Browse Collection - From $32
              </button>
            </div>
          </div>
          <!-- Smaller Items -->
          <div class="flex flex-col gap-8">
            <div class="group relative flex flex-col bg-surface-container-high p-6 rounded-2xl flex-grow overflow-hidden transition-all hover:bg-white hover:shadow-lg">
              <div class="mb-4 h-40 overflow-hidden rounded-xl">
                <img
                  alt="Vanilla Syrup"
                  class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-all duration-500"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuBI63lCoU874ZywkWDg9uOQKV2as5ETvwo_LNZFX82Eu1G7ySzUhsNPER7ypgEd19QIGRCh3FjzDqFP5opEK4S4mKkAe2KuNpwJEg627DqTJ40p4K5KwnRYEqRtpH1XxJmIWs3zJaL8Sv1GcxwHiVWyoYOVC40MF_MqZx78D8i6hRKEc-vgZCzOyRjHYRmvPGKKFTNha-pbVxw8yC-6cRJRgaBqbjO_bjJFWl2ft-JwlZYMbhTmdpBbi01x0U7ojL_qiPcXe0I8qYQ"
                />
              </div>
              <div class="flex justify-between items-center mb-1">
                <h4 class="font-headline-sm text-[18px]">Artisan Vanilla Syrup</h4>
                <span class="text-primary font-bold">$18</span>
              </div>
              <p class="text-on-surface-variant text-[14px] mb-4">Small-batch syrup made with real Madagascan beans.</p>
              <button class="mt-auto py-3 border border-outline rounded-lg font-label-md text-label-md uppercase tracking-widest hover:bg-primary hover:text-white transition-all">Quick Add</button>
            </div>
            <div class="group relative flex flex-col bg-surface-container-high p-6 rounded-2xl flex-grow overflow-hidden transition-all hover:bg-white hover:shadow-lg">
              <div class="mb-4 h-40 overflow-hidden rounded-xl">
                <img
                  alt="Canvas Tote"
                  class="w-full h-full object-cover mix-blend-multiply group-hover:scale-110 transition-all duration-500"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuBLKiZDK38ZK5fb-zjrmSsC0vGcYsZA-x5EE-Jy9fPTUy7yZPoXiNDOqhZ2hSqJpcme4UGy0TNl8a5694WGKcC1nS8cJ1rpS7QQpnXA375SYM8pKhVFvYAL815X7lYWWkHSKoCqimld966NrzXXQzMOCWqq60bYUo-INVLvdZFamlx0HM7QJ639gIdVc6QSDz-B-6k_adImgqq81RvBKtHY7e1IjtoI1E72phifmoN0fM79mk3JWL1-yE1ukLpwN_m3c0T06Ou8DWg"
                />
              </div>
              <div class="flex justify-between items-center mb-1">
                <h4 class="font-headline-sm text-[18px]">Canvas Cafe Tote</h4>
                <span class="text-primary font-bold">$22</span>
              </div>
              <p class="text-on-surface-variant text-[14px] mb-4">Heavyweight organic canvas for your daily essentials.</p>
              <button class="mt-auto py-3 border border-outline rounded-lg font-label-md text-label-md uppercase tracking-widest hover:bg-primary hover:text-white transition-all">Quick Add</button>
            </div>
          </div>
        </div>
      </section>
    </main>
    <!-- Footer -->
    <footer class="w-full py-section-sm bg-surface-container-low border-t border-outline-variant">
      <div class="max-w-container-max-lg mx-auto px-gutter">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-10">
          <div class="md:col-span-1">
            <span class="font-headline-sm text-headline-sm text-primary italic block mb-4">Ngopidea</span>
            <p class="font-body-md text-on-surface-variant">Where hospitality meets productivity. A sanctuary for the modern creative.</p>
          </div>
          <div class="flex flex-col gap-4">
            <h4 class="font-label-md text-label-md uppercase tracking-widest text-on-surface">EXPLORE</h4>
            <nav class="flex flex-col gap-2">
              <a href="#" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Our Story</a>
              <a href="#" class="text-body-md text-primary font-bold">Workshops</a>
              <a href="#" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Menu</a>
            </nav>
          </div>
          <div class="flex flex-col gap-4">
            <h4 class="font-label-md text-label-md uppercase tracking-widest text-on-surface">SUPPORT</h4>
            <nav class="flex flex-col gap-2">
              <a href="#" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Privacy Policy</a>
              <a href="#" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Terms of Service</a>
              <a href="#" class="text-body-md text-on-surface-variant hover:text-primary transition-colors">Contact Us</a>
            </nav>
          </div>
          <div class="flex flex-col items-end gap-6">
            <div class="flex gap-4">
              <span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary">share</span>
              <span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary">mail</span>
              <span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary">location_on</span>
            </div>
            <p class="text-body-md text-on-surface-variant mt-auto">© 2024 Ngopidea Artisanal Cafe. All rights reserved.</p>
          </div>
        </div>
      </div>
    </footer>
    <script>
      // Micro-interaction: Header shadow on scroll
      window.addEventListener("scroll", () => {
        const header = document.querySelector("header");
        if (window.scrollY > 20) {
          header.classList.add("shadow-md");
        } else {
          header.classList.remove("shadow-md");
        }
      });
      // AJAX Add to Cart
      document.querySelectorAll('form[action="cart_action.php"]').forEach((form) => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(this);
          formData.append('ajax', '1');
          
          const btn = this.querySelector('button[type="submit"]');
          const originalText = btn.innerHTML;
          
          // Show loading state
          btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">sync</span> Adding...';
          btn.classList.add("bg-primary", "text-white");
          
          fetch('cart_action.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data.status === 'success') {
              // Show success state
              btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check_circle</span> Added!';
              
              // Update badge
              const badge = document.getElementById('cart-badge');
              if(badge) {
                badge.innerText = data.cart_count;
                if (data.cart_count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
              }
              
              // Reset button after 2 seconds
              setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove("bg-primary", "text-white");
              }, 2000);
            }
          })
          .catch(error => {
            console.error('Error adding to cart:', error);
            btn.innerHTML = originalText;
            btn.classList.remove("bg-primary", "text-white");
          });
        });
      });
    </script>
  </body>
</html>
