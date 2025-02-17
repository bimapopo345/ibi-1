<?php
session_start();
include '../includes/db.php';

// Inisialisasi total_harga
$total_harga = 0;

// Hitung total harga dari keranjang
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $jumlah) {
        $stmt = $conn->prepare("SELECT harga FROM produk WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_harga += $row['harga'] * $jumlah;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nomor_whatsapp = $_POST['nomor_whatsapp'];

    // Simpan pemesanan ke database
    $query = "INSERT INTO pemesanan (nama_pemesan, nomor_whatsapp, total_harga) VALUES ('$nama', '$nomor_whatsapp', '$total_harga')";
    mysqli_query($conn, $query);
    $pemesanan_id = mysqli_insert_id($conn);

    // Simpan detail pemesanan
    foreach ($_SESSION['keranjang'] as $produk_id => $jumlah) {
        $query = "INSERT INTO detail_pemesanan (pemesanan_id, produk_id, jumlah) VALUES ('$pemesanan_id', '$produk_id', '$jumlah')";
        mysqli_query($conn, $query);
    }

    // Hapus keranjang setelah pemesanan
    unset($_SESSION['keranjang']);
    header('Location: konfirmasi.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Pembayaran</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Pembayaran</h1>
    <form method="POST" class="mt-4">
        <div class="form-group">
            <label for="nama">Nama Pemesan</label>
            <input type="text" class="form-control" name="nama" placeholder="Nama Pemesan" required>
        </div>
        <div class="form-group">
            <label for="nomor_whatsapp">Nomor WhatsApp</label>
            <input type="text" class="form-control" name="nomor_whatsapp" placeholder="Nomor WhatsApp" required>
        </div>
        <input type="hidden" name="total_harga" value="<?php echo htmlspecialchars($total_harga); ?>">
        
        <h2>Total Harga: <?php echo htmlspecialchars($total_harga); ?></h2>
        
        <h3>Detail Produk:</h3>
        <ul class="list-group mb-4">
            <?php
            if (isset($_SESSION['keranjang'])) {
                foreach ($_SESSION['keranjang'] as $id => $jumlah) {
                    $stmt = $conn->prepare("SELECT nama, harga FROM produk WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo "<li class='list-group-item'>" . htmlspecialchars($row['nama']) . " - Jumlah: " . htmlspecialchars($jumlah) . " - Harga: " . htmlspecialchars($row['harga']) . "</li>";
                }
            }
            ?>
        </ul>
        
        <button type="submit" class="btn btn-primary btn-block">Konfirmasi Pembayaran</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>