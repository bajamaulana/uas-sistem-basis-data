<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$base_url = isset($base_url) ? $base_url : '';

// Define active and inactive classes
$activeClass = 'text-primary border-b-2 border-primary pb-1';
$inactiveClass = 'text-on-surface-variant hover:text-primary transition-colors nav-link-hover';

// Calculate cart count
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<header class="fixed top-0 w-full z-50 transition-all duration-500 bg-surface/95 backdrop-blur-md shadow-sm">
  <nav class="flex justify-between items-center px-gutter py-4 max-w-container-max-lg mx-auto">
    <a class="font-headline-sm text-headline-sm text-primary tracking-tight" href="<?= $base_url ?>index.php">Ngopidea</a>

    <!-- Desktop Links -->
    <div class="hidden md:flex items-center gap-8">
      <a class="font-label-md text-label-md relative <?php echo ($currentPage == 'index.php') ? $activeClass : $inactiveClass; ?>" href="<?= $base_url ?>index.php">Our Story</a>
      <a class="font-label-md text-label-md relative <?php echo ($currentPage == 'menu.php') ? $activeClass : $inactiveClass; ?>" href="<?= $base_url ?>menu.php">Menu</a>
      <a class="font-label-md text-label-md relative <?php echo ($currentPage == 'location.php') ? $activeClass : $inactiveClass; ?>" href="<?= $base_url ?>location.php">Locations</a>
      <a class="font-label-md text-label-md relative <?php echo ($currentPage == 'journal.php') ? $activeClass : $inactiveClass; ?>" href="<?= $base_url ?>journal.php">Journal</a>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-4 md:gap-6">
      <button class="md:hidden text-on-surface">
        <span class="material-symbols-outlined">menu</span>
      </button>

      <?php if (!isLoggedIn()): ?>
        <a href="<?= $base_url ?>signin.php" class="hidden md:inline-block bg-primary text-on-primary font-label-md px-6 py-2.5 rounded-full hover:scale-105 transition-transform duration-300 shadow-md text-center">
          Sign In
        </a>
      <?php endif; ?>

      <a class="text-on-surface relative p-2 hover:scale-95 transition-transform duration-200" href="<?= $base_url ?><?php echo isLoggedIn() ? 'shopcart.php' : 'signin.php'; ?>">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span id="cart-badge" class="absolute top-0 right-0 bg-primary-container text-on-primary-container text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center <?= $cart_count > 0 ? '' : 'hidden' ?>"><?= $cart_count ?></span>
      </a>

      <?php if (isLoggedIn()): ?>
        <div class="relative group">
          <button class="flex items-center hover:scale-105 transition-transform duration-200 focus:outline-none">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['full_name'] ?? 'User') ?>&background=ffcf99&color=7a562a&bold=true" alt="Profile" class="w-10 h-10 rounded-full border-2 border-primary object-cover shadow-sm" />
          </button>
          
          <!-- Dropdown Menu -->
          <div class="absolute right-0 mt-2 w-56 bg-surface border border-outline-variant rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right z-50">
            <div class="p-2 space-y-1">
              <div class="px-4 py-3 border-b border-outline-variant/50 mb-2">
                <p class="font-label-md text-on-surface truncate"><?= htmlspecialchars($_SESSION['user']['full_name'] ?? 'User') ?></p>
                <p class="text-[12px] text-on-surface-variant truncate"><?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?></p>
              </div>
              <a href="<?= $base_url ?>pelanggan/profile.php" class="block px-4 py-2 text-sm text-on-surface hover:bg-surface-container-high rounded-lg transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">person</span> My Profile
              </a>
              <a href="<?= $base_url ?>pelanggan/history.php" class="block px-4 py-2 text-sm text-on-surface hover:bg-surface-container-high rounded-lg transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span> Orders
              </a>
              <div class="border-t border-outline-variant/50 my-1"></div>
              <a href="<?= $base_url ?>logout.php" class="block px-4 py-2 text-sm text-error hover:bg-error-container hover:text-on-error-container rounded-lg transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">logout</span> Logout
              </a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </nav>
</header>
