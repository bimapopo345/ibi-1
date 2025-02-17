<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
}

$pemesanan = mysqli_query($conn, "SELECT p.*, u.nama_lengkap, u.no_telp 
                                 FROM pesanan p 
                                 JOIN users u ON p.user_id = u.id 
                                 WHERE p.status = 'dibayar'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $query = "UPDATE pesanan SET status = 'selesai' WHERE id = $id";
    mysqli_query($conn, $query);

    // Kirim notifikasi WhatsApp
    $nomor_whatsapp = '+628123456789'; // Ganti dengan nomor WhatsApp pengguna
    $pesan = 'Pesanan Anda telah siap, mohon ambil di toko';
    $token = 'YOUR_TOKEN_HERE'; // Ganti dengan token akses API WhatsApp Business

    $url = 'https://graph.facebook.com/v13.0/' . $token . '/messages';
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );
    $data = array(
        'messaging_product' => 'whatsapp',
        'to' => $nomor_whatsapp,
        'type' => 'text',
        'text' => array(
            'body' => $pesan
        )
    );
    $options = array(
        'http' => array(
            'method' => 'POST',
            'content' => json_encode($data),
            'header' => $headers
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    header('Location: notifikasi.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pemesanan Siap</title>
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
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        @media (max-width: 600px) {
            table {
                width: 100%;
            }
            th, td {
                font-size: 14px;
            }
            button {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<h1>Notifikasi Pemesanan Siap</h1>
<table>
    <tr>
        <th>Nama Pemesan</th>
        <th>Nomor WhatsApp</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($pemesanan)) { ?>
    <tr>
        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
        <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit">Kirim Notifikasi</button>
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
