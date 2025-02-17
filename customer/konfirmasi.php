<?php
session_start();
include '../includes/db.php';

$pemesanan = mysqli_query($conn, "SELECT * FROM pemesanan ORDER BY created_at DESC LIMIT 1");
$row = mysqli_fetch_assoc($pemesanan);
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
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #343a40;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h1 class="text-center">Konfirmasi Pembayaran</h1>
        <p><strong>Nama Pemesan:</strong> <?php echo $row['nama_pemesan']; ?></p>
        <p><strong>Nomor WhatsApp:</strong> <?php echo $row['nomor_whatsapp']; ?></p>
        <p><strong>Total Harga:</strong> <?php echo $row['total_harga']; ?></p>
        <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>