<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ideacafe - santai dan Temukan Idemu Disini</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;900&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="contact.css">
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
        <section class="hero">
            <div class="hero-inner">
                <p class="eyebrow">Hubungi Kami</p>
                <h1 class="title">Reservasi & kolaborasi</h1>
                <p>Kami siap bantu untuk event komunitas, reservasi tim, atau kolaborasi brand.</p>
            </div>
        </section>

        <section class="contact-wrapper">
            <div class="container">
                <div class="contact-grid">
                    <div class="contact-info">
                        <h2>Store Utama</h2>
                        <p>Jl. Wijaya No.12, Jakarta Selatan</p>
                        <ul>
                            <li>Jam operasional: 08.00 - 23.00</li>
                            <li>Email: hello@ideacafe.id</li>
                            <li>Telp: 021-555-1200</li>
                        </ul>
                        <h3>Reservasi Grup</h3>
                        <p>Kirim detail jumlah orang dan kebutuhan alat presentasi.</p>
                    </div>
                    <form class="contact-form">
                        <label>Nama Lengkap
                            <input type="text" placeholder="Tuliskan namamu" required>
                        </label>
                        <label>Email
                            <input type="email" placeholder="emailmu@contoh.com" required>
                        </label>
                        <label>Tujuan
                            <select>
                                <option>Reservasi meja</option>
                                <option>Kolaborasi acara</option>
                                <option>Media & press</option>
                            </select>
                        </label>
                        <label>Pesan
                            <textarea rows="4" placeholder="Ceritakan kebutuhanmu"></textarea>
                        </label>
                        <button class="btn btn-primary" type="submit">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="map-section">
            <div class="container">
                <h2 class="section-title">Lokasi Kami</h2>
                <div class="map-placeholder">
                     <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d833.6650968032544!2d106.79463701671884!3d-6.31591569474929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ee3e065d4f6b%3A0xe176f81a31564166!2sUniversitas%20Pembangunan%20Nasional%20%22Veteran%22%20Jakarta!5e0!3m2!1sid!2sid!4v1764354975317!5m2!1sid!2sid" width="1200" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
                </div>
            </div>
        </section>

        <footer>
            <div class="footer-container">
              <p>&copy; 2025. All rights reserved.</p>
            </div>
        </footer>
    </main>

    <script src="script.js"></script>
</body>
</html>