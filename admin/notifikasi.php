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
    header('Location: notifikasi.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pesanan - Admin</title>
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
                    <a href="notifikasi.php" class="px-3 py-2 rounded-md text-sm font-medium text-orange-600">
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

    <?php include 'mobile_menu.php'; ?>
</nav>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                    <i class="fas fa-bell text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">Notifikasi Pesanan</h2>
                    <p class="text-gray-600">Kelola pesanan yang sudah dibayar</p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Pesanan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pemesan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($pemesanan)) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($row['nama_lengkap']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <?php echo htmlspecialchars($row['no_telp']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Dibayar
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" 
                                                class="text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded-md transition-colors">
                                            <i class="fas fa-check mr-1"></i>
                                            Selesai
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if (mysqli_num_rows($pemesanan) == 0) { ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Tidak ada pesanan yang perlu diproses</p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Link Kembali -->
        <div class="mt-6 text-center">
            <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
