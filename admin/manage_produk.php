<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../images/$gambar");

        $query = "INSERT INTO produk (nama, harga, deskripsi, gambar) VALUES ('$nama', '$harga', '$deskripsi', '$gambar')";
        mysqli_query($conn, $query);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM produk WHERE id = $id";
        mysqli_query($conn, $query);
    }
}

$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }
        input, textarea, button {
            width: 90%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        @media (max-width: 600px) {
            input, textarea, button {
                width: 100%; /* Full width on small screens */
            }
            table {
                font-size: 14px; /* Smaller font size on small screens */
            }
        }
    </style>
</head>
<body>

<h1>Manage Produk</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" placeholder="Nama Produk" required>
    <input type="number" name="harga" placeholder="Harga" required>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea>
    <input type="file" name="gambar" required>
    <button type="submit" name="add">Tambah Produk</button>
</form>

<table>
    <tr>
        <th>Nama</th>
        <th>Harga</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($produk)) { ?>
    <tr>
        <td><?php echo $row['nama']; ?></td>
        <td><?php echo $row['harga']; ?></td>
        <td><?php echo $row['deskripsi']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="delete">Hapus</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<!-- Tambahkan ini di bawah tabel -->
<div class="text-center">
    <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
</div>
</body>
</html>