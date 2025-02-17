<footer class="bg-gray-800 text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Tentang Kami</h3>
                <p class="text-gray-400">Warung Makan menyajikan berbagai hidangan lezat dengan bahan berkualitas dan pelayanan terbaik.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="customer/index.php" class="text-gray-400 hover:text-white transition-colors">Menu</a></li>
                    <li><a href="customer/keranjang.php" class="text-gray-400 hover:text-white transition-colors">Keranjang</a></li>
                    <li><a href="customer/pesanan.php" class="text-gray-400 hover:text-white transition-colors">Pesanan</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-center">
                        <i class="fas fa-map-marker-alt w-6"></i>
                        Jl. Contoh No. 123
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone w-6"></i>
                        +62 123 4567 890
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope w-6"></i>
                        info@warungmakan.com
                    </li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Sosial Media</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-whatsapp text-2xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; <?php echo date("Y"); ?> Warung Makan. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>
