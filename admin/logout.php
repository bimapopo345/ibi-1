<?php
session_start();

// Hapus semua data session
session_destroy();

// Redirect ke halaman login dengan pesan berhasil logout
header('Location: index.php?logout=success');
exit();
?>
