<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/mail_helper.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama_lengkap = filter_input(INPUT_POST, 'nama_lengkap', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $no_telp = filter_input(INPUT_POST, 'no_telp', FILTER_SANITIZE_STRING);

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password) || empty($nama_lengkap) || empty($email)) {
        $error = "Semua field harus diisi";
    } elseif ($password !== $confirm_password) {
        $error = "Password tidak cocok";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid";
    } else {
        // Cek username dan email sudah ada atau belum
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username atau email sudah digunakan";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user baru
            $stmt = $conn->prepare("INSERT INTO users (username, password, nama_lengkap, email, no_telp, role) VALUES (?, ?, ?, ?, ?, 'customer')");
            $stmt->bind_param("sssss", $username, $hashed_password, $nama_lengkap, $email, $no_telp);
            
            if ($stmt->execute()) {
                // Kirim email selamat datang
                $emailTemplate = getEmailTemplateRegistrasi($nama_lengkap, $username);
                if (kirimEmail($email, "Selamat Datang di Warung Makan", $emailTemplate)) {
                    $success = "Pendaftaran berhasil! Silakan login.";
                    
                    // Redirect ke halaman login setelah 3 detik
                    header("refresh:3;url=login.php");
                } else {
                    $success = "Pendaftaran berhasil, tetapi email konfirmasi gagal terkirim.";
                }
            } else {
                $error = "Gagal mendaftar: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Warung Makan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .error { color: #dc2626; }
        .success { color: #059669; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 m-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Daftar Akun</h2>
                <p class="text-gray-600 mt-2">Sudah punya akun? <a href="login.php" class="text-orange-600 hover:text-orange-700">Login</a></p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" 
                           id="nama_lengkap" 
                           name="nama_lengkap" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" 
                           id="no_telp" 
                           name="no_telp" 
                           pattern="[0-9]{10,15}"
                           title="Masukkan nomor telepon yang valid (10-15 digit)"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>

                <button type="submit" 
                        class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="index.php" class="text-orange-600 hover:text-orange-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
