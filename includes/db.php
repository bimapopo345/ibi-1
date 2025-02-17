<?php
// File konfigurasi database
$db_config = array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'aplikasi_pemesanan'
);

// Koneksi database
$conn = new mysqli($db_config['host'], $db_config['user'], $db_config['password'], $db_config['database']);

// Penanganan kesalahan
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>