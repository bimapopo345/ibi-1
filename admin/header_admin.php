<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Warung Makan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="dashboard.php">
                            <i class="fas fa-utensils text-2xl text-orange-600"></i>
                            <span class="ml-2 text-xl font-bold">Admin Panel</span>
                        </a>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="manage_produk.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600">
                        <i class="fas fa-box-open mr-1"></i> Produk
                    </a>
                    <a href="notifikasi.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600 relative">
                        <i class="fas fa-bell mr-1"></i> 
                        Notifikasi
                        <?php if ($notifikasi_count > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo $notifikasi_count; ?>
                            </span>
                        <?php endif; ?>
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

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="manage_produk.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-box-open mr-1"></i> Produk
                </a>
                <a href="notifikasi.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-bell mr-1"></i> 
                    Notifikasi
                    <?php if ($notifikasi_count > 0): ?>
                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                            <?php echo $notifikasi_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="konfirmasi_pembayaran.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-credit-card mr-1"></i> Pembayaran
                </a>
                <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-700">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
