<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Promo — Inspirasip Kopi</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;900&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="promo.css">
</head>
<body>
   <header id="navbar-header">
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <a href="#"><img src="assets/logo-white.png" data-before="assets/logo-white.png" data-after="assets/logo.png" alt="ngopidea logo" /></a>
                </div>

                <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Buka menu">
                    <span class="hamburger" aria-hidden="true"></span>
                </button>

                <div id="nav-menu" class="nav-menu">
                    <ul class="nav-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="#">Promo</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

  <!-- HERO -->
  <section class="promo-hero" aria-labelledby="promo-title">
    <div class="promo-hero-inner">
      <h1 id="promo-title">Promo Spesial</h1>
      <p class="promo-hero-sub">Nikmati diskon menarik untuk beberapa menu pilihan kami hari ini</p>
    </div>
  </section>

  <!-- Promo Menu -->
  <main>
    <section class="promo-menu">
      <div class="promo-wrap">
        <h2 class="section-title">Promo Menu</h2>
        <p class="section-sub">Nikmati diskon spesial untuk beberapa menu pilihan.</p>

        <div class="promo-grid">
          <article class="promo-card">
            <div class="card-media">
              <img src="https://images.unsplash.com/photo-1515037893149-de7f840978e2?q=80&w=694&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Brownies">
            </div>
            <div class="card-body">
              <h3 class="card-title">Brownies</h3>
              <p class="card-desc"> Kelezatan cokelat premium yang dileburkan dalam adonan fudgy.</p>
              <span class="card-arrow" aria-hidden="true">→</span>
            </div>
          </article>

          <article class="promo-card">
            <div class="card-media">
              <img src="https://images.unsplash.com/photo-1675998643404-082cd06a792d?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Croissant">
            </div>
            <div class="card-body">
              <h3 class="card-title">Croissant</h3>
              <p class="card-desc">Roti lapis mentega (butter) yang dipanggang hingga mencapai kerenyahan sempurna.</p>
              <span class="card-arrow" aria-hidden="true">→</span>
            </div>
          </article>

          <article class="promo-card">
            <div class="card-media">
              <img src="https://images.unsplash.com/photo-1669872484166-e11b9638b50e?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Americano">
            </div>
            <div class="card-body">
              <h3 class="card-title">Americano</h3>
              <p class="card-desc">Minuman kopi hitam yang kuat dan pekat.</p>
              <span class="card-arrow" aria-hidden="true">→</span>
            </div>
          </article>

          <article class="promo-card">
            <div class="card-media">
              <img src="https://images.unsplash.com/photo-1710173472469-9d28e977914c?q=80&w=916&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Cappuccino">
            </div>
            <div class="card-body">
              <h3 class="card-title">Cappuccino</h3>
              <p class="card-desc">Minuman kopi dengan busa susu yang lembut dan aroma khas.</p>
              <span class="card-arrow" aria-hidden="true">→</span>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!--Form -->
    <section class="promo-cta" aria-labelledby="cta-title">
      <div class="promo-wrap cta-wrap">
        <h2 id="cta-title">Promo Menarik</h2>
        <p class="cta-sub">Dapatkan update promo terbaru dari kami</p>

        <form class="promo-form" action="#" method="post" novalidate>
          <label class="visually-hidden" for="name">Nama Lengkap</label>
          <input id="name" name="name" type="text" placeholder="Masukkan nama Anda" required>
          <button type="submit" class="btn-submit">Kirim</button>
        </form>
      </div>
    </section>

    <footer class="promo-footer">
    <div class="promo-wrap">
      <p>&copy; 2025 Inspirasi — Semua hak dilindungi.</p>
    </div>
  </footer>
  </main>
  
  <script src="script.js"></script>
</body>
</html>