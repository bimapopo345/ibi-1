<?php
session_start();
include '../includes/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Ambil total pemesanan
$pemesanan_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan");
$total_pemesanan = mysqli_fetch_assoc($pemesanan_count)['total'];

// Ambil total produk
$produk_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$total_produk = mysqli_fetch_assoc($produk_count)['total'];

// Ambil pesanan hari ini
$today = date('Y-m-d');
$pesanan_today = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE DATE(created_at) = '$today'");
$pesanan_hari_ini = mysqli_fetch_assoc($pesanan_today)['total'];

// Ambil pendapatan hari ini
$pendapatan_today = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE DATE(created_at) = '$today' AND status != 'dibatalkan'");
$pendapatan_hari_ini = mysqli_fetch_assoc($pendapatan_today)['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Warung Makan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-utensils text-2xl text-orange-600"></i>
                        <span class="ml-2 text-xl font-bold">Admin Panel</span>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center">
                    <a href="manage_produk.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600">
                        <i class="fas fa-box-open mr-1"></i> Produk
                    </a>
                    <a href="notifikasi.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600">
                        <i class="fas fa-bell mr-1"></i> Notifikasi
                    </a>
                    <a href="konfirmasi_pembayaran.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600">
                        <i class="fas fa-credit-card mr-1"></i> Pembayaran
                    </a>
                    <a href="logout.php" class="ml-4 px-4 py-2 rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-orange-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="manage_produk.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-box-open mr-1"></i> Produk
                </a>
                <a href="notifikasi.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-bell mr-1"></i> Notifikasi
                </a>
                <a href="konfirmasi_pembayaran.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-credit-card mr-1"></i> Pembayaran
                </a>
                <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-orange-600 hover:bg-orange-700">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Banner -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6 bg-white">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-tachometer-alt text-orange-600 mr-2"></i>
                    Selamat datang, Admin!
                </h1>
                <p class="mt-1 text-gray-600">Overview statistik dan aktivitas terkini</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Pesanan -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                            <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $total_pemesanan; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-box-open text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Produk</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $total_produk; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-clock text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pesanan Hari Ini</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo isset($pesanan_hari_ini) ? $pesanan_hari_ini : 0; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-wallet text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pendapatan Hari Ini</p>
                            <p class="text-2xl font-semibold text-gray-900">Rp <?php echo isset($pendapatan_hari_ini) ? number_format($pendapatan_hari_ini, 0, ',', '.') : 0; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="manage_produk.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-plus-circle text-orange-600 text-2xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900">Tambah Produk</h3>
                <p class="mt-1 text-gray-600">Tambahkan menu baru ke daftar produk</p>
            </a>
            
            <a href="notifikasi.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-bell text-orange-600 text-2xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900">Lihat Pesanan</h3>
                <p class="mt-1 text-gray-600">Cek pesanan yang perlu diproses</p>
            </a>
            
            <a href="konfirmasi_pembayaran.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-check-circle text-orange-600 text-2xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pembayaran</h3>
                <p class="mt-1 text-gray-600">Verifikasi pembayaran customer</p>
            </a>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
