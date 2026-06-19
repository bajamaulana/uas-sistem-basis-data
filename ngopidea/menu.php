<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Menu — Inspirasi Kopi</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;900&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="menu.css">

  <?php
    $conn = mysqli_connect("172.22.148.251", "root", "bajamaulana73*", "ngopidea");
    $result = mysqli_query($conn, "SELECT ROW_NUMBER() OVER (ORDER BY harga ASC, id_menu ASC) AS no_urut, id_menu, nama_menu, dsc, harga FROM menus");
    ?>

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
                        <li><a href="promo.php">Promo</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

  <main>
    <section class="menu-section">
      <div class="wrap menu-wrap">
        <h1 class="menu-title">Menu Lezat</h1>
        <p class="menu-sub">Nikmati kopi yang menyatu dengan inspirasi setiap tegukan.</p>

        <div class="menu-grid">
          <div class="menu-column">
            <ul class="menu-list">
              
            <?php while ($row[0] < $row[0] / 2) { ?>
            <?php while($row = mysqli_fetch_row($result)) : ?>
              <li class="menu-item">
                <div>
                  <span class="item-name"><?php echo $row[2]; ?></span>
                  <p class="item-desc"><?php echo $row[3]; ?></p>
                </div>
                <span class="item-price"><?php echo $row[4]; ?>k</span>
              </li>
              <?php endwhile; } ?>
            </ul>
          </div>
        

          <div class="menu-column">
            <ul class="menu-list">
                <?php if ($row[0] >= $row[0] / 2) { ?>
                  <?php while($row = mysqli_fetch_row($result)) : ?>
                    <li class="menu-item">
                      <div>
                        <span class="item-name"><?php echo $row[2]; ?></span>
                        <p class="item-desc"><?php echo $row[3]; ?></p>
                      </div>
                      <span class="item-price"><?php echo $row[4]; ?>k</span>
                    </li>
                  <?php endwhile; ?>
                <?php } ?>
            </ul>
          </div>
        </div>

        <figure class="menu-hero">
          <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3&s=2a2f0f0b3c5f4a69e6c5a6a8b1e4c3a2" alt="Foto kopi artistik">
        </figure>
      </div>
    </section>

    <section class="cta-section">
      <div class="wrap cta-wrap">
        <h2>Pesan Meja</h2>
        <p class="cta-sub">Amankan tempatmu untuk menikmati kopi penuh inspirasi bersama kami.</p>
        <a class="cta-btn" href="contact.html">Pesan Sekarang</a>
      </div>
    </section>

    <section class="gallery-section">
      <div class="wrap gallery-wrap">
        <h2 class="gallery-title">Galeri</h2>
        <p class="gallery-sub">Momen hangat dan cita rasa kopi di inspirasi</p>

        <div class="gallery-grid" aria-hidden="false">
          <div class="g-item g-left-top">
            <img src="https://images.unsplash.com/photo-1727452749600-1cea5bb2b389?q=80&w=685&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
          </div>
          <div class="g-item g-center">
            <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3&s=2a2f0f0b3c5f4a69e6c5a6a8b1e4c3a2" alt="">
          </div>
          <div class="g-item g-right-top">
            <img src="https://images.unsplash.com/photo-1614227373539-d763a95a31a3?q=80&w=688&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
          </div>

          <div class="g-item g-left-bottom">
            <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=800&auto=format&fit=crop&ixlib=rb-4.0.3&s=4a7b6c5d3e2f1a0b9c8d7e6f5a4b3c2d" alt="">
          </div>
          <div class="g-item g-right-bottom">
            <img src="https://images.unsplash.com/photo-1641462746968-0db6fe4424db?q=80&w=1331&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
          </div>
        </div>
      </div>
    </section>

    <footer class="site-footer">
    <div class="wrap">
      <p>&copy; 2025 Inspirasi — Semua hak dilindungi.</p>
    </div>
  </footer>
  </main>

  <script src="script.js"></script>
</body>
</html>