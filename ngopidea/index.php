<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ideacafe - santai dan Temukan Idemu Disini</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;900&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

    // koneksi ke database
    <?php
    $conn = mysqli_connect("172.22.148.251", "root", "bajamaulana73*", "ngopidea");
    $result = mysqli_query($conn, "SELECT * FROM menus where spesial_type = 'signature'");
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
                        <li><a href="#">Home</a></li>
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
            <h2 class="title">Secangkir kopi dan sebuah ide</h2>
            <p>Ambil secangkir kopi, duduk santai, dan biarkan ide-ide mengalir.</p>
        </div>
    </section>
    
    <section>
        <div class="container">
            <h2 class="section-title">Mengapa Harus Memilih Kami?</h2>
            <div class="features">
                <div class="feature">
                    <img src="assets/chill.png" alt="Relax Icon" />
                    <h3>Santai dan Nikmati</h3>
                    <p>Temukan suasana santai yang sempurna untuk memicu kreativitas Anda.</p>
                </div>
                <div class="feature">
                    <img src="assets/idea.png" alt="Idea Icon" />
                    <h3>Temukan Idemu</h3>
                    <p>Dapatkan inspirasi dari berbagai ide segar yang kami sajikan.</p>
                </div>
                <div class="feature">
                    <img src="assets/community.png" alt="Community Icon" />
                    <h3>Bergabung dengan Komunitas</h3>
                    <p>Berkolaborasi dengan individu kreatif lainnya dan kembangkan ide bersama.</p>
                </div>
            </div>
            </div>
        </section>

    <section class="signature-menu">
        <div class="container-menu">
            <h2 class="section-title-menu">Menu Signature Kami</h2>
            <div class="menu-signature">
            
            <?php while($row = mysqli_fetch_row($result)) : ?>
                <div class="menu-flex">
                    <div class="menu-card-flex">
                        <div class="menu-image-flex">
                            <img src="assets/<?php echo $row[4]; ?>" alt="<?php echo $row[1]; ?>" />
                        </div>
                        <h3 class="menu-name-flex"><?php echo $row[1]; ?></h3>
                    </div>
            <?php endwhile; ?>

            </div>
        </div>
    </section>

    <section class="our-story">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <h2 class="story-title">Tentang Kami</h2>
                    <p>
                        Inspirasip lahir dari sebuah keyakinan sederhana bahwa ide-ide besar sering kali bermula dari momen kecil yang tenang. Nama kami merupakan perpaduan antara kata "Inspirasi" dan "Sip" (seruputan), yang mencerminkan misi utama kami untuk menjadi bahan bakar bagi setiap proses kreatif Anda. Kami memahami bahwa pikiran cemerlang membutuhkan ruang yang tepat untuk tumbuh, itulah sebabnya kami memadukan cita rasa kopi berkualitas dengan suasana yang dirancang khusus untuk memantik imajinasi dan menjernihkan pikiran.
                    <br><br>
                        Lebih dari sekadar kedai kopi, Inspirasip adalah ruang di mana kenyamanan bertemu dengan produktivitas. Setiap sudut ruangan kami tata dengan sentuhan hangat agar Anda betah berlama-lama, baik untuk mengejar tenggat waktu, berdiskusi dengan rekan kerja, atau sekadar melamun mencari ilham. Kami mengundang Anda untuk duduk sejenak, menikmati racikan terbaik barista kami, dan membiarkan gagasan-gagasan baru mengalir deras bersama setiap tegukan kopi yang Anda nikmati.
                    </p>
                </div>
                <div class="story-image">
                    <img src="assets/tentang-kami.jpg" alt="Fore Coffee Store" class="main-image" />
                </div>
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