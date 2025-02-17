<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
}

$pemesanan = mysqli_query($conn, "SELECT p.*, u.nama_lengkap, u.no_telp 
                                 FROM pesanan p 
                                 JOIN users u ON p.user_id = u.id 
                                 WHERE p.status = 'pending'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $query = "UPDATE pesanan SET status = 'dibayar' WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: konfirmasi_pembayaran.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
        table {
            width: 100%;
        }
        th, td {
            text-align: center;
        }
    </style>
</head>
<body>

<h1 class="text-center">Konfirmasi Pembayaran</h1>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Nama Pemesan</th>
                <th>Nomor WhatsApp</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($pemesanan)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                <td><?php echo htmlspecialchars($row['total_harga']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Tambahkan ini di bawah tabel -->
<div class="text-center">
    <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
