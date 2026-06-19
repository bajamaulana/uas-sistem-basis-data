<?php
$conn = mysqli_connect("172.22.148.251", "root", "bajamaulana73*", "ngopidea");
$result = mysqli_query($conn, "SELECT * FROM menu");
var_dump($result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>


<body>
    <main>

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
    </section>



    </main>
</body>
</html>