<?php
session_start();
include '../includes/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Ambil data pemesanan yang perlu ditampilkan di dashboard
$pemesanan_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan");
$total_pemesanan = mysqli_fetch_assoc($pemesanan_count)['total'];

$produk_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$total_produk = mysqli_fetch_assoc($produk_count)['total'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 10px;
            padding: 20px;
            flex: 1 1 calc(50% - 40px); /* Responsive width */
            text-align: center;
            min-width: 200px; /* Minimum width for cards */
        }
        @media (max-width: 600px) {
            nav ul li {
                display: block;
                margin: 5px 0;
            }
            .dashboard {
                flex-direction: column;
                align-items: center;
            }
            .card {
                flex: 1 1 100%; /* Full width on small screens */
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <nav>
            <ul>
                <li><a href="manage_produk.php">Manage Produk</a></li>
                <li><a href="notifikasi.php">Notifikasi Pemesanan</a></li>
                <li><a href="konfirmasi_pembayaran.php">Konfirmasi Pembayaran</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Selamat datang, Admin!</h2>
        <div class="dashboard">
            <div class="card">
                <h3>Total Pemesanan</h3>
                <p><?php echo $total_pemesanan; ?></p>
            </div>
            <div class="card">
                <h3>Total Produk</h3>
                <p><?php echo $total_produk; ?></p>
            </div>
        </div>
    </main>
</body>
</html>
