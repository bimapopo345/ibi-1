<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Makan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-orange-600">
                        Warung Makan
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-orange-600 transition-colors">Beranda</a>
                    <a href="customer/index.php" class="text-gray-700 hover:text-orange-600 transition-colors">Menu</a>
                    <a href="customer/keranjang.php" class="flex items-center text-gray-700 hover:text-orange-600 transition-colors">
                        <img src="images/cart_icon.png" alt="Keranjang" class="w-6 h-6 mr-1">
                        Keranjang
                    </a>
                    <a href="customer/pesanan.php" class="text-gray-700 hover:text-orange-600 transition-colors">Pesanan Saya</a>
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
                <a href="index.php" class="block py-2 text-gray-700 hover:text-orange-600">Beranda</a>
                <a href="customer/index.php" class="block py-2 text-gray-700 hover:text-orange-600">Menu</a>
                <a href="customer/keranjang.php" class="block py-2 text-gray-700 hover:text-orange-600">Keranjang</a>
                <a href="customer/pesanan.php" class="block py-2 text-gray-700 hover:text-orange-600">Pesanan Saya</a>
            </div>
        </nav>
    </header>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
