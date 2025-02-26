<?php
session_start();
require_once '../includes/db.php';

$error = '';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi";
    } else {
        $stmt = $conn->prepare("SELECT id, password, nama_lengkap, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] == 'admin') {
                    header('Location: ../admin/dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error = "Password salah";
            }
        } else {
            $error = "Username tidak ditemukan";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung Makan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 m-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Login</h2>
                <p class="text-gray-600 mt-2">Belum punya akun? <a href="register.php" class="text-orange-600 hover:text-orange-700">Daftar</a></p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
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

                <button type="submit" 
                        class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Login
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
