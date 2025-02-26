<?php
// Direktori untuk upload
$upload_dir = __DIR__ . '/uploads';
$bukti_pembayaran_dir = $upload_dir . '/bukti_pembayaran';

// Buat direktori uploads jika belum ada
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "Direktori uploads berhasil dibuat\n";
}

// Buat direktori bukti_pembayaran jika belum ada
if (!file_exists($bukti_pembayaran_dir)) {
    mkdir($bukti_pembayaran_dir, 0777, true);
    echo "Direktori bukti_pembayaran berhasil dibuat\n";
}

// Set permission yang benar
chmod($upload_dir, 0777);
chmod($bukti_pembayaran_dir, 0777);

echo "Setup direktori selesai!\n";
?>
