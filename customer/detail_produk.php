<?php
session_start();
include '../includes/db.php';

// Mengambil ID produk dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Mengambil data produk dari database
$produk = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$row = mysqli_fetch_assoc($produk);

// Jika produk tidak ditemukan, redirect ke index.php
if (!$row) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['nama_produk']); ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #35424a;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        header h1 {
            color: white;
            text-align: center;
        }

        nav {
            background-color: #333;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        main {
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .cta-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #35424a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        @media (max-width: 600px) {
            nav ul li {
                display: block;
                margin: 10px 0;
            }

            main {
                padding: 10px;
            }
        }
    </style>
<body>

<header>
    <h1>Detail Produk</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Beranda</a></li>
        <li><a href="keranjang.php">Keranjang</a></li>
    </ul>
</nav>

<main>
    <h2><?php echo htmlspecialchars($row['nama_produk']); ?></h2>
    <img src="../images/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
    <p>Harga: Rp <?php echo htmlspecialchars($row['harga']); ?></p>
    <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>

    <form method="POST" action="keranjang.php">
        <input type="hidden" name="produk_id" value="<?php echo $row['id']; ?>">
        <input type="number" name="jumlah" min="1" value="1" required>
        <input type="submit" value="Masukkan ke Keranjang">
    </form>

    <a href="index.php" class="cta-button">Kembali ke Beranda</a>
</main>

</body>
</html>
