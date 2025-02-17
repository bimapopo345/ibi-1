<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Makan</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <header>
        <h1>Warung Makan</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="customer/index.php">Pesan</a></li>
                <li><a href="admin/index.php">Admin</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Selamat Datang!</h2>
        <p>Pilih produk yang ingin Anda pesan.</p>
        <a href="customer/index.php" class="cta-button">Pesan Sekarang</a>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Warung Makan. All rights reserved.</p>
    </footer>
</body>
</html>