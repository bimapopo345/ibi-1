<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Makan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
    <script src="<?php echo $base_path; ?>js/script.js" defer></script>
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-4 py-3">
            <?php
            // Determine if we're in a subdirectory
            $is_customer = strpos($_SERVER['PHP_SELF'], '/customer/') !== false;
            $base_path = $is_customer ? '../' : '';
            ?>
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?php echo $base_path; ?>index.php" class="text-2xl font-bold text-orange-600">
                        Warung Makan
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?php echo $base_path; ?>index.php" class="text-gray-700 hover:text-orange-600 transition-colors">Beranda</a>
                    <a href="<?php echo $base_path; ?>customer/index.php" class="text-gray-700 hover:text-orange-600 transition-colors">Menu</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo $base_path; ?>customer/keranjang.php" class="flex items-center text-gray-700 hover:text-orange-600 transition-colors">
                            <img src="<?php echo $base_path; ?>images/cart_icon.png" alt="Keranjang" class="w-6 h-6 mr-1">
                            Keranjang
                        </a>
                        <a href="<?php echo $base_path; ?>customer/riwayat.php" class="text-gray-700 hover:text-orange-600 transition-colors">Riwayat</a>
                        <div class="relative group">
                            <button class="flex items-center text-gray-700 hover:text-orange-600 transition-colors">
                                <i class="fas fa-user-circle mr-2"></i>
                                <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block">
                                <a href="<?php echo $base_path; ?>customer/profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <a href="<?php echo $base_path; ?>customer/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo $base_path; ?>customer/login.php" class="text-orange-600 hover:text-orange-700 font-medium">
                                Login
                            </a>
                            <a href="<?php echo $base_path; ?>customer/register.php" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                Daftar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-orange-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-2">
                <a href="<?php echo $base_path; ?>index.php" class="block py-2 text-gray-700 hover:text-orange-600">Beranda</a>
                <a href="<?php echo $base_path; ?>customer/index.php" class="block py-2 text-gray-700 hover:text-orange-600">Menu</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $base_path; ?>customer/keranjang.php" class="block py-2 text-gray-700 hover:text-orange-600">Keranjang</a>
                    <a href="<?php echo $base_path; ?>customer/riwayat.php" class="block py-2 text-gray-700 hover:text-orange-600">Riwayat</a>
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <div class="px-4 py-2 text-sm text-gray-500">
                            <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>
                        </div>
                        <a href="<?php echo $base_path; ?>customer/profil.php" class="block py-2 text-gray-700 hover:text-orange-600">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <a href="<?php echo $base_path; ?>customer/logout.php" class="block py-2 text-red-600 hover:text-red-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                <?php else: ?>
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <a href="<?php echo $base_path; ?>customer/login.php" class="block py-2 text-orange-600 hover:text-orange-700">Login</a>
                        <a href="<?php echo $base_path; ?>customer/register.php" class="block py-2 text-orange-600 hover:text-orange-700">Daftar</a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
