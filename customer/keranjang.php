<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

// Hapus item dari keranjang
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $produk_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    unset($_SESSION['keranjang'][$produk_id]);
    header('Location: keranjang.php'); // Redirect ke halaman keranjang
    exit;
}

// Menambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produk_id'])) {
    $produk_id = filter_input(INPUT_POST, 'produk_id', FILTER_SANITIZE_NUMBER_INT);
    $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_SANITIZE_NUMBER_INT);

    // Jika keranjang belum ada, inisialisasi
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Tambahkan produk ke keranjang
    if (isset($_SESSION['keranjang'][$produk_id])) {
        $_SESSION['keranjang'][$produk_id] += $jumlah; // Update jumlah jika produk sudah ada
    } else {
        $_SESSION['keranjang'][$produk_id] = $jumlah; // Tambah produk baru
    }

    header('Location: keranjang.php'); // Redirect ke halaman keranjang
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
    <h1>Keranjang Belanja</h1>
</header>

<main>
    <h2>Isi Keranjang</h2>
    <form method="POST" action="keranjang.php">
        <table>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
            <?php
            if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
                foreach ($_SESSION['keranjang'] as $id => $jumlah) {
                    // Ambil data produk dari database
                    $stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    // Tampilkan produk
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                    echo '<td><input type="number" name="jumlah[' . $id . ']" value="' . htmlspecialchars($jumlah) . '" min="1"></td>';
                    echo '<td>Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>';
                    echo '<td>Rp ' . number_format($row['harga'] * $jumlah, 0, ',', '.') . '</td>';
                    echo '<td><a href="keranjang.php?action=hapus&id=' . $id . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus item ini?\');">Hapus</a></td>';
                    echo '</tr>';
                }
            } else {
                echo "<tr><td colspan='5'>Keranjang Anda kosong.</td></tr>";
            }
            ?>
        </table>
    </form>

    <form action="pembayaran.php" method="POST">
        <input type="text" name="nama_pemesan" placeholder="Nama" required>
        <input type="text" name="nomor_whatsapp" placeholder="Nomor Whatsapp" required>
        <input type="submit" value="Checkout">
    </form>
    <a href="index.php" class="btn">Kembali ke Beranda</a>
</main>

</body>
</html>