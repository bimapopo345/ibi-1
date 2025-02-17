<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Makan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
    <h1>Gan's</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Beranda</a></li>
        <li><a href="keranjang.php">Keranjang</a></li>
    </ul>
</nav>
<main>
    <h2>Pilih Produk</h2>
    <div class="produk">
        <?php
        // Mengambil data produk dari database
        $query = "SELECT * FROM produk";
        $result = mysqli_query($conn, $query );

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="produk-item">';
            echo '<a href="detail_produk.php?id=' . $row['id'] . '">';
            echo '<img src="../images/' . $row['gambar'] . '" alt="' . $row['nama_produk'] . '">';
            echo '<h3>' . $row['nama_produk'] . '</h3>';
            echo '<p>Harga: Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </div>
</main>
</body>
</html>
